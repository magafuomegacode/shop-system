{{-- ===== Sidebar Content ===== --}}
<div class="flex flex-col h-full">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-white/10 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 via-cyan-500 to-blue-600 flex items-center justify-center shadow-lg flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <h1 class="text-white font-bold text-base leading-none truncate">
                {{ \App\Models\Setting::get(auth()->user()->shop_id, 'system_name', 'Duka System') }}
            </h1>
            <p class="text-white font-bold text-xs mt-1 truncate">
                {{ auth()->user()->shop->name ?? 'Shop' }}
            </p>
        </div>
    </div>

    {{-- User Info --}}
    <div class="px-5 py-4 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white font-bold text-sm leading-none truncate">
                    {{ auth()->user()->full_name }}
                </p>
                <p class="text-white font-bold text-xs mt-1 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        {{-- Home --}}
        <a href="{{ route('dashboard') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm font-bold text-white">Home</span>
        </a>

        {{-- Products --}}
        <a href="{{ route('products.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('products.*') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="text-sm font-bold text-white">Products</span>
        </a>

        {{-- Sales (POS) --}}
        <a href="{{ route('pos.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('pos.*') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="text-sm font-bold text-white">POS (Sell)</span>
        </a>

        {{-- Sales List --}}
        <a href="{{ route('sales.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('sales.*') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-sm font-bold text-white">Sales</span>
        </a>

        {{-- Stores (Admin & Owner) --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
            <a href="{{ route('stores.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('stores.*') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="text-sm font-bold text-white">Stores</span>
            </a>
        @endif

        {{-- Users (Admin & Owner) --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
            <a href="{{ route('users.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('users.*') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-sm font-bold text-white">Users</span>
            </a>
        @endif

        {{-- Notifications --}}
        <a href="{{ route('notifications.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('notifications.*') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="text-sm font-bold text-white">Notifications</span>
        </a>

        {{-- Settings (Admin & Owner only) --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
            <a href="{{ route('settings.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('settings.*') ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm font-bold text-white">Settings</span>
            </a>
        @endif

    </nav>

</div>