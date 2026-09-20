@extends('layouts.app')

@section('title', 'Stores')

@section('content')

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-stone-600/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-white text-xl sm:text-2xl font-bold">Stores</h2>
                <p class="text-stone-300 text-xs sm:text-sm mt-1">
                    {{ $stores->total() }} {{ Str::plural('store', $stores->total()) }}
                </p>
            </div>
            <a href="{{ route('stores.create') }}"
               class="flex-shrink-0 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Add Store</span>
            </a>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="fade-in-up d-2 bg-green-500/20 border border-green-500/40 text-green-200 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="fade-in-up d-2 bg-red-500/20 border border-red-500/40 text-red-200 px-4 py-3 rounded-xl mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- List --}}
    @if($stores->count() > 0)
        <div class="space-y-3">
            @foreach($stores as $store)
                <div class="fade-in-up bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 hover:bg-stone-700/60 transition shadow-2xl border border-stone-600/50">
                    <div class="flex items-center gap-3">

                        {{-- Icon --}}
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-white font-semibold text-sm truncate">{{ $store->name }}</p>

                                @if(!$store->is_active)
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-500/30 text-red-200 border border-red-400/40 font-semibold uppercase">Inactive</span>
                                @endif

                                @if($store->type)
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-lime-500/20 text-lime-200 border border-lime-500/40 font-semibold uppercase">
                                        {{ $store->type }}
                                    </span>
                                @endif
                            </div>

                            @if($store->location)
                                <p class="text-stone-300 text-xs mt-0.5 truncate">{{ $store->location }}</p>
                            @endif

                            <div class="flex items-center gap-2 text-xs text-stone-300 mt-1">
                                <span class="px-2 py-0.5 bg-stone-800/60 text-stone-200 border border-stone-600/50 rounded-md font-medium">
                                    {{ $store->products_count }} {{ Str::plural('product', $store->products_count) }}
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <a href="{{ route('stores.show', $store) }}"
                               class="w-8 h-8 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                               title="View">
                                <svg class="w-4 h-4 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('stores.edit', $store) }}"
                               class="w-8 h-8 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                               title="Edit">
                                <svg class="w-4 h-4 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('stores.toggle-active', $store) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button class="w-8 h-8 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                                        title="{{ $store->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($store->is_active)
                                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            {{-- Delete — Admin & Owner --}}
                            @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
                                <form method="POST" action="{{ route('stores.destroy', $store) }}" class="inline"
                                      onsubmit="return confirm('Delete {{ $store->name }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-8 h-8 rounded-lg bg-red-500/20 hover:bg-red-500/30 border border-red-500/40 flex items-center justify-center transition"
                                            title="Delete">
                                        <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $stores->links() }}</div>
    @else
        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-10 text-center shadow-2xl border border-stone-600/50">
            <svg class="w-16 h-16 text-stone-500/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="text-stone-200 text-sm font-medium">No stores yet</p>
            <a href="{{ route('stores.create') }}" class="inline-block mt-4 text-lime-400 hover:text-lime-300 text-sm font-semibold">
                Add your first store →
            </a>
        </div>
    @endif

@endsection