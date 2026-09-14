<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->boolean('delete_avatar') && $admin->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($admin->avatar);
            $admin->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            if ($admin->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($admin->avatar);
            }
            $admin->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
            }
            $admin->password = Hash::make($request->password);
        }

        $admin->name = $validated['name'];
        $admin->phone = $validated['phone'];
        $admin->save();

        return back()->with('success', 'Profil Admin berhasil diperbarui!');
    }
}
