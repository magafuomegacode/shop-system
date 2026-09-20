<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate - field inaitwa "login" (inaweza kuwa username au email)
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = $request->input('login');
        $password   = $request->input('password');
        $remember   = $request->boolean('remember');

        // Tambua kama ni email au username
        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $loginField,
            'password' => $password,
        ];

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                LoginLog::record($user->id, $user->username, 'failed');
                return back()->withErrors([
                    'login' => 'Akaunti yako imezimwa.',
                ])->onlyInput('login');
            }

            $user->update(['last_login' => now()]);

            LoginLog::record($user->id, $user->username, 'success');

            ActivityLog::log(
                $user->id, $user->shop_id, $user->store_id,
                'LOGIN', 'auth', 'Ameingia kwenye mfumo'
            );

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Failed - jaribu kutafuta kwa email au username kwa login log
        $user = User::where('email', $loginField)
                    ->orWhere('username', $loginField)
                    ->first();

        LoginLog::record($user?->id, $loginField, 'failed');

        return back()->withErrors([
            'login' => 'Username/Email au password si sahihi.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            LoginLog::record($user->id, $user->username, 'logout');
            ActivityLog::log(
                $user->id, $user->shop_id, $user->store_id,
                'LOGOUT', 'auth', 'Ametoka kwenye mfumo'
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}