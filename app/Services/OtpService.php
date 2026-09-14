<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function generateOtp(User $user, string $purpose = 'register'): OtpCode
    {
        // Nonaktifkan OTP lama yang belum terpakai
        OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $code = sprintf('%06d', mt_rand(100000, 999999));

        $otp = OtpCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Kirim email OTP asli jika Mailer terkonfigurasi
        try {
            if (config('mail.mailer') && config('mail.from.address') && config('mail.from.address') !== 'hello@example.com') {
                \Illuminate\Support\Facades\Mail::raw("Halo {$user->name},\n\nKode OTP Verifikasi Akun Charter Slot Diskominfo Garut Anda adalah: {$code}\n\nKode ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapapun.", function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Verifikasi Akun — Diskominfo Garut');
                });
            }
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email OTP ke {$user->email}: " . $e->getMessage());
        }

        Log::info("OTP Code generated for User {$user->email} [{$purpose}]: {$code}");

        return $otp;
    }

    public function verifyOtp(User $user, string $code, string $purpose = 'register'): bool
    {
        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $code)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($otp) {
            $otp->update(['used_at' => now()]);
            $user->update(['email_verified_at' => now()]);
            return true;
        }

        return false;
    }
}
