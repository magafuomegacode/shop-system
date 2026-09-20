<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', 'Duka System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
            box-sizing: border-box;
        }

        html {
            -webkit-text-size-adjust: 100%;
            -moz-text-size-adjust: 100%;
            text-size-adjust: 100%;
            scroll-behavior: smooth;
        }

        html, body {
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* ===== WHITE BACKGROUND ===== */
        body {
            background: #ffffff;
            min-height: 100vh;
            min-height: 100dvh;
            padding-left: env(safe-area-inset-left);
            padding-right: env(safe-area-inset-right);
        }

        @supports not (min-height: 100dvh) {
            body { min-height: 100vh; }
        }

        /* White card */
        .glass {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        }

        /* Input fields */
        .input-field {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            font-size: 16px;
            width: 100%;
            max-width: 100%;
        }
        .input-field:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            transform: translateY(-2px);
        }
        .input-field::placeholder { color: #94a3b8; }
        select.input-field {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
            padding-right: 3rem;
        }

        /* Fade in up */
        .fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .d-1 { animation-delay: 0.05s; }
        .d-2 { animation-delay: 0.1s; }
        .d-3 { animation-delay: 0.15s; }
        .d-4 { animation-delay: 0.2s; }
        .d-5 { animation-delay: 0.25s; }
        .d-6 { animation-delay: 0.3s; }

        /* Stat card hover */
        .stat-card {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(37, 99, 235, 0.15);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link {
            transition: all 0.25s ease;
        }

        /* Force dark text globally */
        h1, h2, h3, h4, h5, h6 {
            color: #0f172a;
        }
        p, span, label {
            color: #334155;
        }

        /* Responsive typography */
        @media (max-width: 640px) {
            h1, h2 { font-size: clamp(1.25rem, 5vw, 1.75rem); }
            h3 { font-size: clamp(1rem, 4vw, 1.25rem); }
        }

        /* Prevent iOS layout shift */
        @supports (-webkit-touch-callout: none) {
            body {
                min-height: -webkit-fill-available;
            }
        }

        /* Images never overflow */
        img, svg, video, canvas {
            max-width: 100%;
            height: auto;
        }

        /* Touch-friendly */
        button, a, [role="button"] {
            touch-action: manipulation;
        }

        /* Pulse dot */
        .pulse-dot {
            animation: pulseDot 2s ease-in-out infinite;
        }
        @keyframes pulseDot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%      { transform: scale(1.5); opacity: 0.5; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #2563eb, #1e40af); border-radius: 4px; }

        /* Sidebar scrollbar */
        .sidebar nav::-webkit-scrollbar { width: 4px; }
        .sidebar nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar nav::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }

        /* Print-friendly */
        @media print {
            body { background: white; }
            .sidebar, header, footer, #sidebar-overlay { display: none !important; }
            main { padding: 0; margin: 0; }
            .lg\:ml-64 { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="relative">

    <div class="flex min-h-screen relative">

        {{-- ===== Sidebar (desktop — daima inaonekana) ===== --}}
        <aside class="sidebar hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 lg:z-40 bg-gradient-to-b from-slate-900 via-blue-900 to-slate-900 shadow-2xl">
            @include('partials.sidebar')
        </aside>

        {{-- ===== Overlay kwa mobile ===== --}}
        <div id="sidebar-overlay"
             class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
             onclick="closeSidebar()"></div>

        {{-- ===== Mobile Sidebar (inatoka kwa hamburger) ===== --}}
        <aside id="mobile-sidebar"
               class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-slate-900 via-blue-900 to-slate-900 shadow-2xl -translate-x-full lg:hidden">
            @include('partials.sidebar')
        </aside>

        {{-- ===== Main Content Area ===== --}}
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">

            {{-- Header --}}
            @include('partials.header')

            {{-- Main --}}
            <main class="relative z-10 w-full mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 flex-1">
                <div class="max-w-7xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            @include('partials.footer')

        </div>
    </div>

    @stack('scripts')

    <script>
        // ===== SIDEBAR TOGGLE (mobile) =====
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;

            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                closeSidebar();
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;

            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close sidebar kama link inabonyezwa
        document.querySelectorAll('#mobile-sidebar a').forEach(function (link) {
            link.addEventListener('click', closeSidebar);
        });

        // Close kwa Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
</body>
</html>