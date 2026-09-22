@extends('layouts.admin')

@section('title', 'Tinjau Pengajuan — Admin Diskominfo')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.verification.index') }}" class="text-xs font-semibold text-slate-600 hover:text-[#014495] flex items-center gap-1.5 font-heading transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Verifikasi
        </a>
        <span class="text-xs font-mono text-slate-400 bg-slate-100 px-3 py-1 rounded-full">ID Registrasi #{{ $registration->id }}</span>
    </div>

    <!-- Main Card -->
    <div class="bg-white p-4 sm:p-8 lg:p-10 rounded-3xl border border-slate-200 shadow-xl space-y-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="min-w-0 max-w-full">
                <span class="text-xs font-bold text-[#014495] uppercase tracking-wider font-heading">{{ $registration->applicant_status }} — Program {{ $registration->program_type }}</span>
                <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 mt-0.5 break-words">{{ $registration->institution->institution_name ?? '-' }}</h1>
            </div>
            <div>
                @if($registration->status == 'pending')
                    <span class="px-4 py-2 bg-amber-100 text-amber-800 border border-amber-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-clock animate-pulse"></i> Status: Pending
                    </span>
                @elseif($registration->status == 'approved')
                    <span class="px-4 py-2 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> Status: Diterima
                    </span>
                @elseif($registration->status == 'rejected')
                    <span class="px-4 py-2 bg-rose-100 text-rose-800 border border-rose-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-xmark"></i> Status: Ditolak
                    </span>
                @elseif($registration->status == 'completed')
                    <span class="px-4 py-2 bg-sky-100 text-sky-800 border border-sky-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-flag-checkered"></i> Status: Selesai
                    </span>
                @endif
            </div>
        </div>

        <!-- Informasi Rincian Durasi Pelaksanaan & Preferensi Bidang -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Tanggal Mulai -->
            <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-2xl flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#014495] flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-calendar-plus text-base"></i>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block font-heading">Tanggal Mulai</span>
                    <span class="text-xs font-extrabold text-slate-800">
                        {{ $registration->start_date ? \Carbon\Carbon::parse($registration->start_date)->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>
            </div>

            <!-- Tanggal Selesai -->
            <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-2xl flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#014495] flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-calendar-check text-base"></i>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block font-heading">Tanggal Selesai</span>
                    <span class="text-xs font-extrabold text-slate-800">
                        {{ $registration->end_date ? \Carbon\Carbon::parse($registration->end_date)->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>
            </div>

            <!-- Estimasi Durasi -->
            <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-2xl flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-hourglass-half text-base"></i>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block font-heading">Durasi Pelaksanaan</span>
                    <span class="text-xs font-extrabold text-slate-800">
                        @if($registration->start_date && $registration->end_date)
                            @php
                                $start = \Carbon\Carbon::parse($registration->start_date);
                                $end = \Carbon\Carbon::parse($registration->end_date);
                                $days = $start->diffInDays($end) + 1;
                                $months = round($days / 30, 1);
                            @endphp
                            {{ $days }} Hari
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            <!-- Preferensi Bidang -->
            <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-2xl flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-[#0B6FBB] flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-building-user text-base"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block font-heading">Pilihan Bidang</span>
                    <span class="text-xs font-extrabold text-[#014495] truncate block" title="{{ $registration->preferredDepartment->name ?? ($registration->department->name ?? 'Belum ditentukan') }}">
                        {{ $registration->preferredDepartment->name ?? ($registration->department->name ?? 'Belum ditentukan') }}
                    </span>
                </div>
            </div>
        </div>

        @if($registration->status == 'approved' || $registration->status == 'completed')
            @if($registration->supervisor_name)
            <div class="p-5 bg-blue-50/70 border border-blue-200 rounded-2xl text-xs text-blue-950 space-y-1">
                <span class="font-bold flex items-center gap-2 font-heading text-[#014495]"><i class="fa-solid fa-user-tie"></i> Pembimbing Lapangan:</span>
                <p class="text-slate-700 font-medium">{{ $registration->supervisor_name }} <span class="text-slate-500">({{ $registration->supervisor_position ?: 'Tidak ada keterangan jabatan' }})</span></p>
                @if($registration->supervisor_phone)
                    <p class="text-slate-600"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i> {{ $registration->supervisor_phone }}</p>
                @endif
            </div>
            @endif
            @if($registration->acceptance_message)
            <div class="p-5 bg-emerald-50/70 border border-emerald-200 rounded-2xl text-xs text-emerald-950 space-y-1">
                <span class="font-bold flex items-center gap-2 font-heading text-emerald-700"><i class="fa-solid fa-comment-dots"></i> Catatan/Pesan Penerimaan dari Admin:</span>
                <p class="text-slate-700 font-medium leading-relaxed">{{ $registration->acceptance_message }}</p>
            </div>
            @endif
        @endif

        @if($registration->status == 'rejected' && $registration->rejection_reason)
            <div class="p-5 bg-rose-50/70 border border-rose-200 rounded-2xl text-xs text-rose-950 space-y-1">
                <span class="font-bold flex items-center gap-2 font-heading text-rose-700"><i class="fa-solid fa-triangle-exclamation"></i> Alasan Penolakan dari Admin:</span>
                <p class="text-slate-700 font-medium leading-relaxed">{{ $registration->rejection_reason }}</p>
            </div>
        @endif

        @if(strtolower($registration->applicant_status) == 'siswa' && $registration->institution && $registration->institution->teacher_name)
        <div class="p-5 bg-blue-50/60 border border-blue-200 rounded-2xl text-xs space-y-1.5">
            <span class="font-bold flex items-center gap-2 text-[#014495] font-heading"><i class="fa-solid fa-chalkboard-user"></i> Guru Pembimbing Sekolah (SMK / SMA)</span>
            <div class="flex flex-wrap gap-x-6 gap-y-1 mt-1 text-slate-700">
                <span><i class="fa-solid fa-user text-slate-400 mr-1.5"></i><strong>Nama:</strong> {{ $registration->institution->teacher_name }}</span>
                <span><i class="fa-solid fa-phone text-slate-400 mr-1.5"></i><strong>No. WhatsApp:</strong> {{ $registration->institution->teacher_phone ?: '-' }}</span>
                @if($registration->institution->teacher_email)
                <span><i class="fa-solid fa-envelope text-slate-400 mr-1.5"></i><strong>Email:</strong> {{ $registration->institution->teacher_email }}</span>
                @endif
            </div>
        </div>
        @endif

        <!-- Detail Peserta -->
        <div class="space-y-3">
            <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2 font-heading">
                <i class="fa-solid fa-users text-[#014495]"></i> Anggota Tim Peserta ({{ $registration->participant_count }} Orang)
            </h3>
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase font-heading border-b border-slate-200">
                        <tr>
                            <th class="p-3.5">Nama Lengkap</th>
                            <th class="p-3.5">NISN / NIM</th>
                            <th class="p-3.5">Jurusan</th>
                            <th class="p-3.5">Kontak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($registration->participants as $p)
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-3.5 font-semibold text-slate-900">{{ $p->full_name }} {{ $p->is_leader ? '(Ketua)' : '' }}</td>
                                <td class="p-3.5 font-mono text-slate-600">{{ $p->nis_nim }}</td>
                                <td class="p-3.5 text-slate-600">{{ $p->major }}</td>
                                <td class="p-3.5 text-slate-600">{{ $p->phone ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Berkas Dokumen Uploaded -->
        <div class="space-y-3">
            <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2 font-heading">
                <i class="fa-solid fa-folder-open text-[#014495]"></i> Verifikasi Berkas Dokumen Uploaded
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($registration->documents as $doc)
                    <div class="p-4 bg-slate-50/70 border border-slate-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 hover:bg-slate-50 hover:border-slate-300 transition-all">
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-xs shrink-0">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <span class="font-bold text-slate-800 text-xs uppercase font-heading truncate">
                                    {{ str_replace('_', ' ', $doc->document_category) }}
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-500 font-mono break-all block pl-9" title="{{ $doc->file_name }}">
                                {{ $doc->file_name }}
                            </span>
                        </div>
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="w-full sm:w-auto px-4 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs transition-all inline-flex items-center justify-center gap-1.5 font-heading shadow-sm shrink-0 active:scale-[0.98]">
                            <i class="fa-solid fa-eye"></i> Lihat PDF
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- File Surat Balasan Digital saat ini -->
        <div class="p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="min-w-0 flex-1">
                <span class="font-bold text-slate-800 block font-heading">Surat Balasan Digital Resmi:</span>
                @if($registration->replyLetter)
                    <span class="text-emerald-700 font-semibold flex items-center gap-1.5 mt-1 break-all">
                        <i class="fa-solid fa-file-pdf shrink-0"></i> Terunggah pada {{ $registration->replyLetter->uploaded_at ? $registration->replyLetter->uploaded_at->format('d M Y H:i') : '-' }}
                    </span>
                @else
                    <span class="text-slate-400 italic block mt-0.5">Belum diunggah. Unggah saat konfirmasi keputusan di bawah.</span>
                @endif
            </div>
            @if($registration->replyLetter)
                <a href="{{ Storage::url($registration->replyLetter->file_path) }}" target="_blank" class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all font-heading inline-flex items-center justify-center gap-2 shadow-sm shrink-0 active:scale-[0.98]">
                    <i class="fa-solid fa-download"></i> Unduh Surat Balasan PDF
                </a>
            @endif
        </div>
        
        <!-- Panel Aksi Verifikasi, Penempatan Bidang & Upload Surat Balasan Terintegrasi -->
        <div class="p-5 sm:p-8 bg-slate-900 text-white rounded-3xl border border-slate-800 space-y-6 shadow-xl">
            <h3 class="font-heading text-base sm:text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-sliders text-[#2F90E1]"></i> Aksi Keputusan Verifikasi & Penempatan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($registration->status == 'pending')
                    <!-- Form Approve / Penempatan Bidang & Pesan Penerimaan (Hanya tampil jika berstatus Pending / Menunggu Verifikasi) -->
                    <form id="formApproveRegistration" action="{{ route('admin.verification.approve', $registration->id) }}" method="POST" enctype="multipart/form-data" class="p-6 bg-slate-800/90 border border-slate-700 rounded-2xl space-y-4">
                        @csrf
                        <span class="font-bold text-xs text-emerald-400 uppercase tracking-wider block font-heading">Setujui / Konfirmasi DITERIMA</span>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Bidang Penempatan Final *</label>
                            <select name="department_id" id="departmentSelect" required oninvalid="this.setCustomValidity('Tolong pilih bidang penempatan terlebih dahulu')" onchange="this.setCustomValidity('')" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Bidang Diskominfo --</option>
                                @foreach($departments as $dept)
                                    @php $q = $dept->slotQuotas->first(); @endphp
                                    <option value="{{ $dept->id }}" {{ $registration->department_id == $dept->id || $registration->preferred_department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} (Sisa Kuota: {{ $q ? $q->quota_remaining : 0 }} slot)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="p-4 bg-slate-700/50 rounded-xl space-y-4 border border-slate-700">
                            <div>
                                <label class="block text-xs font-semibold text-emerald-400 mb-1.5"><i class="fa-solid fa-user-tie"></i> Pilih Pembimbing Lapangan *</label>
                                <select id="supervisorSelect" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="">-- Pilih Pembimbing (Otomatis Isi Form) --</option>
                                </select>
                                <p class="text-[10px] text-slate-400 mt-1">Daftar pembimbing menyesuaikan dengan Bidang Penempatan yang dipilih.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-400 mb-1">Nama Lengkap Pembimbing *</label>
                                    <input type="text" id="supervisorName" name="supervisor_name" value="{{ old('supervisor_name', $registration->supervisor_name) }}" placeholder="Ketik atau pilih dari opsi di atas..." required oninvalid="this.setCustomValidity('Tolong pilih pembimbing terlebih dahulu')" oninput="this.setCustomValidity('')" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold text-slate-400 mb-1">Jabatan Pembimbing</label>
                                    <input type="text" id="supervisorPosition" name="supervisor_position" value="{{ old('supervisor_position', $registration->supervisor_position) }}" placeholder="Jabatan..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-semibold text-slate-400 mb-1">No. WA Pembimbing</label>
                                    <input type="tel" inputmode="numeric" pattern="[0-9]*" id="supervisorPhone" name="supervisor_phone" value="{{ old('supervisor_phone', $registration->supervisor_phone) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="08xx..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kotak Pesan/Catatan Penerimaan (Opsional)</label>
                            @php
                                $defaultMessage = '';
                                if (!$registration->acceptance_message) {
                                    if (strtolower($registration->applicant_status) == 'siswa') {
                                        $defaultMessage = 'Selamat, pengajuan Anda telah disetujui. Silakan bawa dokumen fisik Surat Pengantar Asli dari sekolah saat pertama kali masuk ke kantor Diskominfo Garut.';
                                    } else {
                                        $defaultMessage = 'Selamat, pengajuan Anda telah disetujui. Silakan bawa dokumen fisik Surat Pengantar Asli dan Surat Rekomendasi Bakesbangpol saat pertama kali masuk ke kantor Diskominfo Garut.';
                                    }
                                }
                            @endphp
                            <textarea name="acceptance_message" rows="4" placeholder="Tuliskan petunjuk / ucapan selamat penerimaan untuk pendaftar..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed">{{ old('acceptance_message', $registration->acceptance_message ?: $defaultMessage) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Upload File Surat Balasan PDF (Opsional)</label>
                            <input type="file" name="reply_letter" accept=".pdf" class="w-full text-xs text-slate-400 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:bg-slate-700 file:text-slate-200">
                        </div>

                        <button type="button" onclick="handleApproveClick()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2 font-heading active:scale-[0.98]">
                            <i class="fa-solid fa-circle-check"></i> Konfirmasi Diterima & Simpan
                        </button>
                    </form>
                @elseif($registration->status == 'approved')
                    <!-- Informasi Ringkasan Pendaftar Diterima (Elegan, Lengkap & Full Width) -->
                    <div class="p-6 sm:p-8 bg-slate-800/90 border border-emerald-500/40 rounded-3xl space-y-6 md:col-span-2 shadow-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-700/80 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg border border-emerald-500/30 shrink-0">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm sm:text-base text-white font-heading">Pendaftar Telah Resmi DITERIMA</h4>
                                    <span class="text-[11px] text-emerald-400 font-medium">Berkas telah diverifikasi dan kuota bidang berhasil dialokasikan</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 text-xs font-bold rounded-xl border border-emerald-500/30 self-start sm:self-auto flex items-center gap-1.5 font-heading">
                                <i class="fa-solid fa-check"></i> Terverifikasi & Ditempatkan
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                            <!-- Bidang Definitif -->
                            <div class="p-4 bg-slate-900/70 rounded-2xl border border-slate-700/70 space-y-1">
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block font-heading">Bidang Penempatan Definitif</span>
                                <span class="font-extrabold text-emerald-400 text-sm block">{{ $registration->department->name ?? '-' }}</span>
                            </div>

                            <!-- Pembimbing Lapangan -->
                            <div class="p-4 bg-slate-900/70 rounded-2xl border border-slate-700/70 space-y-1">
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block font-heading">Pembimbing Lapangan</span>
                                <span class="font-bold text-white text-sm block">{{ $registration->supervisor_name ?: '-' }}</span>
                                @if($registration->supervisor_position)
                                    <span class="text-slate-400 block text-[11px] font-medium">{{ $registration->supervisor_position }}</span>
                                @endif
                                @if($registration->supervisor_phone)
                                    <span class="text-emerald-400 block text-[11px] font-medium mt-0.5"><i class="fa-brands fa-whatsapp mr-1"></i>{{ $registration->supervisor_phone }}</span>
                                @endif
                            </div>

                            <!-- Dokumen Surat Balasan & Form Upload Susulan -->
                            <div class="p-4 bg-slate-900/70 rounded-2xl border border-slate-700/70 space-y-2.5 flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block font-heading">Surat Balasan Digital Resmi</span>
                                    <span class="text-slate-300 text-[11px] block mt-0.5">
                                        {{ $registration->replyLetter ? 'Dokumen balasan resmi telah diterbitkan' : 'Belum ada surat balasan diunggah' }}
                                    </span>
                                    @if($registration->replyLetter)
                                        <a href="{{ Storage::url($registration->replyLetter->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-emerald-400 hover:text-emerald-300 font-bold font-heading mt-1">
                                            <i class="fa-solid fa-file-pdf"></i> Unduh Surat Balasan PDF &rarr;
                                        </a>
                                    @endif
                                </div>

                                <!-- Form Upload / Update Surat Balasan Susulan -->
                                <form action="{{ route('admin.verification.upload-reply-letter', $registration->id) }}" method="POST" enctype="multipart/form-data" class="pt-2 border-t border-slate-800 space-y-1.5">
                                    @csrf
                                    <label class="block text-[10px] font-semibold text-emerald-400">
                                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> {{ $registration->replyLetter ? 'Perbarui Surat Balasan (PDF):' : 'Unggah Surat Balasan Susulan (PDF):' }}
                                    </label>
                                    <div class="flex flex-col sm:flex-row gap-1.5">
                                        <input type="file" name="reply_letter" accept=".pdf" required class="w-full text-[10px] text-slate-400 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-slate-800 file:text-slate-200 file:text-[10px]">
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[11px] transition-all font-heading shrink-0 shadow-sm inline-flex items-center justify-center gap-1 active:scale-[0.98]">
                                            <i class="fa-solid fa-check"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if($registration->acceptance_message)
                        <div class="p-4 bg-slate-900/70 rounded-2xl border border-slate-700/70 space-y-1 text-xs">
                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block font-heading flex items-center gap-1.5">
                                <i class="fa-solid fa-comment-dots text-emerald-400"></i> Catatan & Arahan Penerimaan dari Admin
                            </span>
                            <p class="text-slate-200 leading-relaxed italic text-[11px] bg-slate-950/40 p-3 rounded-xl border border-slate-800">
                                "{{ $registration->acceptance_message }}"
                            </p>
                        </div>
                        @endif
                    </div>
                @endif

                @if($registration->status == 'pending')
                    <!-- Form Reject (Hanya tampil jika berstatus Pending / Menunggu Verifikasi) -->
                    <form id="formRejectRegistration" action="{{ route('admin.verification.reject', $registration->id) }}" method="POST" enctype="multipart/form-data" class="p-6 bg-slate-800/90 border border-slate-700 rounded-2xl space-y-4">
                        @csrf
                        <span class="font-bold text-xs text-rose-400 uppercase tracking-wider block font-heading">Tolak / Ubah Ke DITOLAK</span>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Alasan Penolakan *</label>
                            <textarea id="rejectionReasonInput" name="rejection_reason" required rows="4" placeholder="Tuliskan alasan penolakan secara jelas..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-rose-500 leading-relaxed">{{ old('rejection_reason', $registration->rejection_reason) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Upload File Surat Penolakan PDF (Opsional)</label>
                            <input type="file" name="reply_letter" accept=".pdf" class="w-full text-xs text-slate-400 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:bg-slate-700 file:text-slate-200">
                        </div>

                        <button type="button" onclick="handleRejectClick()" class="w-full py-3 bg-[#8B0000] hover:bg-[#6b0000] text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2 font-heading active:scale-[0.98]">
                            <i class="fa-solid fa-circle-xmark"></i> Konfirmasi Ditolak & Simpan
                        </button>
                    </form>
                @elseif($registration->status == 'rejected')
                    <div class="p-6 bg-slate-800/90 border border-rose-500/40 rounded-2xl md:col-span-2 text-center py-8 space-y-2">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 mx-auto flex items-center justify-center text-xl">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <h4 class="font-bold text-sm text-white font-heading">Pengajuan Ini Berstatus DITOLAK</h4>
                        <p class="text-xs text-rose-300 max-w-md mx-auto leading-relaxed"><strong>Alasan Penolakan:</strong> {{ $registration->rejection_reason ?? 'Tidak memenuhi kualifikasi / kuota bidang telah penuh.' }}</p>
                    </div>
                @elseif($registration->status == 'completed')
                    <div class="p-6 bg-slate-800/90 border border-sky-500/40 rounded-2xl md:col-span-2 text-center py-8 space-y-2">
                        <div class="w-12 h-12 rounded-2xl bg-sky-500/20 text-sky-400 mx-auto flex items-center justify-center text-xl">
                            <i class="fa-solid fa-flag-checkered"></i>
                        </div>
                        <h4 class="font-bold text-sm text-white font-heading">Program Magang / PKL Telah Selesai</h4>
                        <p class="text-xs text-slate-400 max-w-md mx-auto leading-relaxed">Pendaftar ini telah menyelesaikan seluruh masa pelaksanaan di Diskominfo Garut. Kuota bidang telah dilepaskan kembali.</p>
                    </div>
                @endif

                @if($registration->status == 'approved')
                <!-- Form Selesai / Completed (Hanya tampil untuk pendaftar yang sudah Diterima) -->
                <form id="formCompleteRegistration" action="{{ route('admin.verification.complete', $registration->id) }}" method="POST" class="p-6 bg-slate-800/90 border border-slate-700 rounded-2xl space-y-3 md:col-span-2">
                    @csrf
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="font-bold text-xs text-sky-400 uppercase tracking-wider block font-heading flex items-center gap-1.5">
                                <i class="fa-solid fa-flag-checkered"></i> Tandai / Ubah Ke SELESAI
                            </span>
                            <p class="text-[11px] text-slate-400 mt-1">Gunakan opsi ini apabila masa kegiatan magang/PKL telah selesai. Kuota bidang terkait akan otomatis dibebaskan.</p>
                        </div>
                        <button type="button" onclick="handleCompleteClick()" class="px-5 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2 font-heading shrink-0 active:scale-[0.98]">
                            <i class="fa-solid fa-flag-checkered"></i> Konfirmasi Selesai & Simpan
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data Pembimbing Lapangan
        const supervisorsData = @json($departments->mapWithKeys(function($dept) {
            return [$dept->id => $dept->fieldSupervisors];
        }));

        const departmentSelect = document.getElementById('departmentSelect');
        const supervisorSelect = document.getElementById('supervisorSelect');
        
        const inputName = document.getElementById('supervisorName');
        const inputPosition = document.getElementById('supervisorPosition');
        const inputPhone = document.getElementById('supervisorPhone');

        function updateSupervisorDropdown() {
            const deptId = departmentSelect.value;
            
            // Kosongkan opsi lama
            supervisorSelect.innerHTML = '<option value="">-- Pilih Pembimbing (Otomatis Isi Form) --</option>';
            
            if (deptId && supervisorsData[deptId]) {
                const supervisors = supervisorsData[deptId];
                supervisors.forEach(sup => {
                    const option = document.createElement('option');
                    option.value = sup.id;
                    option.textContent = sup.name + (sup.position ? ` - ${sup.position}` : '');
                    // Simpan data di dataset untuk diambil nanti
                    option.dataset.name = sup.name;
                    option.dataset.position = sup.position || '';
                    option.dataset.phone = sup.phone || '';
                    supervisorSelect.appendChild(option);
                });
            }
        }

        function fillSupervisorData() {
            const selectedOption = supervisorSelect.options[supervisorSelect.selectedIndex];
            if (selectedOption.value) {
                inputName.value = selectedOption.dataset.name;
                inputName.setCustomValidity('');
                inputPosition.value = selectedOption.dataset.position;
                inputPhone.value = selectedOption.dataset.phone;
            } else {
                // Jangan kosongkan jika user ingin input manual, 
                // tapi bisa dikosongkan jika ingin force reset.
            }
        }

        // Event Listeners
        if (departmentSelect) {
            departmentSelect.addEventListener('change', function() {
                departmentSelect.setCustomValidity('');
                updateSupervisorDropdown();
            });
            // Panggil sekali saat load jika sudah ada yang terpilih
            updateSupervisorDropdown();
        }

        if (supervisorSelect) {
            supervisorSelect.addEventListener('change', fillSupervisorData);
        }
    });

    // Custom Modal Confirmation Handlers (Design Guidelines Compliant)
    function handleApproveClick() {
        const form = document.getElementById('formApproveRegistration');
        if (!form) return;

        const deptSelect = document.getElementById('departmentSelect');
        const supName = document.getElementById('supervisorName');

        // Cek validasi Bidang Penempatan
        if (!deptSelect || !deptSelect.value) {
            if (deptSelect) {
                deptSelect.setCustomValidity('Tolong pilih bidang penempatan terlebih dahulu');
                deptSelect.focus();
                deptSelect.reportValidity();
            }
            return;
        } else {
            deptSelect.setCustomValidity('');
        }

        // Cek validasi Pembimbing Lapangan
        if (!supName || !supName.value.trim()) {
            if (supName) {
                supName.setCustomValidity('Tolong pilih pembimbing terlebih dahulu');
                supName.focus();
                supName.reportValidity();
            }
            return;
        } else {
            supName.setCustomValidity('');
        }

        // Cek validasi form HTML5 secara keseluruhan
        if (!form.reportValidity()) {
            return;
        }

        // Ambil nama bidang dan nama pembimbing untuk ditampilkan di modal konfirmasi
        const deptText = deptSelect.options[deptSelect.selectedIndex].text.split('(')[0].trim();
        const supervisorText = supName.value.trim();

        openCustomConfirm({
            title: 'Konfirmasi Penerimaan & Penempatan',
            message: `Apakah Anda yakin ingin menyetujui pengajuan ini?<br><div class="mt-2.5 p-3 bg-slate-800/80 rounded-xl border border-slate-700 text-[11px] text-left space-y-1"><div class="flex justify-between"><span class="text-slate-400">Bidang:</span><strong class="text-emerald-400 font-bold">${deptText}</strong></div><div class="flex justify-between"><span class="text-slate-400">Pembimbing:</span><strong class="text-white font-semibold">${supervisorText}</strong></div></div><span class="text-[10px] text-amber-400/90 mt-2 block"><i class="fa-solid fa-circle-exclamation mr-1"></i>Pastikan bidang dan pembimbing telah sesuai sebelum melanjutkan.</span>`,
            icon: 'fa-solid fa-circle-check',
            iconColor: 'text-emerald-400',
            iconBg: 'bg-emerald-500/20',
            iconBorder: 'border-emerald-500/30',
            btnText: 'Ya, Setujui & Simpan',
            btnColor: 'bg-emerald-600 hover:bg-emerald-700',
            onConfirm: function() {
                form.submit();
            }
        });
    }

    function handleRejectClick() {
        const form = document.getElementById('formRejectRegistration');
        if (!form) return;
        
        // Cek validasi form HTML5 (misal textarea wajib diisi)
        if (!form.reportValidity()) {
            return;
        }

        openCustomConfirm({
            title: 'Konfirmasi Tolak Pengajuan',
            message: 'Apakah Anda yakin ingin mengubah status menjadi <strong class="text-rose-400 font-bold">DITOLAK</strong>?<br><span class="text-[11px] text-slate-400 mt-1.5 block">Kuota bidang terkait akan otomatis dikembalikan ke kuota tersedia.</span>',
            icon: 'fa-solid fa-triangle-exclamation',
            iconColor: 'text-rose-400',
            iconBg: 'bg-rose-500/20',
            iconBorder: 'border-rose-500/30',
            btnText: 'Ya, Tolak Pengajuan',
            btnColor: 'bg-[#8B0000] hover:bg-[#6b0000]',
            onConfirm: function() {
                form.submit();
            }
        });
    }

    function handleCompleteClick() {
        const form = document.getElementById('formCompleteRegistration');
        if (!form) return;

        openCustomConfirm({
            title: 'Konfirmasi Selesai Program',
            message: 'Apakah Anda yakin ingin menandai status pengajuan ini sebagai <strong class="text-sky-400 font-bold">SELESAI</strong>?<br><span class="text-[11px] text-slate-400 mt-1.5 block">Kuota bidang terkait akan otomatis dibebaskan kembali.</span>',
            icon: 'fa-solid fa-flag-checkered',
            iconColor: 'text-sky-400',
            iconBg: 'bg-sky-500/20',
            iconBorder: 'border-sky-500/30',
            btnText: 'Ya, Tandai Selesai',
            btnColor: 'bg-sky-600 hover:bg-sky-700',
            onConfirm: function() {
                form.submit();
            }
        });
    }
</script>
@endpush
