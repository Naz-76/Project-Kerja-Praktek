@extends('layouts.app')

@section('title', 'Panduan Integrasi Google OAuth & Gmail OTP — Diskominfo Garut')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="text-center space-y-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-amber-600 px-3 py-1 bg-amber-100 rounded-full border border-amber-300">
            <i class="fa-solid fa-gears"></i> Dokumentasi Konfigurasi Sistem
        </span>
        <h1 class="font-heading text-3xl font-extrabold text-slate-900">Panduan Integrasi Google OAuth & Gmail OTP</h1>
        <p class="text-slate-600 text-sm max-w-2xl mx-auto">Petunjuk rincian variabel lingkungan (`.env`) dan kredensial yang harus disiapkan sebelum sistem dideploy ke lingkungan produksi live.</p>
    </div>

    <!-- 1. Google OAuth -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center font-bold text-lg">
                <i class="fa-brands fa-google"></i>
            </div>
            <div>
                <h2 class="font-heading text-lg font-bold text-slate-900">1. Persiapan Google Sign-In (OAuth 2.0)</h2>
                <p class="text-xs text-slate-500">Integrasi OAuth menggunakan paket `laravel/socialite`.</p>
            </div>
        </div>

        <div class="space-y-3 text-xs text-slate-700 leading-relaxed">
            <p>Untuk mengaktifkan login otomatis menggunakan akun Google pendaftar, siapkan kredensial di <strong>Google Cloud Console</strong> (https://console.cloud.google.com):</p>
            <ol class="list-decimal list-inside space-y-1.5 pl-2 font-medium">
                <li>Buat proyek baru di Google Cloud Console, contoh: <code class="bg-slate-100 px-2 py-0.5 rounded text-sky-700 font-mono">Charter-Slot-Diskominfo</code>.</li>
                <li>Buka menu <strong>APIs & Services &rarr; Credentials &rarr; Create Credentials &rarr; OAuth client ID</strong>.</li>
                <li>Pilih Application Type: <strong>Web application</strong>.</li>
                <li>Tambahkan Authorized Redirect URI: <code class="bg-slate-100 px-2 py-0.5 rounded text-sky-700 font-mono">http://localhost:8000/auth/google/callback</code> (atau domain produksi Diskominfo).</li>
                <li>Salin <strong>Client ID</strong> dan <strong>Client Secret</strong> ke file <code class="bg-slate-100 px-2 py-0.5 rounded text-slate-900 font-mono">.env</code>:</li>
            </ol>

            <div class="p-4 bg-slate-900 text-amber-400 font-mono text-xs rounded-xl overflow-x-auto border border-slate-800 space-y-1">
                <p># Konfigurasi Google OAuth (Socialite)</p>
                <p>GOOGLE_CLIENT_ID="1098237498127391-xxx.apps.googleusercontent.com"</p>
                <p>GOOGLE_CLIENT_SECRET="GOCSPX-xxxxxxxxxxxxxxxxxxxx"</p>
                <p>GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"</p>
            </div>
        </div>
    </div>

    <!-- 2. Gmail SMTP OTP -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>
            <div>
                <h2 class="font-heading text-lg font-bold text-slate-900">2. Persiapan Pengiriman OTP via Gmail SMTP</h2>
                <p class="text-xs text-slate-500">Pengiriman kode OTP 6-digit ke email pendaftar via Job Queue.</p>
            </div>
        </div>

        <div class="space-y-3 text-xs text-slate-700 leading-relaxed">
            <p>Untuk mengirimkan email asli dari akun Gmail resmi Diskominfo Garut:</p>
            <ol class="list-decimal list-inside space-y-1.5 pl-2 font-medium">
                <li>Aktifkan <strong>2-Step Verification</strong> pada akun Gmail/Google Workspace instansi (<code class="bg-slate-100 px-2 py-0.5 rounded text-slate-900 font-mono">diskominfo@garutkab.go.id</code>).</li>
                <li>Buka menu akun Google &rarr; Security &rarr; <strong>App Passwords</strong>.</li>
                <li>Buat App Password khusus untuk aplikasi "Portal Magang & PKL Mailer".</li>
                <li>Simpan password 16 karakter tersebut ke dalam file <code>.env</code> seperti berikut:</li>
            </ol>

            <div class="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-xs overflow-x-auto space-y-1">
                <p>MAIL_MAILER=smtp</p>
                <p>MAIL_HOST=smtp.gmail.com</p>
                <p>MAIL_PORT=587</p>
                <p>MAIL_USERNAME=emailanda@gmail.com</p>
                <p>MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx</p>
                <p>MAIL_ENCRYPTION=tls</p>
                <p>MAIL_FROM_ADDRESS="emailanda@gmail.com"</p>
                <p>MAIL_FROM_NAME="Diskominfo Garut - Portal Magang & PKL"</p>
            </div>
        </div>
    </div>
</div>
@endsection
