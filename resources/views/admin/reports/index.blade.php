@extends('layouts.admin')

@section('title', 'Laporan Rekapitulasi — Admin Diskominfo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#014495] inline-flex items-center gap-1 font-heading transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Laporan & Rekapitulasi Pendaftaran</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Rekapitulasi terintegrasi: Rekap Pendaftar, Rekap Program, Rekap Bidang, dan Rekap Status.</p>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs transition-all flex items-center gap-2 font-heading shadow-md active:scale-[0.98]">
            <i class="fa-solid fa-print"></i> Cetak / Export PDF
        </button>
    </div>

    <!-- 3 Kategori Rekap Utama (Semua, Mahasiswa, Siswa) -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-md space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex flex-wrap gap-2 text-xs font-bold font-heading">
                <!-- Tab Semua -->
                <a href="{{ route('admin.reports.index', array_filter(['date_from' => $dateFrom, 'date_to' => $dateTo, 'program_type' => $programType, 'department_id' => $departmentId, 'status' => $status])) }}" 
                   class="px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 {{ empty($applicantStatus) ? 'bg-[#014495] text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Semua Pendaftar</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ empty($applicantStatus) ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $stats['total'] }}
                    </span>
                </a>

                <!-- Tab Mahasiswa -->
                <a href="{{ route('admin.reports.index', array_filter(['applicant_status' => 'Mahasiswa', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'program_type' => $programType, 'department_id' => $departmentId, 'status' => $status])) }}" 
                   class="px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 {{ $applicantStatus == 'Mahasiswa' ? 'bg-[#014495] text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Mahasiswa</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $applicantStatus == 'Mahasiswa' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $stats['mahasiswa'] }}
                    </span>
                </a>

                <!-- Tab Siswa -->
                <a href="{{ route('admin.reports.index', array_filter(['applicant_status' => 'Siswa', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'program_type' => $programType, 'department_id' => $departmentId, 'status' => $status])) }}" 
                   class="px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 {{ $applicantStatus == 'Siswa' ? 'bg-[#014495] text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fa-solid fa-school"></i>
                    <span>Siswa</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $applicantStatus == 'Siswa' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $stats['siswa'] }}
                    </span>
                </a>
            </div>

            @if($dateFrom || $dateTo || $programType || $departmentId || $status || $applicantStatus)
                <div class="text-[11px] text-slate-500 flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-[#014495]"></i> Filter aktif diterapkan
                </div>
            @endif
        </div>

        <!-- Filter Form Detailed dengan Rentang Waktu -->
        <form action="{{ route('admin.reports.index') }}" method="GET" class="space-y-4 text-xs pt-1">
            @if($applicantStatus)
                <input type="hidden" name="applicant_status" value="{{ $applicantStatus }}">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <!-- Rentang Waktu: Dari Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">
                        <i class="fa-regular fa-calendar text-[#014495] mr-1"></i> Dari Tanggal
                    </label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all font-mono text-xs">
                </div>

                <!-- Rentang Waktu: Sampai Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">
                        <i class="fa-regular fa-calendar-check text-[#014495] mr-1"></i> Sampai Tanggal
                    </label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all font-mono text-xs">
                </div>

                <!-- Program -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Program</label>
                    <select name="program_type" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                        <option value="">-- Semua Program --</option>
                        <option value="PKL" {{ $programType == 'PKL' ? 'selected' : '' }}>PKL</option>
                        <option value="KP" {{ $programType == 'KP' ? 'selected' : '' }}>Kerja Praktik (KP)</option>
                        <option value="Magang" {{ $programType == 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                </div>

                <!-- Bidang -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Bidang Penempatan</label>
                    <select name="department_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                        <option value="">-- Semua Bidang --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Status Pendaftaran</label>
                    <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl transition-all font-heading shadow-sm active:scale-[0.98] text-center">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.reports.index', $applicantStatus ? ['applicant_status' => $applicantStatus] : []) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-center font-heading transition-all" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>


    <!-- Petunjuk Geser Tabel (Desktop & Tablet) -->
    <div class="hidden md:flex items-center justify-between text-[11px] text-slate-500 bg-blue-50/70 px-4 py-2 rounded-2xl border border-blue-100">
        <span class="inline-flex items-center gap-1.5 font-medium text-[#014495]">
            <i class="fa-solid fa-arrows-left-right text-xs"></i>
            <span>Tabel dapat digeser ke samping: Klik & tarik mouse atau gulir horizontal untuk melihat seluruh kolom data</span>
        </span>
        <span class="text-slate-400 text-[10px]">Total: {{ $registrations->count() }} Pengajuan</span>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-md overflow-hidden text-xs">
        <div class="p-4 sm:p-5 border-b border-slate-100 font-bold text-slate-800 flex justify-between items-center font-heading bg-slate-50/50">
            <span>Hasil Rekapitulasi Data (Total: {{ $registrations->count() }} Pengajuan)</span>
        </div>

        <!-- Tampilan Desktop (Tabel Standar) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200 font-heading">
                    <tr>
                        <th class="p-4 text-center w-12">No</th>
                        <th class="p-4">Pendaftar / Ketua</th>
                        <th class="p-4">Status & Program</th>
                        <th class="p-4">Institusi & Pembimbing</th>
                        <th class="p-4">Bidang & Pembimbing Lapangan</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($registrations as $idx => $reg)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block font-heading">{{ $reg->leader->full_name ?? $reg->user->name }}</span>
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
                                    <span class="px-2.5 py-1 bg-blue-50 text-[#014495] rounded-full border border-blue-200 font-bold text-[10px] font-heading inline-block"><i class="fa-solid fa-school mr-1"></i> Siswa PKL</span>
                                @else
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-800 rounded-full border border-indigo-200 font-bold text-[10px] font-heading inline-block"><i class="fa-solid fa-graduation-cap mr-1"></i> Mahasiswa {{ $reg->program_type }}</span>
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
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending</span>
                                @elseif($reg->status == 'approved')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Diterima</span>
                                @elseif($reg->status == 'rejected')
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
                                @elseif($reg->status == 'completed')
                                    <span class="px-3 py-1 bg-sky-100 text-sky-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1"><i class="fa-solid fa-flag-checkered"></i> Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">Tidak ada data rekapitulasi yang cocok dengan kriteria filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile (Card List Khusus Layar HP) -->
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($registrations as $idx => $reg)
                <div class="p-4 space-y-3 hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block">Data #{{ $idx + 1 }}</span>
                            <h3 class="font-heading font-bold text-sm text-slate-900 mt-0.5">{{ $reg->leader->full_name ?? $reg->user->name }}</h3>
                            <span class="text-slate-500 font-mono text-[11px]">NIS/NIM: {{ $reg->leader->nis_nim ?? '-' }}</span>
                        </div>
                        @if($reg->status == 'pending')
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0"><i class="fa-solid fa-clock text-[9px]"></i> Pending</span>
                        @elseif($reg->status == 'approved')
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0"><i class="fa-solid fa-circle-check text-[9px]"></i> Diterima</span>
                        @elseif($reg->status == 'rejected')
                            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0"><i class="fa-solid fa-circle-xmark text-[9px]"></i> Ditolak</span>
                        @elseif($reg->status == 'completed')
                            <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0"><i class="fa-solid fa-flag-checkered text-[9px]"></i> Selesai</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                        @if(strtolower($reg->applicant_status) == 'siswa')
                            <span class="px-2 py-0.5 bg-blue-50 text-[#014495] rounded-md border border-blue-200 font-bold text-[10px]"><i class="fa-solid fa-school mr-1"></i> Siswa PKL</span>
                        @else
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-800 rounded-md border border-indigo-200 font-bold text-[10px]"><i class="fa-solid fa-graduation-cap mr-1"></i> Mahasiswa {{ $reg->program_type }}</span>
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
                                    @if($reg->supervisor_name)
                                        <span class="text-slate-600 block text-[10px]"><i class="fa-solid fa-user-tie text-[#014495]"></i> {{ $reg->supervisor_name }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Belum Ditempatkan</span>
                                    @if($reg->preferredDepartment)
                                        <span class="text-[#014495] font-semibold text-[10px] block">(Pilihan: {{ $reg->preferredDepartment->name }})</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 font-medium">Tidak ada data rekapitulasi yang cocok dengan kriteria filter.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
