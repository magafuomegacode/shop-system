<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $user = Auth::user();

        // 🔒 Only Admin and Owner can access
        if (!$user->isAdmin() && !$user->isOwner()) {
            abort(403, 'Only Admin or Owner can access settings.');
        }

        $shopId = $user->shop_id;

        $settings = [
            'system_name'    => Setting::get($shopId, 'system_name', config('app.name', 'Duka System')),
            'phone'          => Setting::get($shopId, 'phone', ''),
            'email'          => Setting::get($shopId, 'email', ''),
            'address'        => Setting::get($shopId, 'address', ''),
            'currency'       => Setting::get($shopId, 'currency', 'TSh'),
            'receipt_header' => Setting::get($shopId, 'receipt_header', 'Asante kwa kununua!'),
            'receipt_footer' => Setting::get($shopId, 'receipt_footer', ''),
            'discount_max_percent' => Setting::get($shopId, 'discount_max_percent', 20),
            'discount_allowed'     => Setting::get($shopId, 'discount_allowed', '1'),
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOwner()) {
            abort(403, 'Only Admin or Owner can update settings.');
        }

        $validated = $request->validate([
            'system_name'    => ['required', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'email'          => ['nullable', 'email', 'max:100'],
            'address'        => ['nullable', 'string', 'max:200'],
            'currency'       => ['required', 'string', 'max:10'],
            'receipt_header' => ['nullable', 'string', 'max:200'],
            'receipt_footer' => ['nullable', 'string', 'max:200'],
            'discount_max_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_allowed'     => ['nullable', 'boolean'],
        ]);

        Setting::setMany($user->shop_id, [
            'system_name'    => $validated['system_name'],
            'phone'          => $validated['phone'] ?? '',
            'email'          => $validated['email'] ?? '',
            'address'        => $validated['address'] ?? '',
            'currency'       => $validated['currency'],
            'receipt_header' => $validated['receipt_header'] ?? '',
            'receipt_footer' => $validated['receipt_footer'] ?? '',
            'discount_max_percent' => $validated['discount_max_percent'] ?? 20,
            'discount_allowed'     => $request->boolean('discount_allowed') ? '1' : '0',
        ]);

        ActivityLog::log(
            $user->id, $user->shop_id, null,
            'UPDATE_SETTINGS', 'settings',
            "Updated system settings (name: {$validated['system_name']})"
        );

        return back()->with('success', 'Settings updated successfully!');
    }
}