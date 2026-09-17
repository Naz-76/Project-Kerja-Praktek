@extends('layouts.app')

@section('title', 'Detail Pengajuan Saya — Portal Magang & PKL Diskominfo')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('applicant.dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <span class="text-xs font-mono text-slate-400">ID Registrasi #{{ $registration->id }}</span>
    </div>

    <!-- Status Card -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Program {{ $registration->program_type }}</span>
                <h1 class="font-heading text-2xl font-bold text-slate-900">{{ $registration->institution->institution_name ?? '-' }}</h1>
            </div>
            <div>
                @if($registration->status == 'pending')
                    <span class="px-4 py-2 bg-amber-100 text-amber-800 border border-amber-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-clock"></i> Pending (Menunggu Verifikasi)
                    </span>
                @elseif($registration->status == 'approved')
                    <span class="px-4 py-2 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> DITERIMA
                    </span>
                @elseif($registration->status == 'rejected')
                    <span class="px-4 py-2 bg-rose-100 text-rose-800 border border-rose-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-xmark"></i> DITOLAK
                    </span>
                @elseif($registration->status == 'completed')
                    <span class="px-4 py-2 bg-sky-100 text-sky-800 border border-sky-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-flag-checkered"></i> SELESAI
                    </span>
                @endif
            </div>
        </div>

        @if($registration->status == 'approved' || $registration->status == 'completed')
            @if($registration->supervisor_name)
            <div class="p-4 bg-sky-50 border border-sky-200 rounded-xl text-xs text-sky-900 space-y-1">
                <span class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-user-tie text-sky-600"></i> Pembimbing Lapangan Anda:</span>
                <p class="text-slate-700">{{ $registration->supervisor_name }} <span class="text-slate-500">({{ $registration->supervisor_position ?: 'Tidak ada keterangan jabatan' }})</span></p>
                @if($registration->supervisor_phone)
                    <p class="text-slate-600"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i>{{ $registration->supervisor_phone }}</p>
                @endif
            </div>
            @endif
            @if($registration->acceptance_message)
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 space-y-1">
                <span class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-comment-dots text-emerald-600"></i> Pesan dari Admin Diskominfo:</span>
                <p class="text-slate-700 leading-relaxed">{{ $registration->acceptance_message }}</p>
            </div>
            @endif
        @elseif($registration->status == 'rejected')
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 space-y-1">
                <span class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Alasan Penolakan dari Admin:</span>
                <p class="text-slate-700 leading-relaxed">{{ $registration->rejection_reason ?? 'Berkas/kuota tidak memenuhi persyaratan.' }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-500 block mb-1">Bidang Penempatan Final</span>
                <span class="font-bold text-slate-900 text-sm">
                    @if($registration->status == 'approved' || $registration->status == 'completed')
                        {{ $registration->department->name ?? 'Belum Ditentukan' }}
                    @elseif($registration->status == 'rejected')
                        <span class="text-rose-600">Tidak Ditempatkan</span>
                    @else
                        <span class="text-amber-600">Menunggu Verifikasi</span>
                    @endif
                </span>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-500 block mb-1">Durasi Pelaksanaan</span>
                <span class="font-bold text-slate-900 text-sm">
                    {{ $registration->start_date ? $registration->start_date->format('d M Y') : '-' }} s/d {{ $registration->end_date ? $registration->end_date->format('d M Y') : '-' }}
                </span>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-500 block mb-1">Surat Balasan Digital</span>
                @if($registration->replyLetter)
                    <a href="{{ Storage::url($registration->replyLetter->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition-all">
                        <i class="fa-solid fa-download"></i> Unduh PDF
                    </a>
                @else
                    <span class="text-slate-400 font-semibold">Belum Diunggah</span>
                @endif
            </div>
        </div>

        <!-- Data Peserta -->
        <div class="space-y-3 pt-4 border-t border-slate-100">
            <h3 class="font-bold text-sm text-slate-800">Daftar Peserta ({{ $registration->participant_count }} Orang)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase">
                        <tr>
                            <th class="p-3">Nama</th>
                            <th class="p-3">NIS/NIM</th>
                            <th class="p-3">Jurusan</th>
                            <th class="p-3">Kontak</th>
                            <th class="p-3">Peran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($registration->participants as $p)
                            <tr>
                                <td class="p-3 font-semibold text-slate-900">{{ $p->full_name }}</td>
                                <td class="p-3 font-mono text-slate-600">{{ $p->nis_nim }}</td>
                                <td class="p-3 text-slate-600">{{ $p->major }}</td>
                                <td class="p-3 text-slate-600">{{ $p->phone ?: '-' }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $p->is_leader ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $p->is_leader ? 'Ketua' : 'Anggota' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
