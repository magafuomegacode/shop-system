@extends('layouts.app')

@section('title', $store->name)

@section('content')

    <a href="{{ route('stores.index') }}"
       class="inline-flex items-center gap-2 text-stone-300 hover:text-white text-sm mb-4 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Stores
    </a>

    <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-6 mb-4 shadow-2xl border border-stone-600/50">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-xl font-bold truncate">{{ $store->name }}</h2>
                @if($store->type)
                    <p class="text-stone-300 text-sm capitalize">{{ $store->type }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                <p class="text-stone-300 text-xs">Products</p>
                <p class="text-white font-bold text-2xl mt-1">{{ $store->products_count }}</p>
            </div>
            <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                <p class="text-stone-300 text-xs">Status</p>
                <p class="text-sm mt-1 font-semibold {{ $store->is_active ? 'text-green-400' : 'text-red-400' }}">
                    {{ $store->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
            @if($store->location)
                <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 col-span-2 border border-stone-600/50">
                    <p class="text-stone-300 text-xs">Location</p>
                    <p class="text-white font-semibold mt-1">{{ $store->location }}</p>
                </div>
            @endif
        </div>
    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
        <div class="flex gap-3">
            <a href="{{ route('stores.edit', $store) }}"
               class="bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-lg">
                Edit
            </a>
        </div>
    @endif

@endsection