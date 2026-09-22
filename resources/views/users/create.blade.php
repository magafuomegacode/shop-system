@extends('layouts.app')

@section('title', 'Users')

@section('content')

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-stone-600/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-white text-xl sm:text-2xl font-bold">Users</h2>
                <p class="text-white text-xs sm:text-sm mt-1">
                    @if(auth()->user()->isAdmin())
                        Manage all owners and cashiers
                    @else
                        Manage your cashiers
                    @endif
                </p>
            </div>
            <a href="{{ route('users.create') }}"
               class="flex-shrink-0 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Add</span>
            </a>
        </div>
    </div>

    {{-- NEW USER CREDENTIALS — DARK BLUE --}}
    @if(session('new_credentials'))
        @php $creds = session('new_credentials'); @endphp
        <div class="fade-in-up d-2 bg-blue-900/60 border-2 border-blue-500/60 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl shadow-blue-500/30">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-500/30 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/30 border-2 border-blue-400/60 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">User Created Successfully!</h3>
                        <p class="text-blue-200 text-xs">Save these credentials — password won't be shown again</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-blue-950/60 backdrop-blur rounded-xl p-3 border border-blue-500/40">
                        <p class="text-blue-300 text-xs font-semibold uppercase tracking-wide">Full Name</p>
                        <p class="text-white font-bold mt-1">{{ $creds['full_name'] }}</p>
                    </div>
                    <div class="bg-blue-950/60 backdrop-blur rounded-xl p-3 border border-blue-500/40">
                        <p class="text-blue-300 text-xs font-semibold uppercase tracking-wide">Role</p>
                        <p class="text-white font-bold mt-1 capitalize">{{ $creds['role'] }}</p>
                    </div>
                    <div class="bg-blue-950/60 backdrop-blur rounded-xl p-3 border border-blue-500/40">
                        <p class="text-blue-300 text-xs font-semibold uppercase tracking-wide">Username</p>
                        <div class="flex items-center justify-between gap-2 mt-1">
                            <p class="text-white font-mono font-bold text-lg">{{ $creds['username'] }}</p>
                            <button type="button" onclick="copyText(this, '{{ $creds['username'] }}')"
                                    class="bg-lime-500/20 hover:bg-lime-500/30 border border-lime-500/40 text-lime-300 hover:text-lime-200 text-xs font-bold px-3 py-1.5 rounded-lg transition">
                                Copy
                            </button>
                        </div>
                    </div>
                    <div class="bg-blue-950/60 backdrop-blur rounded-xl p-3 border border-blue-500/40">
                        <p class="text-blue-300 text-xs font-semibold uppercase tracking-wide">Password</p>
                        <div class="flex items-center justify-between gap-2 mt-1">
                            <p class="text-white font-mono font-bold text-lg tracking-widest">{{ $creds['password'] }}</p>
                            <button type="button" onclick="copyText(this, '{{ $creds['password'] }}')"
                                    class="bg-lime-500/20 hover:bg-lime-500/30 border border-lime-500/40 text-lime-300 hover:text-lime-200 text-xs font-bold px-3 py-1.5 rounded-lg transition">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-4 bg-yellow-500/20 border border-yellow-500/40 rounded-xl px-4 py-3 flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 text-yellow-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-white text-xs font-medium">
                        <strong>Muhimu:</strong> Share these credentials with the user. The password cannot be displayed again.
                    </p>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            function copyText(btn, text) {
                navigator.clipboard.writeText(text).then(() => {
                    const original = btn.textContent;
                    btn.textContent = 'Copied!';
                    btn.classList.add('bg-green-500/30', 'border-green-500/40', 'text-green-200');
                    setTimeout(() => {
                        btn.textContent = original;
                        btn.classList.remove('bg-green-500/30', 'border-green-500/40', 'text-green-200');
                    }, 1500);
                });
            }
        </script>
        @endpush
    @endif

    {{-- Success message --}}
    @if(session('success'))
        <div class="fade-in-up d-2 bg-green-500/20 border border-green-500/40 text-white px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2 font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Users list --}}
    @if($users->count() > 0)
        <div class="space-y-3">
            @foreach($users as $user)
                <div class="fade-in-up bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 hover:bg-stone-700/60 transition shadow-2xl border border-stone-600/50">
                    <div class="flex items-center gap-3">

                        {{-- Avatar --}}
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg">
                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-white font-semibold text-sm truncate">{{ $user->full_name }}</p>

                                @if($user->isAdmin())
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/30 text-white border border-purple-400/40 font-semibold uppercase">Admin</span>
                                @elseif($user->isOwner())
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-500/30 text-white border border-blue-400/40 font-semibold uppercase">Owner</span>
                                @else
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-lime-500/20 text-white border border-lime-500/40 font-semibold uppercase">Cashier</span>
                                @endif

                                @if(!$user->is_active)
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-500/30 text-white border border-red-400/40 font-semibold uppercase">Blocked</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-xs text-white mt-1 font-medium">
                                <span class="truncate">@<span>{{ $user->username }}</span></span>
                                @if($user->isCashier())
                                    <span class="hidden sm:inline">· All stores</span>
                                @elseif($user->store)
                                    <span class="hidden sm:inline">· {{ $user->store->name }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <a href="{{ route('users.show', $user) }}"
                               class="w-8 h-8 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                               title="View">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('users.edit', $user) }}"
                               class="w-8 h-8 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                               title="Edit">
                                <svg class="w-4 h-4 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('users.toggle-active', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button class="w-8 h-8 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                                        title="{{ $user->is_active ? 'Block' : 'Unblock' }}">
                                    @if($user->is_active)
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @else
        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-10 text-center shadow-2xl border border-stone-600/50">
            <svg class="w-16 h-16 text-white/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-white text-sm font-medium">No users yet</p>
            <a href="{{ route('users.create') }}" class="inline-block mt-4 text-lime-400 hover:text-lime-300 text-sm font-bold">
                Add your first user →
            </a>
        </div>
    @endif

@endsection