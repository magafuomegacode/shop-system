{{-- ===== Top Header — Dark Blue ===== --}}
@php
    use App\Models\Setting;
    $shopId = auth()->user()->shop_id;
    $systemName = Setting::get($shopId, 'system_name', 'Duka System');
    $systemPhone = Setting::get($shopId, 'phone', '');
@endphp

<header class="relative z-30 bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 shadow-lg sticky top-0">
    <div class="px-3 sm:px-4 py-3 flex justify-between items-center">

        {{-- Left: Hamburger (mobile) + Logo --}}
        <div class="flex items-center gap-3 min-w-0">

            {{-- Hamburger (mobile pekee) --}}
            <button type="button"
                    onclick="toggleSidebar()"
                    class="lg:hidden w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Logo & Shop name --}}
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-blue-500 via-cyan-500 to-blue-600 flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="min-w-0 hidden sm:block">
                    <h1 class="text-white font-bold text-sm sm:text-base leading-none truncate">
                        {{ $systemName }}
                    </h1>
                    <p class="text-blue-200 text-[10px] sm:text-xs mt-0.5 truncate">
                        @if($systemPhone)
                            {{ $systemPhone }}
                        @else
                            {{ auth()->user()->shop->name ?? 'Shop' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Right side --}}
        <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">

            {{-- Notifications Bell --}}
            <div class="relative" id="notif-wrapper">
                <button type="button"
                        id="notif-trigger"
                        class="relative w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span id="notif-badge"
                          class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 pulse-dot">
                        0
                    </span>
                </button>

                {{-- Notifications Dropdown --}}
                <div id="notif-menu"
                     class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl overflow-hidden z-[9999] max-h-[80vh] flex flex-col">

                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <div>
                            <h3 class="text-slate-900 font-bold text-sm">Notifications</h3>
                            <p id="notif-count-text" class="text-slate-500 text-xs mt-0.5">Loading...</p>
                        </div>
                        <button type="button"
                                onclick="markAllRead()"
                                class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold">
                            Mark all read
                        </button>
                    </div>

                    <div id="notif-list" class="overflow-y-auto flex-1">
                        <div class="text-center py-8 text-slate-400 text-sm">Loading...</div>
                    </div>

                    <a href="{{ route('notifications.index') }}"
                       class="px-4 py-3 border-t border-slate-100 text-center text-indigo-600 hover:bg-slate-50 text-xs font-semibold block">
                        View all notifications →
                    </a>
                </div>
            </div>

            {{-- Settings link (Admin & Owner only) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
                <a href="{{ route('settings.index') }}"
                   class="relative w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition"
                   title="Settings">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
            @endif

            {{-- ===== User Profile Dropdown ===== --}}
            <div class="relative" id="profile-wrapper">

                {{-- Profile Trigger --}}
                <button type="button"
                        id="profile-trigger"
                        class="flex items-center gap-2 pl-2 sm:pl-3 border-l border-white/20 hover:bg-white/5 rounded-lg pr-2 transition cursor-pointer">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-white text-sm font-medium leading-none">{{ auth()->user()->full_name }}</p>
                        <p class="text-blue-200 text-xs mt-0.5 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <svg class="w-4 h-4 text-white/60 hidden md:block transition-transform duration-200" id="profile-chevron"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Profile Dropdown Menu --}}
                <div id="profile-menu"
                     class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl overflow-hidden z-[9999] border border-slate-200">

                    {{-- User header --}}
                    <div class="px-4 py-3 bg-gradient-to-br from-blue-50 to-cyan-50 border-b border-slate-100">
                        <p class="text-slate-900 font-semibold text-sm truncate">{{ auth()->user()->full_name }}</p>
                        <p class="text-slate-500 text-xs truncate mt-0.5">
                            @if(auth()->user()->email)
                                {{ auth()->user()->email }}
                            @else
                                @<span>{{ auth()->user()->username }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- My Profile --}}
                    <a href="{{ route('profile.index') }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition text-slate-700">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium">My Profile</span>
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition text-red-600">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
            {{-- ===== End Profile Dropdown ===== --}}

        </div>
    </div>
</header>

{{-- Notification Script --}}
<script>
(function () {
    'use strict';

    const trigger = document.getElementById('notif-trigger');
    const menu = document.getElementById('notif-menu');
    const badge = document.getElementById('notif-badge');
    const list = document.getElementById('notif-list');
    const countText = document.getElementById('notif-count-text');

    if (!trigger || !menu) return;

    const csrfToken = '{{ csrf_token() }}';
    const fetchUrl = '{{ route('notifications.index') }}';
    const markAllUrl = '{{ route('notifications.mark-all-read') }}';
    const markReadUrl = '{{ url('notifications') }}';

    trigger.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            loadNotifications();
        } else {
            menu.classList.add('hidden');
        }
    });

    document.addEventListener('click', function (e) {
        if (!menu.classList.contains('hidden') && !e.target.closest('#notif-wrapper')) {
            menu.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') menu.classList.add('hidden');
    });

    function loadNotifications() {
        list.innerHTML = '<div class="text-center py-8 text-slate-400 text-sm">Loading...</div>';

        fetch(fetchUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => renderNotifications(data))
        .catch(err => {
            list.innerHTML = '<div class="text-center py-8 text-red-500 text-sm">Error loading</div>';
        });
    }

    function renderNotifications(data) {
        if (data.count > 0) {
            badge.textContent = data.count > 99 ? '99+' : data.count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        countText.textContent = data.count + ' unread';

        if (!data.notifications || !data.notifications.length) {
            list.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-slate-500 text-sm">No notifications</p>
                </div>
            `;
            return;
        }

        let html = '';
        data.notifications.forEach(function (n) {
            const icon = getIcon(n.icon || n.type);
            const iconColor = getIconColor(n.type);
            const bg = n.is_read ? 'bg-white' : 'bg-indigo-50';
            const dot = n.is_read ? '' : '<div class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></div>';

            html += `
                <a href="${n.link || '#'}"
                   onclick="markRead(${n.id}, event)"
                   class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition border-b border-slate-50 ${bg}">
                    <div class="w-9 h-9 rounded-xl ${iconColor} flex items-center justify-center flex-shrink-0">
                        ${icon}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-slate-900 text-sm font-semibold truncate">${escapeHtml(n.title)}</p>
                        ${n.message ? '<p class="text-slate-500 text-xs mt-0.5 line-clamp-2">' + escapeHtml(n.message) + '</p>' : ''}
                        <p class="text-slate-400 text-[10px] mt-1">${n.time}</p>
                    </div>
                    ${dot}
                </a>
            `;
        });

        list.innerHTML = html;
    }

    window.markRead = function (id, e) {
        fetch(markReadUrl + '/' + id + '/read', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).catch(() => {});
    };

    window.markAllRead = function () {
        fetch(markAllUrl, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(() => {
            loadNotifications();
        });
    };

    function getIcon(icon) {
        const icons = {
            'plus-circle': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>',
            'shopping-cart': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            'alert-triangle': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>',
        };
        return icons[icon] || '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>';
    }

    function getIconColor(type) {
        const colors = {
            'product_added': 'bg-gradient-to-br from-blue-500 to-cyan-500',
            'sale_made': 'bg-gradient-to-br from-green-500 to-emerald-500',
            'low_stock': 'bg-gradient-to-br from-yellow-500 to-orange-500',
        };
        return colors[type] || 'bg-gradient-to-br from-indigo-500 to-purple-500';
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function loadBadge() {
        fetch(fetchUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('hidden');
            }
        })
        .catch(() => {});
    }

    loadBadge();

    setInterval(loadBadge, 30000);
})();

{{-- Profile Dropdown Script --}}
(function () {
    'use strict';

    const trigger = document.getElementById('profile-trigger');
    const menu = document.getElementById('profile-menu');
    const chevron = document.getElementById('profile-chevron');

    if (!trigger || !menu) return;

    trigger.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const isHidden = menu.classList.contains('hidden');

        if (isHidden) {
            menu.classList.remove('hidden');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        } else {
            menu.classList.add('hidden');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });

    document.addEventListener('click', function (e) {
        if (!menu.classList.contains('hidden') && !e.target.closest('#profile-wrapper')) {
            menu.classList.add('hidden');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            menu.classList.add('hidden');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });
})();
</script>