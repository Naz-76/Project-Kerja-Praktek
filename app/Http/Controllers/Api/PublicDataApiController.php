<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Registration;
use App\Services\QuotaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicDataApiController extends Controller
{
    /**
     * Ambil data Bidang & Status Kuota Periode Aktif
     */
    public function departments(Request $request): JsonResponse
    {
        $period = QuotaService::getActivePeriod();

        $departments = Department::with(['slotQuotas' => function ($q) use ($period) {
            $q->where('period', $period);
        }, 'fieldSupervisors'])->get();

        $data = $departments->map(function ($dept) use ($period) {
            $quota = $dept->slotQuotas->first();
            $total = $quota ? (int) $quota->quota_total : 0;
            $used = $quota ? (int) $quota->quota_used : 0;
            $remaining = max(0, $total - $used);

            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'description' => $dept->description,
                'period' => $period,
                'quota' => [
                    'total' => $total,
                    'used' => $used,
                    'remaining' => $remaining,
                    'percentage_used' => $total > 0 ? round(($used / $total) * 100, 1) : 0,
                    'is_available' => $remaining > 0,
                ],
                'supervisors' => $dept->fieldSupervisors->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'position' => $s->position,
                        'phone' => $s->phone,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data bidang dan kuota berhasil ditarik.',
            'period' => $period,
            'total_departments' => $data->count(),
            'data' => $data,
        ]);
    }

    /**
     * Ambil data Pendaftar dengan Filter Kustom
     */
    public function registrations(Request $request): JsonResponse
    {
        $query = Registration::with(['department', 'leader', 'institution']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('applicant_status')) {
            $query->where('applicant_status', $request->applicant_status);
        }

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        $limit = min(100, max(1, (int) $request->input('limit', 20)));
        $registrations = $query->latest()->paginate($limit);

        $transformedData = $registrations->getCollection()->map(function ($reg) {
            return [
                'id' => $reg->id,
                'applicant_status' => $reg->applicant_status,
                'program_type' => $reg->program_type,
                'status' => $reg->status,
                'leader_name' => $reg->leader->full_name ?? null,
                'participant_count' => (int) $reg->participant_count,
                'institution' => [
                    'name' => $reg->institution->institution_name ?? null,
                    'major' => $reg->leader->major ?? null,
                ],
                'placement' => [
                    'department_id' => $reg->department_id,
                    'department_name' => $reg->department->name ?? null,
                    'supervisor_name' => $reg->supervisor_name,
                    'supervisor_position' => $reg->supervisor_position,
                ],
                'schedule' => [
                    'start_date' => $reg->start_date ? $reg->start_date->format('Y-m-d') : null,
                    'end_date' => $reg->end_date ? $reg->end_date->format('Y-m-d') : null,
                    'submitted_at' => $reg->submitted_at ? $reg->submitted_at->toIso8601String() : null,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran berhasil ditarik.',
            'pagination' => [
                'current_page' => $registrations->currentPage(),
                'per_page' => $registrations->perPage(),
                'total' => $registrations->total(),
                'last_page' => $registrations->lastPage(),
            ],
            'data' => $transformedData,
        ]);
    }

    /**
     * Ambil data Statistik Agregat untuk Dashboard Eksternal
     */
    public function statistics(Request $request): JsonResponse
    {
        $period = QuotaService::getActivePeriod();
        $departments = Department::with(['slotQuotas' => function ($q) use ($period) {
            $q->where('period', $period);
        }])->get();

        $totalQuota = $departments->sum(fn($d) => $d->slotQuotas->first()->quota_total ?? 0);
        $totalUsed = $departments->sum(fn($d) => $d->slotQuotas->first()->quota_used ?? 0);

        return response()->json([
            'success' => true,
            'message' => 'Data ringkasan statistik berhasil ditarik.',
            'data' => [
                'current_period' => $period,
                'overview' => [
                    'total_registrations' => Registration::count(),
                    'total_quota_capacity' => $totalQuota,
                    'total_quota_used' => $totalUsed,
                    'total_quota_remaining' => max(0, $totalQuota - $totalUsed),
                ],
                'by_status' => [
                    'pending' => Registration::where('status', 'pending')->count(),
                    'approved' => Registration::where('status', 'approved')->count(),
                    'completed' => Registration::where('status', 'completed')->count(),
                    'rejected' => Registration::where('status', 'rejected')->count(),
                ],
                'by_applicant_status' => [
                    'mahasiswa' => Registration::where('applicant_status', 'Mahasiswa')->count(),
                    'siswa' => Registration::where('applicant_status', 'Siswa')->count(),
                ],
                'by_program_type' => [
                    'pkl' => Registration::where('program_type', 'PKL')->count(),
                    'kp' => Registration::where('program_type', 'KP')->count(),
                    'magang' => Registration::where('program_type', 'Magang')->count(),
                ],
            ],
        ]);
    }
}
