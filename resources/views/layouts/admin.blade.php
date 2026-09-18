<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin — Diskominfo Garut')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            light: '#2F90E1',
                            accent: '#0B6FBB',
                            primary: '#014495',
                            500: '#0B6FBB',
                            600: '#014495',
                            700: '#003a80',
                            900: '#002752',
                        },
                        garut: {
                            gold: '#fbbf24',
                            green: '#059669',
                            dark: '#0f172a'
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-active {
            background: linear-gradient(90deg, rgba(2, 132, 199, 0.25) 0%, rgba(2, 132, 199, 0.05) 100%);
            border-left: 4px solid #38bdf8;
            color: #ffffff;
            font-weight: 700;
        }

        /* Custom Horizontal Scrollbar */
        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 #f1f5f9;
        }
        .overflow-x-auto::-webkit-scrollbar {
            height: 7px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 9999px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 9999px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Drag-to-Scroll Cursor */
        .overflow-x-auto.cursor-grab {
            cursor: grab;
        }
        .overflow-x-auto.cursor-grabbing {
            cursor: grabbing;
            user-select: none;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Realtime Data Notification Pill (Opsi 3: Tanpa Hard Reload) -->
    <div id="admin-new-data-pill" class="fixed top-4 right-4 z-[9999] hidden transition-all duration-300 transform translate-y-[-20px] opacity-0 max-w-sm sm:max-w-md">
        <div class="bg-slate-900/95 text-white border border-blue-400/40 shadow-2xl backdrop-blur-md px-4 py-3 rounded-2xl flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-base shrink-0 animate-bounce">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-heading font-bold text-xs text-white truncate" id="admin-pill-text">Ada pengajuan pendaftaran baru masuk!</p>
                <p class="text-[10px] text-slate-400">Pembaruan data tersedia di sistem</p>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="button" onclick="window.location.reload()" class="px-3 py-1.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1 font-heading active:scale-[0.98]">
                    <i class="fa-solid fa-rotate-right text-[10px]"></i>
                    <span>Muat Ulang</span>
                </button>
                <button type="button" onclick="dismissNewDataPill()" class="w-7 h-7 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition-colors text-xs" title="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Top Header Bar (With Hamburger Menu Icon) -->
    <header class="md:hidden bg-slate-900 text-white p-4 flex items-center justify-between sticky top-0 z-50 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-500 text-slate-950 rounded-lg flex items-center justify-center font-bold">
                <i class="fa-solid fa-shield-halved text-base"></i>
            </div>
            <div>
                <span class="font-heading font-extrabold text-sm block tracking-tight">Admin Portal Magang & PKL</span>
                <span class="text-[10px] text-amber-400">Diskominfo Garut</span>
            </div>
        </div>
        <button id="hamburger-btn" onclick="toggleSidebar()" class="p-2 text-slate-300 hover:text-white focus:outline-none">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </header>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-slate-950/60 z-40 md:hidden backdrop-blur-sm"></div>

    <!-- Left Sidebar Navigation for Admin (Sticky Layout) -->
    <aside id="sidebar-menu" class="hidden md:flex flex-col w-72 shrink-0 bg-slate-900 text-slate-300 h-screen sticky top-0 overflow-y-auto border-r border-slate-800 fixed md:sticky inset-y-0 left-0 z-50 transition-all duration-300">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-slate-800/80 flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-amber-400 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20 text-slate-950 font-bold text-lg">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <div class="font-heading font-extrabold text-base text-white tracking-tight">
                    Admin <span class="text-amber-400">Panel</span>
                </div>
                <p class="text-[11px] text-slate-400">Diskominfo Kab. Garut</p>
            </div>
        </div>

        <!-- Admin Profile Summary Box -->
        <div class="p-4 mx-4 my-4 bg-slate-800/60 rounded-xl border border-slate-700/50 flex items-center gap-3">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar Admin" class="w-10 h-10 rounded-full object-cover border-2 border-amber-400">
            <div class="overflow-hidden">
                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-amber-400 font-semibold">Admin Kepegawaian</p>
            </div>
        </div>

        <!-- Sidebar Links -->
        <nav class="flex-grow px-3 py-2 space-y-1.5 text-xs font-medium">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-800 text-slate-300 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active text-white' : '' }}">
                <i class="fa-solid fa-gauge-high text-sm text-sky-400 w-5"></i>
                <span>Dashboard Admin</span>
            </a>

            <a href="{{ route('admin.verification.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-800 text-slate-300 {{ request()->routeIs('admin.verification.*') ? 'sidebar-active text-white' : '' }}">
                <i class="fa-solid fa-list-check text-sm text-amber-400 w-5"></i>
                <span>Verifikasi & Placement</span>
            </a>

            <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-800 text-slate-300 {{ request()->routeIs('admin.departments.*') ? 'sidebar-active text-white' : '' }}">
                <i class="fa-solid fa-layer-group text-sm text-emerald-400 w-5"></i>
                <span>Kelola Bidang & Kuota</span>
            </a>


            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-800 text-slate-300 {{ request()->routeIs('admin.reports.*') ? 'sidebar-active text-white' : '' }}">
                <i class="fa-solid fa-chart-column text-sm text-purple-400 w-5"></i>
                <span>Laporan & Rekapitulasi</span>
            </a>

            <a href="{{ route('admin.api.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-800 text-slate-300 {{ request()->routeIs('admin.api.*') ? 'sidebar-active text-white' : '' }}">
                <i class="fa-solid fa-code text-sm text-cyan-400 w-5"></i>
                <span>Integrasi REST API</span>
            </a>

            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-800 text-slate-300 {{ request()->routeIs('admin.profile') ? 'sidebar-active text-white' : '' }}">
                <i class="fa-solid fa-id-badge text-sm text-teal-400 w-5"></i>
                <span>Profil Saya (Admin)</span>
            </a>
        </nav>

        <!-- Sidebar Footer & Logout -->
        <div class="p-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-xl text-xs font-bold transition-all">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Admin
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Right Content Area -->
    <div class="flex-grow flex flex-col min-h-screen overflow-x-hidden">
        <!-- Floating Flash Alert Toast Overlay -->
        <div class="fixed top-6 right-6 z-50 max-w-md w-full pointer-events-none space-y-3 px-2 sm:px-0">
            @if(session('success'))
                <div data-flash class="pointer-events-auto p-4 bg-emerald-900/95 backdrop-blur-md border border-emerald-500/30 rounded-2xl text-emerald-100 flex items-center justify-between shadow-2xl shadow-emerald-950/40 transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-solid fa-circle-check text-base"></i>
                        </div>
                        <span class="font-medium text-xs sm:text-sm leading-snug">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.closest('[data-flash]').remove()" class="text-emerald-400 hover:text-white p-1.5 rounded-lg hover:bg-emerald-800/60 transition-colors shrink-0 ml-2" aria-label="Tutup"><i class="fa-solid fa-xmark text-sm"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div data-flash class="pointer-events-auto p-4 bg-rose-900/95 backdrop-blur-md border border-rose-500/30 rounded-2xl text-rose-100 flex items-center justify-between shadow-2xl shadow-rose-950/40 transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        </div>
                        <span class="font-medium text-xs sm:text-sm leading-snug">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.closest('[data-flash]').remove()" class="text-rose-400 hover:text-white p-1.5 rounded-lg hover:bg-rose-800/60 transition-colors shrink-0 ml-2" aria-label="Tutup"><i class="fa-solid fa-xmark text-sm"></i></button>
                </div>
            @endif

            @if(session('warning'))
                <div data-flash class="pointer-events-auto p-4 bg-amber-900/95 backdrop-blur-md border border-amber-500/30 rounded-2xl text-amber-100 flex items-center justify-between shadow-2xl shadow-amber-950/40 transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        </div>
                        <span class="font-medium text-xs sm:text-sm leading-snug">{{ session('warning') }}</span>
                    </div>
                    <button onclick="this.closest('[data-flash]').remove()" class="text-amber-400 hover:text-white p-1.5 rounded-lg hover:bg-amber-800/60 transition-colors shrink-0 ml-2" aria-label="Tutup"><i class="fa-solid fa-xmark text-sm"></i></button>
                </div>
            @endif
        </div>

        <main class="flex-grow p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-slate-200 text-slate-500 text-xs py-4 px-6 text-center md:text-left flex flex-col sm:flex-row justify-between items-center gap-2">
            <span>&copy; 2026 Panel Kepegawaian Diskominfo Kabupaten Garut.</span>
            <span>PORMA — Portal Magang & PKL v2.0</span>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
            }
        }

        // =========================================================================
        // REALTIME UPDATE CHECKER (OPSI 3: SILENT BACKGROUND POLLING TANPA RELOAD PAKSA)
        // =========================================================================
        let lastCheckedId = 0;
        let lastPendingCount = -1;

        function dismissNewDataPill() {
            const pill = document.getElementById('admin-new-data-pill');
            if (pill) {
                pill.classList.add('translate-y-[-20px]', 'opacity-0');
                setTimeout(() => pill.classList.add('hidden'), 300);
            }
        }

        function showNewDataPill(message) {
            const pill = document.getElementById('admin-new-data-pill');
            const text = document.getElementById('admin-pill-text');
            if (pill && text) {
                text.textContent = message || 'Ada pengajuan pendaftaran baru masuk!';
                pill.classList.remove('hidden');
                requestAnimationFrame(() => {
                    pill.classList.remove('translate-y-[-20px]', 'opacity-0');
                });
            }
        }

        async function checkAdminUpdates() {
            try {
                const url = new URL("{{ route('admin.check-updates') }}", window.location.origin);
                if (lastCheckedId > 0) url.searchParams.set('last_id', lastCheckedId);
                if (lastPendingCount >= 0) url.searchParams.set('pending_count', lastPendingCount);

                const response = await fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Inisialisasi awal saat pertama kali dijalankan di halaman
                    if (lastCheckedId === 0) {
                        lastCheckedId = data.latest_id;
                        lastPendingCount = data.pending_count;
                        return;
                    }

                    if (data.has_new) {
                        const countText = data.new_count > 1 ? `${data.new_count} pengajuan baru masuk!` : 'Pengajuan baru masuk!';
                        showNewDataPill(`Ada ${countText}`);
                        lastCheckedId = data.latest_id;
                        lastPendingCount = data.pending_count;
                    }
                }
            } catch (err) {
                // Silent fail: tidak mengganggu admin jika koneksi jaringan terputus sesaat
            }
        }

        // Pengecekan hening setiap 45 detik (tanpa memuat ulang halaman secara paksa)
        setInterval(checkAdminUpdates, 45000);
        // Jalankan pengecekan inisialisasi awal setelah DOM siap
        document.addEventListener('DOMContentLoaded', checkAdminUpdates);

        // Auto-dismiss flash notifications
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-flash]').forEach(function (el) {
                setTimeout(function () {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 500);
                }, 4000);
            });

            // Global Drag-to-Scroll for all horizontally scrollable tables
            const scrollContainers = document.querySelectorAll('.overflow-x-auto');
            scrollContainers.forEach(container => {
                const updateGrabState = () => {
                    if (container.scrollWidth > container.clientWidth) {
                        container.classList.add('cursor-grab');
                    } else {
                        container.classList.remove('cursor-grab');
                    }
                };
                updateGrabState();
                window.addEventListener('resize', updateGrabState);

                let isDown = false;
                let startX;
                let scrollLeft;

                container.addEventListener('mousedown', (e) => {
                    if (e.target.closest('a, button, input, select, textarea, form')) return;
                    isDown = true;
                    container.classList.add('cursor-grabbing');
                    container.classList.remove('cursor-grab');
                    startX = e.pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                });

                container.addEventListener('mouseleave', () => {
                    isDown = false;
                    container.classList.remove('cursor-grabbing');
                    updateGrabState();
                });

                container.addEventListener('mouseup', () => {
                    isDown = false;
                    container.classList.remove('cursor-grabbing');
                    updateGrabState();
                });

                container.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    container.scrollLeft = scrollLeft - walk;
                });
            });
        });

        // =========================================================================
        // GLOBAL CUSTOM CONFIRMATION MODAL (DESIGN GUIDELINES COMPLIANT)
        // =========================================================================
        let currentConfirmCallback = null;

        function openCustomConfirm({ title, message, icon, iconColor, iconBg, iconBorder, btnText, btnColor, onConfirm }) {
            const modal = document.getElementById('customConfirmModal');
            const card = document.getElementById('customConfirmCard');
            const titleEl = document.getElementById('customConfirmTitle');
            const messageEl = document.getElementById('customConfirmMessage');
            const iconWrapper = document.getElementById('customConfirmIconWrapper');
            const iconEl = document.getElementById('customConfirmIcon');
            const submitBtn = document.getElementById('customConfirmSubmitBtn');

            if (!modal) return;

            titleEl.textContent = title || 'Konfirmasi Tindakan';
            messageEl.innerHTML = message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            
            iconEl.className = icon || 'fa-solid fa-triangle-exclamation';
            iconWrapper.className = `w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-2xl shadow-lg border ${iconBg || 'bg-amber-500/20'} ${iconColor || 'text-amber-400'} ${iconBorder || 'border-amber-500/30'}`;

            submitBtn.textContent = btnText || 'Ya, Lanjutkan';
            submitBtn.className = `px-4 py-2.5 text-white font-bold text-xs rounded-xl shadow-md transition-all font-heading active:scale-[0.98] ${btnColor || 'bg-[#014495] hover:bg-[#002f6c]'}`;

            currentConfirmCallback = onConfirm;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCustomConfirm() {
            const modal = document.getElementById('customConfirmModal');
            const card = document.getElementById('customConfirmCard');
            if (!modal) return;

            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                currentConfirmCallback = null;
            }, 200);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const submitBtn = document.getElementById('customConfirmSubmitBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', () => {
                    if (typeof currentConfirmCallback === 'function') {
                        currentConfirmCallback();
                    }
                    closeCustomConfirm();
                });
            }
        });
    </script>

    <!-- Global Custom Confirmation Modal (Design Guidelines Compliant) -->
    <div id="customConfirmModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4">
        <!-- Backdrop Blur -->
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" onclick="closeCustomConfirm()"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-slate-900 border border-slate-700/80 rounded-3xl shadow-2xl max-w-md w-full p-6 text-center space-y-5 transform transition-all duration-200 scale-95 opacity-0 z-10" id="customConfirmCard">
            <div id="customConfirmIconWrapper" class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-2xl shadow-lg border">
                <i id="customConfirmIcon" class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <div class="space-y-1.5">
                <h3 id="customConfirmTitle" class="font-heading font-extrabold text-lg sm:text-xl text-white">Konfirmasi Tindakan</h3>
                <div id="customConfirmMessage" class="text-xs text-slate-300 leading-relaxed max-w-xs mx-auto">Apakah Anda yakin ingin melanjutkan tindakan ini?</div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" onclick="closeCustomConfirm()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs rounded-xl border border-slate-700 transition-all font-heading">
                    Batal
                </button>
                <button type="button" id="customConfirmSubmitBtn" class="px-4 py-2.5 text-white font-bold text-xs rounded-xl shadow-md transition-all font-heading active:scale-[0.98]">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
@stack('scripts')
</body>
</html>

