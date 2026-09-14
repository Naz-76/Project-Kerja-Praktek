@extends('layouts.admin')

@section('title', 'Profil Admin — Diskominfo Garut')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#014495] inline-flex items-center gap-1 font-heading transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
        <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Kelola Profil Admin</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui foto profil, informasi diri, dan kata sandi akun Admin Kepegawaian.</p>
    </div>

    <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xl space-y-6">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Avatar Upload & Preview -->
            <div class="flex flex-col sm:flex-row items-center gap-6 border-b border-slate-100 pb-6">
                <div class="relative w-24 h-24 rounded-full overflow-hidden border-4 border-[#014495] shadow-lg shrink-0">
                    <img src="{{ $admin->avatar_url }}" alt="Foto Profil Admin" class="w-full h-full object-cover">
                </div>
                <div class="space-y-2 text-center sm:text-left flex-grow">
                    <span class="font-bold text-xs text-slate-800 block uppercase tracking-wider font-heading">Foto Profil Admin</span>
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#014495] hover:file:bg-blue-100">
                    <p class="text-[11px] text-slate-400">Format gambar JPG, PNG (Max 2MB).</p>
                    
                    @if($admin->avatar)
                        <label class="inline-flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer pt-1">
                            <input type="checkbox" name="delete_avatar" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                            Hapus Foto Profil Saat Ini
                        </label>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Nama Lengkap Admin *</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Alamat Email (Admin)</label>
                <input type="email" value="{{ $admin->email }}" disabled class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">No. WhatsApp / Telepon Admin *</label>
                <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                @error('phone') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-4">
                <span class="font-bold text-xs text-slate-800 uppercase tracking-wider block font-heading">Ubah Kata Sandi (Kosongkan jika tidak diubah)</span>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    @error('current_password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Kata Sandi Baru</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                        @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-lg transition-all text-xs sm:text-sm font-heading active:scale-[0.98]">
                Simpan Perubahan Profil Admin
            </button>
        </form>
    </div>
</div>
@endsection
