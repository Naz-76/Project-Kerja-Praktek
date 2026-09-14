@extends('layouts.app')

@section('title', 'Verifikasi OTP — Portal Magang & PKL Diskominfo')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-2xl border border-slate-200 shadow-xl text-center">
        <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto text-2xl font-bold font-heading">
            <i class="fa-solid fa-key"></i>
        </div>
        <div class="space-y-1">
            <h2 class="font-heading text-2xl font-bold text-slate-900">Verifikasi Kode OTP</h2>
            <p class="text-xs text-slate-500">Masukkan 6-digit kode OTP yang telah dikirim ke email akun Anda.</p>
        </div>

        <form action="{{ route('auth.otp.verify') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="otp_code" maxlength="6" required placeholder="000000" class="w-full text-center text-3xl font-mono tracking-widest font-bold px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all">
                @error('otp_code') <p class="text-xs text-rose-500 mt-2">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl shadow-md shadow-amber-500/20 transition-all text-sm">
                Verifikasi OTP & Lanjutkan
            </button>
        </form>
    </div>
</div>
@endsection
