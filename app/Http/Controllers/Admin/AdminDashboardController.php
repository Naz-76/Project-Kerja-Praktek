<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Registration;
use App\Models\SlotQuota;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        \App\Services\QuotaService::syncDepartmentQuotas();
        $period = \App\Services\QuotaService::getActivePeriod();

        $totalRegistrations = Registration::count();
        $pendingCount = Registration::where('status', 'pending')->count();
        $approvedCount = Registration::where('status', 'approved')->count();
        $rejectedCount = Registration::where('status', 'rejected')->count();
        $completedCount = Registration::where('status', 'completed')->count();

        // Metrik Demografi Siswa vs Mahasiswa & Total Peserta
        $totalSiswaCount = Registration::where('applicant_status', 'Siswa')->count();
        $totalMahasiswaCount = Registration::where('applicant_status', 'Mahasiswa')->count();
        $totalApprovedParticipants = (int) Registration::where('status', 'approved')->sum('participant_count');
        $totalPendingParticipants = (int) Registration::where('status', 'pending')->sum('participant_count');
        
        // Status Pembimbing Lapangan
        $needsSupervisorCount = Registration::where('status', 'approved')->whereNull('supervisor_name')->count();
        $assignedSupervisorCount = Registration::where('status', 'approved')->whereNotNull('supervisor_name')->count();

        $departments = Department::with(['slotQuotas' => function ($q) use ($period) {
            $q->where('period', $period);
        }])->get();

        $recentRegistrations = Registration::with(['user', 'preferredDepartment', 'department', 'leader', 'institution'])
            ->latest()
            ->take(5)
            ->get();

        $pendingRegistrations = Registration::with(['user', 'department', 'preferredDepartment', 'leader', 'participants', 'institution'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        // Daftar Pendaftar yang Telah Diterima (Disetujui) Beserta Pembimbing Lapangannya
        $approvedRegistrations = Registration::with(['user', 'department', 'leader', 'participants', 'institution', 'replyLetter'])
            ->where('status', 'approved')
            ->orderBy('placed_at', 'desc')
            ->take(10)
            ->get();

        // Rekap Beban Bimbingan per Pembimbing Lapangan Diskominfo
        $supervisorWorkloads = Registration::with(['department', 'participants', 'leader', 'institution'])
            ->where('status', 'approved')
            ->whereNotNull('supervisor_name')
            ->get()
            ->groupBy('supervisor_name')
            ->map(function ($regs, $name) {
                return [
                    'name' => $name,
                    'position' => $regs->first()->supervisor_position ?? 'Pembimbing Lapangan',
                    'phone' => $regs->first()->supervisor_phone ?? null,
                    'department' => $regs->first()->department->name ?? '-',
                    'group_count' => $regs->count(),
                    'total_students' => (int) $regs->sum('participant_count'),
                    'groups' => $regs,
                ];
            });

        // Data Lengkap Semua Registrasi untuk Quick-View Modal Drilldown
        $allRegistrations = Registration::with(['user', 'preferredDepartment', 'department', 'leader', 'participants', 'institution', 'replyLetter'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalRegistrations',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'completedCount',
            'totalSiswaCount',
            'totalMahasiswaCount',
            'totalApprovedParticipants',
            'totalPendingParticipants',
            'needsSupervisorCount',
            'assignedSupervisorCount',
            'departments',
            'recentRegistrations',
            'pendingRegistrations',
            'approvedRegistrations',
            'supervisorWorkloads',
            'allRegistrations',
            'period'
        ));
    }
}
