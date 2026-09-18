<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldSupervisor;
use Illuminate\Http\Request;

class FieldSupervisorController extends Controller
{
    public function store(Request $request)
    {
        // Mendukung input jamak / batch seperti penambahan anggota pendaftar
        if ($request->has('supervisors') && is_array($request->input('supervisors'))) {
            $validated = $request->validate([
                'department_id' => 'required|exists:departments,id',
                'supervisors' => 'required|array|min:1',
                'supervisors.*.name' => 'required|string|max:255',
                'supervisors.*.position' => 'nullable|string|max:255',
                'supervisors.*.phone' => 'nullable|string|max:255',
            ], [
                'department_id.required' => 'Bidang penempatan wajib dipilih.',
                'supervisors.required' => 'Minimal satu data pembimbing harus diisi.',
                'supervisors.*.name.required' => 'Nama lengkap pembimbing wajib diisi.',
            ]);

            $count = 0;
            foreach ($validated['supervisors'] as $sup) {
                if (!empty(trim($sup['name'] ?? ''))) {
                    FieldSupervisor::create([
                        'department_id' => $validated['department_id'],
                        'name' => $sup['name'],
                        'position' => $sup['position'] ?? null,
                        'phone' => $sup['phone'] ?? null,
                    ]);
                    $count++;
                }
            }

            return back()->with('success', "{$count} Pembimbing Lapangan berhasil ditambahkan!");
        }

        // Fallback input tunggal untuk kompatibilitas
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        FieldSupervisor::create($validated);

        return back()->with('success', 'Pembimbing Lapangan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $supervisor = FieldSupervisor::findOrFail($id);
        $supervisor->delete();

        return back()->with('success', 'Pembimbing Lapangan berhasil dihapus.');
    }
}
