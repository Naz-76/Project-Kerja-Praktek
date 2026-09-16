@extends('layouts.app')

@section('title', 'Dashboard Pendaftar — Portal Magang & PKL Diskominfo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Welcome Header Banner (Figma Token #2F90E1) -->
    <div class="bg-[#2F90E1] text-white p-8 sm:p-10 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-1.5 text-center md:text-left flex-1">
            <h1 class="font-heading text-2xl sm:text-4xl font-extrabold tracking-tight">Selamat Datang {{ auth()->user()->name }}!</h1>
            <p class="text-blue-50 text-xs sm:text-sm font-medium">Pantau status verifikasi berkas, penempatan bidang, dan unduh surat balasan digital Anda.</p>
        </div>
        @if($activeRegistration)
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('applicant.my-registration.show', $activeRegistration->id) }}" class="px-5 py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-2 text-xs font-heading active:scale-[0.98]">
                    <i class="fa-solid fa-file-lines"></i> Rincian Pengajuan
                </a>
            </div>
        @endif
    </div>

    <!-- Active Registration Section (Beranda Pengguna 2.png) -->
    @if($activeRegistration)
        <div class="space-y-6">
            <!-- 3 Horizontal Status Cards (Figma Reference: Beranda Pengguna 2.png) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Step 1: Surat Berhasil Dikirim -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md flex items-start gap-4">
                    <div class="w-11 h-11 bg-[#014495] text-white rounded-xl flex items-center justify-center font-bold text-lg font-heading shrink-0 shadow-sm">
                        1
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base font-heading flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-[#0B6FBB] text-xs"></i> Surat berhasil di kirim
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-600 mt-1 leading-relaxed">
                            Surat dikirim dan akan diverifikasi oleh admin / staff TU
                        </p>
                    </div>
                </div>

                <!-- Step 2: Surat Sedang Diverifikasi -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md flex items-start gap-4">
                    <div class="w-11 h-11 {{ $activeRegistration->status != 'pending' ? 'bg-[#014495]' : 'bg-[#0B6FBB]' }} text-white rounded-xl flex items-center justify-center font-bold text-lg font-heading shrink-0 shadow-sm">
                        2
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base font-heading flex items-center gap-2">
                            <i class="fa-solid {{ $activeRegistration->status == 'pending' ? 'fa-spinner animate-spin text-amber-500' : 'fa-certificate text-[#0B6FBB]' }} text-xs"></i> Surat Sedang diverifikasi
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-600 mt-1 leading-relaxed">
                            Surat sedang diverifikasi / ditinjau untuk menentukan lolos atau tidak
                        </p>
                    </div>
                </div>

                <!-- Step 3: Hasil Keputusan Akhir -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md flex items-start gap-4">
                    <div class="w-11 h-11 {{ $activeRegistration->status == 'approved' ? 'bg-[#014495]' : ($activeRegistration->status == 'rejected' ? 'bg-[#8B0000]' : 'bg-slate-300 text-slate-600') }} text-white rounded-xl flex items-center justify-center font-bold text-lg font-heading shrink-0 shadow-sm">
                        3
                    </div>
                    <div>
                        <h3 class="font-bold {{ $activeRegistration->status == 'approved' ? 'text-emerald-800' : ($activeRegistration->status == 'rejected' ? 'text-rose-800' : 'text-slate-800') }} text-sm sm:text-base font-heading flex items-center gap-2">
                            <i class="fa-solid {{ $activeRegistration->status == 'approved' ? 'fa-circle-check text-emerald-600' : ($activeRegistration->status == 'rejected' ? 'fa-circle-xmark text-rose-600' : 'fa-circle-info text-slate-400') }} text-xs"></i>
                            {{ $activeRegistration->status == 'approved' ? 'Selamat anda berhasil lolos!' : ($activeRegistration->status == 'rejected' ? 'Pengajuan Ditolak' : 'Hasil Verifikasi Akhir') }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-600 mt-1 leading-relaxed">
                            @if($activeRegistration->status == 'approved')
                                Selamat anda lolos untuk program magang / PKL silahkan melakukan tahap selanjutnya
                            @elseif($activeRegistration->status == 'rejected')
                                Berkas belum memenuhi persyaratan kuota / administrasi. Silakan periksa catatan admin.
                            @else
                                Menunggu keputusan verifikasi dan penempatan bidang resmi dari Diskominfo.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Information Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <span class="text-xs font-bold text-[#0B6FBB] uppercase tracking-wider font-heading">Ringkasan Pengajuan Aktif</span>
                        <h2 class="font-heading text-lg sm:text-xl font-bold text-slate-900 mt-0.5">
                            Program {{ $activeRegistration->program_type }} — {{ $activeRegistration->institution->institution_name ?? '-' }}
                        </h2>
                    </div>
                    <div>
                        @if($activeRegistration->status == 'pending')
                            <span class="px-4 py-2 bg-amber-100 text-amber-800 border border-amber-300 rounded-xl text-xs font-bold flex items-center gap-2">
                                <i class="fa-solid fa-clock animate-pulse"></i> Menunggu Verifikasi
                            </span>
                        @elseif($activeRegistration->status == 'approved')
                            <span class="px-4 py-2 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold flex items-center gap-2">
                                <i class="fa-solid fa-circle-check"></i> DITERIMA ({{ $activeRegistration->department->name ?? 'Bidang Terpilih' }})
                            </span>
                        @elseif($activeRegistration->status == 'rejected')
                            <span class="px-4 py-2 bg-rose-100 text-rose-800 border border-rose-300 rounded-xl text-xs font-bold flex items-center gap-2">
                                <i class="fa-solid fa-circle-xmark"></i> DITOLAK
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Catatan / Arahan Admin -->
                @if($activeRegistration->status == 'approved' && $activeRegistration->acceptance_message)
                    <div class="p-5 bg-emerald-50/80 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-1.5">
                        <span class="font-bold flex items-center gap-2 font-heading"><i class="fa-solid fa-comment-dots text-emerald-600"></i> Arahan & Catatan Admin:</span>
                        <p class="text-slate-700 leading-relaxed font-medium">{{ $activeRegistration->acceptance_message }}</p>
                    </div>
                @elseif($activeRegistration->status == 'rejected' && $activeRegistration->rejection_reason)
                    <div class="p-5 bg-rose-50/80 border border-rose-200 rounded-2xl text-xs text-rose-900 space-y-1.5">
                        <span class="font-bold flex items-center gap-2 font-heading"><i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Alasan Penolakan dari Admin:</span>
                        <p class="text-slate-700 leading-relaxed font-medium">{{ $activeRegistration->rejection_reason }}</p>
                    </div>
                @endif

                <!-- Supervisor Info & Download Letter -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($activeRegistration->status == 'approved' && $activeRegistration->supervisor_name)
                        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-base shrink-0">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase block">Pembimbing Lapangan Diskominfo</span>
                                <span class="font-bold text-xs text-slate-900">{{ $activeRegistration->supervisor_name }}</span>
                                @if($activeRegistration->supervisor_position)
                                    <span class="text-[11px] text-slate-600 block font-medium">{{ $activeRegistration->supervisor_position }}</span>
                                @endif
                                @if($activeRegistration->supervisor_phone)
                                    <span class="text-[11px] text-slate-600 block font-medium mt-0.5"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i>{{ $activeRegistration->supervisor_phone }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($activeRegistration->replyLetter)
                        <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/60 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base shrink-0">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] text-emerald-700 font-bold uppercase block">Surat Balasan Resmi</span>
                                    <span class="font-bold text-xs text-slate-900">Dokumen Digital Diskominfo</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $activeRegistration->replyLetter->file_path) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 font-heading">
                                <i class="fa-solid fa-download"></i> Unduh
                            </a>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-2">
                    @if($activeRegistration->status == 'rejected')
                        <a href="{{ route('applicant.registration.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-md transition-all text-xs font-heading">
                            <i class="fa-solid fa-rotate-right"></i> Ajukan Pendaftaran Ulang
                        </a>
                    @else
                        <div></div> <!-- spacer -->
                    @endif
                    <a href="{{ route('applicant.my-registration.show', $activeRegistration->id) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#014495] hover:text-[#002f6c]">
                        Lihat Seluruh Anggota Tim & Detail Berkas →
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State (Beranda Pengguna.png) -->
        <div class="bg-white p-10 sm:p-14 rounded-3xl border border-slate-100 shadow-2xl text-center space-y-5">
            <div class="w-16 h-16 bg-[#2F90E1] text-white rounded-2xl flex items-center justify-center mx-auto text-3xl shadow-md">
                <i class="fa-solid fa-file-circle-plus"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900">Belum ada Pengajuan Pendaftaran nih!</h3>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
                    Anda belum memiliki berkas pengajuan magang. Klik tombol di bawah untuk mengisi formulir pendaftaran melalui 5 langkah.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('applicant.registration.create') }}" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-lg transition-all text-sm font-heading active:scale-[0.98]">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Buat Pengajuan Baru</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
