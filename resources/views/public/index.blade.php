@extends('layouts.app')

@section('title', 'Beranda — Portal Magang/KP/PKL Diskominfo Garut')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-b from-[#2F90E1]/80 via-[#2F90E1]/30 to-slate-50 overflow-hidden pt-12 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <!-- Hero Left -->
            <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-[#0a192f] leading-tight">
                    Bangun Karier Digitalmu di Diskominfo Kabupaten Garut
                </h1>
                <p class="text-slate-800 text-base sm:text-lg font-medium leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Daftar PKL & Magang bidang TIK makin mudah. Pantau kuota real-time, pilih divisi sesuai skill-mu, dan mulai langkah karier digitalmu!
                </p>
                <div class="pt-2 flex justify-center lg:justify-start">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-8 py-3.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-3 text-base">
                                <i class="fa-solid fa-gauge-high text-lg"></i>
                                <span>Panel Admin</span>
                            </a>
                        @else
                            <a href="{{ route('applicant.registration.create') }}" class="px-8 py-3.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-3 text-base">
                                <i class="fa-solid fa-user-plus text-lg"></i>
                                <span>Daftar</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-3.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-3 text-base">
                            <i class="fa-solid fa-user-plus text-lg"></i>
                            <span>Daftar</span>
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Hero Right (Quota Card) -->
            <div class="lg:col-span-6">
                <div class="p-6 sm:p-8 rounded-3xl border border-slate-100/90 shadow-2xl bg-white text-slate-800 space-y-6 hover:shadow-[0_25px_60px_-15px_rgba(1,68,149,0.18)] hover:-translate-y-1 transition-all duration-300 ease-out">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <span class="font-heading font-extrabold text-sm sm:text-base text-slate-900">Informasi Real Time Ketersediaan Kuota</span>
                        <div class="text-[11px] text-slate-600 font-medium text-left sm:text-right space-y-0.5">
                            @if($earliestDate && $latestDate)
                                <div>Tanggal daftar termuda {{ \Carbon\Carbon::parse($earliestDate)->translatedFormat('j F Y') }}</div>
                                <div>Tanggal selesai tertua {{ \Carbon\Carbon::parse($latestDate)->translatedFormat('j F Y') }}</div>
                            @else
                                <div>Tanggal daftar termuda 5 Agustus 2026</div>
                                <div>Tanggal selesai tertua 25 Oktober 2026</div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-5">
                        @foreach($departments as $dept)
                            @php
                                $quota = $dept->slotQuotas->first();
                                $total = $quota ? $quota->quota_total : 15;
                                $used = $quota ? $quota->quota_used : 0;
                                $percent = $total > 0 ? min(100, round(($used / $total) * 100)) : 0;
                            @endphp
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 mb-2">{{ $dept->name }}</h4>
                                <div class="w-full bg-[#0B6FBB]/20 h-4 rounded-full overflow-hidden p-0.5">
                                    <div class="bg-[#0B6FBB] h-full rounded-full transition-all duration-500" style="width: {{ max(8, $percent) }}%"></div>
                                </div>
                                <div class="flex justify-between items-center text-xs text-slate-700 mt-1.5 font-medium">
                                    <span>Jumlah Terdaftar {{ $used }}</span>
                                    <span>Jumlah Kuota {{ $total }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Departments Cards Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($departments as $dept)
        <div class="bg-white rounded-3xl p-7 sm:p-8 shadow-md border border-slate-100/90 hover:shadow-2xl hover:shadow-blue-900/10 hover:border-blue-300 hover:-translate-y-2 transition-all duration-300 ease-out flex flex-col justify-between group cursor-default">
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#014495] group-hover:bg-[#014495] group-hover:text-white group-hover:scale-110 group-hover:rotate-3 flex items-center justify-center text-xl transition-all duration-300 shrink-0 shadow-sm">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg font-heading group-hover:text-[#014495] transition-colors duration-300">{{ $dept->name }}</h3>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed text-justify">
                    {{ $dept->description ?: 'Fokus pada perancangan, pengembangan, integrasi, dan pemeliharaan aplikasi layanan publik serta sistem informasi pemerintahan berbasis web dan mobile di lingkungan Pemerintah Kabupaten Garut. Bidang ini sangat ideal bagi mahasiswa yang ingin mengasah keahlian di bidang software engineering, web development, perancangan database, hingga UI/UX design.' }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Tata Cara Pendaftaran Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 uppercase tracking-wide">TATA CARA PENDAFTARAN</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <!-- Step 1 -->
        <div class="p-6 bg-[#014495] hover:bg-[#0B6FBB] rounded-2xl text-center space-y-4 shadow-lg hover:shadow-2xl hover:shadow-[#014495]/30 hover:-translate-y-2.5 transition-all duration-300 ease-out relative overflow-hidden flex flex-col justify-between min-h-[220px] group cursor-default">
            <div class="w-11 h-11 bg-[#2F90E1] text-white group-hover:bg-white group-hover:text-[#014495] group-hover:scale-110 rounded-xl flex items-center justify-center text-xl font-bold font-heading shadow-md transition-all duration-300 mx-auto">1</div>
            <div class="my-auto">
                <h3 class="font-bold text-white text-base group-hover:tracking-wide transition-all">Pilih Program</h3>
                <p class="text-[12px] text-blue-100 mt-2 leading-snug">Pilih program PKL/KP/Magang, preferensi bidang & tanggal.</p>
            </div>
            <div></div>
        </div>

        <!-- Step 2 -->
        <div class="p-6 bg-[#014495] hover:bg-[#0B6FBB] rounded-2xl text-center space-y-4 shadow-lg hover:shadow-2xl hover:shadow-[#014495]/30 hover:-translate-y-2.5 transition-all duration-300 ease-out relative overflow-hidden flex flex-col justify-between min-h-[220px] group cursor-default">
            <div class="w-11 h-11 bg-[#2F90E1] text-white group-hover:bg-white group-hover:text-[#014495] group-hover:scale-110 rounded-xl flex items-center justify-center text-xl font-bold font-heading shadow-md transition-all duration-300 mx-auto">2</div>
            <div class="my-auto">
                <h3 class="font-bold text-white text-base group-hover:tracking-wide transition-all">Data Peserta</h3>
                <p class="text-[12px] text-blue-100 mt-2 leading-snug">Isi data identitas ketua & anggota tim pendaftar.</p>
            </div>
            <div></div>
        </div>

        <!-- Step 3 -->
        <div class="p-6 bg-[#014495] hover:bg-[#0B6FBB] rounded-2xl text-center space-y-4 shadow-lg hover:shadow-2xl hover:shadow-[#014495]/30 hover:-translate-y-2.5 transition-all duration-300 ease-out relative overflow-hidden flex flex-col justify-between min-h-[220px] group cursor-default">
            <div class="w-11 h-11 bg-[#2F90E1] text-white group-hover:bg-white group-hover:text-[#014495] group-hover:scale-110 rounded-xl flex items-center justify-center text-xl font-bold font-heading shadow-md transition-all duration-300 mx-auto">3</div>
            <div class="my-auto">
                <h3 class="font-bold text-white text-base group-hover:tracking-wide transition-all">Data Institusi</h3>
                <p class="text-[12px] text-blue-100 mt-2 leading-snug">Isi profil sekolah, kampus, dan kontak narahubung.</p>
            </div>
            <div></div>
        </div>

        <!-- Step 4 -->
        <div class="p-6 bg-[#014495] hover:bg-[#0B6FBB] rounded-2xl text-center space-y-4 shadow-lg hover:shadow-2xl hover:shadow-[#014495]/30 hover:-translate-y-2.5 transition-all duration-300 ease-out relative overflow-hidden flex flex-col justify-between min-h-[220px] group cursor-default">
            <div class="w-11 h-11 bg-[#2F90E1] text-white group-hover:bg-white group-hover:text-[#014495] group-hover:scale-110 rounded-xl flex items-center justify-center text-xl font-bold font-heading shadow-md transition-all duration-300 mx-auto">4</div>
            <div class="my-auto">
                <h3 class="font-bold text-white text-base group-hover:tracking-wide transition-all">Upload Dokumen</h3>
                <p class="text-[12px] text-blue-100 mt-2 leading-snug">Unggah surat pengantar resmi, proposal & berkas pendukung.</p>
            </div>
            <div></div>
        </div>

        <!-- Step 5 -->
        <div class="p-6 bg-[#014495] hover:bg-[#0B6FBB] rounded-2xl text-center space-y-4 shadow-lg hover:shadow-2xl hover:shadow-[#014495]/30 hover:-translate-y-2.5 transition-all duration-300 ease-out relative overflow-hidden flex flex-col justify-between min-h-[220px] group cursor-default">
            <div class="w-11 h-11 bg-[#2F90E1] text-white group-hover:bg-white group-hover:text-[#014495] group-hover:scale-110 rounded-xl flex items-center justify-center text-xl font-bold font-heading shadow-md transition-all duration-300 mx-auto">5</div>
            <div class="my-auto">
                <h3 class="font-bold text-white text-base group-hover:tracking-wide transition-all">Review & Kirim</h3>
                <p class="text-[12px] text-blue-100 mt-2 leading-snug">Cek kembali rincian data lalu kirim untuk verifikasi admin.</p>
            </div>
            <div></div>
        </div>
    </div>
</div>

<!-- Persyaratan Berkas Dokumen Resmi Section -->
<div class="bg-gradient-to-b from-white via-[#2F90E1] to-[#014495] py-16 shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 flex justify-center items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#00E676] text-white flex items-center justify-center text-xl shadow-md">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h2 class="font-heading text-2xl md:text-3xl font-extrabold text-white tracking-wide drop-shadow-md">
                Persyaratan Berkas Dokumen Resmi
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-7 shadow-md border border-white/50 flex gap-4 items-start hover:shadow-2xl hover:shadow-blue-950/20 hover:-translate-y-2 hover:border-white transition-all duration-300 ease-out group cursor-default">
                <div class="w-11 h-11 bg-[#2F90E1] group-hover:bg-[#014495] group-hover:scale-110 group-hover:rotate-6 text-white rounded-xl flex items-center justify-center font-bold text-xl shrink-0 shadow-sm transition-all duration-300">1</div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm mb-1.5 font-heading group-hover:text-[#014495] transition-colors">Surat Pengantar Resmi (Wajib)</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Dikeluarkan oleh Kepala Sekolah / Dekan / Kaprodi yang ditujukan kepada Kepala Diskominfo Kabupaten Garut (PDF, max 5MB).</p>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-7 shadow-md border border-white/50 flex gap-4 items-start hover:shadow-2xl hover:shadow-blue-950/20 hover:-translate-y-2 hover:border-white transition-all duration-300 ease-out group cursor-default">
                <div class="w-11 h-11 bg-[#2F90E1] group-hover:bg-[#014495] group-hover:scale-110 group-hover:rotate-6 text-white rounded-xl flex items-center justify-center font-bold text-xl shrink-0 shadow-sm transition-all duration-300">2</div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm mb-1.5 font-heading group-hover:text-[#014495] transition-colors">Proposal Magang / KP (Opsional)</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Memuat gambaran latar belakang, rencana topik bahasan, dan durasi pelaksanaan (PDF, max 10MB).</p>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-7 shadow-md border border-white/50 flex gap-4 items-start hover:shadow-2xl hover:shadow-blue-950/20 hover:-translate-y-2 hover:border-white transition-all duration-300 ease-out group cursor-default">
                <div class="w-11 h-11 bg-[#2F90E1] group-hover:bg-[#014495] group-hover:scale-110 group-hover:rotate-6 text-white rounded-xl flex items-center justify-center font-bold text-xl shrink-0 shadow-sm transition-all duration-300">3</div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm mb-1.5 font-heading group-hover:text-[#014495] transition-colors">Curriculum Vitae (CV) Peserta</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Memuat biodata, riwayat pendidikan, serta keahlian utama masing-masing peserta (PDF, max 5MB).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
