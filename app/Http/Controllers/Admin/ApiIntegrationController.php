<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiIntegrationController extends Controller
{
    public function index()
    {
        $apiKeys = ApiKey::latest()->get();
        $baseUrl = url('/api/v1');

        return view('admin.api.index', compact('apiKeys', 'baseUrl'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama aplikasi/sistem pemanggil wajib diisi.',
        ]);

        $key = 'porma_' . Str::random(40);

        $apiKey = ApiKey::create([
            'name' => $validated['name'],
            'key' => $key,
            'is_active' => true,
        ]);

        return redirect()->route('admin.api.index')
            ->with('success', "API Key untuk '{$apiKey->name}' berhasil dibuat! Salin kunci API Anda sekarang.");
    }

    public function toggle($id)
    {
        $apiKey = ApiKey::findOrFail($id);
        $apiKey->update(['is_active' => ! $apiKey->is_active]);

        $status = $apiKey->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.api.index')
            ->with('success', "API Key '{$apiKey->name}' berhasil {$status}.");
    }

    public function destroy($id)
    {
        $apiKey = ApiKey::findOrFail($id);
        $apiKey->delete();

        return redirect()->route('admin.api.index')
            ->with('success', "API Key '{$apiKey->name}' berhasil dihapus.");
    }
}
