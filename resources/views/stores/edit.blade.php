@extends('layouts.app')

@section('title', 'Edit Store')

@section('content')

    <a href="{{ route('stores.index') }}"
       class="inline-flex items-center gap-2 text-blue-300 hover:text-white text-sm mb-4 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Stores
    </a>

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-xl sm:text-2xl font-bold truncate">Edit Store</h2>
                <p class="text-blue-200 text-xs sm:text-sm mt-0.5 truncate">{{ $store->name }}</p>
            </div>
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

    <form method="POST" action="{{ route('stores.update', $store) }}"
          class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xl border border-blue-700/50">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold text-blue-200 mb-2">
                Store Name <span class="text-red-400">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name', $store->name) }}" required
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
        </div>

        {{-- Type --}}
        <div>
            <label for="type" class="block text-xs font-semibold text-blue-200 mb-2">Type (optional)</label>
            <input type="text" name="type" id="type" value="{{ old('type', $store->type) }}"
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-xs font-semibold text-blue-200 mb-2">Location (optional)</label>
            <input type="text" name="location" id="location" value="{{ old('location', $store->location) }}"
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
        </div>

        {{-- Active --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $store->is_active) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-blue-700 bg-blue-900/40 text-cyan-500 focus:ring-cyan-400/50">
            <label for="is_active" class="text-sm text-blue-100">Active store</label>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                Save Changes
            </button>
            <a href="{{ route('stores.index') }}"
               class="px-5 py-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-blue-100 text-sm font-medium transition">
                Cancel
            </a>
        </div>
    </form>

@endsection