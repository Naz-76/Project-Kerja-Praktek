@extends('layouts.app')

@section('title', 'Surat Balasan Saya — Diskominfo Garut')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-slate-900">Surat Balasan Digital</h1>
        <p class="text-xs text-slate-500 mt-1">Unduh dokumen resmi surat balasan penerimaan / penolakan dari Diskominfo Garut.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($registrations->count() > 0)
            <div class="divide-y divide-slate-200 text-xs">
                @foreach($registrations as $reg)
                    <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                        <div class="space-y-1">
                            <span class="font-mono text-[10px] text-slate-400">ID Pengajuan #{{ $reg->id }}</span>
                            <h3 class="font-bold text-sm text-slate-900">{{ $reg->institution->institution_name ?? '-' }}</h3>
                            <p class="text-slate-500">Program {{ $reg->program_type }} — {{ $reg->department->name ?? '-' }}</p>
                        </div>
                        <div>
                            @if($reg->replyLetter)
                                <a href="{{ Storage::url($reg->replyLetter->file_path) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-2 text-xs">
                                    <i class="fa-solid fa-file-pdf text-sm"></i> Unduh Surat Balasan PDF
                                </a>
                            @else
                                <span class="text-xs text-slate-400 font-semibold italic">Surat Belum Diupload Admin</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-slate-400 text-xs">
                Belum ada surat balasan yang tersedia untuk diunduh.
            </div>
        @endif
    </div>
</div>
@endsection
