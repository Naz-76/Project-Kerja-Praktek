<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user(), 'Anda sudah masuk.');
        }
        return view('auth.login');
    }

    /**
     * Login via email/password — khusus Admin (hardcoded account).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Admin → langsung ke admin dashboard
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, Admin!');
            }

            // Non-admin tidak boleh login via form, harus via Google
            Auth::logout();
            return back()->withErrors(['email' => 'Akun pendaftar harus masuk menggunakan Google.'])->withInput();
        }

        return back()->withErrors(['email' => 'Email atau kata sandi tidak cocok.'])->withInput();
    }

    /**
     * Google OAuth — redirect ke Google.
     */
    public function googleRedirect()
    {
        if (config('services.google.client_id')) {
            return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
        }

        // Fallback demo jika belum setup kredensial Google OAuth di .env
        $user = User::firstOrCreate(
            ['email' => 'google_user@gmail.com'],
            [
                'name' => 'Pengguna Google (Demo)',
                'google_id' => '1098237498127391',
                'phone' => null,
                'role' => 'pendaftar',
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user, true);
        request()->session()->regenerate();
        return $this->redirectByRole($user, 'Berhasil login melalui Google OAuth (Demo)!');
    }

    /**
     * Google OAuth — callback setelah user login di Google.
     */
    public function googleCallback(Request $request)
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update data Google terbaru jika belum tersimpan
                $user->update([
                    'name' => $googleUser->getName() ?? $user->name,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar() ?? $user->avatar,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                // Buat user pendaftar baru
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'pendaftar',
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return $this->redirectByRole($user, 'Berhasil masuk dengan akun Google!');
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal autentikasi Google: ' . $e->getMessage());
        }
    }

    /**
     * Redirect berdasarkan role setelah login.
     */
    private function redirectByRole(User $user, string $message)
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))->with('success', $message);
        }
        return redirect()->intended(route('applicant.dashboard'))->with('success', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('public.index')->with('success', 'Anda telah keluar dari sistem.');
    }
}
