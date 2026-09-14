@extends('layouts.app')

@section('title', 'Masuk — Portal Magang/KP/PKL Diskominfo Garut')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-slate-50/40 relative">
    
    <!-- Outer Login Card Container with Layered Blue Rectangles -->
    <div class="relative w-full max-w-lg flex items-center justify-center">
        
        <!-- Layered Blue Rectangles Top Left (Figma Tokens: #014495, #0B6FBB, #2F90E1) -->
        <div class="absolute -top-5 -left-5 w-28 h-28 bg-[#014495] rounded-3xl shadow-md pointer-events-none"></div>
        <div class="absolute -top-2.5 -left-2.5 w-28 h-28 bg-[#0B6FBB] rounded-3xl shadow-sm pointer-events-none"></div>
        <div class="absolute top-0 left-0 w-28 h-28 bg-[#2F90E1] rounded-3xl shadow-sm pointer-events-none"></div>

        <!-- Layered Blue Rectangles Bottom Right (Figma Tokens: #014495, #0B6FBB, #2F90E1) -->
        <div class="absolute -bottom-5 -right-5 w-28 h-28 bg-[#014495] rounded-3xl shadow-md pointer-events-none"></div>
        <div class="absolute -bottom-2.5 -right-2.5 w-28 h-28 bg-[#0B6FBB] rounded-3xl shadow-sm pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-28 h-28 bg-[#2F90E1] rounded-3xl shadow-sm pointer-events-none"></div>

        <!-- Main White Card -->
        <div class="relative z-10 w-full bg-white px-8 py-12 sm:px-12 sm:py-14 rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.07)] space-y-7 text-center">
            
            <!-- Center Logo -->
            <div class="flex justify-center">
                <div class="w-20 h-20 p-3 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-center">
                    <img src="{{ asset('logo_diskominfo.png') }}" alt="Logo Diskominfo" class="w-full h-full object-contain">
                </div>
            </div>

            <!-- Subtitle -->
            <div class="space-y-1">
                <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed max-w-xs mx-auto">
                    Untuk masuk kedalam sistem silahkan masuk menggunakan akun google anda
                </p>
            </div>

            <!-- Google Login Button -->
            <div class="pt-1">
                <a href="{{ route('auth.google') }}" class="w-full max-w-sm mx-auto flex items-center justify-center gap-3 py-3.5 px-8 border border-slate-200 rounded-full font-bold text-slate-800 bg-white hover:bg-slate-50 hover:shadow-md hover:border-slate-300 transition-all text-sm shadow-sm active:scale-[0.98]">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span class="text-sm font-bold text-slate-800 font-heading">Masuk dengan Google</span>
                </a>
            </div>

            <!-- Admin Login Toggle (Preserving Business Logic) -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('admin-login-form').classList.toggle('hidden')" class="text-xs text-slate-400 hover:text-slate-600 transition-colors inline-flex items-center gap-1.5 font-medium">
                    <i class="fa-solid fa-lock text-[10px]"></i>
                    <span>Masuk sebagai Pengelola / Admin</span>
                </button>
            </div>

            <!-- Admin Login Form -->
            <div id="admin-login-form" class="hidden space-y-4 pt-2 text-left">
                <div class="text-center">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider font-heading">Login Admin Kepegawaian</span>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email Admin</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="admin@garutkab.go.id">
                        @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                        @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-md transition-all text-sm font-heading">
                        Masuk Admin
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
