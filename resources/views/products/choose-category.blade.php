@extends('layouts.app')

@section('title', 'Choose Category')

@section('content')

    <a href="{{ route('stores.index') }}"
       class="inline-flex items-center gap-2 text-white hover:text-cyan-300 text-sm mb-4 font-bold">
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
                          d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white font-bold text-xl">Choose a Category</h2>
                <p class="text-white font-bold text-xs mt-1 opacity-80">
                    Adding product to <span class="text-cyan-300">{{ $store->name }}</span>
                </p>
            </div>
        </div>
    </div>

    @if($categories->count() > 0)
        {{-- Category grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('products.create', ['store_id' => $store->id, 'category_id' => $category->id]) }}"
                   class="fade-in-up group bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 hover:bg-blue-800/60 transition shadow-2xl border border-blue-700/50 hover:border-cyan-400/60 flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-sm truncate">{{ $category->name }}</p>
                        @if($category->description ?? false)
                            <p class="text-white font-bold text-[10px] opacity-70 truncate mt-0.5">{{ $category->description }}</p>
                        @endif
                    </div>

                    <svg class="w-5 h-5 text-white/60 group-hover:text-cyan-300 group-hover:translate-x-1 transition-all flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @else
        {{-- Empty state --}}
        <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-10 text-center shadow-2xl border border-blue-700/50">
            <div class="w-16 h-16 rounded-2xl bg-blue-900/40 border border-blue-700/50 flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <p class="text-white font-bold text-sm">No categories available for this store</p>
            <p class="text-white font-bold text-xs mt-1 opacity-70">
                Add categories via the seeder or contact your admin
            </p>
            <a href="{{ route('stores.index') }}"
               class="inline-block mt-4 text-white font-bold hover:text-cyan-300 text-sm">
                Back to Stores →
            </a>
        </div>
    @endif

@endsection