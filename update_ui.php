<?php
$appLayoutPath = 'c:/laragon/www/sistem-pendaftaran-diskominfo-V2/resources/views/layouts/app.blade.php';
$appLayout = file_get_contents($appLayoutPath);

// Replace Tailwind brand colors with logo colors
$appLayout = preg_replace(
    "/colors: \{.*?\}/s",
    "colors: {
                        brand: {
                            50: '#f0f8ff',
                            100: '#e0f0fe',
                            400: '#42b9f5', // Light blue from logo
                            500: '#0082CC', // Primary blue from logo
                            600: '#0069d9',
                            700: '#005cbf',
                            800: '#004085',
                            900: '#002752',
                        },
                        garut: {
                            gold: '#fbbf24',
                            green: '#059669',
                            dark: '#0f172a'
                        }
                    }",
    $appLayout
);

// Update Header to be clean white
$appLayout = str_replace(
    'class="glass-header sticky top-0 z-50 text-white border-b border-slate-700/50"',
    'class="bg-white sticky top-0 z-50 text-slate-800 border-b border-slate-200 shadow-sm"',
    $appLayout
);

// Replace logo block
$appLayout = preg_replace(
    "/<!-- Logo Diskominfo Garut -->.*?<\/a>/s",
    "<!-- Logo Diskominfo Garut -->
                <a href=\"{{ route('public.index') }}\" class=\"flex items-center gap-3\">
                    <img src=\"{{ asset('logo_diskominfo.png') }}\" alt=\"Logo Diskominfo Garut\" class=\"h-12 w-auto object-contain\">
                    <div>
                        <div class=\"font-heading font-extrabold text-lg tracking-tight text-slate-800 flex items-center gap-2\">
                            Charter Slot
                        </div>
                        <p class=\"text-xs text-brand-600 font-medium\">Pendaftaran & Kuota Magang Digital</p>
                    </div>
                </a>",
    $appLayout
);

// Update Nav Menu Text Colors
$appLayout = str_replace("text-slate-200", "text-slate-600", $appLayout);
$appLayout = str_replace("hover:text-amber-400", "hover:text-brand-600", $appLayout);
$appLayout = str_replace("text-amber-400 font-semibold", "text-brand-600 font-semibold", $appLayout);

// Update Footer to be light
$appLayout = str_replace(
    '<footer class="bg-slate-900 text-slate-400 border-t border-slate-800 text-sm py-10">',
    '<footer class="bg-white text-slate-600 border-t border-slate-200 text-sm py-10">',
    $appLayout
);
$appLayout = str_replace('border-slate-800', 'border-slate-200', $appLayout);
$appLayout = preg_replace(
    "/<div class=\"w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center text-white text-lg font-bold shadow-lg shadow-brand-500\/30\">.*?<\/div>/s",
    "<img src=\"{{ asset('logo_diskominfo.png') }}\" alt=\"Logo Diskominfo Garut\" class=\"w-10 h-10 object-contain\">",
    $appLayout
);
$appLayout = str_replace('text-white block', 'text-slate-800 block', $appLayout);
$appLayout = str_replace('text-slate-400', 'text-slate-500', $appLayout);
$appLayout = str_replace('bg-slate-800 hover:bg-gradient-to-tr', 'bg-slate-100 hover:bg-gradient-to-tr', $appLayout);
$appLayout = str_replace('bg-slate-800 hover:bg-red-600', 'bg-slate-100 hover:bg-red-600', $appLayout);
$appLayout = str_replace('bg-slate-800 hover:bg-blue-600', 'bg-slate-100 hover:bg-blue-600', $appLayout);
$appLayout = str_replace('bg-slate-800 hover:bg-sky-500', 'bg-slate-100 hover:bg-sky-500', $appLayout);
$appLayout = str_replace('text-slate-300', 'text-slate-600', $appLayout);

file_put_contents($appLayoutPath, $appLayout);

$publicIndexPath = 'c:/laragon/www/sistem-pendaftaran-diskominfo-V2/resources/views/public/index.blade.php';
$publicIndex = file_get_contents($publicIndexPath);

// Update Hero to be light
$publicIndex = str_replace(
    '<div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-sky-950 text-white overflow-hidden py-16 md:py-24">',
    '<div class="relative bg-white overflow-hidden py-16 md:py-24">',
    $publicIndex
);
$publicIndex = str_replace(
    'bg-[radial-gradient(#38bdf8_1px,transparent_1px)]',
    'bg-[radial-gradient(#e2e8f0_1px,transparent_1px)]',
    $publicIndex
);
$publicIndex = str_replace('text-white leading-tight', 'text-slate-900 leading-tight', $publicIndex);
$publicIndex = str_replace('text-slate-300 text-base', 'text-slate-600 text-base', $publicIndex);
$publicIndex = str_replace('text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-amber-300', 'text-brand-600', $publicIndex);
$publicIndex = str_replace('bg-amber-500/10 border-amber-500/30 text-amber-400', 'bg-brand-50 border-brand-200 text-brand-600', $publicIndex);
$publicIndex = str_replace('bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl shadow-lg shadow-amber-500/25', 'bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold rounded-xl shadow-md border border-brand-200', $publicIndex);
$publicIndex = str_replace('text-amber-400', 'text-brand-600', $publicIndex);

$publicIndex = str_replace(
    'bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-700',
    'bg-white hover:bg-slate-50 text-slate-700 border border-slate-300',
    $publicIndex
);
$publicIndex = str_replace(
    'bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700',
    'bg-brand-600 hover:bg-brand-700',
    $publicIndex
);
$publicIndex = str_replace('shadow-sky-500/25', 'shadow-brand-500/25', $publicIndex);

// Update Quota Card to be light
$publicIndex = str_replace(
    'class="glass-card p-6 rounded-2xl border border-slate-700/50 shadow-2xl bg-slate-900/80 text-white space-y-4"',
    'class="p-6 rounded-2xl border border-slate-200 shadow-xl bg-white text-slate-800 space-y-4"',
    $publicIndex
);
$publicIndex = str_replace('border-slate-700/60', 'border-slate-200', $publicIndex);
$publicIndex = str_replace('text-sky-400', 'text-brand-500', $publicIndex);
$publicIndex = str_replace('bg-emerald-500/20 text-emerald-300', 'bg-emerald-100 text-emerald-700', $publicIndex);

// Loop items inside Quota Card
$publicIndex = str_replace('bg-slate-800/60 rounded-xl border border-slate-700/40', 'bg-slate-50 rounded-xl border border-slate-100', $publicIndex);
$publicIndex = str_replace('text-slate-200 truncate', 'text-slate-700 truncate', $publicIndex);
$publicIndex = str_replace('text-brand-600 font-bold', 'text-brand-600 font-bold', $publicIndex); // already replaced amber earlier, wait it was text-amber-400, I replaced it globally
$publicIndex = str_replace('bg-slate-700 h-2', 'bg-slate-200 h-2', $publicIndex);
$publicIndex = str_replace('from-sky-400 to-teal-400', 'from-brand-400 to-brand-600', $publicIndex);

file_put_contents($publicIndexPath, $publicIndex);

echo "Done.";
