@extends('layouts.admin')

@section('title', 'Kelola Surat Balasan — Admin Diskominfo')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#014495] inline-flex items-center gap-1 font-heading transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
        <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Kelola & Upload Surat Balasan Manual</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Unggah file PDF surat balasan penerimaan atau penolakan resmi untuk pendaftar yang sudah diverifikasi.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200 font-heading">
                    <tr>
                        <th class="p-4">Pendaftar / Institusi</th>
                        <th class="p-4">Program & Bidang Placement</th>
                        <th class="p-4">Status Pengajuan</th>
                        <th class="p-4">File Surat Balasan PDF saat ini</th>
                        <th class="p-4">Upload / Ganti Surat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 text-sm block font-heading">{{ $reg->leader->full_name ?? $reg->user->name }}</span>
                                <span class="text-slate-500 text-[11px]">{{ $reg->institution->institution_name ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-slate-800 block font-heading">Program {{ $reg->program_type }}</span>
                                <span class="text-[#014495] font-bold text-[11px]">{{ $reg->department->name ?? 'Belum Ditempatkan' }}</span>
                            </td>
                            <td class="p-4">
                                @if($reg->status == 'approved')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px]">Diterima</span>
                                @elseif($reg->status == 'rejected')
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 font-bold rounded-full text-[10px]">Ditolak</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($reg->replyLetter)
                                    <a href="{{ Storage::url($reg->replyLetter->file_path) }}" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-all inline-flex items-center gap-1.5 font-heading shadow-sm">
                                        <i class="fa-solid fa-file-pdf"></i> Lihat PDF
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Belum Diupload</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <form action="{{ route('admin.reply-letters.upload', $reg->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    <input type="file" name="reply_letter" required accept=".pdf" class="w-48 text-[11px] text-slate-500 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:bg-slate-200 file:font-semibold">
                                    <button type="submit" class="px-3.5 py-1.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl transition-all text-xs font-heading shadow-sm active:scale-[0.98]">
                                        Upload
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 font-medium">Belum ada pendaftaran yang disetujui/ditolak untuk diunggah surat alasannya.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
