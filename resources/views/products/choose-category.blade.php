@extends('layouts.app')

@section('title', 'Choose Category')

@section('content')
    <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border border-blue-700/50">
        <h2 class="text-white text-xl font-bold">Choose a Category</h2>
        <p class="text-blue-200 text-sm mt-1">
            Adding product to <span class="text-cyan-300 font-semibold">{{ $store->name }}</span>
        </p>
    </div>

    @if($categories->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('products.create', ['store_id' => $store->id, 'category_id' => $category->id]) }}"
                   class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 hover:bg-blue-800/50 transition shadow-2xl border border-blue-700/50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-semibold text-sm truncate">{{ $category->name }}</p>
                        @if($category->description ?? false)
                            <p class="text-blue-200 text-xs truncate">{{ $category->description }}</p>
                        @endif
                    </div>
                    <svg class="w-5 h-5 text-cyan-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-10 text-center shadow-2xl border border-blue-700/50">
            <p class="text-blue-100 text-sm font-medium">No categories available</p>
            <a href="{{ route('categories.create') ?? '#' }}" class="inline-block mt-4 text-cyan-400 hover:text-cyan-300 text-sm font-semibold">
                Create a category →
            </a>
        </div>
    @endif
@endsection