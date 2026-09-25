@extends('layouts.app')

@section('title', 'Add User')

@section('content')

    {{-- Back link --}}
    <a href="{{ route('users.index') }}"
       class="inline-flex items-center gap-2 text-cyan-400 hover:text-cyan-300 text-sm mb-4 fade-in-up d-1 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Users
    </a>

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-white text-xl sm:text-2xl font-bold">Add New User</h2>
            <p class="text-blue-200 text-xs sm:text-sm mt-1">
                @if(auth()->user()->isAdmin())
                    You are creating a new <span class="text-cyan-400 font-semibold">Owner</span>
                @else
                    You are creating a new <span class="text-cyan-400 font-semibold">Cashier</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Info banner: dark blue --}}
    <div class="fade-in-up d-2 bg-blue-900/60 border-2 border-blue-500/60 text-white px-4 py-4 rounded-xl mb-6 text-xs sm:text-sm flex items-start gap-3 shadow-2xl shadow-blue-500/30">
        <svg class="w-6 h-6 flex-shrink-0 mt-0.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="font-bold text-white text-sm">Username & password will be auto-generated</p>
            <p class="text-white mt-1 font-medium">
                Username: <span class="text-cyan-400 font-semibold">first 5 letters of name + ID</span> ·
                Password: <span class="text-cyan-400 font-semibold">5 random digits</span>
            </p>
            <p class="text-blue-200 mt-2 font-semibold flex items-center gap-1">
                <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Credentials will be sent to the user's email (if provided).
            </p>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="fade-in-up d-2 bg-red-500/20 border border-red-500/40 text-white px-4 py-3 rounded-xl mb-6 text-sm">
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

    {{-- Form --}}
    <form method="POST" action="{{ route('users.store') }}"
          class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xl border border-blue-700/50">
        @csrf

        {{-- Role display (read-only) --}}
        <div>
            <label class="block text-xs font-semibold text-white mb-2">Role</label>
            <div class="flex items-center gap-2 bg-blue-900/40 backdrop-blur border border-blue-700/50 rounded-xl px-4 py-3">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <span class="text-white text-sm font-semibold capitalize">
                    {{ auth()->user()->isAdmin() ? 'Owner' : 'Cashier' }}
                </span>
            </div>
        </div>

        {{-- Full Name --}}
        <div>
            <label for="full_name" class="block text-xs font-semibold text-white mb-2">
                Full Name <span class="text-red-400">*</span>
            </label>
            <input type="text" name="full_name" id="full_name"
                   value="{{ old('full_name') }}"
                   required
                   placeholder="e.g. Asha Juma"
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            <p class="text-blue-200/70 text-xs mt-1">
                Username will be generated from this name (first 5 letters)
            </p>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-white mb-2">
                Email
                <span class="text-cyan-400 font-normal">(recommended — credentials will be sent here)</span>
            </label>
            <input type="email" name="email" id="email"
                   value="{{ old('email') }}"
                   placeholder="e.g. asha@shop.co.tz"
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            <p class="text-blue-200/70 text-xs mt-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Credentials (username & password) will be sent to this email.
            </p>
        </div>

        {{-- Phone --}}
        <div>
            <label for="phone" class="block text-xs font-semibold text-white mb-2">Phone (optional)</label>
            <input type="tel" name="phone" id="phone"
                   value="{{ old('phone') }}"
                   placeholder="e.g. 0712345678"
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
        </div>

        {{-- Info: Cashier has access to all stores --}}
        @if(auth()->user()->isOwner())
            <div class="bg-green-500/20 border border-green-500/40 text-white px-4 py-3 rounded-xl text-xs sm:text-sm flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-semibold text-white">Cashier access</p>
                    <p class="text-white/80 mt-0.5">This cashier will be able to sell from <strong>all stores</strong> in your shop.</p>
                </div>
            </div>
        @endif

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-3 rounded-xl transition shadow-lg">
                Create User
            </button>
            <a href="{{ route('users.index') }}"
               class="px-5 py-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white text-sm font-bold transition">
                Cancel
            </a>
        </div>

    </form>

@endsection