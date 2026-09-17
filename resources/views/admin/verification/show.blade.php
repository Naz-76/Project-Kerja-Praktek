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
    <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xl space-y-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="text-xs font-bold text-[#014495] uppercase tracking-wider font-heading">{{ $registration->applicant_status }} — Program {{ $registration->program_type }}</span>
                <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 mt-0.5">{{ $registration->institution->institution_name ?? '-' }}</h1>
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
                    <div class="p-4 bg-slate-50/70 border border-slate-200 rounded-2xl flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800 text-xs uppercase block font-heading">{{ str_replace('_', ' ', $doc->document_category) }}</span>
                            <span class="text-[11px] text-slate-500 font-mono">{{ $doc->file_name }}</span>
                        </div>
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="px-3.5 py-2 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 font-heading shadow-sm">
                            <i class="fa-solid fa-eye"></i> Lihat PDF
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- File Surat Balasan Digital saat ini -->
        <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div>
                <span class="font-bold text-slate-800 block font-heading">Surat Balasan Digital Resmi:</span>
                @if($registration->replyLetter)
                    <span class="text-emerald-700 font-semibold flex items-center gap-1.5 mt-1">
                        <i class="fa-solid fa-file-pdf"></i> Terunggah pada {{ $registration->replyLetter->uploaded_at ? $registration->replyLetter->uploaded_at->format('d M Y H:i') : '-' }}
                    </span>
                @else
                    <span class="text-slate-400 italic">Belum diunggah. Unggah saat konfirmasi keputusan di bawah.</span>
                @endif
            </div>
            @if($registration->replyLetter)
                <a href="{{ Storage::url($registration->replyLetter->file_path) }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all font-heading flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-download"></i> Unduh Surat Balasan PDF
                </a>
            @endif
        </div>
        
        <!-- Panel Aksi Verifikasi, Penempatan Bidang & Upload Surat Balasan Terintegrasi -->
        <div class="p-8 bg-slate-900 text-white rounded-3xl border border-slate-800 space-y-6 shadow-xl">
            <h3 class="font-heading text-base sm:text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-sliders text-[#2F90E1]"></i> Aksi Keputusan Verifikasi & Penempatan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Form Approve / Penempatan Bidang & Pesan Penerimaan -->
                <form action="{{ route('admin.verification.approve', $registration->id) }}" method="POST" enctype="multipart/form-data" class="p-6 bg-slate-800/90 border border-slate-700 rounded-2xl space-y-4">
                    @csrf
                    <span class="font-bold text-xs text-emerald-400 uppercase tracking-wider block font-heading">Setujui / Ubah Ke DITERIMA</span>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Bidang Penempatan Final *</label>
                        <select name="department_id" id="departmentSelect" required class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
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
                            <label class="block text-xs font-semibold text-emerald-400 mb-1.5"><i class="fa-solid fa-user-tie"></i> Pilih Pembimbing Lapangan</label>
                            <select id="supervisorSelect" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Pembimbing (Otomatis Isi Form) --</option>
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">Daftar pembimbing menyesuaikan dengan Bidang Penempatan yang dipilih.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] font-semibold text-slate-400 mb-1">Nama Lengkap Pembimbing</label>
                                <input type="text" id="supervisorName" name="supervisor_name" value="{{ old('supervisor_name', $registration->supervisor_name) }}" placeholder="Ketik atau pilih dari atas..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
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

                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2 font-heading active:scale-[0.98]">
                        <i class="fa-solid fa-circle-check"></i> Konfirmasi Diterima & Simpan
                    </button>
                </form>

                <!-- Form Reject -->
                <form action="{{ route('admin.verification.reject', $registration->id) }}" method="POST" enctype="multipart/form-data" class="p-6 bg-slate-800/90 border border-slate-700 rounded-2xl space-y-4">
                    @csrf
                    <span class="font-bold text-xs text-rose-400 uppercase tracking-wider block font-heading">Tolak / Ubah Ke DITOLAK</span>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Alasan Penolakan *</label>
                        <textarea name="rejection_reason" required rows="4" placeholder="Tuliskan alasan penolakan secara jelas..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-rose-500 leading-relaxed">{{ old('rejection_reason', $registration->rejection_reason) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Upload File Surat Penolakan PDF (Opsional)</label>
                        <input type="file" name="reply_letter" accept=".pdf" class="w-full text-xs text-slate-400 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:bg-slate-700 file:text-slate-200">
                    </div>

                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengubah status menjadi DITOLAK? Kuota bidang terkait akan otomatis dikembalikan.')" class="w-full py-3 bg-[#8B0000] hover:bg-[#6b0000] text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2 font-heading active:scale-[0.98]">
                        <i class="fa-solid fa-circle-xmark"></i> Konfirmasi Ditolak & Simpan
                    </button>
                </form>

                @if($registration->status == 'approved')
                <!-- Form Selesai / Completed (Hanya tampil untuk pendaftar yang sudah Diterima) -->
                <form action="{{ route('admin.verification.complete', $registration->id) }}" method="POST" class="p-6 bg-slate-800/90 border border-slate-700 rounded-2xl space-y-3 md:col-span-2">
                    @csrf
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="font-bold text-xs text-sky-400 uppercase tracking-wider block font-heading flex items-center gap-1.5">
                                <i class="fa-solid fa-flag-checkered"></i> Tandai / Ubah Ke SELESAI
                            </span>
                            <p class="text-[11px] text-slate-400 mt-1">Gunakan opsi ini apabila masa kegiatan magang/PKL telah selesai. Kuota bidang terkait akan otomatis dibebaskan.</p>
                        </div>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menandai status pengajuan ini sebagai SELESAI?')" class="px-5 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2 font-heading shrink-0 active:scale-[0.98]">
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
                inputPosition.value = selectedOption.dataset.position;
                inputPhone.value = selectedOption.dataset.phone;
            } else {
                // Jangan kosongkan jika user ingin input manual, 
                // tapi bisa dikosongkan jika ingin force reset.
            }
        }

        // Event Listeners
        if (departmentSelect) {
            departmentSelect.addEventListener('change', updateSupervisorDropdown);
            // Panggil sekali saat load jika sudah ada yang terpilih
            updateSupervisorDropdown();
        }

        if (supervisorSelect) {
            supervisorSelect.addEventListener('change', fillSupervisorData);
        }
    });
</script>
@endpush
