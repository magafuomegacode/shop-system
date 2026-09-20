@extends('layouts.app')

@section('title', 'Add Store')

@section('content')

    <a href="{{ route('stores.index') }}"
       class="inline-flex items-center gap-2 text-stone-300 hover:text-white text-sm mb-4 fade-in-up d-1 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Stores
    </a>

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-stone-600/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-white text-xl sm:text-2xl font-bold">Add New Store</h2>
            <p class="text-stone-300 text-xs sm:text-sm mt-1">
                Create a new store for your shop
            </p>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="fade-in-up d-2 bg-red-500/20 border border-red-500/40 text-red-200 px-4 py-3 rounded-xl mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('stores.store') }}"
          class="fade-in-up d-2 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xl border border-stone-600/50">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold text-stone-300 mb-2">
                Store Name <span class="text-red-400">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   placeholder="e.g. Store Vinywaji"
                   class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
        </div>

        {{-- Type --}}
        <div>
            <label for="type" class="block text-xs font-semibold text-stone-300 mb-2">Type (optional)</label>
            <input type="text" name="type" id="type" value="{{ old('type') }}"
                   placeholder="e.g. beverages, lotions, general"
                   class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            <p class="text-stone-400/70 text-xs mt-1">Category of products in this store</p>
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-xs font-semibold text-stone-300 mb-2">Location (optional)</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}"
                   placeholder="e.g. Section A"
                   class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
        </div>

        {{-- Active --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                   class="w-4 h-4 rounded border-stone-600 bg-stone-800/60 text-lime-500 focus:ring-lime-400/50">
            <label for="is_active" class="text-sm text-stone-200">Active store</label>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                Add Store
            </button>
            <a href="{{ route('stores.index') }}"
               class="px-5 py-3 rounded-xl bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 text-stone-200 text-sm font-medium transition">
                Cancel
            </a>
        </div>
    </form>

@endsection