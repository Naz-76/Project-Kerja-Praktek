<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\ReplyLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $registrations = Registration::with(['department', 'preferredDepartment', 'leader', 'institution', 'replyLetter'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $activeRegistration = $registrations->first();

        return view('applicant.dashboard', compact('registrations', 'activeRegistration'));
    }

    public function showRegistration($id)
    {
        $user = Auth::user();
        $registration = Registration::with(['department', 'preferredDepartment', 'participants', 'institution', 'documents', 'replyLetter', 'verifier'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('applicant.registration-detail', compact('registration'));
    }

    public function replyLetters()
    {
        $user = Auth::user();
        $registrations = Registration::with(['department', 'replyLetter'])
            ->where('user_id', $user->id)
            ->whereHas('replyLetter')
            ->latest()
            ->get();

        return view('applicant.reply-letters', compact('registrations'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('applicant.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->boolean('delete_avatar') && $user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
