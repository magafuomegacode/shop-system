@extends('layouts.app')

@section('title', $user->full_name)

@section('content')

    <a href="{{ route('users.index') }}"
       class="inline-flex items-center gap-2 text-lime-400 hover:text-lime-300 text-sm mb-4 fade-in-up d-1 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Users
    </a>

    {{-- Profile header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-6 mb-4 relative overflow-hidden shadow-2xl border border-stone-600/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center text-white font-bold text-2xl sm:text-3xl flex-shrink-0 shadow-lg">
                {{ strtoupper(substr($user->full_name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-white text-xl sm:text-2xl font-bold truncate">{{ $user->full_name }}</h2>

                    @if($user->isAdmin())
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/30 text-white border border-purple-400/40 font-semibold uppercase">Admin</span>
                    @elseif($user->isOwner())
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-500/30 text-white border border-blue-400/40 font-semibold uppercase">Owner</span>
                    @else
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-lime-500/20 text-white border border-lime-500/40 font-semibold uppercase">Cashier</span>
                    @endif

                    @if(!$user->is_active)
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-500/30 text-white border border-red-400/40 font-semibold uppercase">Blocked</span>
                    @endif
                </div>
                <p class="text-white text-sm mt-1 truncate">
                    @@<span>{{ $user->username }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="fade-in-up d-2 bg-green-500/20 border border-green-500/40 text-white px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="fade-in-up d-2 bg-red-500/20 border border-red-500/40 text-white px-4 py-3 rounded-xl mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Info grid --}}
    <div class="fade-in-up d-2 grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">

        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 shadow-2xl border border-stone-600/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-lime-500/20 border border-lime-500/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-medium">Email</p>
                    <p class="text-white text-sm mt-0.5 truncate font-semibold">{{ $user->email ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 shadow-2xl border border-stone-600/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 border border-green-500/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-medium">Phone</p>
                    <p class="text-white text-sm mt-0.5 truncate font-semibold">{{ $user->phone ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Store / Access --}}
        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 shadow-2xl border border-stone-600/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-medium">
                        @if($user->isCashier())
                            Store Access
                        @else
                            Store
                        @endif
                    </p>
                    <p class="text-white text-sm mt-0.5 truncate font-semibold">
                        @if($user->isCashier())
                            All Stores
                        @elseif($user->store)
                            {{ $user->store->name }}
                        @else
                            —
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 shadow-2xl border border-stone-600/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl {{ $user->is_active ? 'bg-green-500/20 border border-green-500/40' : 'bg-red-500/20 border border-red-500/40' }} flex items-center justify-center flex-shrink-0">
                    @if($user->is_active)
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-medium">Status</p>
                    <p class="text-sm mt-0.5 {{ $user->is_active ? 'text-green-400' : 'text-red-400' }} font-bold">
                        {{ $user->is_active ? 'Active' : 'Blocked' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Account details --}}
    <div class="fade-in-up d-3 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-4 shadow-2xl border border-stone-600/50">
        <h3 class="text-white font-bold mb-4">Account Details</h3>
        <div class="space-y-3">

            <div class="flex items-center justify-between py-2 border-b border-stone-600/50">
                <span class="text-white text-sm font-medium">Created at</span>
                <span class="text-white text-sm font-semibold">{{ $user->created_at->format('d M Y, H:i') }}</span>
            </div>

            <div class="flex items-center justify-between py-2 border-b border-stone-600/50">
                <span class="text-white text-sm font-medium">Last login</span>
                <span class="text-white text-sm font-semibold">
                    {{ $user->last_login ? $user->last_login->format("d M Y") : 'Never' }}
                </span>
            </div>

            @if($user->creator)
                <div class="flex items-center justify-between py-2 border-b border-stone-600/50">
                    <span class="text-white text-sm font-medium">Created by</span>
                    <span class="text-white text-sm font-semibold">{{ $user->creator->full_name }}</span>
                </div>
            @endif

        </div>
    </div>

    {{-- Actions --}}
    <div class="fade-in-up d-4 flex flex-wrap gap-3">
        <a href="{{ route('users.edit', $user) }}"
           class="bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>

        @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('users.toggle-active', $user) }}">
                @csrf
                @method('PATCH')

                @if($user->is_active)
                    {{-- BLOCK BUTTON — RED --}}
                    <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Block User
                    </button>
                @else
                    {{-- UNBLOCK BUTTON — GREEN --}}
                    <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Unblock User
                    </button>
                @endif
            </form>

            {{-- DELETE BUTTON --}}
            @if(auth()->user()->isAdmin() || (auth()->user()->isOwner() && $user->isCashier()))
                <form method="POST" action="{{ route('users.destroy', $user) }}"
                      onsubmit="return confirm('Are you sure you want to DELETE {{ $user->full_name }}? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete User
                    </button>
                </form>
            @endif
        @endif
    </div>

@endsection