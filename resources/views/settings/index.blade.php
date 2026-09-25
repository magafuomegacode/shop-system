@extends('layouts.app')

@section('title', 'Settings')

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
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-xl sm:text-2xl font-bold truncate">System Settings</h2>
                <p class="text-blue-200 text-xs sm:text-sm mt-0.5">
                    Update your shop name, phone, and other preferences
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

    <form method="POST" action="{{ route('settings.update') }}"
          class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 space-y-6 shadow-2xl border border-blue-700/50">
        @csrf
        @method('PUT')

        {{-- Section: Shop Identity --}}
        <div>
            <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
                Shop Identity
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="system_name" class="block text-xs font-semibold text-blue-200 mb-2">
                        System / Shop Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="system_name" id="system_name"
                           value="{{ old('system_name', $settings['system_name']) }}"
                           required placeholder="e.g. Duka System"
                           class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-blue-200 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone"
                               value="{{ old('phone', $settings['phone']) }}"
                               placeholder="e.g. 0712345678"
                               class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-blue-200 mb-2">Email</label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $settings['email']) }}"
                               placeholder="e.g. shop@example.co.tz"
                               class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-semibold text-blue-200 mb-2">Address</label>
                    <input type="text" name="address" id="address"
                           value="{{ old('address', $settings['address']) }}"
                           placeholder="e.g. Kariakoo, Dar es Salaam"
                           class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
            </div>
        </div>

        {{-- Section: Currency --}}
        <div class="border-t border-blue-700/50 pt-6">
            <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Currency
            </h3>

            <div>
                <label for="currency" class="block text-xs font-semibold text-blue-200 mb-2">
                    Currency Symbol <span class="text-red-400">*</span>
                </label>
                <input type="text" name="currency" id="currency"
                       value="{{ old('currency', $settings['currency']) }}"
                       required placeholder="e.g. TSh, KSh, USD"
                       class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                <p class="text-blue-300/70 text-xs mt-1">Used in all prices across the system</p>
            </div>
        </div>

        {{-- Section: Receipt --}}
        <div class="border-t border-blue-700/50 pt-6">
            <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Receipt
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="receipt_header" class="block text-xs font-semibold text-blue-200 mb-2">Receipt Header</label>
                    <input type="text" name="receipt_header" id="receipt_header"
                           value="{{ old('receipt_header', $settings['receipt_header']) }}"
                           placeholder="e.g. Asante kwa kununua!"
                           class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>

                <div>
                    <label for="receipt_footer" class="block text-xs font-semibold text-blue-200 mb-2">Receipt Footer</label>
                    <input type="text" name="receipt_footer" id="receipt_footer"
                           value="{{ old('receipt_footer', $settings['receipt_footer']) }}"
                           placeholder="e.g. Karibu tena!"
                           class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
            </div>
        </div>

        {{-- Section: Discount --}}
        <div class="border-t border-blue-700/50 pt-6">
            <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Discount Settings
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="discount_max_percent" class="block text-xs font-semibold text-blue-200 mb-2">
                        Max Discount (%)
                    </label>
                    <input type="number" name="discount_max_percent" id="discount_max_percent"
                           value="{{ old('discount_max_percent', $settings['discount_max_percent']) }}"
                           min="0" max="100" step="1" placeholder="20"
                           class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white placeholder-blue-200/50 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                    <p class="text-blue-300/70 text-xs mt-1">Cashiers cannot apply discounts above this percentage</p>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="discount_allowed" id="discount_allowed" value="1"
                           {{ old('discount_allowed', $settings['discount_allowed']) == '1' ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-blue-700 bg-blue-900/40 text-cyan-500 focus:ring-cyan-400/50">
                    <label for="discount_allowed" class="text-sm text-blue-100">Allow discounts at POS</label>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-4 border-t border-blue-700/50">
            <button type="submit"
                    class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                Save Settings
            </button>
            <a href="{{ route('dashboard') }}"
               class="px-5 py-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-blue-100 text-sm font-medium transition">
                Cancel
            </a>
        </div>

    </form>

@endsection