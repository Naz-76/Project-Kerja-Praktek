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

    <!-- 4 Kategori Rekap Navigation Tabs -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-md space-y-5">
        <div class="flex flex-wrap gap-2 text-xs font-bold font-heading border-b border-slate-100 pb-4">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 rounded-xl transition-all {{ !$programType && !$departmentId && !$status ? 'bg-[#014495] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fa-solid fa-users mr-1.5"></i> 1. Rekap Pendaftar (Semua)
            </a>
            <a href="{{ route('admin.reports.index', ['program_type' => 'PKL']) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $programType ? 'bg-[#014495] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fa-solid fa-graduation-cap mr-1.5"></i> 2. Rekap Program ({{ $programType ?? 'Filter' }})
            </a>
            <a href="{{ route('admin.reports.index', ['department_id' => $departments->first()->id ?? 1]) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $departmentId ? 'bg-[#014495] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fa-solid fa-sitemap mr-1.5"></i> 3. Rekap Bidang
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'approved']) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $status ? 'bg-[#014495] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fa-solid fa-chart-pie mr-1.5"></i> 4. Rekap Status ({{ $status ? ucfirst($status) : 'Filter' }})
            </a>
        </div>

        <!-- Filter Form Detailed -->
        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs pt-1">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Filter Program</label>
                <select name="program_type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    <option value="">-- Semua Program --</option>
                    <option value="PKL" {{ $programType == 'PKL' ? 'selected' : '' }}>PKL</option>
                    <option value="KP" {{ $programType == 'KP' ? 'selected' : '' }}>Kerja Praktik (KP)</option>
                    <option value="Magang" {{ $programType == 'Magang' ? 'selected' : '' }}>Magang Mandiri</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Filter Bidang</label>
                <select name="department_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    <option value="">-- Semua Bidang --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Filter Status</label>
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl transition-all font-heading shadow-sm active:scale-[0.98]">
                    Filter Rekap
                </button>
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-center font-heading transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden text-xs">
        <div class="p-5 border-b border-slate-100 font-bold text-slate-800 flex justify-between items-center font-heading bg-slate-50/50">
            <span>Hasil Rekapitulasi Data (Total: {{ $registrations->count() }} Pengajuan)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200 font-heading">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="p-4">Pendaftar / Ketua</th>
                        <th class="p-4">Program</th>
                        <th class="p-4">Institusi</th>
                        <th class="p-4">Bidang Penempatan</th>
                        <th class="p-4">Periode Pelaksanaan</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($registrations as $idx => $reg)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-mono font-bold">{{ $idx + 1 }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block font-heading">{{ $reg->leader->full_name ?? $reg->user->name }}</span>
                                <span class="text-slate-500 text-[11px]">{{ $reg->participant_count }} Orang | {{ $reg->leader->nis_nim ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-semibold text-slate-800">{{ $reg->program_type }}</td>
                            <td class="p-4">{{ $reg->institution->institution_name ?? '-' }}</td>
                            <td class="p-4 font-semibold text-emerald-700 font-heading">{{ $reg->department->name ?? '-' }}</td>
                            <td class="p-4 text-slate-500 text-[11px] font-mono">
                                {{ $reg->start_date ? $reg->start_date->format('d/m/Y') : '-' }} - {{ $reg->end_date ? $reg->end_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="p-4">
                                @if($reg->status == 'pending')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 font-bold rounded-full text-[10px]">Pending</span>
                                @elseif($reg->status == 'approved')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px]">Diterima</span>
                                @elseif($reg->status == 'rejected')
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 font-bold rounded-full text-[10px]">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">Tidak ada data rekapitulasi yang cocok dengan kriteria filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
