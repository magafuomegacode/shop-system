@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

    {{-- Header --}}
    <div class="fade-in-up d-1 glass rounded-2xl p-5 mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-full -mr-20 -mt-20 opacity-70"></div>
        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-slate-900 text-xl sm:text-2xl font-bold">Notifications</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">
                    <span id="total-count">{{ $notifications->count() }}</span> total ·
                    <span id="unread-count">{{ $unreadCount }}</span> unread
                </p>
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" id="mark-all-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                        Mark all read
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- List --}}
    @if($notifications->count() > 0)
        <div class="space-y-2" id="notification-list">
            @foreach($notifications as $n)
                @php
                    $colors = [
                        'product_added' => 'from-blue-500 to-cyan-500',
                        'sale_made'     => 'from-green-500 to-emerald-500',
                        'low_stock'     => 'from-yellow-500 to-orange-500',
                    ];
                    $color = $colors[$n->type] ?? 'from-indigo-500 to-purple-500';
                @endphp
                <a href="{{ $n->link ?? '#' }}"
                   data-id="{{ $n->id }}"
                   data-read="{{ $n->is_read ? '1' : '0' }}"
                   data-link="{{ $n->link ?? '' }}"
                   class="notification-item fade-in-up glass rounded-2xl p-4 hover:bg-slate-50 transition block {{ !$n->is_read ? 'border-l-4 border-indigo-500' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $color }} flex items-center justify-center flex-shrink-0">
                            @if($n->type === 'product_added')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            @elseif($n->type === 'sale_made')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/>
                                </svg>
                            @elseif($n->type === 'low_stock')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-slate-900 font-semibold text-sm truncate">{{ $n->title }}</p>
                                @if(!$n->is_read)
                                    <span class="new-badge text-[10px] px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-semibold uppercase">New</span>
                                @endif
                            </div>
                            @if($n->message)
                                <p class="text-slate-500 text-xs mt-1">{{ $n->message }}</p>
                            @endif
                            <p class="text-slate-400 text-[10px] mt-1">
                                {{ $n->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="glass rounded-2xl p-10 text-center">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
            </svg>
            <p class="text-slate-600 text-sm font-medium">No notifications</p>
        </div>
    @endif

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const csrfToken = '{{ csrf_token() }}';
    const markReadUrlTemplate = '{{ route('notifications.mark-read', ['notification' => '__ID__']) }}';

    function updateUnreadCount(delta) {
        const el = document.getElementById('unread-count');
        if (!el) return;
        let current = parseInt(el.textContent, 10) || 0;
        current = Math.max(0, current + delta);
        el.textContent = current;

        // Hide "Mark all read" button if no unread left
        if (current === 0) {
            const form = document.getElementById('mark-all-form');
            if (form) form.style.display = 'none';
        }
    }

    function markAsRead(item) {
        const id = item.dataset.id;
        const isRead = item.dataset.read === '1';

        if (isRead) return Promise.resolve();

        const url = markReadUrlTemplate.replace('__ID__', id);

        return fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function () {
            // Update UI
            item.dataset.read = '1';
            item.classList.remove('border-l-4', 'border-indigo-500');

            const badge = item.querySelector('.new-badge');
            if (badge) badge.remove();

            updateUnreadCount(-1);
        })
        .catch(function (err) {
            console.error('❌ Failed to mark as read:', err);
        });
    }

    document.querySelectorAll('.notification-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault();

            const isRead = item.dataset.read === '1';
            const link = item.dataset.link;

            // Mark as read (AJAX) then navigate
            markAsRead(item).then(function () {
                if (link) {
                    window.location.href = link;
                }
            });
        });
    });
})();
</script>
@endpush