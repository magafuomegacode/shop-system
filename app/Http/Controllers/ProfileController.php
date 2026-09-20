<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show profile page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update profile info.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email'     => ['nullable', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'     => ['nullable', 'string', 'max:30'],
        ]);

        $user->update([
            'full_name' => $validated['full_name'],
            'email'     => $validated['email'] ?? null,
            'phone'     => $validated['phone'] ?? null,
        ]);

        ActivityLog::log(
            $user->id, $user->shop_id, null,
            'UPDATE_PROFILE', 'users',
            "Updated own profile"
        );

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        ActivityLog::log(
            $user->id, $user->shop_id, null,
            'CHANGE_PASSWORD', 'users',
            "Changed own password"
        );

        return back()->with('success', 'Password changed successfully!');
    }
}