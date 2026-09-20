{{-- ===== Bottom Navigation ===== --}}
<nav class="bottom-nav fixed bottom-0 left-0 right-0 z-50 pb-[env(safe-area-inset-bottom)]">
    <div class="max-w-7xl mx-auto px-2">
        <div class="flex justify-around items-center py-2">

            {{-- Home --}}
            <a href="{{ route('dashboard') }}"
               class="nav-item flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-icon w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center bg-slate-100">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="nav-label text-[10px] sm:text-xs text-slate-700 font-medium">Home</span>
            </a>

            {{-- Products --}}
            <a href="{{ route('products.index') }}"
               class="nav-item flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1 {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <div class="nav-icon w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center bg-slate-100">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="nav-label text-[10px] sm:text-xs text-slate-700 font-medium">Products</span>
            </a>

            {{-- Sales (POS) --}}
            <a href="{{ route('pos.index') }}"
               class="nav-item flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1 {{ request()->routeIs('pos.*') || request()->routeIs('sales.*') ? 'active' : '' }}">
                <div class="nav-icon w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center bg-slate-100">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="nav-label text-[10px] sm:text-xs text-slate-700 font-medium">Sales</span>
            </a>

            {{-- Stores (Admin & Owner only) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
                <a href="{{ route('stores.index') }}"
                   class="nav-item flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1 {{ request()->routeIs('stores.*') ? 'active' : '' }}">
                    <div class="nav-icon w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center bg-slate-100">
                        <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="nav-label text-[10px] sm:text-xs text-slate-700 font-medium">Stores</span>
                </a>
            @endif

            {{-- Users (Admin & Owner only) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
                <a href="{{ route('users.index') }}"
                   class="nav-item flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1 {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <div class="nav-icon w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center bg-slate-100">
                        <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="nav-label text-[10px] sm:text-xs text-slate-700 font-medium">Users</span>
                </a>
            @endif

            {{-- Settings --}}
            <a href="#"
               class="nav-item flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1 {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <div class="nav-icon w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center bg-slate-100">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="nav-label text-[10px] sm:text-xs text-slate-700 font-medium">Settings</span>
            </a>

        </div>
    </div>
</nav>