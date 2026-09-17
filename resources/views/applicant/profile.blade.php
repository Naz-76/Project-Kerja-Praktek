@extends('layouts.app')

@section('title', 'Profil Saya — Diskominfo Garut')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div>
        <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Profil Pengguna</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data profil, kontak, dan pengaturan kata sandi akun Anda.</p>
    </div>

    <!-- READ-ONLY PROFILE VIEW (Profile Pengguna.png) -->
    <div id="profileViewCard" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden {{ $errors->any() ? 'hidden' : '' }}">
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100">
            <!-- Left Column: Avatar & Logout Button -->
            <div class="p-8 flex flex-col items-center justify-between text-center space-y-6 bg-slate-50/50">
                <div class="space-y-4 flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-[#2F90E1] shadow-lg bg-white p-1">
                        <img src="{{ $user->avatar_url }}" alt="Foto Profil" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-heading font-extrabold text-lg text-slate-900">{{ $user->name }}</h3>
                        <span class="inline-block px-3 py-1 bg-blue-100 text-[#014495] rounded-full text-xs font-bold font-heading">
                            Pendaftar
                        </span>
                        <p class="text-xs text-slate-500 pt-1">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Danger Logout Button (#8B0000) -->
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-3 px-6 bg-[#8B0000] hover:bg-[#6b0000] text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-heading active:scale-[0.98]">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>

            <!-- Right Column: Profile Data & Edit Profile CTA -->
            <div class="p-8 md:col-span-2 space-y-6 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="font-heading font-extrabold text-lg text-slate-900">Data Profil Pengguna</h2>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full flex items-center gap-1.5 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Akun Aktif
                        </span>
                    </div>

                    <!-- Field 1: Nama -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider font-heading">Nama Lengkap</label>
                        <div class="w-full p-3.5 bg-blue-50/40 border-2 border-[#2F90E1]/50 rounded-2xl text-sm font-semibold text-slate-800">
                            {{ $user->name }}
                        </div>
                    </div>

                    <!-- Field 2: Email -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider font-heading">Alamat Email</label>
                        <div class="w-full p-3.5 bg-blue-50/40 border-2 border-[#2F90E1]/50 rounded-2xl text-sm font-semibold text-slate-800">
                            {{ $user->email }}
                        </div>
                    </div>

                    <!-- Field 3: WhatsApp -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider font-heading">No. WhatsApp / Telepon</label>
                        <div class="w-full p-3.5 bg-blue-50/40 border-2 border-[#2F90E1]/50 rounded-2xl text-sm font-semibold text-slate-800">
                            {{ $user->phone ?: '-' }}
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Button -->
                <div class="pt-4 flex justify-end">
                    <button type="button" onclick="toggleEditProfile(true)" class="px-8 py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold text-sm rounded-xl shadow-lg transition-all flex items-center gap-2 font-heading active:scale-[0.98]">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Edit Profile</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT PROFILE FORM (Card Edit Profile.png) -->
    <div id="profileEditCard" class="bg-white rounded-3xl border border-slate-200 shadow-2xl p-8 sm:p-10 space-y-8 {{ $errors->any() ? '' : 'hidden' }}">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <h2 class="font-heading font-extrabold text-xl text-slate-900">Perbarui Profil Akun</h2>
                <p class="text-xs text-slate-500 mt-0.5">Ubah data identitas, foto avatar, dan kata sandi akun Anda.</p>
            </div>
            <button type="button" onclick="toggleEditProfile(false)" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('applicant.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Avatar Picture Upload with Preview -->
            <div class="flex flex-col sm:flex-row items-center gap-6 p-6 bg-slate-50/60 rounded-3xl border border-slate-200">
                <div class="relative w-24 h-24 rounded-full overflow-hidden border-4 border-[#2F90E1] shadow-md shrink-0 bg-white">
                    <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="Foto Profil" class="w-full h-full object-cover">
                </div>
                <div class="space-y-2 text-center sm:text-left flex-grow">
                    <span class="font-bold text-xs text-slate-800 block font-heading uppercase tracking-wider">Unggah Foto Profil</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="cursor-pointer px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-sm transition-all inline-flex items-center gap-2">
                            <i class="fa-solid fa-image text-[#0B6FBB]"></i>
                            <span>Pilih File</span>
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewNewAvatar(this)">
                        </label>
                        <span id="avatarFileName" class="text-xs text-slate-500 italic">Belum ada file dipilih</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Format JPG, PNG (Maks. 2MB).</p>
                    
                    @if($user->avatar)
                        <label class="inline-flex items-center gap-2 text-xs text-[#8B0000] font-semibold cursor-pointer pt-1">
                            <input type="checkbox" name="delete_avatar" value="1" class="rounded text-[#8B0000] focus:ring-[#8B0000]">
                            Hapus Foto Profil Khusus (Gunakan Avatar Default)
                        </label>
                    @endif
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email (Disabled) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Alamat Email (Akun)</label>
                <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
            </div>

            <!-- Nomor WhatsApp -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">No. WhatsApp / Telepon <span class="text-rose-500">*</span></label>
                <input type="tel" inputmode="numeric" minlength="10" maxlength="15" pattern="[0-9]{10,15}" name="phone" value="{{ old('phone', $user->phone) }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="08xxxxxxxxxx (10-15 digit)">
                <p class="text-[11px] text-slate-400 mt-1">Minimal 10 digit angka (contoh: 081234567890)</p>
                @error('phone') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Password Change Accordion Section -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <span class="font-bold text-xs text-slate-800 uppercase tracking-wider block font-heading">Ubah Kata Sandi (Kosongkan jika tidak ingin mengubah)</span>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                    @error('current_password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Kata Sandi Baru</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                        @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                    </div>
                </div>
            </div>

            <!-- Action Buttons: Urungkan & Simpan -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <button type="button" onclick="toggleEditProfile(false)" class="px-6 py-3 bg-[#8B0000] hover:bg-[#6b0000] text-white font-bold text-xs rounded-xl shadow-md transition-all font-heading active:scale-[0.98]">
                    Urungkan
                </button>
                <button type="submit" class="px-8 py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold text-xs rounded-xl shadow-lg transition-all font-heading active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEditProfile(showEdit) {
        const viewCard = document.getElementById('profileViewCard');
        const editCard = document.getElementById('profileEditCard');
        if (showEdit) {
            viewCard.classList.add('hidden');
            editCard.classList.remove('hidden');
        } else {
            editCard.classList.add('hidden');
            viewCard.classList.remove('hidden');
        }
    }

    function previewNewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
            document.getElementById('avatarFileName').textContent = input.files[0].name;
        }
    }
</script>
@endsection
