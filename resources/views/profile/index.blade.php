@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-2 text-blue-300 hover:text-white text-sm mb-4 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Dashboard
    </a>

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg">
                {{ strtoupper(substr($user->full_name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-xl sm:text-2xl font-bold truncate">{{ $user->full_name }}</h2>
                <p class="text-blue-200 text-xs sm:text-sm mt-0.5 capitalize">
                    {{ $user->role }} · @<span>{{ $user->username }}</span>
                </p>
            </div>
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
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    {{-- ===== Personal Information ===== --}}
    <form method="POST" action="{{ route('profile.update') }}"
          class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xl border border-blue-700/50 mb-4">
        @csrf
        @method('PUT')

        <h3 class="text-white font-bold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Personal Information
        </h3>

        <div>
            <label for="full_name" class="block text-xs font-semibold text-blue-200 mb-2">
                Full Name <span class="text-red-400">*</span>
            </label>
            <input type="text" name="full_name" id="full_name"
                   value="{{ old('full_name', $user->full_name) }}" required
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="email" class="block text-xs font-semibold text-blue-200 mb-2">Email</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}"
                       placeholder="e.g. jina@example.com"
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-blue-200 mb-2">Phone</label>
                <input type="tel" name="phone" id="phone"
                       value="{{ old('phone', $user->phone) }}"
                       placeholder="e.g. 0712345678"
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-blue-200 mb-2">Username (read-only)</label>
            <div class="flex items-center gap-2 bg-blue-900/30 border border-blue-700/30 rounded-xl px-4 py-3">
                <span class="text-blue-400 text-sm">@</span>
                <span class="text-white font-mono font-semibold">{{ $user->username }}</span>
            </div>
            <p class="text-blue-300/70 text-xs mt-1">Username cannot be changed</p>
        </div>

        <div class="flex items-center gap-3 pt-3 border-t border-blue-700/50">
            <button type="submit"
                    class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                Save Changes
            </button>
        </div>
    </form>

    {{-- ===== Change Password (same page) ===== --}}
    <form method="POST" action="{{ route('profile.change-password') }}"
          class="fade-in-up d-3 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xl border border-blue-700/50">
        @csrf
        @method('PUT')

        <h3 class="text-white font-bold text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Change Password
        </h3>

        <div>
            <label for="current_password" class="block text-xs font-semibold text-blue-200 mb-2">
                Current Password <span class="text-red-400">*</span>
            </label>
            <input type="password" name="current_password" id="current_password" required
                   autocomplete="current-password"
                   class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="new_password" class="block text-xs font-semibold text-blue-200 mb-2">
                    New Password <span class="text-red-400">*</span>
                </label>
                <input type="password" name="new_password" id="new_password" required
                       minlength="5" autocomplete="new-password"
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                <p class="text-blue-300/70 text-xs mt-1">At least 5 characters</p>
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-xs font-semibold text-blue-200 mb-2">
                    Confirm New Password <span class="text-red-400">*</span>
                </label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                       minlength="5" autocomplete="new-password"
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-3 border-t border-blue-700/50">
            <button type="submit"
                    class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                Change Password
            </button>
        </div>
    </form>

@endsection