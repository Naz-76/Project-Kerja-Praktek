@extends('layouts.app')

@section('title', 'Form Pengajuan Pendaftaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="space-y-8">
        
        <!-- Header Section -->
        <div class="text-center space-y-2">
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Form Pengajuan Pendaftaran</h1>
            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Lengkapi seluruh data pendaftaran di bawah ini untuk memulai program Praktik Kerja Lapangan (PKL), Kerja Praktik (KP), atau Magang di Diskominfo Kabupaten Garut.
            </p>
        </div>

        <form id="registrationForm" action="{{ route('applicant.registration.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Server-side Validation Errors --}}
            @if($errors->any())
                <div id="serverErrorAlert" class="p-5 bg-rose-50 border border-rose-300 rounded-2xl text-sm text-rose-800 space-y-2 shadow-sm transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <p class="font-bold flex items-center gap-2 font-heading"><i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Terdapat kesalahan pada data yang Anda kirim:</p>
                        <button type="button" onclick="dismissErrorAlert()" class="text-rose-500 hover:text-rose-800 p-1 text-xs" title="Tutup Notifikasi">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                    <ul class="list-disc pl-5 space-y-1 text-xs font-medium" id="serverErrorList">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="formSection" class="space-y-6">
                <!-- 1. Status Pendaftar & Jenis Program -->
                <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-sm font-heading shrink-0">
                            1
                        </div>
                        <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900">Status Pendaftar & Jenis Program</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 font-heading">
                                Status Pendidikan Anda <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-[#2F90E1] cursor-pointer transition-all flex items-center gap-3 bg-slate-50/50 has-[:checked]:border-[#014495] has-[:checked]:bg-blue-50/40">
                                    <input type="radio" name="applicant_status" value="Siswa" {{ old('applicant_status') === 'Siswa' ? 'checked' : '' }} required class="w-4 h-4 text-[#014495] focus:ring-[#014495]" onchange="updateProgramType()">
                                    <div>
                                        <span class="font-bold text-sm text-slate-800 block font-heading">Siswa</span>
                                        <span class="text-[11px] text-slate-500">Program PKL Sekolah Menengah Kejuruan</span>
                                    </div>
                                </label>
                                <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-[#2F90E1] cursor-pointer transition-all flex items-center gap-3 bg-slate-50/50 has-[:checked]:border-[#014495] has-[:checked]:bg-blue-50/40">
                                    <input type="radio" name="applicant_status" value="Mahasiswa" {{ old('applicant_status') === 'Mahasiswa' ? 'checked' : '' }} required class="w-4 h-4 text-[#014495] focus:ring-[#014495]" onchange="updateProgramType()">
                                    <div>
                                        <span class="font-bold text-sm text-slate-800 block font-heading">Mahasiswa</span>
                                        <span class="text-[11px] text-slate-500">Program Kerja Praktik / Magang Kuliah</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 font-heading">
                                Jenis Program <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3" id="programTypeContainer">
                                <label class="p-3.5 bg-white border-2 border-slate-200 rounded-2xl cursor-pointer hover:border-[#2F90E1] transition-all program-siswa hidden has-[:checked]:border-[#014495] has-[:checked]:bg-blue-50/40">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="program_type" value="PKL" {{ old('program_type') === 'PKL' ? 'checked' : '' }} class="text-[#014495] focus:ring-[#014495]">
                                        <div>
                                            <span class="font-bold text-xs text-slate-900 block font-heading">PKL</span>
                                            <span class="text-[10px] text-slate-500">Praktik Kerja Lapangan</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="p-3.5 bg-white border-2 border-slate-200 rounded-2xl cursor-pointer hover:border-[#2F90E1] transition-all program-mahasiswa hidden has-[:checked]:border-[#014495] has-[:checked]:bg-blue-50/40">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="program_type" value="KP" {{ old('program_type') === 'KP' ? 'checked' : '' }} class="text-[#014495] focus:ring-[#014495]">
                                        <div>
                                            <span class="font-bold text-xs text-slate-900 block font-heading">KP</span>
                                            <span class="text-[10px] text-slate-500">Kerja Praktik</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="p-3.5 bg-white border-2 border-slate-200 rounded-2xl cursor-pointer hover:border-[#2F90E1] transition-all program-mahasiswa hidden has-[:checked]:border-[#014495] has-[:checked]:bg-blue-50/40">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="program_type" value="Magang" {{ old('program_type') === 'Magang' ? 'checked' : '' }} class="text-[#014495] focus:ring-[#014495]">
                                        <div>
                                            <span class="font-bold text-xs text-slate-900 block font-heading">Magang</span>
                                            <span class="text-[10px] text-slate-500">Magang</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2. Data Guru / Dosen Pembimbing (Conditional) -->
                <section id="teacherSection" class="hidden bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-[#0B6FBB] text-white flex items-center justify-center font-bold text-sm font-heading shrink-0">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <h2 id="teacherSectionTitle" class="font-heading font-bold text-base sm:text-lg text-slate-900">Data Guru Pembimbing Sekolah</h2>
                            <p id="teacherSectionDesc" class="text-xs text-slate-500 mt-0.5">Isi kontak guru pembimbing resmi dari sekolah Anda.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap Pembimbing <span class="text-rose-500">*</span></label>
                            <input type="text" id="teacher_name" name="teacher_name" value="{{ old('teacher_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="Contoh: Drs. H. Ahmad Sudrajat, M.Pd">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">No. WhatsApp / Telepon <span class="text-rose-500">*</span></label>
                            <input type="tel" inputmode="numeric" minlength="10" maxlength="15" pattern="[0-9]{10,15}" id="teacher_phone" name="teacher_phone" value="{{ old('teacher_phone') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="08xxxxxxxxxx (10-15 digit)">
                            <p class="text-[11px] text-slate-400 mt-1">Minimal 10 digit angka (contoh: 081234567890)</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email Pembimbing (Opsional)</label>
                            <input type="email" id="teacher_email" name="teacher_email" value="{{ old('teacher_email') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="guru.pembimbing@sekolah.sch.id">
                        </div>
                    </div>
                </section>

                <!-- 3. Data Pendaftar & Anggota Tim -->
                <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-sm font-heading shrink-0">
                                2
                            </div>
                            <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900">Data Pendaftar (Ketua & Anggota)</h2>
                        </div>
                        <button type="button" onclick="addParticipant()" class="text-xs px-3.5 py-2 bg-blue-50 text-[#014495] border border-[#014495] font-bold rounded-xl hover:bg-blue-100 transition-all font-heading flex items-center gap-1.5">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>+ Tambah Anggota</span>
                        </button>
                    </div>
                    
                    <div id="participantsContainer" class="space-y-4">
                        <!-- Pendaftar Utama (Ketua) -->
                        <div class="participant-row bg-slate-50/60 p-5 border-2 border-[#2F90E1]/40 rounded-2xl space-y-4 relative">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[#014495] uppercase tracking-wider font-heading flex items-center gap-1.5">
                                    <i class="fa-solid fa-crown text-amber-500"></i> Pendaftar Utama / Ketua Tim
                                </span>
                                <span class="text-[10px] font-bold bg-[#014495] text-white px-2.5 py-1 rounded-full">Ketua</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                                    <input type="text" name="participants[0][full_name]" value="{{ old('participants.0.full_name', auth()->user()->name) }}" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIS / NIM <span class="text-rose-500">*</span></label>
                                    <input type="text" name="participants[0][nis_nim]" value="{{ old('participants.0.nis_nim') }}" required minlength="5" maxlength="50" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="Nomor Induk Siswa / Mahasiswa (min. 5 digit)">
                                    <p class="text-[11px] text-slate-400 mt-1">Minimal 5 digit/karakter</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor WhatsApp Aktif <span class="text-rose-500">*</span></label>
                                <input type="tel" inputmode="numeric" minlength="10" maxlength="15" pattern="[0-9]{10,15}" name="leader_phone" value="{{ old('leader_phone', auth()->user()->phone) }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="08xxxxxxxxxx (10-15 digit untuk koordinasi status)">
                                <p class="text-[11px] text-slate-400 mt-1">Minimal 10 digit angka (otomatis tersinkron dengan profil Anda)</p>
                            </div>
                        </div>

                        @if(old('participants') && count(old('participants')) > 1)
                            @foreach(old('participants') as $idx => $p)
                                @if($idx > 0)
                                <div class="participant-row bg-white p-4 border border-slate-200 rounded-lg space-y-3 relative mt-3" id="participant_{{ $idx }}">
                                    <button type="button" onclick="document.getElementById('participant_{{ $idx }}').remove()" class="absolute top-2 right-2 text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded hover:bg-rose-100">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                            <input type="text" name="participants[{{ $idx }}][full_name]" value="{{ $p['full_name'] ?? '' }}" required class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">NIS / NIM <span class="text-rose-500">*</span></label>
                                            <input type="text" name="participants[{{ $idx }}][nis_nim]" value="{{ $p['nis_nim'] ?? '' }}" required minlength="5" maxlength="50" placeholder="Min. 5 digit" class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-500 italic">
                        * Anggota kelompok hanya perlu mengisi Nama Lengkap dan NIS/NIM. Jika mendaftar perorangan, Anda tidak perlu menambah anggota.
                    </p>
                </section>

                <!-- 4. Data Akademik / Asal Institusi -->
                <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-sm font-heading shrink-0">
                            3
                        </div>
                        <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900">Data Akademik / Institusi</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Asal Sekolah / Kampus <span class="text-rose-500">*</span></label>
                            <input type="text" name="institution_name" value="{{ old('institution_name') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="Contoh: SMKN 1 Garut / Universitas Garut">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jurusan / Program Studi <span class="text-rose-500">*</span></label>
                            <input type="text" name="major" value="{{ old('major') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all" placeholder="Contoh: Rekayasa Perangkat Lunak / Teknik Informatika">
                        </div>
                    </div>
                </section>

                <!-- 5. Bidang yang Diminati -->
                <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-sm font-heading shrink-0">
                            4
                        </div>
                        <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900">Bidang yang Diminati (Preferensi)</h2>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilihan Bidang di Diskominfo Garut</label>
                        <select name="preferred_department_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                            <option value="">-- Pilih Bidang yang Diminati (Opsional) --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('preferred_department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-3 p-3.5 bg-blue-50/60 border border-blue-200 rounded-2xl text-xs text-[#014495] flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-info text-base mt-0.5 shrink-0 text-[#0B6FBB]"></i>
                            <p class="leading-relaxed">
                                Pilihan bidang di atas bersifat <b>preferensi / minat</b>. Penempatan bidang final akan ditentukan resmi oleh Diskominfo berdasarkan kuota slot dan relevansi kurikulum jurusan.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- 6. Periode Pelaksanaan Kegiatan -->
                <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-sm font-heading shrink-0">
                            5
                        </div>
                        <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900">Periode Pelaksanaan Kegiatan</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mulai <span class="text-rose-500">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Selesai <span class="text-rose-500">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] transition-all">
                        </div>
                    </div>
                </section>

                <!-- 7. Surat Pengantar Resmi (Dropzone) -->
                <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-sm font-heading shrink-0">
                            6
                        </div>
                        <h2 class="font-heading font-bold text-base sm:text-lg text-slate-900">Unggah Surat Pengantar Resmi</h2>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 font-heading">
                            File Surat Pengantar Sekolah / Kampus (PDF) <span class="text-rose-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-slate-300 hover:border-[#2F90E1] rounded-3xl p-8 text-center bg-slate-50/50 transition-all cursor-pointer relative" onclick="document.getElementById('doc_surat_pengantar').click()">
                            <input type="file" id="doc_surat_pengantar" name="doc_surat_pengantar" accept=".pdf" required class="hidden" onchange="displayFileName(this)">
                            <div class="w-14 h-14 bg-blue-50 text-[#014495] rounded-2xl flex items-center justify-center mx-auto text-2xl mb-3 shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <span id="uploadLabel" class="block font-bold text-sm text-slate-800 font-heading">
                                Seret dan taruh file di sini, atau klik untuk memilih file
                            </span>
                            <span class="block text-xs text-slate-500 mt-1 font-medium" id="hintSuratPengantar">
                                Mendukung format <b>.PDF</b> (Maksimal ukuran file: 5 MB)
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Declaration Agreement & Button Tinjau -->
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 space-y-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="w-4 h-4 text-[#014495] focus:ring-[#014495] rounded mt-0.5">
                        <span class="text-xs text-slate-700 font-medium leading-relaxed">
                            Saya menyatakan bahwa semua data yang diisi di atas adalah benar dan sesuai dengan surat pengantar asli dari institusi pendidikan saya.
                        </span>
                    </label>
                    <div class="pt-2 flex justify-end">
                        <button type="button" onclick="showReview()" class="w-full sm:w-auto px-8 py-3.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-lg transition-all text-sm font-heading flex items-center justify-center gap-2 active:scale-[0.98]">
                            <span>Tinjau Pengajuan</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 8. Tinjau Pengajuan Modal Card -->
            <div id="reviewSection" class="hidden space-y-6">
                <div class="p-8 bg-blue-50/60 border border-blue-200 rounded-3xl space-y-6 shadow-xl">
                    <div class="border-b border-blue-200 pb-4">
                        <h2 class="font-heading font-extrabold text-xl text-[#014495]">Tinjau Pengajuan Formulir</h2>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1">Pastikan seluruh data di bawah ini sudah akurat sebelum dikirim ke tim verifikator Diskominfo Garut.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <div class="p-4 bg-white rounded-2xl border border-blue-100 space-y-1">
                            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Status & Program</span>
                            <p class="font-bold text-slate-900 text-sm" id="rev_status_program">-</p>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-blue-100 space-y-1">
                            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Institusi & Jurusan</span>
                            <p class="font-bold text-slate-900 text-sm" id="rev_akademik">-</p>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-blue-100 space-y-1">
                            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Periode Pelaksanaan</span>
                            <p class="font-bold text-slate-900 text-sm" id="rev_periode">-</p>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-blue-100 space-y-1">
                            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Preferensi Bidang</span>
                            <p class="font-bold text-slate-900 text-sm" id="rev_bidang">-</p>
                        </div>
                    </div>

                    <div class="p-4 bg-white rounded-2xl border border-blue-100">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px] mb-2">Daftar Peserta Kelompok</span>
                        <ul class="list-disc pl-5 font-semibold text-slate-800 text-xs space-y-1" id="rev_anggota">
                        </ul>
                    </div>
                </div>

                <!-- Final Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <button type="button" onclick="hideReview()" class="w-full sm:w-auto px-6 py-3 text-slate-700 hover:text-slate-900 font-bold text-xs border border-slate-300 rounded-xl bg-white hover:bg-slate-50 transition-all">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali Ubah Formulir
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-lg transition-all text-xs font-heading flex items-center justify-center gap-2 active:scale-[0.98]">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Kirim Formulir Pengajuan Sekarang</span>
                    </button>
                </div>
        </form>
    </div>
</div>

<script>
    function displayFileName(input) {
        const label = document.getElementById('uploadLabel');
        if (input.files && input.files[0]) {
            label.innerHTML = `<span class="text-[#014495] font-bold"><i class="fa-solid fa-file-pdf mr-1"></i> ${input.files[0].name}</span>`;
        }
    }
    let participantCount = {{ old('participants') ? count(old('participants')) : 1 }};

    function updateProgramType(keepSelectedProgram = false) {
        const status = document.querySelector('input[name="applicant_status"]:checked')?.value;
        const pSiswa = document.querySelectorAll('.program-siswa');
        const pMahasiswa = document.querySelectorAll('.program-mahasiswa');
        const programRadios = document.querySelectorAll('input[name="program_type"]');
        const teacherSection = document.getElementById('teacherSection');
        const teacherTitle = document.getElementById('teacherSectionTitle');
        const teacherDesc = document.getElementById('teacherSectionDesc');
        const teacherName = document.getElementById('teacher_name');
        const teacherPhone = document.getElementById('teacher_phone');

        // Reset program type jika bukan dipanggil saat restore old value
        if (!keepSelectedProgram) {
            programRadios.forEach(r => r.checked = false);
        }
        pSiswa.forEach(el => el.classList.add('hidden'));
        pMahasiswa.forEach(el => el.classList.add('hidden'));

        if (status === 'Siswa') {
            // Show program PKL
            pSiswa.forEach(el => el.classList.remove('hidden'));
            if (!keepSelectedProgram || !document.querySelector('input[name="program_type"]:checked')) {
                const pkl = document.querySelector('input[name="program_type"][value="PKL"]');
                if (pkl) pkl.checked = true;
            }

            // Show teacher section with Guru label
            teacherSection.classList.remove('hidden');
            teacherTitle.textContent = 'Data Guru Pembimbing';
            teacherDesc.textContent = 'Isi data guru pembimbing dari sekolah Anda.';
            teacherName.required = true;
            teacherPhone.required = true;

        } else if (status === 'Mahasiswa') {
            // Show programs KP & Magang
            pMahasiswa.forEach(el => el.classList.remove('hidden'));

            // Hide teacher section for Mahasiswa
            teacherSection.classList.add('hidden');
            teacherName.required = false;
            teacherPhone.required = false;

        } else {
            // Hide teacher section and remove required
            teacherSection.classList.add('hidden');
            teacherName.required = false;
            teacherPhone.required = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('input[name="applicant_status"]:checked')) {
            updateProgramType(true);
        }
    });

    function addParticipant() {
        const container = document.getElementById('participantsContainer');
        const idx = participantCount;
        
        const html = `
        <div class="participant-row bg-white p-4 border border-slate-200 rounded-lg space-y-3 relative mt-3" id="participant_${idx}">
            <button type="button" onclick="document.getElementById('participant_${idx}').remove()" class="absolute top-2 right-2 text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded hover:bg-rose-100">
                <i class="fa-solid fa-trash"></i> Hapus
            </button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="participants[${idx}][full_name]" required class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">NIS / NIM <span class="text-rose-500">*</span></label>
                    <input type="text" name="participants[${idx}][nis_nim]" required minlength="5" maxlength="50" placeholder="Min. 5 digit" class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                </div>
            </div>
        </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        participantCount++;
    }

    function showReview() {
        const form = document.getElementById('registrationForm');
        
        // Temporarily unhide formSection for validation (browser skips validation on hidden elements)
        const formSection = document.getElementById('formSection');
        formSection.style.display = '';
        
        // HTML5 Validation Check
        if (!form.reportValidity()) {
            return; // Don't proceed if form is invalid
        }

        // Validasi Panjang Nomor WhatsApp Ketua (10-15 digit)
        const leaderPhone = document.querySelector('input[name="leader_phone"]');
        if (leaderPhone && (leaderPhone.value.trim().length < 10 || leaderPhone.value.trim().length > 15)) {
            leaderPhone.focus();
            leaderPhone.setCustomValidity('Nomor WhatsApp Ketua harus terdiri dari 10 sampai 15 digit angka.');
            leaderPhone.reportValidity();
            return;
        } else if (leaderPhone) {
            leaderPhone.setCustomValidity('');
        }

        // Validasi Panjang Nomor WhatsApp Guru Pembimbing (10-15 digit jika status Siswa)
        const teacherPhone = document.getElementById('teacher_phone');
        const teacherSec = document.getElementById('teacherSection');
        if (teacherPhone && !teacherSec.classList.contains('hidden')) {
            if (teacherPhone.value.trim().length < 10 || teacherPhone.value.trim().length > 15) {
                teacherPhone.focus();
                teacherPhone.setCustomValidity('Nomor WhatsApp Guru Pembimbing harus terdiri dari 10 sampai 15 digit angka.');
                teacherPhone.reportValidity();
                return;
            } else {
                teacherPhone.setCustomValidity('');
            }
        }

        // Validasi Tanggal Pelaksanaan: Tanggal Selesai harus setelah Tanggal Mulai
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');
        if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
            const startD = new Date(startDateInput.value);
            const endD = new Date(endDateInput.value);
            if (endD <= startD) {
                endDateInput.focus();
                endDateInput.setCustomValidity('Tanggal selesai pelaksanaan harus setelah tanggal mulai pelaksanaan.');
                endDateInput.reportValidity();
                return;
            } else {
                endDateInput.setCustomValidity('');
            }
        }

        // Otomatis hilangkan notifikasi kesalahan input saat seluruh data sudah benar dan masuk ke tahap tinjau
        dismissErrorAlert();

        // Gather Data
        const status = document.querySelector('input[name="applicant_status"]:checked')?.value || '-';
        const program = document.querySelector('input[name="program_type"]:checked')?.value || '-';
        const inst = document.querySelector('input[name="institution_name"]').value;
        const major = document.querySelector('input[name="major"]').value;
        const start = document.querySelector('input[name="start_date"]').value;
        const end = document.querySelector('input[name="end_date"]').value;
        
        const selDept = document.querySelector('select[name="preferred_department_id"]');
        const deptText = selDept.options[selDept.selectedIndex].text;

        const fileName = document.querySelector('input[name="doc_surat_pengantar"]').files[0]?.name || '-';
        
        // Populate Review Section
        document.getElementById('rev_status_program').innerText = `${status} - Program: ${program}`;
        document.getElementById('rev_akademik').innerText = `${inst} (${major})`;
        document.getElementById('rev_periode').innerText = `${start} s/d ${end}`;
        document.getElementById('rev_bidang').innerText = selDept.value ? deptText : 'Tidak memilih preferensi khusus';

        // Populate Members
        const pNames = document.querySelectorAll('input[name^="participants"][name$="[full_name]"]');
        const pIds = document.querySelectorAll('input[name^="participants"][name$="[nis_nim]"]');
        let membersHtml = '';
        for(let i = 0; i < pNames.length; i++){
            let badge = i === 0 ? ' <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded ml-1">Ketua</span>' : '';
            membersHtml += `<li>${pNames[i].value} (${pIds[i].value})${badge}</li>`;
        }
        document.getElementById('rev_anggota').innerHTML = membersHtml;

        // Hide form visually but keep inputs accessible (NOT display:none)
        // Using position absolute + opacity 0 + height 0 so browser still submits the data
        formSection.style.position = 'absolute';
        formSection.style.opacity = '0';
        formSection.style.height = '0';
        formSection.style.overflow = 'hidden';
        formSection.style.pointerEvents = 'none';
        
        document.getElementById('reviewSection').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    function hideReview() {
        document.getElementById('reviewSection').classList.add('hidden');
        
        // Restore form visibility
        const formSection = document.getElementById('formSection');
        formSection.style.position = '';
        formSection.style.opacity = '';
        formSection.style.height = '';
        formSection.style.overflow = '';
        formSection.style.pointerEvents = '';
    }

    // Filter input WhatsApp/Telepon agar hanya menerima digit angka (0-9) secara realtime
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[type="tel"], input[name="leader_phone"], input[name="teacher_phone"], #teacher_phone')) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        }
    });

    document.addEventListener('keypress', function(e) {
        if (e.target.matches('input[type="tel"], input[name="leader_phone"], input[name="teacher_phone"], #teacher_phone')) {
            if (e.charCode !== 0 && (e.charCode < 48 || e.charCode > 57)) {
                e.preventDefault();
            }
        }
    });

    document.addEventListener('paste', function(e) {
        if (e.target.matches('input[type="tel"], input[name="leader_phone"], input[name="teacher_phone"], #teacher_phone')) {
            setTimeout(() => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            }, 0);
        }
    });

    // Ensure form data is fully visible before actual POST submission
    document.getElementById('registrationForm').addEventListener('submit', function() {
        const formSection = document.getElementById('formSection');
        formSection.style.position = '';
        formSection.style.opacity = '';
        formSection.style.height = '';
        formSection.style.overflow = '';
        formSection.style.pointerEvents = '';
    });

    // Fungsi untuk menghilangkan notifikasi error secara halus
    function dismissErrorAlert() {
        const alertEl = document.getElementById('serverErrorAlert');
        if (alertEl) {
            alertEl.style.transition = 'all 0.3s ease';
            alertEl.style.opacity = '0';
            setTimeout(() => {
                alertEl.style.display = 'none';
            }, 300);
        }
    }

    // Pantau perbaikan input form: jika semua sudah benar, hilangkan notifikasi error secara otomatis
    function checkFormErrorsResolution() {
        const form = document.getElementById('registrationForm');
        if (!form) return;

        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');
        let dateValid = true;

        if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
            if (new Date(endDateInput.value) <= new Date(startDateInput.value)) {
                dateValid = false;
            } else {
                endDateInput.setCustomValidity('');
            }
        }

        // Jika semua input wajib terisi dan valid
        if (form.checkValidity() && dateValid) {
            dismissErrorAlert();
        }
    }

    document.getElementById('registrationForm')?.addEventListener('input', checkFormErrorsResolution);
    document.getElementById('registrationForm')?.addEventListener('change', checkFormErrorsResolution);
</script>
@endsection
