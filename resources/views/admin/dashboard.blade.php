@extends('layouts.admin')

@section('title', 'Dashboard Admin — Diskominfo Garut')

@section('content')
<div class="space-y-8">
    <!-- Header Admin Banner (Figma Tokens: #014495 & #0B6FBB) -->
    <div class="bg-gradient-to-r from-[#014495] to-[#0B6FBB] text-white p-8 sm:p-10 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <span class="text-xs font-bold px-3 py-1 bg-white/20 text-white rounded-full font-heading backdrop-blur-sm">Panel Utama Kepegawaian</span>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold">Dashboard Pengelola Sistem PORMA</h1>
            <p class="text-blue-50 text-xs sm:text-sm max-w-xl">Ringkasan aktivitas pendaftaran Siswa PKL & Mahasiswa, status verifikasi berkas, dan alokasi kuota per bidang secara real-time.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.verification.index') }}" class="px-5 py-3 bg-white text-[#014495] hover:bg-blue-50 font-bold rounded-xl shadow-lg transition-all text-xs flex items-center gap-2 font-heading active:scale-[0.98]">
                <i class="fa-solid fa-list-check"></i> Verifikasi Pengajuan
            </a>
            <a href="{{ route('admin.departments.index') }}" class="px-5 py-3 bg-[#014495]/40 hover:bg-[#014495]/60 text-white border border-white/30 font-bold rounded-xl transition-all text-xs flex items-center gap-2 font-heading">
                <i class="fa-solid fa-layer-group"></i> Kelola Kuota
            </a>
        </div>
    </div>

    <!-- Primary Metric Cards (Status Berkas) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card Total Pendaftaran -->
        <a href="{{ route('admin.verification.index', ['status' => 'all']) }}" class="block p-6 bg-white rounded-3xl border border-slate-200 shadow-md space-y-2 hover:shadow-xl hover:border-blue-300 hover:-translate-y-1.5 transition-all duration-300 cursor-pointer group select-none">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider font-heading group-hover:text-[#014495] transition-colors">Total Pendaftaran</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-300 group-hover:text-[#014495] transition-colors"></i>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-heading text-3xl font-extrabold text-slate-900 group-hover:text-[#014495] transition-colors">{{ $totalRegistrations }}</span>
                <div class="w-11 h-11 bg-blue-50 text-[#014495] group-hover:bg-[#014495] group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-medium">Semua berkas masuk</span>
                <span class="text-[10px] font-bold text-[#014495] opacity-0 group-hover:opacity-100 transition-opacity">Lihat Data →</span>
            </div>
        </a>

        <!-- Card Menunggu Verifikasi -->
        <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}" class="block p-6 bg-white rounded-3xl border border-slate-200 shadow-md space-y-2 hover:shadow-xl hover:border-amber-300 hover:-translate-y-1.5 transition-all duration-300 cursor-pointer group select-none">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider font-heading">Menunggu Verifikasi</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-300 group-hover:text-amber-600 transition-colors"></i>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-heading text-3xl font-extrabold text-amber-600">{{ $pendingCount }}</span>
                <div class="w-11 h-11 bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px]">
                <span class="text-amber-600 font-semibold">{{ $totalPendingParticipants }} calon peserta</span>
                <span class="text-[10px] font-bold text-amber-600 opacity-0 group-hover:opacity-100 transition-opacity">Tinjau →</span>
            </div>
        </a>

        <!-- Card Disetujui / Diterima -->
        <a href="{{ route('admin.verification.index', ['status' => 'approved']) }}" class="block p-6 bg-white rounded-3xl border border-slate-200 shadow-md space-y-2 hover:shadow-xl hover:border-emerald-300 hover:-translate-y-1.5 transition-all duration-300 cursor-pointer group select-none">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider font-heading">Disetujui / Diterima</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-300 group-hover:text-emerald-600 transition-colors"></i>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-heading text-3xl font-extrabold text-emerald-600">{{ $approvedCount }}</span>
                <div class="w-11 h-11 bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px]">
                <span class="text-emerald-600 font-semibold">{{ $totalApprovedParticipants }} peserta aktif</span>
                <span class="text-[10px] font-bold text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity">Lihat Data →</span>
            </div>
        </a>

        <!-- Card Ditolak -->
        <a href="{{ route('admin.verification.index', ['status' => 'rejected']) }}" class="block p-6 bg-white rounded-3xl border border-slate-200 shadow-md space-y-2 hover:shadow-xl hover:border-rose-300 hover:-translate-y-1.5 transition-all duration-300 cursor-pointer group select-none">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider font-heading">Ditolak</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-300 group-hover:text-rose-600 transition-colors"></i>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-heading text-3xl font-extrabold text-rose-600">{{ $rejectedCount }}</span>
                <div class="w-11 h-11 bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-medium">Berkas tidak memenuhi</span>
                <span class="text-[10px] font-bold text-rose-600 opacity-0 group-hover:opacity-100 transition-opacity">Lihat Alasan →</span>
            </div>
        </a>
        <!-- Card Selesai Magang -->
        <a href="{{ route('admin.verification.index', ['status' => 'completed']) }}" class="block p-6 bg-white rounded-3xl border border-slate-200 shadow-md space-y-2 hover:shadow-xl hover:border-sky-300 hover:-translate-y-1.5 transition-all duration-300 cursor-pointer group select-none">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-sky-600 uppercase tracking-wider font-heading">Selesai Magang</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-300 group-hover:text-sky-600 transition-colors"></i>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-heading text-3xl font-extrabold text-sky-600">{{ $completedCount }}</span>
                <div class="w-11 h-11 bg-sky-50 text-sky-600 group-hover:bg-sky-600 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-flag-checkered"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-medium">Program telah usai</span>
                <span class="text-[10px] font-bold text-sky-600 opacity-0 group-hover:opacity-100 transition-opacity">Lihat Data →</span>
            </div>
        </a>
    </div>



    <!-- Kuota Real-time per Bidang -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="font-heading text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-[#014495]"></i> Alokasi Kuota Terpakai Per Bidang
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($departments as $dept)
                @php
                    $quota = $dept->slotQuotas->first();
                    $total = $quota ? $quota->quota_total : 0;
                    $used = $quota ? $quota->quota_used : 0;
                    $remaining = max(0, $total - $used);
                    $percent = $total > 0 ? round(($used / $total) * 100) : 0;
                @endphp
                <div class="p-3.5 bg-slate-50/60 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-800 font-heading truncate">{{ $dept->name }}</span>
                    </div>
                    <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-[#0B6FBB] to-[#014495] h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-500 font-medium">
                        <span>Terpakai: <b>{{ $used }}</b> / {{ $total }}</span>
                        <span class="font-bold text-[#014495]">{{ $remaining }} Slot Tersisa</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tabel 1: Antrean Pendaftar Menunggu Verifikasi -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-md space-y-4 sm:space-y-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
                <h2 class="font-heading text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock text-amber-500"></i> Antrean Pendaftar Menunggu Verifikasi & Penempatan
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Tinjau kelengkapan dokumen, identitas ketua/anggota, serta kontak pendamping sekolah/kampus.</p>
            </div>
            <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}" class="text-xs font-bold text-[#014495] hover:underline font-heading">
                Lihat Semua Antrean ({{ $pendingCount }}) →
            </a>
        </div>

        <!-- Petunjuk Geser Tabel (Desktop & Tablet) -->
        <div class="hidden md:flex items-center justify-between text-[11px] text-slate-500 bg-blue-50/70 px-3.5 py-1.5 rounded-xl border border-blue-100">
            <span class="inline-flex items-center gap-1.5 font-medium text-[#014495]">
                <i class="fa-solid fa-arrows-left-right text-xs"></i>
                <span>Tabel dapat digeser ke samping: Klik & tarik mouse atau gulir horizontal untuk melihat kolom lengkap</span>
            </span>
            <span class="text-slate-400 text-[10px]">Tersedia {{ $pendingRegistrations->count() }} data</span>
        </div>

        <!-- Tampilan Desktop (Tabel Standar) -->
        <div class="hidden md:block overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200 font-heading">
                    <tr>
                        <th class="p-4 text-center w-12">No</th>
                        <th class="p-4">Pendaftar / Ketua</th>
                        <th class="p-4">Status & Program</th>
                        <th class="p-4">Institusi & Pembimbing</th>
                        <th class="p-4">Bidang & Pembimbing Lapangan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingRegistrations as $reg)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 text-center font-bold text-slate-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block font-heading text-sm">{{ $reg->leader->full_name ?? $reg->user->name }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-slate-500 font-mono text-[11px]">NIS/NIM: {{ $reg->leader->nis_nim ?? '-' }}</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[10px] font-semibold">
                                        {{ $reg->participant_count > 1 ? 'Kelompok (' . $reg->participant_count . ' Org)' : 'Individu' }}
                                    </span>
                                </div>
                                <span class="text-slate-400 text-[10px] block mt-1"><i class="fa-regular fa-clock mr-1"></i>Masuk: {{ $reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            <td class="p-4 font-semibold text-slate-800">
                                @if(strtolower($reg->applicant_status) == 'siswa')
                                    <span class="px-2.5 py-1 bg-blue-50 text-[#014495] rounded-full border border-blue-200 font-bold text-[10px] font-heading inline-block">
                                        <i class="fa-solid fa-school mr-1"></i> Siswa PKL
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-800 rounded-full border border-indigo-200 font-bold text-[10px] font-heading inline-block">
                                        <i class="fa-solid fa-graduation-cap mr-1"></i> Mahasiswa {{ $reg->program_type }}
                                    </span>
                                @endif
                                @if($reg->start_date && $reg->end_date)
                                    <div class="mt-1.5 text-[11px] text-slate-600">
                                        <span class="font-medium block"><i class="fa-regular fa-calendar text-[#2F90E1] mr-1"></i>{{ \Carbon\Carbon::parse($reg->start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($reg->end_date)->format('d/m/y') }}</span>
                                        <span class="text-[10px] text-slate-400 block">({{ \Carbon\Carbon::parse($reg->start_date)->diffInDays(\Carbon\Carbon::parse($reg->end_date)) + 1 }} hari)</span>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-slate-800 block">{{ $reg->institution->institution_name ?? '-' }}</span>
                                <span class="text-slate-500 text-[11px] block">Jurusan: {{ $reg->leader->major ?? '-' }}</span>
                                @if($reg->institution && $reg->institution->teacher_name)
                                    <div class="mt-1 pt-1 border-t border-slate-100 text-[11px] text-slate-600">
                                        <span class="font-medium text-slate-800 block"><i class="fa-solid fa-chalkboard-user text-[#0B6FBB] mr-1"></i>{{ $reg->institution->teacher_name }}</span>
                                        @if($reg->institution->teacher_phone)
                                            <span class="text-slate-400 text-[10px]"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i>{{ $reg->institution->teacher_phone }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($reg->department)
                                    <span class="font-bold text-emerald-700 font-heading block">{{ $reg->department->name }}</span>
                                    @if($reg->supervisor_name)
                                        <div class="mt-1.5 p-2 bg-blue-50/70 border border-blue-100 rounded-lg">
                                            <span class="font-bold text-[#014495] block text-[11px]"><i class="fa-solid fa-user-tie mr-1"></i>{{ $reg->supervisor_name }}</span>
                                            <span class="text-[10px] text-slate-500 block">{{ $reg->supervisor_position ?: 'Pembimbing Lapangan' }}</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-amber-600 font-medium italic mt-0.5 block">Belum ada pembimbing lapangan</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic block">Belum Ditempatkan</span>
                                    @if($reg->preferredDepartment)
                                        <span class="block text-[10px] text-[#014495] font-semibold">(Pilihan: {{ $reg->preferredDepartment->name }})</span>
                                    @endif
                                @endif
                            </td>
                            <td class="p-4">
                                @if($reg->status == 'pending')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> Pending
                                    </span>
                                @elseif($reg->status == 'approved')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> Diterima
                                    </span>
                                @elseif($reg->status == 'rejected')
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                    </span>
                                @elseif($reg->status == 'completed')
                                    <span class="px-3 py-1 bg-sky-100 text-sky-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-flag-checkered"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <a href="{{ route('admin.verification.show', $reg->id) }}" class="inline-flex items-center justify-center gap-1.5 w-32 py-2 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs transition-all font-heading shadow-sm hover:shadow active:scale-[0.98] whitespace-nowrap">
                                    <i class="fa-solid fa-sliders text-[11px]"></i>
                                    <span>{{ in_array($reg->status, ['approved', 'completed']) ? 'Ubah / Detail' : 'Tinjau' }}</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">Tidak ada pendaftar yang menunggu verifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile (Card List Khusus Layar HP - Tanpa Perlu Geser Kanan-Kiri) -->
        <div class="block md:hidden divide-y divide-slate-100 rounded-2xl border border-slate-200 bg-white overflow-hidden text-xs">
            @forelse($pendingRegistrations as $reg)
                <div class="p-4 space-y-3 hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block">Antrean #{{ $loop->iteration }}</span>
                            <h3 class="font-heading font-bold text-sm text-slate-900 mt-0.5">{{ $reg->leader->full_name ?? $reg->user->name }}</h3>
                            <span class="text-slate-500 font-mono text-[11px]">NIS/NIM: {{ $reg->leader->nis_nim ?? '-' }}</span>
                        </div>
                        @if($reg->status == 'pending')
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0">
                                <i class="fa-solid fa-clock text-[9px]"></i> Pending
                            </span>
                        @elseif($reg->status == 'approved')
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> Diterima
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                        @if(strtolower($reg->applicant_status) == 'siswa')
                            <span class="px-2 py-0.5 bg-blue-50 text-[#014495] rounded-md border border-blue-200 font-bold text-[10px]">
                                <i class="fa-solid fa-school mr-1"></i> Siswa PKL
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-800 rounded-md border border-indigo-200 font-bold text-[10px]">
                                <i class="fa-solid fa-graduation-cap mr-1"></i> Mahasiswa
                            </span>
                        @endif
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-medium text-[10px]">
                            {{ $reg->participant_count > 1 ? $reg->participant_count . ' Orang' : 'Individu' }}
                        </span>
                        @if($reg->start_date && $reg->end_date)
                            <span class="text-slate-500 text-[10px] ml-auto">
                                <i class="fa-regular fa-calendar text-[#2F90E1]"></i> {{ \Carbon\Carbon::parse($reg->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($reg->end_date)->format('d/m/y') }}
                            </span>
                        @endif
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 space-y-1 text-[11px]">
                        <div class="flex items-start gap-1.5">
                            <i class="fa-solid fa-building-columns text-slate-400 mt-0.5 shrink-0"></i>
                            <div>
                                <span class="font-semibold text-slate-800">{{ $reg->institution->institution_name ?? '-' }}</span>
                                <span class="text-slate-500 block text-[10px]">Jurusan: {{ $reg->leader->major ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-1.5 pt-1 border-t border-slate-200/50">
                            <i class="fa-solid fa-layer-group text-slate-400 mt-0.5 shrink-0"></i>
                            <div>
                                @if($reg->department)
                                    <span class="font-bold text-emerald-700">{{ $reg->department->name }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum Ditempatkan</span>
                                    @if($reg->preferredDepartment)
                                        <span class="text-[#014495] font-semibold text-[10px] block">(Pilihan: {{ $reg->preferredDepartment->name }})</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Full Width di Layar HP -->
                    <a href="{{ route('admin.verification.show', $reg->id) }}" class="w-full py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs transition-all font-heading shadow-sm flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-sliders text-xs"></i>
                        <span>{{ in_array($reg->status, ['approved', 'completed']) ? 'Ubah / Detail Penempatan' : 'Tinjau Berkas Pengajuan' }}</span>
                    </a>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 font-medium">Tidak ada pendaftar yang menunggu verifikasi.</div>
            @endforelse
        </div>
    </div>



    <!-- Bagian 3: Rekap Beban Bimbingan per Pembimbing Lapangan Diskominfo -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
                <h2 class="font-heading text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-[#014495]"></i> Rekap Penugasan Pembimbing Lapangan Diskominfo
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Pantau jumlah kelompok dan total siswa/mahasiswa yang saat ini sedang dibina oleh masing-masing pembimbing internal.</p>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full font-heading">
                {{ count($supervisorWorkloads) }} Pembimbing Aktif
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($supervisorWorkloads as $sup)
                <div class="p-5 bg-gradient-to-br from-white to-slate-50/70 rounded-2xl border-2 border-slate-200 hover:border-[#2F90E1] shadow-sm space-y-4 transition-all">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#014495] text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 font-heading text-sm">{{ $sup['name'] }}</h3>
                                <p class="text-[11px] text-slate-500 font-medium leading-tight">{{ $sup['position'] }}</p>
                                @if($sup['phone'])
                                    <p class="text-[11px] text-slate-500 font-medium leading-tight mt-0.5"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i>{{ $sup['phone'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-center pt-2 border-t border-slate-100">
                        <div class="p-2.5 bg-blue-50/80 rounded-xl">
                            <span class="text-[10px] font-bold text-[#014495] block uppercase font-heading">Total Binaan</span>
                            <span class="font-extrabold text-lg text-[#014495] font-heading">{{ $sup['total_students'] }}</span>
                            <span class="text-[10px] text-slate-500 block">Siswa / Mhs</span>
                        </div>
                        <div class="p-2.5 bg-emerald-50/80 rounded-xl">
                            <span class="text-[10px] font-bold text-emerald-800 block uppercase font-heading">Kelompok</span>
                            <span class="font-extrabold text-lg text-emerald-800 font-heading">{{ $sup['group_count'] }}</span>
                            <span class="text-[10px] text-slate-500 block">Tim PKL/KP</span>
                        </div>
                    </div>

                    <!-- List Kelompok yang Dibimbing -->
                    <div class="space-y-2 pt-1">
                        <span class="text-[11px] font-bold text-slate-700 block font-heading">Daftar Tim yang Dibimbing:</span>
                        <div class="space-y-1.5 max-h-32 overflow-y-auto pr-1">
                            @foreach($sup['groups'] as $g)
                                <a href="{{ route('admin.verification.show', $g->id) }}" class="p-2 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 text-[11px] flex items-center justify-between transition-colors group">
                                    <div>
                                        <span class="font-bold text-slate-800 block group-hover:text-[#014495] transition-colors">{{ $g->leader->full_name ?? $g->user->name }}</span>
                                        <span class="text-slate-500 text-[10px]">{{ $g->institution->institution_name ?? '-' }} ({{ $g->participant_count }} Orang)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-bold text-[9px] rounded font-heading">
                                            {{ $g->program_type }}
                                        </span>
                                        <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-[#014495] transition-colors text-[10px]"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-8 text-center bg-slate-50 rounded-2xl border border-slate-200 text-slate-400 font-medium">
                    Belum ada penugasan pembimbing lapangan pada pendaftar yang diterima.
                </div>
            @endforelse
        </div>
    </div>


</div>

<!-- ========================================================================= -->
<!-- QUICK-VIEW MODAL POPUP (Drill-Down Metric Cards)                         -->
<!-- ========================================================================= -->
<div id="quickViewModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto" onclick="closeQuickViewModal()">
    <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[90vh] my-auto transform transition-all duration-300 scale-95 opacity-0" id="quickViewModalCard" onclick="event.stopPropagation()">
        
        <!-- Modal Top Header -->
        <div class="p-5 sm:p-6 bg-slate-50/80 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div id="modalIconContainer" class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-sm shrink-0">
                    <i id="modalIcon" class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h3 id="modalTitle" class="font-heading font-extrabold text-base sm:text-lg text-slate-900">Daftar Pendaftar</h3>
                        <span id="modalCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono">0 Data</span>
                    </div>
                    <p id="modalSubtitle" class="text-xs text-slate-500 mt-0.5">Rincian data pendaftar berdasarkan kategori metrik yang Anda pilih.</p>
                </div>
            </div>

            <!-- Search & Close Button -->
            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                    <input type="text" id="modalSearchInput" onkeyup="filterModalTable()" placeholder="Cari nama, kampus, jurusan..." class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#014495] focus:border-[#014495] transition-all">
                </div>
                <button type="button" onclick="closeQuickViewModal()" class="w-9 h-9 rounded-xl bg-white hover:bg-rose-50 hover:text-rose-600 text-slate-500 border border-slate-200 flex items-center justify-center transition-colors shrink-0 shadow-sm" title="Tutup (ESC)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Modal Table Body (Scrollable) -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-grow">
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase font-heading border-b border-slate-200 sticky top-0 z-10">
                        <tr>
                            <th class="p-3.5">Pendaftar / Ketua</th>
                            <th class="p-3.5">Status & Program</th>
                            <th class="p-3.5">Sekolah / Kampus</th>
                            <th class="p-3.5">Durasi Magang</th>
                            <th class="p-3.5">Bidang</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody" class="divide-y divide-slate-100 bg-white">
                        <!-- Dynamic Rows injected via JS -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State Container -->
            <div id="modalEmptyState" class="hidden text-center py-12 space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <h4 class="font-heading font-bold text-sm text-slate-700">Tidak Ada Data Ditemukan</h4>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Tidak ada berkas pendaftaran yang cocok dengan kriteria filter atau kata kunci pencarian ini.</p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 sm:p-5 bg-slate-50/90 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <span class="text-slate-500 font-medium text-[11px]" id="modalFooterInfo">Menampilkan data real-time dari database.</span>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a id="modalFullViewBtn" href="{{ route('admin.verification.index') }}" class="w-full sm:w-auto px-4 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl font-heading transition-all text-center flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>Buka Halaman Verifikasi Lengkap</span>
                </a>
                <button type="button" onclick="closeQuickViewModal()" class="w-full sm:w-auto px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 font-bold border border-slate-200 rounded-xl transition-all text-center">
                    Tutup
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Dataset Registrasi Lengkap untuk Quick-View Modal --}}
@php
    $modalDataset = $allRegistrations->map(function($r) {
        return [
            'id' => $r->id,
            'leader_name' => $r->leader->full_name ?? ($r->user->name ?? 'Pendaftar'),
            'nis_nim' => $r->leader->nis_nim ?? '-',
            'participant_count' => (int) ($r->participant_count ?: 1),
            'applicant_status' => strtolower($r->applicant_status),
            'program_type' => $r->program_type ?? 'PKL',
            'institution_name' => $r->institution->institution_name ?? '-',
            'major' => $r->leader->major ?? '-',
            'start_date' => $r->start_date ? \Carbon\Carbon::parse($r->start_date)->translatedFormat('d/m/y') : '-',
            'end_date' => $r->end_date ? \Carbon\Carbon::parse($r->end_date)->translatedFormat('d/m/y') : '-',
            'duration_days' => ($r->start_date && $r->end_date) ? (\Carbon\Carbon::parse($r->start_date)->diffInDays(\Carbon\Carbon::parse($r->end_date)) + 1) : 0,
            'department_name' => $r->department->name ?? ($r->preferredDepartment->name ?? 'Belum ditentukan'),
            'has_department' => (bool) $r->department_id,
            'supervisor_name' => $r->supervisor_name ?? null,
            'supervisor_position' => $r->supervisor_position ?? null,
            'status' => $r->status,
            'detail_url' => route('admin.verification.show', $r->id),
        ];
    });
@endphp

<script>
    const registrationsRawData = @json($modalDataset);
    let currentModalFilteredData = [];
    let currentCategoryKey = 'all';

    const categoryConfigs = {
        'all': {
            title: 'Semua Pendaftaran Masuk',
            subtitle: 'Daftar seluruh berkas pendaftaran dari Siswa PKL dan Mahasiswa yang tercatat di sistem.',
            icon: 'fa-file-invoice',
            iconBg: 'bg-blue-100 text-[#014495]',
            badgeBg: 'bg-blue-100 text-[#014495]',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'all']) }}",
            filterFn: () => true
        },
        'pending': {
            title: 'Pengajuan Menunggu Verifikasi',
            subtitle: 'Berkas baru yang membutuhkan tinjauan dokumen dan penempatan bidang oleh Admin.',
            icon: 'fa-clock',
            iconBg: 'bg-amber-100 text-amber-700',
            badgeBg: 'bg-amber-100 text-amber-800',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'pending']) }}",
            filterFn: (item) => item.status === 'pending'
        },
        'approved': {
            title: 'Pengajuan Disetujui / Diterima',
            subtitle: 'Pendaftar yang telah lolos verifikasi dan ditempatkan pada bidang di Diskominfo Garut.',
            icon: 'fa-circle-check',
            iconBg: 'bg-emerald-100 text-emerald-700',
            badgeBg: 'bg-emerald-100 text-emerald-800',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'approved']) }}",
            filterFn: (item) => item.status === 'approved'
        },
        'rejected': {
            title: 'Pengajuan Ditolak',
            subtitle: 'Daftar pengajuan yang tidak memenuhi syarat dokumen atau ketersediaan kuota penuh.',
            icon: 'fa-circle-xmark',
            iconBg: 'bg-rose-100 text-rose-700',
            badgeBg: 'bg-rose-100 text-rose-800',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'rejected']) }}",
            filterFn: (item) => item.status === 'rejected'
        },
        'siswa': {
            title: 'Daftar Pengajuan Siswa (SMK / SMA)',
            subtitle: 'Seluruh pengajuan program Praktik Kerja Lapangan (PKL) tingkat Sekolah Menengah Kejuruan/SMA.',
            icon: 'fa-school',
            iconBg: 'bg-blue-100 text-[#014495]',
            badgeBg: 'bg-blue-100 text-[#014495]',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'all']) }}",
            filterFn: (item) => item.applicant_status === 'siswa'
        },
        'mahasiswa': {
            title: 'Daftar Pengajuan Mahasiswa (Perguruan Tinggi)',
            subtitle: 'Seluruh pengajuan program Kerja Praktek (KP) dan Magang dari Perguruan Tinggi / Universitas.',
            icon: 'fa-graduation-cap',
            iconBg: 'bg-indigo-100 text-indigo-700',
            badgeBg: 'bg-indigo-100 text-indigo-800',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'all']) }}",
            filterFn: (item) => item.applicant_status === 'mahasiswa'
        },
        'active_participants': {
            title: 'Daftar Individu Peserta Aktif Diterima',
            subtitle: 'Seluruh tim/individu pendaftar yang statusnya telah Diterima dan siap/sedang menjalani program.',
            icon: 'fa-users',
            iconBg: 'bg-emerald-100 text-emerald-700',
            badgeBg: 'bg-emerald-100 text-emerald-800',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'approved']) }}",
            filterFn: (item) => item.status === 'approved'
        },
        'supervisors': {
            title: 'Daftar Kelompok & Pembimbing Lapangan',
            subtitle: 'Data pendaftar yang telah disetujui dan plotting pembimbing lapangan internal Diskominfo.',
            icon: 'fa-user-tie',
            iconBg: 'bg-amber-100 text-amber-700',
            badgeBg: 'bg-amber-100 text-amber-800',
            verificationUrl: "{{ route('admin.verification.index', ['status' => 'approved']) }}",
            filterFn: (item) => item.status === 'approved'
        }
    };

    function openQuickViewModal(categoryKey) {
        currentCategoryKey = categoryKey;
        const config = categoryConfigs[categoryKey] || categoryConfigs['all'];
        
        // Set Header
        document.getElementById('modalTitle').textContent = config.title;
        document.getElementById('modalSubtitle').textContent = config.subtitle;
        document.getElementById('modalIcon').className = 'fa-solid ' + config.icon;
        document.getElementById('modalIconContainer').className = 'w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-sm shrink-0 ' + config.iconBg;
        document.getElementById('modalCountBadge').className = 'px-2.5 py-0.5 rounded-full text-xs font-bold font-mono ' + config.badgeBg;
        document.getElementById('modalFullViewBtn').href = config.verificationUrl;
        document.getElementById('modalSearchInput').value = '';

        // Filter Data
        currentModalFilteredData = registrationsRawData.filter(config.filterFn);
        renderModalTable(currentModalFilteredData);

        // Show Modal with Animation
        const modal = document.getElementById('quickViewModal');
        const card = document.getElementById('quickViewModalCard');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeQuickViewModal() {
        const modal = document.getElementById('quickViewModal');
        const card = document.getElementById('quickViewModalCard');
        
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    function filterModalTable() {
        const keyword = document.getElementById('modalSearchInput').value.toLowerCase().trim();
        if (!keyword) {
            renderModalTable(currentModalFilteredData);
            return;
        }

        const filtered = currentModalFilteredData.filter(item => {
            return item.leader_name.toLowerCase().includes(keyword) ||
                   item.institution_name.toLowerCase().includes(keyword) ||
                   item.nis_nim.toLowerCase().includes(keyword) ||
                   item.major.toLowerCase().includes(keyword) ||
                   item.department_name.toLowerCase().includes(keyword) ||
                   (item.supervisor_name && item.supervisor_name.toLowerCase().includes(keyword));
        });

        renderModalTable(filtered);
    }

    function renderModalTable(data) {
        const tbody = document.getElementById('modalTableBody');
        const emptyState = document.getElementById('modalEmptyState');
        const badgeCount = document.getElementById('modalCountBadge');
        const footerInfo = document.getElementById('modalFooterInfo');

        badgeCount.textContent = data.length + ' Data';
        footerInfo.textContent = 'Menampilkan ' + data.length + ' pengajuan.';

        if (data.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        
        let html = '';
        data.forEach(item => {
            let statusBadge = '';
            if (item.status === 'pending') {
                statusBadge = '<span class="px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full font-bold text-[10px] font-heading inline-flex items-center gap-1"><i class="fa-solid fa-clock text-[9px]"></i> Pending</span>';
            } else if (item.status === 'approved') {
                statusBadge = '<span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full font-bold text-[10px] font-heading inline-flex items-center gap-1"><i class="fa-solid fa-circle-check text-[9px]"></i> Diterima</span>';
            } else if (item.status === 'completed') {
                statusBadge = '<span class="px-2.5 py-1 bg-sky-50 text-sky-800 border border-sky-200 rounded-full font-bold text-[10px] font-heading inline-flex items-center gap-1"><i class="fa-solid fa-flag-checkered text-[9px]"></i> Selesai</span>';
            } else {
                statusBadge = '<span class="px-2.5 py-1 bg-rose-50 text-rose-800 border border-rose-200 rounded-full font-bold text-[10px] font-heading inline-flex items-center gap-1"><i class="fa-solid fa-circle-xmark text-[9px]"></i> Ditolak</span>';
            }

            let typeBadge = '';
            if (item.applicant_status === 'siswa') {
                typeBadge = '<span class="px-2 py-0.5 bg-blue-50 text-[#014495] rounded-md font-bold text-[10px] font-heading">Siswa PKL</span>';
            } else {
                typeBadge = '<span class="px-2 py-0.5 bg-indigo-50 text-indigo-800 rounded-md font-bold text-[10px] font-heading">Mhs ' + item.program_type + '</span>';
            }

            let supervisorInfo = '';
            if (item.supervisor_name) {
                supervisorInfo = `<span class="block text-[10px] text-[#014495] font-semibold mt-0.5"><i class="fa-solid fa-user-tie text-[9px] mr-1"></i>${item.supervisor_name}</span>`;
            }

            html += `
                <tr class="hover:bg-blue-50/40 transition-colors">
                    <td class="p-3.5">
                        <span class="font-bold text-slate-900 block font-heading">${item.leader_name}</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="font-mono text-slate-500 text-[10px]">NIS/NIM: ${item.nis_nim}</span>
                            <span class="px-1.5 py-0.2 bg-slate-100 text-slate-600 rounded text-[9px] font-semibold">${item.participant_count > 1 ? item.participant_count + ' Org' : 'Individu'}</span>
                        </div>
                    </td>
                    <td class="p-3.5">${typeBadge}</td>
                    <td class="p-3.5">
                        <span class="font-semibold text-slate-800 block">${item.institution_name}</span>
                        <span class="text-slate-500 text-[10px] block">${item.major}</span>
                    </td>
                    <td class="p-3.5">
                        ${item.start_date !== '-' ? `
                            <span class="font-medium text-slate-700 block">${item.start_date} - ${item.end_date}</span>
                            <span class="text-[10px] text-slate-400 block font-mono">(${item.duration_days} hari)</span>
                        ` : '<span class="text-slate-400">-</span>'}
                    </td>
                    <td class="p-3.5">
                        <span class="font-bold ${item.has_department ? 'text-[#014495]' : 'text-slate-600'} block">${item.department_name}</span>
                        ${supervisorInfo}
                    </td>
                    <td class="p-3.5 text-center">${statusBadge}</td>
                    <td class="p-3.5 text-center whitespace-nowrap">
                        <a href="${item.detail_url}" class="inline-flex items-center justify-center gap-1.5 w-32 py-2 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs font-heading shadow-sm hover:shadow transition-all active:scale-[0.98] whitespace-nowrap">
                            <i class="fa-solid fa-sliders text-[11px]"></i>
                            <span>${['approved', 'completed'].includes(item.status) ? 'Ubah / Detail' : 'Tinjau'}</span>
                        </a>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    // Close on Escape key press
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('quickViewModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeQuickViewModal();
            }
        }
    });
</script>
@endsection
