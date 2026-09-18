@extends('layouts.admin')

@section('title', 'Integrasi REST API — Admin Diskominfo')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#014495] inline-flex items-center gap-1 font-heading transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-semibold text-slate-500">Integrasi Sistem</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Integrasi REST API</h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-full">
                    <i class="fa-solid fa-circle-nodes text-[10px]"></i> RESTful API v1
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Fasilitas bagi sistem eksternal (Satu Data Garut, Bakesbangpol, Bappeda) untuk menarik data kuota dan pendaftar secara otomatis.</p>
        </div>
    </div>

    <!-- Layout Grid: Form Generate Key + List Key -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Form Generate API Key Baru -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-md space-y-5">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-[#014495] text-white flex items-center justify-center text-sm shadow-sm">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h2 class="font-heading text-base font-bold text-slate-900">Generate API Key Baru</h2>
                    <p class="text-xs text-slate-500">Buat token otorisasi untuk sistem pemanggil</p>
                </div>
            </div>

            <form action="{{ route('admin.api.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 font-heading">
                        Nama Aplikasi / Sistem Konsumen <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Portal Satu Data Kab. Garut" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] focus:outline-none transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Tuliskan nama instansi atau sistem yang akan mengonsumsi data API.</p>
                </div>
                <button type="submit" class="w-full py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs shadow-md transition-all font-heading flex items-center justify-center gap-2 active:scale-[0.98]">
                    <i class="fa-solid fa-plus"></i> Generate Token API
                </button>
            </form>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-600 space-y-2">
                <span class="font-bold text-slate-800 flex items-center gap-1.5 font-heading">
                    <i class="fa-solid fa-shield-halved text-[#0B6FBB]"></i> Keamanan API
                </span>
                <p class="text-[11px] leading-relaxed text-slate-500">
                    Setiap request dari sistem luar harus menyertakan header <code>X-API-KEY: {token}</code> atau <code>Authorization: Bearer {token}</code>. Jangan bagikan token ke pihak yang tidak berwenang.
                </p>
            </div>
        </div>

        <!-- Tabel Daftar API Key Aktif -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden text-xs">
            <div class="p-5 border-b border-slate-100 font-bold text-slate-800 flex justify-between items-center font-heading bg-slate-50/50">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-[#014495]"></i> Daftar Kunci API Aktif
                </span>
                <span class="text-xs px-2.5 py-1 bg-blue-50 text-[#014495] border border-blue-200 rounded-lg font-bold">
                    {{ $apiKeys->count() }} Kunci Terdaftar
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200 font-heading">
                        <tr>
                            <th class="p-4">Nama Sistem</th>
                            <th class="p-4">Kunci API (Token)</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Terakhir Digunakan</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($apiKeys as $key)
                            <tr class="hover:bg-slate-50/80">
                                <td class="p-4 font-bold text-slate-900 font-heading">
                                    {{ $key->name }}
                                    <span class="text-slate-400 font-normal text-[10px] block mt-0.5">Dibuat: {{ $key->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2 font-mono text-[11px] bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 w-fit">
                                        <span id="key_text_{{ $key->id }}">{{ substr($key->key, 0, 14) }}••••••••</span>
                                        <button type="button" onclick="copyToClipboard('{{ $key->key }}', this)" class="text-slate-500 hover:text-[#014495] transition-colors" title="Salin Token">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($key->is_active)
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle text-[6px]"></i> Aktif
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-100 text-rose-800 font-bold rounded-full text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle text-[6px]"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-500 text-[11px]">
                                    {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Belum pernah' }}
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.api.toggle', $key->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all {{ $key->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                                {{ $key->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        
                                        <!-- Hapus Key -->
                                        <form action="{{ route('admin.api.destroy', $key->id) }}" method="POST" onsubmit="return confirm('Hapus API Key untuk {{ $key->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-7 h-7 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-all text-xs" title="Hapus Kunci">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                    Belum ada API Key yang dibuat. Silakan generate API Key pertama Anda di form sebelah kiri.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- FORM API INTERAKTIF (API Playground / Tester) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden text-xs">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 via-blue-50/40 to-indigo-50/40">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#014495] text-white flex items-center justify-center text-base shadow-sm shrink-0">
                    <i class="fa-solid fa-terminal"></i>
                </div>
                <div>
                    <h2 class="font-heading text-lg font-bold text-slate-900">Form Uji Coba API (API Playground)</h2>
                    <p class="text-xs text-slate-500">Uji langsung respon JSON dari sistem ini sebelum diintegrasikan ke aplikasi luar.</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Konfigurasi Permintaan API -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5 font-heading">Pilih Endpoint API</label>
                        <select id="apiEndpoint" onchange="updateEndpointHelp()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] font-mono text-xs">
                            <option value="/api/v1/departments">GET /api/v1/departments (Data Kuota Bidang)</option>
                            <option value="/api/v1/registrations">GET /api/v1/registrations (Data Peserta Magang)</option>
                            <option value="/api/v1/statistics">GET /api/v1/statistics (Ringkasan Statistik)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5 font-heading">API Key (X-API-KEY)</label>
                        <select id="selectedApiKey" onchange="syncManualApiKey()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] font-mono text-xs mb-2">
                            <option value="">-- Pilih dari Kunci Terdaftar --</option>
                            @foreach($apiKeys as $k)
                                @if($k->is_active)
                                    <option value="{{ $k->key }}">{{ $k->name }} ({{ substr($k->key, 0, 12) }}...)</option>
                                @endif
                            @endforeach
                        </select>
                        <input type="text" id="manualApiKey" placeholder="Atau ketik token API secara manual..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#2F90E1] font-mono text-xs">
                    </div>

                    <!-- Parameter Tambahan (Khusus Registrations) -->
                    <div id="extraParams" class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="font-bold text-slate-700 block font-heading text-[11px] uppercase tracking-wider">Parameter Query (Opsional)</span>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] text-slate-500 block mb-0.5">Status</label>
                                <select id="paramStatus" class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-xs">
                                    <option value="">Semua</option>
                                    <option value="approved">Approved</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 block mb-0.5">Jenjang</label>
                                <select id="paramApplicantStatus" class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-xs">
                                    <option value="">Semua</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Siswa">Siswa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="executeApiTest()" id="btnTestApi" class="w-full py-3 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl text-xs shadow-md transition-all font-heading flex items-center justify-center gap-2 active:scale-[0.98]">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Request (Uji API)
                    </button>
                </div>

                <!-- Kolom Preview Respon JSON -->
                <div class="lg:col-span-2 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800 font-heading flex items-center gap-2">
                            <i class="fa-solid fa-code text-[#014495]"></i> Output Respon JSON
                        </span>
                        <div id="responseStatusBadge" class="hidden text-[11px] font-bold font-mono px-2.5 py-0.5 rounded-full"></div>
                    </div>
                    
                    <div class="relative bg-slate-900 text-slate-100 rounded-2xl p-4 font-mono text-xs overflow-x-auto min-h-[300px] max-h-[460px] border border-slate-800 shadow-inner">
                        <pre id="jsonResponse" class="whitespace-pre-wrap leading-relaxed">// Klik "Kirim Request (Uji API)" untuk melihat respon data JSON dari server...</pre>
                        
                        <button type="button" onclick="copyResponseJson()" id="btnCopyJson" class="hidden absolute top-3 right-3 px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-[10px] font-sans font-bold border border-slate-700 transition-all">
                            <i class="fa-solid fa-copy mr-1"></i> Salin JSON
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DOKUMENTASI LENGKAP ENDPOINT REST API -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-md p-6 sm:p-8 space-y-6 text-xs text-slate-700">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="font-heading text-lg font-bold text-slate-900">Petunjuk Teknis & Dokumentasi API</h2>
            <p class="text-xs text-slate-500 mt-0.5">Panduan integrasi pengembang aplikasi eksternal (cURL, Axios, Fetch)</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                <span class="font-bold text-slate-900 block font-heading">1. Base URL</span>
                <code class="block p-2 bg-white rounded-lg border border-slate-300 font-mono text-[#014495] select-all">{{ $baseUrl }}</code>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                <span class="font-bold text-slate-900 block font-heading">2. Required Headers</span>
                <div class="space-y-1 font-mono text-[11px]">
                    <div class="bg-white p-1.5 rounded border border-slate-300">X-API-KEY: porma_...</div>
                    <div class="bg-white p-1.5 rounded border border-slate-300">Accept: application/json</div>
                </div>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                <span class="font-bold text-slate-900 block font-heading">3. Format Output</span>
                <p class="text-slate-500 text-[11px] leading-relaxed">
                    Semua respon dikembalikan dalam format standard JSON UTF-8 dengan status HTTP baku (200 OK, 401 Unauthorized, 422 Unprocessable).
                </p>
            </div>
        </div>

        <!-- Contoh Perintah cURL -->
        <div class="space-y-2">
            <span class="font-bold text-slate-800 font-heading">Contoh Request cURL:</span>
            <div class="bg-slate-900 text-slate-100 p-4 rounded-2xl font-mono text-xs overflow-x-auto leading-relaxed">
curl -X GET "{{ $baseUrl }}/departments" \<br>
&nbsp;&nbsp;-H "X-API-KEY: porma_your_api_key_here" \<br>
&nbsp;&nbsp;-H "Accept: application/json"
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-500"></i>';
            setTimeout(() => btn.innerHTML = original, 2000);
        });
    }

    function syncManualApiKey() {
        const select = document.getElementById('selectedApiKey');
        const input = document.getElementById('manualApiKey');
        if (select.value) {
            input.value = select.value;
        }
    }

    function updateEndpointHelp() {
        const ep = document.getElementById('apiEndpoint').value;
        const extra = document.getElementById('extraParams');
        if (ep.includes('/registrations')) {
            extra.classList.remove('hidden');
        } else {
            extra.classList.add('hidden');
        }
    }

    function executeApiTest() {
        const endpoint = document.getElementById('apiEndpoint').value;
        const apiKey = document.getElementById('manualApiKey').value.trim();
        const jsonPre = document.getElementById('jsonResponse');
        const badge = document.getElementById('responseStatusBadge');
        const btn = document.getElementById('btnTestApi');
        const btnCopy = document.getElementById('btnCopyJson');

        let url = endpoint;
        if (endpoint.includes('/registrations')) {
            const params = new URLSearchParams();
            const status = document.getElementById('paramStatus').value;
            const applicantStatus = document.getElementById('paramApplicantStatus').value;
            if (status) params.append('status', status);
            if (applicantStatus) params.append('applicant_status', applicantStatus);
            if (params.toString()) url += '?' + params.toString();
        }

        jsonPre.innerText = "Mengirim request ke " + url + "...\nMenunggu respon server...";
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Memproses...';
        badge.classList.add('hidden');

        const startTime = performance.now();

        fetch(url, {
            headers: {
                'X-API-KEY': apiKey,
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const duration = Math.round(performance.now() - startTime);
            const status = response.status;
            let data;
            try {
                data = await response.json();
            } catch (e) {
                data = { error: 'Invalid JSON response from server' };
            }

            badge.classList.remove('hidden');
            if (response.ok) {
                badge.className = 'text-[11px] font-bold font-mono px-2.5 py-0.5 rounded-full bg-emerald-950 text-emerald-300 border border-emerald-700';
                badge.innerText = `HTTP ${status} OK (${duration}ms)`;
            } else {
                badge.className = 'text-[11px] font-bold font-mono px-2.5 py-0.5 rounded-full bg-rose-950 text-rose-300 border border-rose-700';
                badge.innerText = `HTTP ${status} Error (${duration}ms)`;
            }

            jsonPre.innerText = JSON.stringify(data, null, 2);
            btnCopy.classList.remove('hidden');
        })
        .catch(error => {
            badge.classList.remove('hidden');
            badge.className = 'text-[11px] font-bold font-mono px-2.5 py-0.5 rounded-full bg-rose-950 text-rose-300 border border-rose-700';
            badge.innerText = 'Network / CORS Error';
            jsonPre.innerText = "Error: " + error.message;
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Request (Uji API)';
        });
    }

    function copyResponseJson() {
        const text = document.getElementById('jsonResponse').innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert('Respon JSON berhasil disalin ke clipboard!');
        });
    }

    // Jalankan inisialisasi awal
    updateEndpointHelp();
</script>
@endsection
