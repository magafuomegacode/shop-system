{{-- ===== Footer ===== --}}
<footer class="relative z-10 mt-auto bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">

            {{-- Left: Copyright --}}
            <p class="text-white font-bold text-xs text-center sm:text-left">
                © {{ date('Y') }} {{ auth()->user()->shop->name ?? 'My Shop' }}. All rights reserved.
            </p>

            {{-- Right: Status + Version + Made in --}}
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs">
                <span class="flex items-center gap-1.5 text-white font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 pulse-dot"></span>
                    System Online
                </span>
                <span class="text-white font-bold">v1.0.0</span>
                <span class="text-white font-bold">Made within Tanzania</span>
            </div>
        </div>
    </div>
</footer>