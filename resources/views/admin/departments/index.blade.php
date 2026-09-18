@extends('layouts.admin')

@section('title', 'Kelola Bidang & Kuota — Admin Diskominfo')

@section('content')
<div class="space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#014495] inline-flex items-center gap-1 font-heading transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-semibold text-slate-500">Administrasi Kantor</span>
            </div>
            <h1 class="font-heading text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                Kelola Bidang & Kuota Magang
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Atur daya tampung kuota peserta magang/PKL dan penugasan pembimbing lapangan di Diskominfo Garut.
            </p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="text-xs font-medium text-slate-600 bg-slate-100 px-3.5 py-2 rounded-2xl border border-slate-200">
                <i class="fa-regular fa-calendar-check text-[#014495] mr-1"></i> Periode: <strong>{{ $period }}</strong>
            </span>
        </div>
    </div>

    <!-- 4 Kotak Ringkasan Sederhana (Tetap Muncul di Semua Tab - Responsif HP & Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3 sm:gap-3.5">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-[#014495] flex items-center justify-center text-base sm:text-lg shrink-0">
                <i class="fa-solid fa-building"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs text-slate-500 block truncate">Total Bidang</span>
                <span class="font-heading text-base sm:text-xl font-bold text-slate-900 block mt-0.5 truncate">{{ $stats['total_departments'] }} Bidang</span>
            </div>
        </div>

        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3 sm:gap-3.5">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base sm:text-lg shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs text-slate-500 block truncate">Total Daya Tampung</span>
                <span class="font-heading text-base sm:text-xl font-bold text-slate-900 block mt-0.5 truncate">{{ $stats['total_quota'] }} Orang</span>
            </div>
        </div>

        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3 sm:gap-3.5">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base sm:text-lg shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs text-slate-500 block truncate">Peserta Diterima</span>
                <span class="font-heading text-base sm:text-xl font-bold text-slate-900 block mt-0.5 truncate">{{ $stats['total_used'] }} Orang</span>
            </div>
        </div>

        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3 sm:gap-3.5">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base sm:text-lg shrink-0">
                <i class="fa-solid fa-door-open"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs text-slate-500 block truncate">Sisa Tempat Kosong</span>
                <span class="font-heading text-base sm:text-xl font-bold text-emerald-600 block mt-0.5 truncate">{{ $stats['total_remaining'] }} Slot</span>
            </div>
        </div>
    </div>

    <!-- 2 Tab Navigasi Utama (Responsif HP & Desktop) -->
    <div class="flex border-b border-slate-200 gap-1 sm:gap-2 text-xs sm:text-sm font-bold font-heading overflow-x-auto no-scrollbar">
        <button type="button" onclick="switchTab('tab-bidang')" id="btn-tab-bidang" class="px-3 sm:px-5 py-2.5 sm:py-3 border-b-2 border-[#014495] text-[#014495] flex items-center justify-center sm:justify-start gap-1.5 sm:gap-2 flex-1 sm:flex-initial transition-all whitespace-nowrap">
            <i class="fa-solid fa-building-columns"></i>
            <span>1. Kapasitas & Kuota Bidang</span>
            <span class="px-2 py-0.5 bg-blue-100 text-[#014495] text-xs rounded-full">{{ $departments->count() }}</span>
        </button>
        <button type="button" onclick="switchTab('tab-pembimbing')" id="btn-tab-pembimbing" class="px-3 sm:px-5 py-2.5 sm:py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center justify-center sm:justify-start gap-1.5 sm:gap-2 flex-1 sm:flex-initial transition-all whitespace-nowrap">
            <i class="fa-solid fa-user-tie"></i>
            <span>2. Data Pembimbing Lapangan</span>
            <span class="px-2 py-0.5 bg-slate-200 text-slate-700 text-xs rounded-full">{{ $supervisors->count() }}</span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: KAPASITAS & KUOTA BIDANG -->
    <!-- ========================================================================= -->
    <div id="content-tab-bidang" class="space-y-4">
        <!-- Petunjuk Geser Tabel (Desktop & Tablet) -->
        <div class="hidden md:flex items-center justify-between text-[11px] text-slate-500 bg-blue-50/70 px-4 py-2 rounded-2xl border border-blue-100">
            <span class="inline-flex items-center gap-1.5 font-medium text-[#014495]">
                <i class="fa-solid fa-arrows-left-right text-xs"></i>
                <span>Tabel dapat digeser ke samping: Klik & tarik mouse atau gulir horizontal untuk melihat seluruh kolom data</span>
            </span>
            <span class="text-slate-400 text-[10px]">Total: {{ $departments->count() }} Bidang</span>
        </div>

        <!-- Tabel Kuota Bidang yang Sangat Bersih & Responsif -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-md overflow-hidden text-xs">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
                <div>
                    <h2 class="font-heading text-sm sm:text-base font-bold text-slate-900">Tabel Kapasitas Kuota Per Bidang</h2>
                    <p class="text-slate-500 text-[11px] sm:text-xs mt-0.5">Daftar batas kuota peserta yang dapat diterima pada periode aktif ini</p>
                </div>
                <!-- Tombol Tambah Bidang -->
                <button type="button" onclick="openAddDeptModal()" class="w-full sm:w-auto px-4 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs shadow-sm transition-all flex items-center justify-center gap-2 font-heading active:scale-[0.98]">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Tambah Bidang Baru</span>
                </button>
            </div>

            <!-- Tampilan Desktop (Tabel Standar) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase text-[11px] border-b border-slate-200 font-heading">
                        <tr>
                            <th class="p-4 text-center w-12">No</th>
                            <th class="p-4">Nama Bidang Kerja</th>
                            <th class="p-4 text-center">Daya Tampung (Kuota)</th>
                            <th class="p-4 text-center">Peserta Diterima</th>
                            <th class="p-4 text-center">Sisa Kuota</th>
                            <th class="p-4 text-center">Status Kuota</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($departments as $idx => $dept)
                            @php
                                $quota = $dept->slotQuotas->first();
                                $total = $quota ? (int)$quota->quota_total : 0;
                                $used = $quota ? (int)$quota->quota_used : 0;
                                $remaining = max(0, $total - $used);

                                if ($total == 0 || $remaining == 0) {
                                    $statusBadge = 'bg-rose-100 text-rose-800 border-rose-200';
                                    $statusText = 'Penuh';
                                } elseif ($remaining <= 2) {
                                    $statusBadge = 'bg-amber-100 text-amber-800 border-amber-200';
                                    $statusText = 'Sisa Sedikit';
                                } else {
                                    $statusBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                    $statusText = 'Tersedia';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- No -->
                                <td class="p-4 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>

                                <!-- Nama Bidang -->
                                <td class="p-4">
                                    <span class="font-heading font-bold text-sm text-slate-900 block">{{ $dept->name }}</span>
                                    @if($dept->description)
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed max-w-md">{{ $dept->description }}</p>
                                    @endif
                                    <span class="text-[11px] text-slate-400 mt-1 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-user-tie text-[#014495]"></i> {{ $dept->fieldSupervisors->count() }} Pembimbing ditugaskan
                                    </span>
                                </td>

                                <!-- Kuota Total -->
                                <td class="p-4 text-center font-bold text-sm text-slate-800 font-heading">
                                    {{ $total }} <span class="text-slate-400 text-xs font-normal">Orang</span>
                                </td>

                                <!-- Terisi -->
                                <td class="p-4 text-center font-bold text-sm text-amber-700 font-heading">
                                    {{ $used }} <span class="text-slate-400 text-xs font-normal">Orang</span>
                                </td>

                                <!-- Sisa -->
                                <td class="p-4 text-center font-bold text-sm {{ $remaining > 0 ? 'text-emerald-700' : 'text-rose-700' }} font-heading">
                                    {{ $remaining }} <span class="text-slate-400 text-xs font-normal">Slot</span>
                                </td>

                                <!-- Status -->
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border inline-block {{ $statusBadge }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="openEditDeptModal('{{ $dept->id }}', '{{ addslashes($dept->name) }}', '{{ addslashes($dept->description ?? '') }}', '{{ $total }}')" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#014495] border border-blue-200 rounded-xl font-bold text-xs transition-all font-heading inline-flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit Bidang
                                        </button>
                                        <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bidang {{ $dept->name }}?')">
                                             @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all font-heading inline-flex items-center gap-1" title="Hapus Bidang">
                                                <i class="fa-solid fa-trash text-xs"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-slate-400">
                                    Belum ada data bidang. Silakan klik tombol <strong>Tambah Bidang Baru</strong> di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tampilan Mobile (Card List Responsif Layar HP) -->
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($departments as $idx => $dept)
                    @php
                        $quota = $dept->slotQuotas->first();
                        $total = $quota ? (int)$quota->quota_total : 0;
                        $used = $quota ? (int)$quota->quota_used : 0;
                        $remaining = max(0, $total - $used);

                        if ($total == 0 || $remaining == 0) {
                            $statusBadge = 'bg-rose-100 text-rose-800 border-rose-200';
                            $statusText = 'Penuh';
                        } elseif ($remaining <= 2) {
                            $statusBadge = 'bg-amber-100 text-amber-800 border-amber-200';
                            $statusText = 'Sisa Sedikit';
                        } else {
                            $statusBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            $statusText = 'Tersedia';
                        }
                    @endphp
                    <div class="p-4 space-y-3 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bidang #{{ $idx + 1 }}</span>
                                <h3 class="font-heading font-bold text-sm text-slate-900 mt-0.5">{{ $dept->name }}</h3>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border shrink-0 {{ $statusBadge }}">
                                {{ $statusText }}
                            </span>
                        </div>

                        @if($dept->description)
                            <p class="text-xs text-slate-500 leading-relaxed">{{ $dept->description }}</p>
                        @endif

                        <div class="text-[11px] text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-tie text-[#014495]"></i>
                            <span>{{ $dept->fieldSupervisors->count() }} Pembimbing ditugaskan</span>
                        </div>

                        <!-- 3 Kotak Ringkas Angka Kuota -->
                        <div class="grid grid-cols-3 gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 text-center">
                            <div>
                                <span class="text-[10px] text-slate-400 block">Kapasitas</span>
                                <span class="font-heading font-bold text-xs text-slate-800">{{ $total }} Org</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block">Diterima</span>
                                <span class="font-heading font-bold text-xs text-amber-700">{{ $used }} Org</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block">Sisa Slot</span>
                                <span class="font-heading font-bold text-xs {{ $remaining > 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $remaining }} Slot</span>
                            </div>
                        </div>

                        <!-- Tombol Aksi di Mobile -->
                        <div class="flex items-center gap-2 pt-1">
                            <button type="button" onclick="openEditDeptModal('{{ $dept->id }}', '{{ addslashes($dept->name) }}', '{{ addslashes($dept->description ?? '') }}', '{{ $total }}')" class="flex-1 py-2 bg-blue-50 hover:bg-blue-100 text-[#014495] border border-blue-200 rounded-xl font-bold text-xs transition-all font-heading inline-flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Bidang
                            </button>
                            <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bidang {{ $dept->name }}?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all font-heading inline-flex items-center justify-center gap-1" title="Hapus Bidang">
                                    <i class="fa-solid fa-trash text-xs"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        Belum ada data bidang. Silakan klik tombol <strong>Tambah Bidang Baru</strong> di atas.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: DATA PEMBIMBING LAPANGAN -->
    <!-- ========================================================================= -->
    <div id="content-tab-pembimbing" class="hidden space-y-4">
        <!-- Petunjuk Geser Tabel (Desktop & Tablet) -->
        <div class="hidden md:flex items-center justify-between text-[11px] text-slate-500 bg-blue-50/70 px-4 py-2 rounded-2xl border border-blue-100">
            <span class="inline-flex items-center gap-1.5 font-medium text-[#014495]">
                <i class="fa-solid fa-arrows-left-right text-xs"></i>
                <span>Tabel dapat digeser ke samping: Klik & tarik mouse atau gulir horizontal untuk melihat seluruh kolom data</span>
            </span>
            <span class="text-slate-400 text-[10px]">Total: {{ $supervisors->count() }} Pembimbing</span>
        </div>

        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-md overflow-hidden text-xs">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
                <div>
                    <h2 class="font-heading text-sm sm:text-base font-bold text-slate-900">Daftar Pembimbing Lapangan Diskominfo</h2>
                    <p class="text-slate-500 text-[11px] sm:text-xs mt-0.5">Pegawai yang ditugaskan membimbing dan memberikan penilaian bagi peserta magang</p>
                </div>
                <!-- Tombol Tambah Pembimbing -->
                <button type="button" onclick="openAddSupervisorModal()" class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all flex items-center justify-center gap-2 font-heading active:scale-[0.98]">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Tambah Pembimbing Baru</span>
                </button>
            </div>

            <!-- Tampilan Desktop (Tabel Standar) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase text-[11px] border-b border-slate-200 font-heading">
                        <tr>
                            <th class="p-4 text-center w-12">No</th>
                            <th class="p-4">Nama Pembimbing</th>
                            <th class="p-4">Bidang Penempatan</th>
                            <th class="p-4">Jabatan / Peran</th>
                            <th class="p-4">Kontak WhatsApp</th>
                            <th class="p-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($supervisors as $idx => $sup)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="p-4 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-[#014495] flex items-center justify-center font-bold text-xs shrink-0">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <span class="font-heading font-bold text-sm text-slate-900">{{ $sup->name }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-blue-50 text-[#014495] border border-blue-200 rounded-lg text-xs font-semibold">
                                        {{ $sup->department->name ?? 'Semua Bidang' }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-600 font-medium">
                                    {{ $sup->position ?: 'Pembimbing Lapangan' }}
                                </td>
                                <td class="p-4">
                                    @if($sup->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sup->phone) }}" target="_blank" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-bold inline-flex items-center gap-1.5 transition-colors">
                                            <i class="fa-brands fa-whatsapp text-sm"></i> {{ $sup->phone }}
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada nomor</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('admin.supervisors.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Hapus data pembimbing {{ $sup->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Pembimbing">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-slate-400">
                                    Belum ada pembimbing lapangan yang terdaftar. Klik tombol <strong>Tambah Pembimbing Baru</strong> di atas untuk mendaftarkan pembimbing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tampilan Mobile (Card List Responsif Layar HP) -->
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($supervisors as $idx => $sup)
                    <div class="p-4 space-y-3 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-[#014495] flex items-center justify-center font-bold text-xs shrink-0">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <h3 class="font-heading font-bold text-sm text-slate-900">{{ $sup->name }}</h3>
                                    <span class="text-[11px] text-slate-500">{{ $sup->position ?: 'Pembimbing Lapangan' }}</span>
                                </div>
                            </div>
                            <form action="{{ route('admin.supervisors.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Hapus data pembimbing {{ $sup->name }}?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Pembimbing">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                            <span class="px-2.5 py-1 bg-blue-50 text-[#014495] border border-blue-200 rounded-lg text-[11px] font-semibold">
                                <i class="fa-solid fa-building text-[10px] mr-1"></i> {{ $sup->department->name ?? 'Semua Bidang' }}
                            </span>

                            @if($sup->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sup->phone) }}" target="_blank" class="px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-bold text-xs inline-flex items-center gap-1.5 transition-colors">
                                    <i class="fa-brands fa-whatsapp text-sm"></i> {{ $sup->phone }}
                                </a>
                            @else
                                <span class="text-slate-400 text-xs italic">Tidak ada nomor WA</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        Belum ada pembimbing lapangan yang terdaftar. Klik tombol <strong>Tambah Pembimbing Baru</strong> di atas untuk mendaftarkan pembimbing.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 1: TAMBAH BIDANG BARU -->
<!-- ========================================================================= -->
<div id="addDeptModal" class="hidden fixed inset-0 z-50 overflow-y-auto p-3 sm:p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddDeptModal()"></div>
    <div class="flex min-h-full items-center justify-center">
        <div class="relative w-full max-w-md bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200 overflow-hidden text-xs my-auto">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#014495] text-white flex items-center justify-center text-base shrink-0">
                        <i class="fa-solid fa-building-circle-check"></i>
                    </div>
                    <div>
                        <h3 class="font-heading text-sm sm:text-base font-bold text-slate-900">Tambah Bidang Baru</h3>
                        <p class="text-xs text-slate-500">Daftarkan unit kerja baru di Diskominfo Garut</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddDeptModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.departments.store') }}" method="POST" class="p-4 sm:p-6 space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 font-heading">
                        Nama Bidang Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Bidang Informasi dan Komunikasi Publik (IKP)" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 font-heading">
                        Uraian Tugas Singkat
                    </label>
                    <textarea name="description" rows="3" placeholder="Uraian singkat fokus pekerjaan bidang..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all leading-relaxed"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 font-heading">
                        Daya Tampung Kuota (Orang) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="quota_total" value="10" min="1" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Batas maksimal mahasiswa/siswa yang bisa diterima di bidang ini.</p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeAddDeptModal()" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all font-heading text-center">
                        Batal
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs shadow-md transition-all font-heading text-center">
                        Simpan Bidang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: UBAH DATA BIDANG & KUOTA -->
<!-- ========================================================================= -->
<div id="editDeptModal" class="hidden fixed inset-0 z-50 overflow-y-auto p-3 sm:p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditDeptModal()"></div>
    <div class="flex min-h-full items-center justify-center">
        <div class="relative w-full max-w-md bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200 overflow-hidden text-xs my-auto">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-base shrink-0">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="font-heading text-sm sm:text-base font-bold text-slate-900">Ubah Data & Kuota Bidang</h3>
                        <p class="text-xs text-slate-500">Perbarui kuota atau nama bidang kerja</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditDeptModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="editDeptForm" method="POST" class="p-4 sm:p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 font-heading">
                        Nama Bidang Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="edit_dept_name" name="name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 font-heading">
                        Uraian Tugas Singkat
                    </label>
                    <textarea id="edit_dept_desc" name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all leading-relaxed"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 font-heading">
                        Daya Tampung Kuota (Orang) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="edit_dept_quota" name="quota_total" min="0" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Sesuaikan jumlah kuota dengan ketersediaan tempat di ruangan bidang.</p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditDeptModal()" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all font-heading text-center">
                        Batal
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs shadow-md transition-all font-heading text-center">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 3: TAMBAH PEMBIMBING LAPANGAN (BISA INPUT BANYAK SEKALIGUS) -->
<!-- ========================================================================= -->
<div id="addSupervisorModal" class="hidden fixed inset-0 z-50 overflow-y-auto p-3 sm:p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddSupervisorModal()"></div>
    <div class="flex min-h-full items-center justify-center">
        <div class="relative w-full max-w-2xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200 overflow-hidden text-xs my-auto">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-base shrink-0">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="font-heading text-sm sm:text-base font-bold text-slate-900">Tambah Pembimbing Lapangan</h3>
                        <p class="text-xs text-slate-500">Tambahkan satu atau banyak pembimbing sekaligus untuk bidang yang dipilih</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddSupervisorModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.supervisors.store') }}" method="POST" class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                @csrf
                
                <!-- Pilihan Bidang Penempatan -->
                <div class="bg-slate-50 p-3.5 sm:p-4 rounded-2xl border border-slate-200">
                    <label class="block font-semibold text-slate-800 mb-1.5 font-heading">
                        Pilih Bidang Penempatan <span class="text-rose-500">*</span>
                    </label>
                    <select id="modal_sup_department_id" name="department_id" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                        <option value="">-- Pilih Bidang Kerja Diskominfo --</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Seluruh data pembimbing di bawah akan langsung ditempatkan pada bidang ini.</p>
                </div>

                <!-- Bagian Form Dinamis Daftar Pembimbing -->
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800 font-heading">Daftar Pembimbing yang Ditambahkan</span>
                            <span id="sup_count_badge" class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded-full">1 Orang</span>
                        </div>
                        <button type="button" onclick="addSupervisorRow()" class="w-full sm:w-auto px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-bold font-heading inline-flex items-center justify-center gap-1.5 transition-all text-xs">
                            <i class="fa-solid fa-plus text-xs"></i> Tambah Baris Pembimbing
                        </button>
                    </div>

                    <!-- Container Baris Pembimbing -->
                    <div id="supervisorRowsContainer" class="space-y-3 max-h-[50vh] sm:max-h-[380px] overflow-y-auto pr-1">
                        <!-- Baris Pertama (Default) -->
                        <div class="supervisor-row bg-slate-50/80 p-3.5 sm:p-4 rounded-2xl border border-slate-200 space-y-3 relative" id="supervisor_row_0">
                            <div class="flex items-center justify-between pb-1 border-b border-slate-200/70">
                                <span class="font-bold text-slate-700 text-[11px] font-heading supervisor-num">
                                    <i class="fa-solid fa-user-tie text-emerald-600 mr-1"></i> Pembimbing #1
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1 text-[11px]">
                                        Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="supervisors[0][name]" required placeholder="Contoh: Rahmat Hidayat, S.Kom." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-[#2F90E1]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1 text-[11px]">
                                        Jabatan / Peran di Kantor
                                    </label>
                                    <input type="text" name="supervisors[0][position]" placeholder="Contoh: Pranata Komputer Ahli" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-[#2F90E1]">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1 text-[11px]">
                                        Nomor WhatsApp
                                    </label>
                                    <input type="tel" inputmode="numeric" name="supervisors[0][phone]" placeholder="08xxxxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-[#2F90E1]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeAddSupervisorModal()" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all font-heading text-center">
                        Batal
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md transition-all font-heading flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Pembimbing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tab Switcher
    function switchTab(tabId) {
        const tabBidang = document.getElementById('content-tab-bidang');
        const tabPembimbing = document.getElementById('content-tab-pembimbing');
        const btnBidang = document.getElementById('btn-tab-bidang');
        const btnPembimbing = document.getElementById('btn-tab-pembimbing');

        const activeClass = "px-3 sm:px-5 py-2.5 sm:py-3 border-b-2 border-[#014495] text-[#014495] flex items-center justify-center sm:justify-start gap-1.5 sm:gap-2 flex-1 sm:flex-initial transition-all whitespace-nowrap";
        const inactiveClass = "px-3 sm:px-5 py-2.5 sm:py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 flex items-center justify-center sm:justify-start gap-1.5 sm:gap-2 flex-1 sm:flex-initial transition-all whitespace-nowrap";

        if (tabId === 'tab-bidang') {
            tabBidang.classList.remove('hidden');
            tabPembimbing.classList.add('hidden');
            btnBidang.className = activeClass;
            btnPembimbing.className = inactiveClass;
        } else {
            tabBidang.classList.add('hidden');
            tabPembimbing.classList.remove('hidden');
            btnBidang.className = inactiveClass;
            btnPembimbing.className = activeClass;
        }
    }

    // Modal 1: Tambah Bidang
    function openAddDeptModal() {
        document.getElementById('addDeptModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeAddDeptModal() {
        document.getElementById('addDeptModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Modal 2: Edit Bidang & Kuota
    function openEditDeptModal(id, name, desc, quota) {
        document.getElementById('editDeptForm').action = "/admin/bidang-kuota/" + id;
        document.getElementById('edit_dept_name').value = name;
        document.getElementById('edit_dept_desc').value = desc;
        document.getElementById('edit_dept_quota').value = quota;
        document.getElementById('editDeptModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditDeptModal() {
        document.getElementById('editDeptModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Modal 3: Tambah Pembimbing (Bisa Banyak Sekaligus)
    let supervisorRowCount = 1;

    function openAddSupervisorModal(deptId = null) {
        const modal = document.getElementById('addSupervisorModal');
        const select = document.getElementById('modal_sup_department_id');
        if (deptId && select) {
            select.value = deptId;
        }
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddSupervisorModal() {
        document.getElementById('addSupervisorModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function addSupervisorRow() {
        const container = document.getElementById('supervisorRowsContainer');
        const idx = supervisorRowCount;

        const rowHtml = `
        <div class="supervisor-row bg-slate-50/80 p-4 rounded-2xl border border-slate-200 space-y-3 relative mt-3" id="supervisor_row_${idx}">
            <div class="flex items-center justify-between pb-1 border-b border-slate-200/70">
                <span class="font-bold text-slate-700 text-[11px] font-heading supervisor-num">
                    <i class="fa-solid fa-user-tie text-emerald-600 mr-1"></i> Pembimbing #${idx + 1}
                </span>
                <button type="button" onclick="removeSupervisorRow(${idx})" class="text-[11px] font-bold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-2 py-0.5 rounded-lg inline-flex items-center gap-1 transition-all">
                    <i class="fa-solid fa-trash-can text-xs"></i> Hapus Baris
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-[11px]">
                        Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="supervisors[${idx}][name]" required placeholder="Contoh: Rahmat Hidayat, S.Kom." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-[#2F90E1]">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-[11px]">
                        Jabatan / Peran di Kantor
                    </label>
                    <input type="text" name="supervisors[${idx}][position]" placeholder="Contoh: Pranata Komputer Ahli" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-[#2F90E1]">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-[11px]">
                        Nomor WhatsApp
                    </label>
                    <input type="tel" inputmode="numeric" name="supervisors[${idx}][phone]" placeholder="08xxxxxxxxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-[#2F90E1]">
                </div>
            </div>
        </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);
        supervisorRowCount++;
        updateSupervisorBadge();
    }

    function removeSupervisorRow(idx) {
        const row = document.getElementById('supervisor_row_' + idx);
        if (row) {
            row.remove();
            updateSupervisorBadge();
        }
    }

    function updateSupervisorBadge() {
        const rows = document.querySelectorAll('.supervisor-row');
        const badge = document.getElementById('sup_count_badge');
        if (badge) {
            badge.innerText = rows.length + ' Orang';
        }
        rows.forEach((r, i) => {
            const numSpan = r.querySelector('.supervisor-num');
            if (numSpan) {
                numSpan.innerHTML = `<i class="fa-solid fa-user-tie text-emerald-600 mr-1"></i> Pembimbing #${i + 1}`;
            }
        });
    }

    // ESC key close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddDeptModal();
            closeEditDeptModal();
            closeAddSupervisorModal();
        }
    });
</script>
@endsection
