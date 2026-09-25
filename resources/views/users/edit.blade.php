@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

    <a href="{{ route('users.index') }}"
       class="inline-flex items-center gap-2 text-blue-300 hover:text-white text-sm mb-4 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>

    <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 shadow-2xl border border-blue-700/50">
        <h2 class="text-white text-xl font-bold mb-1">Edit User</h2>
        <p class="text-blue-200 text-sm mb-6">{{ $user->full_name }} · {{ ucfirst($user->role) }}</p>

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/40 text-red-200 px-4 py-3 rounded-xl mb-5 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-blue-200 mb-2">Full Name</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-blue-200 mb-2">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-blue-200 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-blue-200 mb-2">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>

            {{-- Info: Cashier has access to all stores --}}
            @if($user->isCashier())
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-100">Cashier access</p>
                        <p class="text-green-200/80 mt-0.5">This cashier can sell from <strong>all stores</strong> in the shop.</p>
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ $user->is_active ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-blue-700 bg-blue-900/40 text-cyan-500 focus:ring-cyan-400/50">
                <label for="is_active" class="text-sm text-blue-100">Active account</label>
            </div>

            <div class="flex gap-3 pt-3">
                <button type="submit"
                        class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                    Save Changes
                </button>
                <a href="{{ route('users.index') }}"
                   class="px-5 py-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-blue-100 text-sm font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection