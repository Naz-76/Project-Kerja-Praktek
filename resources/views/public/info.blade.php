@extends('layouts.app')

@section('title', 'Profil Instansi & Struktur Organisasi — Diskominfo Garut')

@section('content')
<!-- Tentang Kami Section -->
<div class="bg-gradient-to-b from-[#2F90E1]/80 via-[#2F90E1]/30 to-slate-50 min-h-screen py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Title & Toggle -->
        <div class="text-center space-y-6">
            <h1 class="font-heading text-3xl md:text-5xl font-extrabold text-[#0a192f] tracking-tight">Profil Instansi</h1>
            
            <div class="inline-flex bg-white rounded-full p-1.5 shadow-md border border-slate-200" id="tabContainer">
                <button type="button" onclick="switchTab('struktur')" id="btn-tab-struktur" class="px-6 py-2.5 rounded-full bg-[#014495] text-white font-bold text-sm transition-all shadow-sm">
                    Struktur Organisasi
                </button>
                <button type="button" onclick="switchTab('visi-misi')" id="btn-tab-visi-misi" class="px-6 py-2.5 rounded-full text-slate-700 font-bold text-sm hover:bg-slate-100 transition-all">
                    VISI & MISI
                </button>
            </div>
        </div>

        <!-- Tab 1: Struktur Organisasi Card -->
        <div id="tab-struktur" class="bg-white rounded-3xl p-4 sm:p-8 md:p-10 shadow-2xl border border-slate-100 flex flex-col items-center space-y-6 sm:space-y-8">
            
            <!-- Bagan Struktur Organisasi Container (Flexible & Responsive) -->
            <div class="w-full bg-slate-50/80 rounded-2xl border border-slate-200 p-2 sm:p-4 shadow-inner">
                <div class="space-y-2 text-center mb-4 pt-2">
                    <h3 class="font-heading font-extrabold text-lg sm:text-2xl text-[#014495] uppercase tracking-wider">BAGAN STRUKTUR ORGANISASI</h3>
                    <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-widest">DINAS KOMUNIKASI DAN INFORMATIKA KABUPATEN GARUT</p>
                </div>

                <!-- Responsive Image Container -->
                <div class="relative w-full overflow-hidden rounded-xl bg-white border border-slate-200 shadow-sm group">
                    <img src="{{ asset('images/struktur_organisasi.png') }}" 
                         alt="Bagan Struktur Organisasi Diskominfo Garut" 
                         class="w-full h-auto max-w-full rounded-xl object-contain block mx-auto transition-transform duration-300 group-hover:scale-[1.01] cursor-pointer"
                         onclick="openImageModal()">
                </div>

                <!-- Control / Zoom Hint for Mobile -->
                <div class="flex flex-wrap items-center justify-between gap-2 mt-3 px-2 text-[11px] sm:text-xs text-slate-500">
                    <span class="flex items-center gap-1.5 font-medium">
                        <i class="fa-solid fa-circle-check text-[#014495]"></i> Struktur Kepemimpinan & Koordinasi
                    </span>
                    <button type="button" onclick="openImageModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#014495] hover:bg-[#002f6c] text-white rounded-lg font-bold text-[11px] font-heading shadow-sm transition-all active:scale-[0.98]">
                        <i class="fa-solid fa-magnifying-glass-plus"></i> Lihat Ukuran Penuh
                    </button>
                </div>
            </div>

            <!-- Stats Cards Bottom -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 w-full">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-md border border-slate-100 flex items-center gap-4 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-[#2F90E1] text-white flex items-center justify-center text-xl shrink-0 shadow-md">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold font-heading">Total Bidang</p>
                        <p class="text-base sm:text-lg font-extrabold text-slate-900 font-heading">{{ count($departments) }} Bidang Utama</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-md border border-slate-100 flex items-center gap-4 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-[#00E676] text-white flex items-center justify-center text-xl shrink-0 shadow-md">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold font-heading">Status Pelayanan</p>
                        <p class="text-base sm:text-lg font-extrabold text-slate-900 font-heading">{{ count($departments) }} Bidang Aktif</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-md border border-slate-100 flex items-center gap-4 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-[#FF5252] text-white flex items-center justify-center text-xl shrink-0 shadow-md">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold font-heading">Terakhir Diperbarui</p>
                        <p class="text-base sm:text-lg font-extrabold text-slate-900 font-heading">{{ date('Y') }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tab 2: VISI & MISI Card -->
        <div id="tab-visi-misi" class="hidden bg-white rounded-3xl p-6 sm:p-10 md:p-12 shadow-2xl border border-slate-100 space-y-8 text-left">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-xs font-bold text-[#0B6FBB] uppercase tracking-wider font-heading">Pemerintah Kabupaten Garut</h3>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">Visi & Misi Diskominfo</h2>
            </div>

            <!-- Visi -->
            <div class="p-6 bg-blue-50/70 rounded-2xl border border-blue-100 space-y-2">
                <span class="px-3 py-1 bg-[#014495] text-white font-bold text-xs rounded-full inline-block font-heading">VISI</span>
                <p class="text-base sm:text-lg font-bold text-slate-800 leading-relaxed italic">
                    "Terwujudnya Garut yang Bertaqwa, Maju, dan Sejahtera melalui Transformasi Digital dan Keterbukaan Informasi Publik yang Andal."
                </p>
            </div>

            <!-- Misi -->
            <div class="space-y-4">
                <span class="px-3 py-1 bg-[#014495] text-white font-bold text-xs rounded-full inline-block font-heading">MISI</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 flex gap-3.5 items-start">
                        <div class="w-8 h-8 rounded-full bg-[#014495] text-white flex items-center justify-center font-bold text-xs shrink-0 font-heading">1</div>
                        <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">Meningkatkan infrastruktur teknologi informasi dan komunikasi yang merata dan handal.</p>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 flex gap-3.5 items-start">
                        <div class="w-8 h-8 rounded-full bg-[#014495] text-white flex items-center justify-center font-bold text-xs shrink-0 font-heading">2</div>
                        <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">Mengembangkan integrasi sistem pemerintahan berbasis elektronik (SPBE) yang terpadu.</p>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 flex gap-3.5 items-start">
                        <div class="w-8 h-8 rounded-full bg-[#014495] text-white flex items-center justify-center font-bold text-xs shrink-0 font-heading">3</div>
                        <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">Mewujudkan keterbukaan informasi publik dan tata kelola komunikasi yang transparan.</p>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 flex gap-3.5 items-start">
                        <div class="w-8 h-8 rounded-full bg-[#014495] text-white flex items-center justify-center font-bold text-xs shrink-0 font-heading">4</div>
                        <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">Memperkuat keamanan informasi, data statistik daerah, dan persandian pemerintah.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Fullscreen Lightbox for Struktur Organisasi -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6" onclick="closeImageModal()">
    <div class="relative max-w-6xl w-full max-h-[90vh] bg-white rounded-3xl p-3 sm:p-5 shadow-2xl overflow-auto space-y-3" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h4 class="font-heading font-extrabold text-sm sm:text-base text-[#014495]">Bagan Struktur Organisasi Diskominfo Garut</h4>
            <button type="button" onclick="closeImageModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="overflow-auto max-h-[75vh] rounded-2xl">
            <img src="{{ asset('images/struktur_organisasi.png') }}" alt="Bagan Struktur Organisasi Diskominfo Garut (Full)" class="w-full h-auto min-w-[700px] object-contain rounded-xl block mx-auto">
        </div>
        <p class="text-[11px] text-slate-400 text-center font-medium">Gunakan scroll/geser untuk melihat detail struktur di layar smartphone.</p>
    </div>
</div>

<script>
    function switchTab(tab) {
        const tabStruktur = document.getElementById('tab-struktur');
        const tabVisiMisi = document.getElementById('tab-visi-misi');
        const btnStruktur = document.getElementById('btn-tab-struktur');
        const btnVisiMisi = document.getElementById('btn-tab-visi-misi');

        if (tab === 'struktur') {
            tabStruktur.classList.remove('hidden');
            tabVisiMisi.classList.add('hidden');
            btnStruktur.className = 'px-6 py-2.5 rounded-full bg-[#014495] text-white font-bold text-sm transition-all shadow-sm';
            btnVisiMisi.className = 'px-6 py-2.5 rounded-full text-slate-700 font-bold text-sm hover:bg-slate-100 transition-all';
        } else {
            tabStruktur.classList.add('hidden');
            tabVisiMisi.classList.remove('hidden');
            btnVisiMisi.className = 'px-6 py-2.5 rounded-full bg-[#014495] text-white font-bold text-sm transition-all shadow-sm';
            btnStruktur.className = 'px-6 py-2.5 rounded-full text-slate-700 font-bold text-sm hover:bg-slate-100 transition-all';
        }
    }

    function openImageModal() {
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
@endsection
