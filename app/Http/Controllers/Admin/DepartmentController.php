<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Registration;
use App\Models\SlotQuota;
use App\Services\QuotaService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $period = QuotaService::getActivePeriod();
        $departments = Department::with(['slotQuotas' => function ($q) use ($period) {
            $q->where('period', $period);
        }, 'fieldSupervisors'])->get();

        $stats = [
            'total_departments' => $departments->count(),
            'total_quota' => $departments->sum(fn($d) => $d->slotQuotas->first()->quota_total ?? 0),
            'total_used' => $departments->sum(fn($d) => $d->slotQuotas->first()->quota_used ?? 0),
            'total_remaining' => max(0, $departments->sum(fn($d) => ($d->slotQuotas->first()->quota_total ?? 0) - ($d->slotQuotas->first()->quota_used ?? 0))),
            'total_supervisors' => $departments->sum(fn($d) => $d->fieldSupervisors->count()),
        ];

        $supervisors = \App\Models\FieldSupervisor::with('department')->latest()->get();

        return view('admin.departments.index', compact('departments', 'period', 'stats', 'supervisors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'quota_total' => 'required|integer|min:1',
        ]);

        $department = Department::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $period = QuotaService::getActivePeriod();
        SlotQuota::create([
            'department_id' => $department->id,
            'period' => $period,
            'quota_total' => $validated['quota_total'],
            'quota_used' => 0,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Bidang & Kuota baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'quota_total' => 'required|integer|min:0',
        ]);

        $department->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $period = QuotaService::getActivePeriod();
        $slotQuota = SlotQuota::firstOrCreate(
            ['department_id' => $department->id, 'period' => $period],
            ['quota_total' => $validated['quota_total'], 'quota_used' => 0]
        );

        $slotQuota->update(['quota_total' => $validated['quota_total']]);

        return redirect()->route('admin.departments.index')->with('success', 'Data Bidang & Kuota berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        // Proteksi: cegah hapus jika bidang masih memiliki pengajuan aktif (pending / approved)
        $activeCount = Registration::where(function ($q) use ($id) {
            $q->where('department_id', $id)
              ->orWhere('preferred_department_id', $id);
        })->whereIn('status', ['pending', 'approved'])->count();

        if ($activeCount > 0) {
            return redirect()->route('admin.departments.index')
                ->with('error', "Gagal menghapus bidang '{$department->name}'. Masih terdapat {$activeCount} pengajuan aktif yang terhubung dengan bidang ini.");
        }

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Bidang berhasil dihapus.');
    }
}
