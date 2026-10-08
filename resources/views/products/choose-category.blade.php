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

        {{-- ========================================================== --}}
        {{-- SEARCH BOX --}}
        {{-- ========================================================== --}}
        <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 mb-4 shadow-2xl border border-blue-700/50">
            <div class="relative">
                <svg class="w-5 h-5 text-white/60 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>

                <input type="text"
                       id="category-search"
                       placeholder="Search categories..."
                       autocomplete="off"
                       autofocus
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white font-bold placeholder-white/40 pl-12 pr-24 py-3 rounded-xl outline-none text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">

                {{-- Clear button --}}
                <button type="button"
                        id="category-search-clear"
                        class="hidden absolute right-20 top-1/2 -translate-y-1/2 w-7 h-7 rounded-md bg-blue-800/60 hover:bg-blue-700/60 flex items-center justify-center transition">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Result count badge --}}
                <span id="category-search-count"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-white bg-cyan-500/30 border border-cyan-400/40 px-2 py-1 rounded-lg">
                    {{ $categories->count() }} total
                </span>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- CATEGORY GRID --}}
        {{-- ========================================================== --}}
        <div id="category-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('products.create', ['store_id' => $store->id, 'category_id' => $category->id]) }}"
                   data-category-name="{{ strtolower($category->name) }}"
                   class="category-card fade-in-up group bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 hover:bg-blue-800/60 transition shadow-2xl border border-blue-700/50 hover:border-cyan-400/60 flex items-center gap-3">

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

        {{-- No results message --}}
        <div id="no-results" class="hidden bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-10 text-center shadow-2xl border border-blue-700/50 mt-4">
            <div class="w-16 h-16 rounded-2xl bg-blue-900/40 border border-blue-700/50 flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <p class="text-white font-bold text-sm">No categories match your search</p>
            <p class="text-white font-bold text-xs mt-1 opacity-70">Try a different keyword</p>
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

    @if($categories->count() > 0)
        <script>
        (function () {
            const input      = document.getElementById('category-search');
            const clearBtn   = document.getElementById('category-search-clear');
            const countBadge = document.getElementById('category-search-count');
            const cards      = Array.from(document.querySelectorAll('.category-card'));
            const noResults  = document.getElementById('no-results');

            if (!input || !cards.length) return;

            const totalCount = cards.length;

            function filter() {
                const q = input.value.trim().toLowerCase();
                let visible = 0;

                cards.forEach(function (card) {
                    const name = card.getAttribute('data-category-name') || '';
                    if (!q || name.includes(q)) {
                        card.classList.remove('hidden');
                        visible++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                // toggle clear button
                clearBtn.classList.toggle('hidden', !q);

                // count badge
                if (q) {
                    countBadge.textContent = visible + ' of ' + totalCount;
                } else {
                    countBadge.textContent = totalCount + ' total';
                }

                // no-results message
                noResults.classList.toggle('hidden', visible > 0);
            }

            input.addEventListener('input', filter);

            clearBtn.addEventListener('click', function () {
                input.value = '';
                filter();
                input.focus();
            });

            // Keyboard shortcut: "/" focuses search
            document.addEventListener('keydown', function (e) {
                if (e.key === '/' && document.activeElement !== input) {
                    e.preventDefault();
                    input.focus();
                }
                if (e.key === 'Escape' && document.activeElement === input) {
                    input.value = '';
                    filter();
                    input.blur();
                }
            });
        })();
        </script>
    @endif

@endsection