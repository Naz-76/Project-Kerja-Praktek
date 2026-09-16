<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldSupervisor;
use Illuminate\Http\Request;

class FieldSupervisorController extends Controller
{
    public function store(Request $request)
    {
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
