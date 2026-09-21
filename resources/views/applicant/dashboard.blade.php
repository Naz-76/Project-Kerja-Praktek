@extends('layouts.app')

@section('title', 'Dashboard Pendaftar — Portal Magang & PKL Diskominfo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Welcome Header Banner (Figma Token #2F90E1) -->
    <div class="bg-[#2F90E1] text-white p-6 sm:p-10 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-1.5 text-center md:text-left flex-1">
            <h1 class="font-heading text-2xl sm:text-4xl font-extrabold tracking-tight">Selamat Datang {{ auth()->user()->name }}!</h1>
            <p class="text-blue-50 text-xs sm:text-sm font-medium">Pantau status verifikasi berkas, penempatan bidang, dan unduh surat balasan digital Anda.</p>
        </div>
    </div>

    <!-- Active Registration Section (Figma Reference: Beranda Pengguna 2.png) -->
    @if($activeRegistration)
        <div class="space-y-6">
            <!-- 3 Horizontal Status Cards (Design Awal Sesuai Figma) -->
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
                    <div class="w-11 h-11 {{ $activeRegistration->status == 'approved' ? 'bg-[#014495]' : ($activeRegistration->status == 'rejected' ? 'bg-[#8B0000]' : ($activeRegistration->status == 'completed' ? 'bg-[#014495]' : 'bg-slate-300 text-slate-600')) }} text-white rounded-xl flex items-center justify-center font-bold text-lg font-heading shrink-0 shadow-sm">
                        3
                    </div>
                    <div>
                        <h3 class="font-bold {{ $activeRegistration->status == 'approved' ? 'text-emerald-800' : ($activeRegistration->status == 'completed' ? 'text-sky-800' : ($activeRegistration->status == 'rejected' ? 'text-rose-800' : 'text-slate-800')) }} text-sm sm:text-base font-heading flex items-center gap-2">
                            <i class="fa-solid {{ $activeRegistration->status == 'approved' ? 'fa-circle-check text-emerald-600' : ($activeRegistration->status == 'completed' ? 'fa-flag-checkered text-sky-600' : ($activeRegistration->status == 'rejected' ? 'fa-circle-xmark text-rose-600' : 'fa-circle-info text-slate-400')) }} text-xs"></i>
                            {{ $activeRegistration->status == 'approved' ? 'Selamat anda berhasil lolos!' : ($activeRegistration->status == 'completed' ? 'Program Magang/PKL Telah Selesai' : ($activeRegistration->status == 'rejected' ? 'Pengajuan Ditolak' : 'Hasil Verifikasi Akhir')) }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-600 mt-1 leading-relaxed">
                            @if($activeRegistration->status == 'approved')
                                Selamat anda lolos untuk program magang / PKL silahkan melakukan tahap selanjutnya
                            @elseif($activeRegistration->status == 'completed')
                                Masa pelaksanaan program magang / PKL telah selesai. Terima kasih atas partisipasi dan kontribusi Anda di Diskominfo Garut.
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
                        @elseif($activeRegistration->status == 'completed')
                            <span class="px-4 py-2 bg-sky-100 text-sky-800 border border-sky-300 rounded-xl text-xs font-bold flex items-center gap-2">
                                <i class="fa-solid fa-flag-checkered"></i> SELESAI ({{ $activeRegistration->department->name ?? 'Bidang Terpilih' }})
                            </span>
                        @elseif($activeRegistration->status == 'rejected')
                            <span class="px-4 py-2 bg-rose-100 text-rose-800 border border-rose-300 rounded-xl text-xs font-bold flex items-center gap-2">
                                <i class="fa-solid fa-circle-xmark"></i> DITOLAK
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Status Specific Message -->
                @if($activeRegistration->status == 'completed')
                    <div class="p-5 bg-gradient-to-r from-sky-50 via-blue-50 to-indigo-50 border-2 border-sky-300 rounded-2xl text-xs text-sky-950 space-y-2 shadow-sm">
                        <div class="flex items-center gap-2.5 font-heading font-bold text-sm text-[#014495]">
                            <span class="w-8 h-8 rounded-xl bg-[#014495] text-white flex items-center justify-center shrink-0 shadow">
                                <i class="fa-solid fa-award text-base"></i>
                            </span>
                            <span>Program Selesai — Selamat!</span>
                        </div>
                        <p class="text-slate-700 leading-relaxed font-medium pl-10">
                            Selamat Anda telah menyelesaikan kegiatan sesuai program <strong class="text-slate-900 font-bold">{{ $activeRegistration->program_type }}</strong> di Dinas Komunikasi dan Informatika Kabupaten Garut. Silakan bawa berkas surat administrasi (seperti surat penilaian / lembar nilai) ke kantor Diskominfo Kabupaten Garut untuk pengesahan akhir oleh Pembimbing Lapangan.
                        </p>
                    </div>
                @elseif($activeRegistration->status == 'approved' && $activeRegistration->acceptance_message)
                    <div class="p-5 bg-emerald-50/80 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-1.5">
                        <span class="font-bold flex items-center gap-2 font-heading"><i class="fa-solid fa-comment-dots text-emerald-600"></i> Arahan & Catatan Admin:</span>
                        <p class="text-slate-700 leading-relaxed font-medium">{{ $activeRegistration->acceptance_message }}</p>
                    </div>
                @elseif($activeRegistration->status == 'rejected' && $activeRegistration->rejection_reason)
                    <!-- Blok Notifikasi Penolakan Murni (Tanpa Tombol) -->
                    <div class="p-5 bg-rose-50/80 border border-rose-200 rounded-2xl text-xs text-rose-900 space-y-1.5">
                        <span class="font-bold flex items-center gap-2 font-heading"><i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Alasan Penolakan dari Admin:</span>
                        <p class="text-slate-700 leading-relaxed font-medium">{{ $activeRegistration->rejection_reason }}</p>
                    </div>
                @endif

                <!-- Supervisor Info & Download Letter Resmi (Grid Proporsional & Rapi) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kartu Pembimbing Lapangan (Jika Diterima / Ada Pembimbing) -->
                    @if(in_array($activeRegistration->status, ['approved', 'completed']) && $activeRegistration->supervisor_name)
                        <div class="p-4 sm:p-5 rounded-2xl border border-slate-200 bg-slate-50 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-sm">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">Pembimbing Lapangan Diskominfo</span>
                                <span class="font-heading font-bold text-sm text-slate-900 block truncate">{{ $activeRegistration->supervisor_name }}</span>
                                @if($activeRegistration->supervisor_position)
                                    <span class="text-xs text-slate-600 block font-medium truncate">{{ $activeRegistration->supervisor_position }}</span>
                                @endif
                                @if($activeRegistration->supervisor_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $activeRegistration->supervisor_phone) }}" target="_blank" class="text-xs text-emerald-600 hover:text-emerald-700 font-bold inline-flex items-center gap-1.5 mt-1">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $activeRegistration->supervisor_phone }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Kartu & Tombol Surat Balasan Resmi Diskominfo -->
                    @if($activeRegistration->replyLetter)
                        <div class="p-4 sm:p-5 rounded-2xl border-2 border-emerald-200 bg-emerald-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm {{ !in_array($activeRegistration->status, ['approved', 'completed']) || !$activeRegistration->supervisor_name ? 'md:col-span-2' : '' }}">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-sm">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] text-emerald-800 font-bold uppercase tracking-wider block">Surat Balasan Resmi Diskominfo</span>
                                    <span class="font-heading font-bold text-xs sm:text-sm text-slate-900 block truncate">
                                        {{ $activeRegistration->replyLetter->letter_number ? 'No. ' . $activeRegistration->replyLetter->letter_number : 'Dokumen Surat Digital Resmi' }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 block">Diterbitkan: {{ $activeRegistration->replyLetter->created_at ? $activeRegistration->replyLetter->created_at->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                            </div>
                            <!-- Tombol Unduh Surat Balasan Resmi -->
                            <a href="{{ asset('storage/' . $activeRegistration->replyLetter->file_path) }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 font-heading shrink-0 active:scale-[0.98]">
                                <i class="fa-solid fa-download"></i>
                                <span>Unduh Surat Balasan</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Footer Aksi: Tombol Daftar Ulang (Di Luar Notifikasi) & Link Rincian -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100">
                    @if($activeRegistration->status == 'rejected')
                        <a href="{{ route('applicant.registration.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-md transition-all text-xs font-heading active:scale-[0.98]">
                            <i class="fa-solid fa-rotate-right"></i>
                            <span>Ajukan Pendaftaran Ulang</span>
                        </a>
                    @else
                        <div></div> <!-- spacer -->
                    @endif
                    <a href="{{ route('applicant.my-registration.show', $activeRegistration->id) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#014495] hover:text-[#002f6c]">
                        <span>Lihat Seluruh Anggota Tim & Detail Berkas</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
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

    <!-- ========================================================================= -->
    <!-- BLOK RIWAYAT PENDAFTARAN LENGKAP -->
    <!-- ========================================================================= -->
    <div class="space-y-4 pt-2">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2.5">
                    <h2 class="font-heading text-lg sm:text-xl font-bold text-slate-900">Riwayat Pendaftaran Anda</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-[#014495] border border-blue-200">
                        {{ $registrations->count() }} Pengajuan
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">Catatan seluruh permohonan magang & PKL yang pernah Anda kirimkan ke Diskominfo Garut</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">
            <!-- Petunjuk Geser Tabel Horizontal (Desktop & Tablet) -->
            <div class="hidden md:flex items-center justify-between text-[11px] text-slate-500 bg-blue-50/70 px-5 py-2.5 border-b border-blue-100">
                <span class="inline-flex items-center gap-1.5 font-medium text-[#014495]">
                    <i class="fa-solid fa-arrows-left-right text-xs"></i>
                    <span>Tabel riwayat dapat digeser ke samping: Klik & tarik mouse atau gulir horizontal untuk melihat seluruh data</span>
                </span>
                <span class="text-slate-400 text-[10px]">Total Riwayat: {{ $registrations->count() }}</span>
            </div>

            <!-- Tampilan Desktop & Tablet (Tabel Standar) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase text-[11px] border-b border-slate-200 font-heading">
                        <tr>
                            <th class="p-4 text-center w-12">No</th>
                            <th class="p-4">Tanggal Pengajuan</th>
                            <th class="p-4">Program & Asal Institusi</th>
                            <th class="p-4">Tipe Peserta</th>
                            <th class="p-4">Bidang Kerja</th>
                            <th class="p-4 text-center">Status Akhir</th>
                            <th class="p-4 text-center">Surat Balasan</th>
                            <th class="p-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($registrations as $idx => $reg)
                            @php
                                if ($reg->status == 'approved') {
                                    $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                                    $badgeLabel = 'Diterima';
                                    $badgeIcon = 'fa-circle-check';
                                } elseif ($reg->status == 'completed') {
                                    $badgeClass = 'bg-sky-100 text-sky-800 border-sky-300';
                                    $badgeLabel = 'Selesai';
                                    $badgeIcon = 'fa-flag-checkered';
                                } elseif ($reg->status == 'rejected') {
                                    $badgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
                                    $badgeLabel = 'Ditolak';
                                    $badgeIcon = 'fa-circle-xmark';
                                } else {
                                    $badgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
                                    $badgeLabel = 'Pending';
                                    $badgeIcon = 'fa-clock';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $activeRegistration && $activeRegistration->id == $reg->id ? 'bg-blue-50/30' : '' }}">
                                <td class="p-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block">
                                        {{ $reg->created_at ? $reg->created_at->translatedFormat('d M Y') : '-' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block">
                                        {{ $reg->created_at ? $reg->created_at->format('H:i') : '-' }} WIB
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="font-heading font-bold text-sm text-slate-900 block">
                                        Program {{ $reg->program_type }}
                                    </span>
                                    <span class="text-xs text-slate-600 block truncate max-w-xs" title="{{ $reg->institution->institution_name ?? '-' }}">
                                        {{ $reg->institution->institution_name ?? '-' }}
                                    </span>
                                    @if($reg->leader && $reg->leader->major)
                                        <span class="text-[10px] text-slate-400 block truncate max-w-xs">Jurusan: {{ $reg->leader->major }}</span>
                                    @endif
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $reg->participant_count > 1 ? 'Kelompok (' . $reg->participant_count . ')' : 'Individu' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($reg->department)
                                        <span class="font-bold text-[#014495] block text-xs">{{ $reg->department->name }}</span>
                                        <span class="text-[10px] text-emerald-600 block">Bidang Definitif</span>
                                    @else
                                        <span class="font-medium text-slate-700 block text-xs">{{ $reg->preferredDepartment->name ?? 'Semua Bidang' }}</span>
                                        <span class="text-[10px] text-slate-400 block">Bidang Pilihan</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold border inline-flex items-center gap-1.5 {{ $badgeClass }}">
                                        <i class="fa-solid {{ $badgeIcon }}"></i>
                                        <span>{{ $badgeLabel }}</span>
                                    </span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($reg->replyLetter)
                                        <a href="{{ asset('storage/' . $reg->replyLetter->file_path) }}" target="_blank" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-[11px] font-bold inline-flex items-center gap-1" title="Unduh Surat Balasan">
                                            <i class="fa-solid fa-file-pdf"></i> Unduh
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">Belum Terbit</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <a href="{{ route('applicant.my-registration.show', $reg->id) }}" class="px-3 py-1.5 bg-[#014495] hover:bg-[#002f6c] text-white rounded-xl font-bold text-xs transition-all font-heading inline-flex items-center gap-1.5 shadow-sm active:scale-[0.98]">
                                        <span>Rincian</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">
                                    Belum ada riwayat pendaftaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tampilan Mobile: Card List Responsif (Anti Geser Horizontal di HP) -->
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($registrations as $idx => $reg)
                    @php
                        if ($reg->status == 'approved') {
                            $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                            $badgeLabel = 'Diterima';
                            $badgeIcon = 'fa-circle-check';
                        } elseif ($reg->status == 'completed') {
                            $badgeClass = 'bg-sky-100 text-sky-800 border-sky-300';
                            $badgeLabel = 'Selesai';
                            $badgeIcon = 'fa-flag-checkered';
                        } elseif ($reg->status == 'rejected') {
                            $badgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
                            $badgeLabel = 'Ditolak';
                            $badgeIcon = 'fa-circle-xmark';
                        } else {
                            $badgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
                            $badgeLabel = 'Pending';
                            $badgeIcon = 'fa-clock';
                        }
                    @endphp
                    <div class="p-4 space-y-3 hover:bg-slate-50/50 transition-colors {{ $activeRegistration && $activeRegistration->id == $reg->id ? 'bg-blue-50/20' : '' }}">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    Pengajuan #{{ $idx + 1 }} • {{ $reg->created_at ? $reg->created_at->translatedFormat('d M Y') : '-' }}
                                </span>
                                <h3 class="font-heading font-bold text-sm text-slate-900 mt-0.5">
                                    Program {{ $reg->program_type }}
                                </h3>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border shrink-0 inline-flex items-center gap-1 {{ $badgeClass }}">
                                <i class="fa-solid {{ $badgeIcon }} text-[10px]"></i>
                                <span>{{ $badgeLabel }}</span>
                            </span>
                        </div>

                        <div class="text-xs text-slate-600 space-y-1">
                            <p class="font-medium text-slate-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-school text-[#014495] text-xs"></i>
                                <span>{{ $reg->institution->institution_name ?? '-' }}</span>
                            </p>
                            @if($reg->leader && $reg->leader->major)
                                <p class="text-[11px] text-slate-500 pl-4">
                                    Jurusan: {{ $reg->leader->major }}
                                </p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 text-[11px]">
                            <div>
                                <span class="text-[10px] text-slate-400 block">Bidang</span>
                                <span class="font-bold text-slate-800 block truncate">
                                    {{ $reg->department->name ?? ($reg->preferredDepartment->name ?? 'Semua Bidang') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block">Tipe Peserta</span>
                                <span class="font-bold text-slate-800 block">
                                    {{ $reg->participant_count > 1 ? 'Kelompok (' . $reg->participant_count . ' Org)' : 'Individu' }}
                                </span>
                            </div>
                        </div>

                        @if($reg->replyLetter)
                            <div class="flex items-center justify-between p-2.5 bg-emerald-50/80 border border-emerald-200 rounded-xl text-xs">
                                <span class="text-emerald-800 font-medium inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-file-pdf text-emerald-600"></i> Surat Balasan Resmi
                                </span>
                                <a href="{{ asset('storage/' . $reg->replyLetter->file_path) }}" target="_blank" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] rounded-lg">
                                    Unduh
                                </a>
                            </div>
                        @endif

                        <a href="{{ route('applicant.my-registration.show', $reg->id) }}" class="w-full py-2 bg-[#014495] hover:bg-[#002f6c] text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-1.5 font-heading">
                            <span>Lihat Rincian Pengajuan Lengkap</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        Belum ada riwayat pendaftaran.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
