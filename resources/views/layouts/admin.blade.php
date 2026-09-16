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
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex flex-col md:flex-row">

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

        // Auto reload when navigated back/forward from browser cache (BFCache)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        function isUserTyping() {
            const active = document.activeElement;
            if (active) {
                const tag = active.tagName.toLowerCase();
                if (tag === 'input' || tag === 'textarea' || tag === 'select' || active.isContentEditable) {
                    return true;
                }
            }
            // Cegah reload jika terdapat input/textarea yang sedang memiliki nilai pada form aktif
            const formInputs = document.querySelectorAll('form input:not([type="hidden"]):not([type="submit"]):not([type="button"]), form textarea');
            for (let i = 0; i < formInputs.length; i++) {
                if (formInputs[i].value && formInputs[i].value.trim() !== '') {
                    return true;
                }
            }
            return false;
        }

        // Auto reload when user switches back to this tab (hanya jika form tidak sedang diisi)
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible' && !isUserTyping()) {
                window.location.reload();
            }
        });

        // Smart Auto-Refresh: reload every 60 seconds, pause if admin is typing in a form
        (function() {
            const REFRESH_INTERVAL = 60;
            let countdown = REFRESH_INTERVAL;

            setInterval(function() {
                if (document.visibilityState === 'hidden') return;
                if (isUserTyping()) {
                    countdown = REFRESH_INTERVAL;
                    return;
                }
                countdown--;
                if (countdown <= 0) {
                    window.location.reload();
                }
            }, 1000);
        })();

        // Auto-dismiss flash notifications
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-flash]').forEach(function (el) {
                setTimeout(function () {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 500);
                }, 4000);
            });
        });
    </script>
@stack('scripts')
</body>
</html>

