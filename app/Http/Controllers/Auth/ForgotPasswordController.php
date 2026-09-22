<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Send a new password to the user's email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Kama user haipo — usiambie mtumiaji (usalama)
        if (!$user) {
            return back()->with('status', 'If your email is registered, you will receive a new password.');
        }

        // Generate password mpya (5-digit)
        $plainPassword = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        $user->update(['password' => Hash::make($plainPassword)]);

        // Tuma email
        try {
            $systemName = Setting::get($user->shop_id, 'system_name', 'Duka System');

            Mail::to($user->email)->send(
                new PasswordResetMail($user, $plainPassword, $systemName)
            );

            Log::info("Password reset email sent to: {$user->email}");

        } catch (\Exception $e) {
            Log::error("Failed to send password reset: " . $e->getMessage());
        }

        return back()->with('status', 'If your email is registered, you will receive a new password.');
    }
}