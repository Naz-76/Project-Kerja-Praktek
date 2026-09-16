@extends('layouts.admin')

@section('title', 'Kelola Bidang & Kuota — Admin Diskominfo')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#014495] inline-flex items-center gap-1 font-heading transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Manajemen Bidang & Kuota Diskominfo</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data unit kerja/bidang dan alokasi total kuota pendaftaran.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Tambah Bidang -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-5 h-fit">
            <h2 class="font-heading text-base font-bold text-slate-800 border-b border-slate-100 pb-3">Tambah Bidang Baru</h2>
            <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Nama Bidang *</label>
                    <input type="text" name="name" required placeholder="Contoh: Bidang Aptika" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Deskripsi Tugas & Fungsi</label>
                    <textarea name="description" rows="3" placeholder="Uraian singkat..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all leading-relaxed"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">Alokasi Total Kuota (Orang) *</label>
                    <input type="number" name="quota_total" value="10" min="1" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                </div>
                <button type="submit" class="w-full py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs shadow-md transition-all font-heading active:scale-[0.98]">
                    Simpan Bidang Baru
                </button>
            </form>
        </div>

        <!-- Tabel List Bidang -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">
            <div class="p-5 border-b border-slate-100 font-bold text-sm text-slate-800 font-heading bg-slate-50/50">
                Daftar Bidang & Status Kuota Periode Ini
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($departments as $dept)
                    @php
                        $quota = $dept->slotQuotas->first();
                        $total = $quota ? $quota->quota_total : 0;
                        $used = $quota ? $quota->quota_used : 0;
                        $remaining = max(0, $total - $used);
                    @endphp
                    <div class="p-6 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm font-heading">{{ $dept->name }}</h3>
                                <p class="text-slate-500 text-xs mt-1 leading-relaxed">{{ $dept->description ?? 'Tidak ada deskripsi.' }}</p>
                            </div>
                            <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Hapus bidang ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[#8B0000] hover:text-rose-700 text-xs font-bold font-heading">Hapus</button>
                            </form>
                        </div>

                        <!-- Form Update Kuota Quick -->
                        <form action="{{ route('admin.departments.update', $dept->id) }}" method="POST" class="flex flex-wrap items-center justify-between gap-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-200">
                            @csrf @method('PUT')
                            <input type="hidden" name="name" value="{{ $dept->name }}">
                            <input type="hidden" name="description" value="{{ $dept->description }}">
                            <div class="flex-grow flex items-center gap-2">
                                <span class="font-semibold text-slate-700">Total Kuota:</span>
                                <input type="number" name="quota_total" value="{{ $total }}" min="0" class="w-20 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-bold font-mono">
                                <span class="text-slate-500 text-[11px] font-medium">(Terpakai: <b>{{ $used }}</b> | Sisa: <b class="text-[#014495]">{{ $remaining }}</b>)</span>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs transition-all font-heading shadow-sm active:scale-[0.98]">
                                Perbarui
                            </button>
                        </form>

                        <!-- Pembimbing Lapangan Section -->
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <h4 class="font-bold text-slate-800 text-xs mb-3 font-heading flex justify-between items-center">
                                <span><i class="fa-solid fa-user-tie text-[#014495] mr-1"></i> Data Pembimbing Lapangan</span>
                            </h4>
                            
                            <!-- List Pembimbing -->
                            @if($dept->fieldSupervisors->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                    @foreach($dept->fieldSupervisors as $supervisor)
                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex justify-between items-start gap-2">
                                            <div>
                                                <div class="font-bold text-slate-800">{{ $supervisor->name }}</div>
                                                <div class="text-[10px] text-slate-500">{{ $supervisor->position ?? 'Pembimbing' }} • {{ $supervisor->phone ?? '-' }}</div>
                                            </div>
                                            <form action="{{ route('admin.supervisors.destroy', $supervisor->id) }}" method="POST" onsubmit="return confirm('Hapus pembimbing ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-500 italic mb-3">Belum ada data pembimbing.</div>
                            @endif

                            <!-- Form Tambah Pembimbing -->
                            <form action="{{ route('admin.supervisors.store') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="department_id" value="{{ $dept->id }}">
                                <input type="text" name="name" required placeholder="Nama Pembimbing" class="flex-1 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs focus:ring-[#2F90E1]">
                                <input type="text" name="position" placeholder="Jabatan" class="flex-1 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs focus:ring-[#2F90E1]">
                                <input type="text" name="phone" placeholder="No. WA" class="flex-1 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs focus:ring-[#2F90E1]">
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all"><i class="fa-solid fa-plus"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
