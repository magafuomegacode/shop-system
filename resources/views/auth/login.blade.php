<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    @php
        use App\Models\Setting;
        // Try to get settings from the first shop (login page has no auth)
        $firstShop = \App\Models\Shop::first();
        $shopId = $firstShop ? $firstShop->id : 0;
        $systemName = Setting::get($shopId, 'system_name', 'Duka System');
        $systemPhone = Setting::get($shopId, 'phone', '');
    @endphp
    <title>Login - {{ $systemName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            overflow-x: hidden;
            overscroll-behavior: none;
        }

        /* ===== Animated gradient background ===== */
        body {
            background: linear-gradient(-45deg, #000000, #1a1a2e, #16213e, #0f3460, #000000);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
        }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ===== Floating orbs ===== */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
            will-change: transform;
        }

        .orb-1 {
            width: 60vmin; height: 60vmin;
            max-width: 400px; max-height: 400px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            top: -20vmin; left: -20vmin;
        }

        .orb-2 {
            width: 55vmin; height: 55vmin;
            max-width: 350px; max-height: 350px;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            bottom: -20vmin; right: -20vmin;
            animation-delay: 2s;
        }

        .orb-3 {
            width: 45vmin; height: 45vmin;
            max-width: 300px; max-height: 300px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(30px, -30px) scale(1.1); }
            66%      { transform: translate(-30px, 30px) scale(0.95); }
        }

        /* ===== Login Card ===== */
        .login-card {
            animation: cardEntrance 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(20, 20, 30, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.8),
                0 0 80px rgba(102, 126, 234, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 28rem;
            position: relative;
            z-index: 10;
        }

        @keyframes cardEntrance {
            0% {
                opacity: 0;
                transform: translateY(60px) scale(0.9) rotateX(-15deg);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1) rotateX(0);
            }
        }

        /* ===== Logo pulse ===== */
        .logo-icon {
            animation: logoPulse 2.5s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 30px rgba(102, 126, 234, 0.5);
            }
            50% {
                transform: scale(1.08);
                box-shadow: 0 0 50px rgba(102, 126, 234, 0.8);
            }
        }

        /* ===== Input fields ===== */
        .input-field {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 16px;
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        /* ===== Button ===== */
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            animation: gradientMove 3s ease infinite;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            min-height: 52px;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow:
                0 15px 35px rgba(102, 126, 234, 0.4),
                0 0 40px rgba(240, 147, 251, 0.3);
        }

        .btn-gradient:active {
            transform: translateY(0) scale(0.98);
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50%      { background-position: 100% 50%; }
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        /* ===== Fade-in-up ===== */
        .fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.35s; }
        .delay-3 { animation-delay: 0.5s; }
        .delay-4 { animation-delay: 0.65s; }
        .delay-5 { animation-delay: 0.8s; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== Error shake ===== */
        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%      { transform: translateX(-8px); }
            40%      { transform: translateX(8px); }
            60%      { transform: translateX(-5px); }
            80%      { transform: translateX(5px); }
        }

        .text-glow {
            text-shadow: 0 0 20px rgba(102, 126, 234, 0.6),
                         0 0 40px rgba(240, 147, 251, 0.3);
        }

        /* ===== Particles ===== */
        .particle {
            position: fixed;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: rise linear infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes rise {
            from {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% { opacity: 1; }
            90% { opacity: 1; }
            to {
                transform: translateY(-100px) scale(1);
                opacity: 0;
            }
        }

        /* ===== Mobile-specific ===== */
        @media (max-width: 640px) {
            .login-card {
                border-radius: 1.5rem;
            }
        }

        /* ===== Landscape on phone ===== */
        @media (max-height: 500px) and (orientation: landscape) {
            .login-card {
                max-width: 90%;
                padding: 1rem 2rem;
            }
            .logo-icon {
                width: 3rem !important;
                height: 3rem !important;
            }
            .logo-icon svg {
                width: 1.75rem !important;
                height: 1.75rem !important;
            }
        }

        button, a {
            touch-action: manipulation;
        }
    </style>
</head>
<body>

    <!-- ===== Background Orbs ===== -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- ===== Floating Particles ===== -->
    <div class="particle" style="width:4px;height:4px;left:10%;animation-duration:12s;animation-delay:0s;"></div>
    <div class="particle" style="width:6px;height:6px;left:25%;animation-duration:15s;animation-delay:2s;"></div>
    <div class="particle" style="width:3px;height:3px;left:40%;animation-duration:10s;animation-delay:4s;"></div>
    <div class="particle" style="width:5px;height:5px;left:55%;animation-duration:14s;animation-delay:1s;"></div>
    <div class="particle" style="width:4px;height:4px;left:70%;animation-duration:13s;animation-delay:3s;"></div>
    <div class="particle" style="width:7px;height:7px;left:85%;animation-duration:16s;animation-delay:5s;"></div>
    <div class="particle" style="width:3px;height:3px;left:95%;animation-duration:11s;animation-delay:2.5s;"></div>

    <!-- ===== Login Card ===== -->
    <div class="login-card rounded-3xl p-6 sm:p-8 md:p-10 mx-4 sm:mx-6">

        <!-- Logo -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="logo-icon w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-4 sm:mb-5">
                <svg class="w-9 h-9 sm:w-12 sm:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            {{-- ✅ System name from settings --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-white text-glow fade-in-up delay-1">
                {{ $systemName }}
            </h1>
            <p class="text-gray-400 text-xs sm:text-sm mt-2 fade-in-up delay-2">
                Ingia kwenye mfumo
            </p>
            @if($systemPhone)
                <p class="text-gray-500 text-xs mt-1 fade-in-up delay-2">
                    📞 {{ $systemPhone }}
                </p>
            @endif
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="error-shake bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl mb-5 text-xs sm:text-sm backdrop-blur-sm">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4 sm:space-y-5">
            @csrf

            <!-- Username / Email -->
            <div class="fade-in-up delay-3">
                <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">
                    Username au Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="login"
                           value="{{ old('login') }}"
                           placeholder="username au email"
                           required
                           autofocus
                           autocomplete="username"
                           inputmode="email"
                           class="input-field w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 rounded-xl text-white outline-none">
                </div>
            </div>

            <!-- Password -->
            <div class="fade-in-up delay-4">
                <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input type="password"
                           name="password"
                           id="password"
                           required
                           autocomplete="current-password"
                           class="input-field w-full pl-10 sm:pl-12 pr-10 sm:pr-12 py-3 rounded-xl text-white outline-none">
                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-500 hover:text-gray-300 transition"
                            aria-label="Onyesha/Ficha password">
                        <svg id="eye-icon" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember me -->
            <div class="flex items-center fade-in-up delay-4">
                <input type="checkbox" name="remember" id="remember"
                       class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0">
                <label for="remember" class="ml-2 text-xs sm:text-sm text-gray-400 cursor-pointer select-none">
                    Nikumbuke
                </label>
            </div>

            <!-- Submit -->
            <div class="fade-in-up delay-5">
                <button type="submit"
                        class="btn-gradient w-full text-white text-sm sm:text-base font-semibold py-3.5 rounded-xl relative">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Ingia
                    </span>
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="text-center mt-5 sm:mt-6 fade-in-up delay-5">
            <p class="text-[10px] sm:text-xs text-gray-500">
                © {{ date('Y') }} {{ $systemName }} · All rights reserved
            </p>
        </div>
    </div>

    <!-- Password toggle -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }
    </script>

</body>
</html>