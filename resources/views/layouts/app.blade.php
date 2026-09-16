<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Magang & PKL — Diskominfo Garut')</title>
    
    <!-- Google Fonts (Poppins) & Tailwind CSS CDN -->
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
                            50: '#f0f8ff',
                            100: '#e0f0fe',
                            light: '#2F90E1', // Figma Accent Light Blue
                            accent: '#0B6FBB', // Figma Brand Blue
                            primary: '#014495', // Figma Deep Navy
                            dark: '#002f6c',
                            400: '#2F90E1',
                            500: '#0B6FBB',
                            600: '#014495',
                            700: '#003a80',
                            800: '#014495',
                            900: '#002752',
                        },
                        figma: {
                            white: '#FFFFFF',
                            navy: '#014495',
                            blue: '#0B6FBB',
                            lightBlue: '#2F90E1',
                            danger: '#8B0000',
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
        .glass-header {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <header class="bg-white sticky top-0 z-50 text-slate-800 border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo Pemkab & Diskominfo -->
                <a href="{{ route('public.index') }}" class="flex items-center">
                    <img src="{{ asset('logo_navbar.png') }}" alt="Dinas Komunikasi dan Informatika Kabupaten Garut - Portal Magang/KP/PKL" class="h-10 sm:h-12 w-auto object-contain">
                </a>

                <!-- Nav Menu Desktop -->
                <nav class="hidden md:flex items-center space-x-6 text-sm font-semibold">
                    <a href="{{ route('public.index') }}" class="hover:text-brand-primary transition-colors {{ request()->routeIs('public.index') ? 'text-[#014495] font-bold' : 'text-slate-600' }}">
                        Beranda
                    </a>
                    <a href="{{ route('public.info') }}" class="hover:text-brand-primary transition-colors {{ request()->routeIs('public.info') ? 'text-[#014495] font-bold' : 'text-slate-600' }}">
                        Tentang Kami
                    </a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 bg-amber-500/15 text-amber-800 border border-amber-500/30 rounded-xl hover:bg-amber-500/25 transition-all font-semibold flex items-center gap-2">
                                <i class="fa-solid fa-user-shield text-amber-600"></i>
                                <span>Panel Admin</span>
                            </a>
                        @else
                            <a href="{{ route('applicant.dashboard') }}" class="px-3.5 py-2 bg-blue-500/15 text-[#014495] border border-blue-500/30 rounded-xl hover:bg-blue-500/25 transition-all font-semibold flex items-center gap-2">
                                <i class="fa-solid fa-gauge text-[#0B6FBB]"></i>
                                <span>Dashboard Saya</span>
                            </a>
                        @endif

                        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.profile') : route('applicant.profile') }}" class="w-10 h-10 rounded-full border-2 border-[#2F90E1] hover:border-[#014495] transition-all overflow-hidden shadow-sm flex items-center justify-center bg-slate-100" title="Kelola Profil Saya ({{ auth()->user()->name }})">
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                            </a>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-[#014495] hover:bg-[#002f6c] text-white font-bold rounded-xl shadow-sm transition-all text-sm">
                            Masuk
                        </a>
                    @endauth
                </nav>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center gap-3 md:hidden">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.profile') : route('applicant.profile') }}" class="w-9 h-9 rounded-full border-2 border-[#2F90E1] overflow-hidden shadow-sm flex items-center justify-center bg-slate-100">
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                        </a>
                    @endauth
                    <button type="button" onclick="openMobileMenu()" class="w-11 h-11 rounded-xl text-slate-800 hover:text-[#014495] hover:bg-blue-50 focus:outline-none transition-all flex items-center justify-center border border-slate-200 shadow-sm" aria-label="Buka Menu Navigasi">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar Drawer (Off-Canvas Menu) -->
    <div id="mobileMenuBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeMobileMenu()"></div>

    <aside id="mobileMenuDrawer" class="fixed top-0 right-0 bottom-0 w-[290px] sm:w-[320px] bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between">
        <!-- Top Drawer Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('logo_navbar.png') }}" alt="Logo" class="h-8 w-auto object-contain">
            </div>
            <button type="button" onclick="closeMobileMenu()" class="w-9 h-9 rounded-xl bg-white hover:bg-rose-50 hover:text-rose-600 text-slate-600 flex items-center justify-center transition-colors border border-slate-200 shadow-sm" aria-label="Tutup Menu">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Menu Navigation Items -->
        <div class="p-5 space-y-2 flex-grow overflow-y-auto">
            @auth
                <!-- User Profile Summary in Mobile Drawer -->
                <div class="p-4 mb-4 bg-blue-50/60 rounded-2xl border border-blue-100 flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-[#2F90E1] shadow-sm">
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-slate-900 truncate font-heading">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</div>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[9px] font-bold {{ auth()->user()->isAdmin() ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-[#014495]' }}">
                            {{ auth()->user()->isAdmin() ? 'Admin Kepegawaian' : 'Pendaftar' }}
                        </span>
                    </div>
                </div>
            @endauth

            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">Navigasi Utama</div>

            <a href="{{ route('public.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('public.index') ? 'bg-[#014495] text-white font-bold shadow-sm' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-house w-5 text-center {{ request()->routeIs('public.index') ? 'text-white' : 'text-[#0B6FBB]' }}"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('public.info') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('public.info') ? 'bg-[#014495] text-white font-bold shadow-sm' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-circle-info w-5 text-center {{ request()->routeIs('public.info') ? 'text-white' : 'text-[#0B6FBB]' }}"></i>
                <span>Tentang Kami</span>
            </a>

            @auth
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 pt-3 mb-2">Akses Menu</div>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm bg-amber-50 text-amber-900 hover:bg-amber-100 transition-all border border-amber-200">
                        <i class="fa-solid fa-gauge-high w-5 text-center text-amber-600"></i>
                        <span>Panel Admin</span>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 transition-all">
                        <i class="fa-solid fa-user-gear w-5 text-center text-slate-500"></i>
                        <span>Profil Admin</span>
                    </a>
                @else
                    <a href="{{ route('applicant.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm bg-blue-50 text-[#014495] hover:bg-blue-100 transition-all border border-blue-100">
                        <i class="fa-solid fa-gauge w-5 text-center text-[#0B6FBB]"></i>
                        <span>Dashboard Saya</span>
                    </a>
                    <a href="{{ route('applicant.registration.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 transition-all">
                        <i class="fa-solid fa-file-circle-plus w-5 text-center text-slate-500"></i>
                        <span>Pengajuan Baru</span>
                    </a>
                    <a href="{{ route('applicant.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 transition-all">
                        <i class="fa-solid fa-user-gear w-5 text-center text-slate-500"></i>
                        <span>Profil Saya</span>
                    </a>
                @endif
            @endauth
        </div>

        <!-- Drawer Footer -->
        <div class="p-5 border-t border-slate-100 bg-slate-50">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 bg-[#8B0000] hover:bg-[#6b0000] text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar Akun (Logout)</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full py-3.5 px-4 bg-[#014495] hover:bg-[#002f6c] text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk ke Sistem</span>
                </a>
            @endauth
        </div>
    </aside>

    <!-- Floating Toast Notification Overlay -->
    <div class="fixed top-24 right-4 sm:right-6 z-50 max-w-md w-full pointer-events-none space-y-3 px-2 sm:px-0">
        @if(session('success'))
            <div data-flash class="pointer-events-auto p-4 bg-emerald-50/95 backdrop-blur-md border border-emerald-200 rounded-2xl text-emerald-900 flex items-center justify-between shadow-2xl shadow-emerald-950/10 transition-all duration-300 transform translate-y-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </div>
                    <span class="font-medium text-xs sm:text-sm leading-snug">{{ session('success') }}</span>
                </div>
                <button onclick="this.closest('[data-flash]').remove()" class="text-emerald-500 hover:text-emerald-800 p-1.5 rounded-lg hover:bg-emerald-100/60 transition-colors shrink-0 ml-2" aria-label="Tutup"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div data-flash class="pointer-events-auto p-4 bg-rose-50/95 backdrop-blur-md border border-rose-200 rounded-2xl text-rose-900 flex items-center justify-between shadow-2xl shadow-rose-950/10 transition-all duration-300 transform translate-y-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    </div>
                    <span class="font-medium text-xs sm:text-sm leading-snug">{{ session('error') }}</span>
                </div>
                <button onclick="this.closest('[data-flash]').remove()" class="text-rose-500 hover:text-rose-800 p-1.5 rounded-lg hover:bg-rose-100/60 transition-colors shrink-0 ml-2" aria-label="Tutup"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
        @endif

        @if(session('info'))
            <div data-flash class="pointer-events-auto p-4 bg-sky-50/95 backdrop-blur-md border border-sky-200 rounded-2xl text-sky-900 flex items-center justify-between shadow-2xl shadow-sky-950/10 transition-all duration-300 transform translate-y-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-sky-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-circle-info text-base"></i>
                    </div>
                    <span class="font-medium text-xs sm:text-sm leading-snug">{{ session('info') }}</span>
                </div>
                <button onclick="this.closest('[data-flash]').remove()" class="text-sky-500 hover:text-sky-800 p-1.5 rounded-lg hover:bg-sky-100/60 transition-colors shrink-0 ml-2" aria-label="Tutup"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow pb-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white text-slate-600 border-t border-slate-200 text-sm py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left side -->
                <div class="space-y-4 max-w-sm">
                    <img src="{{ asset('logo_footer.png') }}" alt="Logo Diskominfo" class="h-10 w-auto object-contain">
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Dinas Komunikasi dan Informatika Kabupaten Garut. Mendorong transformasi digital untuk Garut yang lebih maju.
                    </p>
                </div>

                <!-- Right side (Contact) -->
                <div class="flex flex-col md:items-end">
                    <div class="space-y-3">
                        <h4 class="font-bold text-slate-800 text-xs uppercase mb-2">Kontak</h4>
                        <div class="flex items-start gap-2 text-xs">
                            <i class="fa-solid fa-location-dot text-[#00E676] mt-0.5"></i>
                            <span>Jl. Pembangunan No. 181, Tarogong Kidul, Garut</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <i class="fa-solid fa-envelope text-[#FF5252]"></i>
                            <span>diskominfo@garutkab.go.id</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <i class="fa-solid fa-phone text-[#2979FF]"></i>
                            <span>(0262) 232822</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Auto-dismiss flash notifications & Mobile Menu Script --}}
    <script>
        function openMobileMenu() {
            const backdrop = document.getElementById('mobileMenuBackdrop');
            const drawer = document.getElementById('mobileMenuDrawer');
            if (backdrop && drawer) {
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');
                drawer.classList.remove('translate-x-full');
                drawer.classList.add('translate-x-0');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileMenu() {
            const backdrop = document.getElementById('mobileMenuBackdrop');
            const drawer = document.getElementById('mobileMenuDrawer');
            if (backdrop && drawer) {
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                drawer.classList.remove('translate-x-0');
                drawer.classList.add('translate-x-full');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });

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

        // Smart Auto-Refresh: reload every 60 seconds, pause if user is typing
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

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-flash]').forEach(function (el) {
                setTimeout(function () {
                    el.style.transition = 'all 0.4s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-12px) scale(0.95)';
                    setTimeout(function () { el.remove(); }, 400);
                }, 4000);
            });
        });
    </script>

</body>
</html>
