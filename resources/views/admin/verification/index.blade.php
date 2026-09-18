@extends('layouts.admin')

@section('title', 'Verifikasi & Penempatan Bidang — Admin Diskominfo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 inline-flex items-center gap-1 font-heading">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Daftar Verifikasi & Penempatan Bidang</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Tinjau berkas pendaftaran, verifikasi dokumen, dan lakukan penempatan bidang.</p>
        </div>
    </div>

    <!-- Filter Tab & Search -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200 shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap gap-2 text-xs font-bold font-heading">
            <a href="{{ route('admin.verification.index', ['status' => 'all']) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $status == 'all' ? 'bg-[#014495] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Status
            </a>
            <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $status == 'pending' ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Menunggu Verifikasi
            </a>
            <a href="{{ route('admin.verification.index', ['status' => 'approved']) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $status == 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Diterima
            </a>
            <a href="{{ route('admin.verification.index', ['status' => 'rejected']) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $status == 'rejected' ? 'bg-[#8B0000] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Ditolak
            </a>
            <a href="{{ route('admin.verification.index', ['status' => 'completed']) }}" class="px-4 py-2.5 rounded-xl transition-all {{ $status == 'completed' ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Selesai
            </a>
        </div>

        <form action="{{ route('admin.verification.index') }}" method="GET" class="w-full md:w-auto">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / instansi..." class="w-full md:w-64 pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
            </div>
        </form>
    </div>

    <!-- Petunjuk Geser Tabel (Desktop & Tablet) -->
    <div class="hidden md:flex items-center justify-between text-[11px] text-slate-500 bg-blue-50/70 px-4 py-2 rounded-2xl border border-blue-100">
        <span class="inline-flex items-center gap-1.5 font-medium text-[#014495]">
            <i class="fa-solid fa-arrows-left-right text-xs"></i>
            <span>Tabel dapat digeser ke samping: Klik & tarik mouse atau gulir horizontal untuk melihat seluruh kolom data</span>
        </span>
        <span class="text-slate-400 text-[10px]">Total: {{ $registrations->total() }} Pengajuan</span>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-md overflow-hidden">
        <!-- Tampilan Desktop (Tabel Standar) -->
        <div class="hidden md:block overflow-x-auto">
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
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 text-center font-bold text-slate-500">
                                {{ $registrations->firstItem() ? $registrations->firstItem() + $loop->index : $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block font-heading">{{ $reg->leader->full_name ?? $reg->user->name }}</span>
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
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">Tidak ada pengajuan pendaftaran yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile (Card List Khusus Layar HP) -->
        <div class="block md:hidden divide-y divide-slate-100 text-xs">
            @forelse($registrations as $reg)
                <div class="p-4 space-y-3 hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block">No. #{{ $registrations->firstItem() ? $registrations->firstItem() + $loop->index : $loop->iteration }}</span>
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
                        @elseif($reg->status == 'rejected')
                            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0">
                                <i class="fa-solid fa-circle-xmark text-[9px]"></i> Ditolak
                            </span>
                        @elseif($reg->status == 'completed')
                            <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1 shrink-0">
                                <i class="fa-solid fa-flag-checkered text-[9px]"></i> Selesai
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
                                <i class="fa-solid fa-graduation-cap mr-1"></i> Mahasiswa {{ $reg->program_type }}
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

                    <!-- Tombol Aksi Full Width -->
                    <a href="{{ route('admin.verification.show', $reg->id) }}" class="w-full py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs transition-all font-heading shadow-sm flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-sliders text-xs"></i>
                        <span>{{ in_array($reg->status, ['approved', 'completed']) ? 'Ubah / Detail Penempatan' : 'Tinjau Berkas Pengajuan' }}</span>
                    </a>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 font-medium">Tidak ada pengajuan pendaftaran yang ditemukan.</div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
@endsection
