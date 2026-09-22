<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Mail\UserCredentialsMail;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users in the shop.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $users = User::where('shop_id', $user->shop_id)
                ->with(['store', 'creator'])
                ->orderByDesc('created_at')
                ->paginate(15);
        } else {
            $users = User::where('shop_id', $user->shop_id)
                ->where('role', 'cashier')
                ->with(['store', 'creator'])
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $user = Auth::user();
        $canCreateRoles = $user->isAdmin() ? ['owner'] : ['cashier'];

        return view('users.create', compact('canCreateRoles'));
    }

    /**
     * Store a newly created user and email credentials.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $allowedRole = $currentUser->isAdmin() ? 'owner' : 'cashier';

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'phone'     => ['nullable', 'string', 'max:20'],
        ]);

        $tempUsername = 'temp_' . Str::random(10);
        $plainPassword = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'shop_id'    => $currentUser->shop_id,
            'store_id'   => null,
            'full_name'  => $validated['full_name'],
            'username'   => $tempUsername,
            'email'      => $validated['email'] ?? null,
            'phone'      => $validated['phone'] ?? null,
            'password'   => Hash::make($plainPassword),
            'role'       => $allowedRole,
            'is_active'  => true,
            'created_by' => $currentUser->id,
        ]);

        // Build official username: first 5 letters of name + ID
        $cleanName = preg_replace('/[^A-Za-z]/', '', $validated['full_name']);
        $prefix = strtolower(substr($cleanName, 0, 5));
        $username = $prefix . $user->id;

        while (User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
            $username = $prefix . $user->id . random_int(1, 99);
        }

        $user->update(['username' => $username]);

        // ===== Tuma email kwa user =====
        $emailStatus = 'no email provided';
        if ($user->email) {
            try {
                $settings = [
                    'system_name' => Setting::get($currentUser->shop_id, 'system_name', 'Duka System'),
                    'phone'       => Setting::get($currentUser->shop_id, 'phone', ''),
                    'email'       => Setting::get($currentUser->shop_id, 'email', ''),
                ];

                Mail::to($user->email)->send(
                    new UserCredentialsMail($user, $plainPassword, $settings)
                );

                $emailStatus = 'email sent to ' . $user->email;
                Log::info("Credentials email sent to: {$user->email}");

            } catch (\Exception $e) {
                $emailStatus = 'user created but email failed';
                Log::error("Failed to send credentials email: " . $e->getMessage());
            }
        }

        // Activity log
        ActivityLog::log(
            $currentUser->id,
            $currentUser->shop_id,
            null,
            'CREATE_USER',
            'users',
            "Created new user: {$user->full_name} ({$user->role}) with username {$username} — {$emailStatus}",
            $user->id,
            'users',
            null,
            ['role' => $user->role, 'username' => $username]
        );

        return redirect()
            ->route('users.index')
            ->with('success', "User {$user->full_name} created successfully! ({$emailStatus})")
            ->with('new_credentials', [
                'username'  => $username,
                'password'  => $plainPassword,
                'full_name' => $user->full_name,
                'role'      => $user->role,
            ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $this->authorizeUser($user);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        $this->authorizeUser($user);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);
        $currentUser = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'username'  => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id), 'alpha_dash'],
            'email'     => ['nullable', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $oldValues = $user->only(['full_name', 'username', 'email', 'phone', 'is_active']);

        $user->update([
            'full_name' => $validated['full_name'],
            'username'  => $validated['username'],
            'email'     => $validated['email'] ?? null,
            'phone'     => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log(
            $currentUser->id, $currentUser->shop_id, null,
            'UPDATE_USER', 'users',
            "Updated user: {$user->full_name}",
            $user->id, 'users',
            $oldValues,
            $user->only(['full_name', 'username', 'email', 'phone', 'is_active'])
        );

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Change a user's password (admin/owner sets a new one).
     */
    public function changePassword(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        ActivityLog::log(
            Auth::id(), Auth::user()->shop_id, null,
            'CHANGE_PASSWORD', 'users',
            "Changed password for: {$user->full_name}",
            $user->id, 'users'
        );

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Reset a user's password to a new random 5-digit code and email it.
     */
    public function resetPassword(User $user)
    {
        $this->authorizeUser($user);
        $currentUser = Auth::user();

        $plainPassword = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        $user->update(['password' => Hash::make($plainPassword)]);

        // ===== Tuma email kwa user =====
        $emailStatus = 'no email provided';
        if ($user->email) {
            try {
                $systemName = Setting::get($currentUser->shop_id, 'system_name', 'Duka System');

                Mail::to($user->email)->send(
                    new PasswordResetMail($user, $plainPassword, $systemName)
                );

                $emailStatus = 'email sent to ' . $user->email;
                Log::info("Password reset email sent to: {$user->email}");

            } catch (\Exception $e) {
                $emailStatus = 'password reset but email failed';
                Log::error("Failed to send password reset email: " . $e->getMessage());
            }
        }

        ActivityLog::log(
            $currentUser->id, $currentUser->shop_id, null,
            'RESET_PASSWORD', 'users',
            "Reset password for: {$user->full_name} — {$emailStatus}",
            $user->id, 'users'
        );

        return back()
            ->with('success', "Password reset for {$user->full_name}! ({$emailStatus})")
            ->with('new_credentials', [
                'username'  => $user->username,
                'password'  => $plainPassword,
                'full_name' => $user->full_name,
                'role'      => $user->role,
            ]);
    }

    /**
     * Block or unblock a user.
     */
    public function toggleActive(User $user)
    {
        $this->authorizeUser($user);
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return back()->withErrors(['error' => 'You cannot block your own account.']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'unblocked' : 'blocked';

        ActivityLog::log(
            $currentUser->id, $currentUser->shop_id, null,
            $user->is_active ? 'UNBLOCK_USER' : 'BLOCK_USER',
            'users',
            "User {$user->full_name} {$status}",
            $user->id, 'users'
        );

        return back()->with('success', "User {$status} successfully!");
    }

    /**
     * Permanently delete the specified user.
     *
     * Admin can delete any user except other admins and themselves.
     * Owner can delete cashiers only.
     */
    public function destroy(User $user)
    {
        // 1. Basic shop check
        $this->authorizeUser($user);
        $currentUser = Auth::user();

        // 2. Cannot delete yourself
        if ($user->id === $currentUser->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        // 3. Admin can delete owners & cashiers (but NOT other admins)
        if ($currentUser->isAdmin()) {
            if ($user->isAdmin()) {
                return back()->withErrors(['error' => 'You cannot delete another Admin.']);
            }
            // Allowed — proceed
        }

        // 4. Owner can delete cashiers only
        elseif ($currentUser->isOwner()) {
            if (!$user->isCashier()) {
                return back()->withErrors(['error' => 'You can only delete Cashiers.']);
            }
            // Allowed — proceed
        }

        // 5. Cashier cannot delete anyone
        else {
            abort(403, 'Unauthorized.');
        }

        // ===== HARD DELETE — Futa kabisa =====
        $name = $user->full_name;
        $role = $user->role;

        // Futa user kabisa
        $user->delete();

        // Activity log — subject_id = null kwa sababu user amefutwa
        ActivityLog::log(
            $currentUser->id,
            $currentUser->shop_id,
            null,
            'DELETE_USER',
            'users',
            "Permanently deleted user: {$name} ({$role})",
            null,
            'users'
        );

        return redirect()
            ->route('users.index')
            ->with('success', "User {$name} has been permanently deleted!");
    }

    /**
     * Check if the current user has permission to manage the target user.
     */
    private function authorizeUser(User $target): void
    {
        $current = Auth::user();

        // Must be in the same shop
        if ($target->shop_id !== $current->shop_id) {
            abort(403, 'Unauthorized.');
        }

        // Admin can manage everyone except other admins (except themselves)
        if ($current->isAdmin()) {
            if ($target->isAdmin() && $target->id !== $current->id) {
                abort(403, 'You cannot manage another Admin.');
            }
            return;
        }

        // Owner can manage cashiers only
        if ($current->isOwner()) {
            if (!$target->isCashier()) {
                abort(403, 'You can only manage Cashiers.');
            }
            return;
        }

        // Cashier cannot manage anyone
        abort(403, 'Unauthorized.');
    }
}