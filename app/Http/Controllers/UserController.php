<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Admin sees everyone; Owner sees only cashiers
        if ($user->isAdmin()) {
            $users = User::where('shop_id', $user->shop_id)
                ->with(['store', 'creator'])
                ->orderByDesc('created_at')
                ->paginate(15);
        } else {
            // Owner
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
     *
     * Username is auto-generated: first 5 letters of name + user ID.
     * Password is auto-generated: 5 random digits.
     */
    public function create()
    {
        $user = Auth::user();

        // Which roles can this user create
        $canCreateRoles = $user->isAdmin() ? ['owner'] : ['cashier'];

        return view('users.create', compact('canCreateRoles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        // Role this user can create
        $allowedRole = $currentUser->isAdmin() ? 'owner' : 'cashier';

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'phone'     => ['nullable', 'string', 'max:20'],
        ]);

        // ===== 1. Temporary username (replaced after we know the ID) =====
        $tempUsername = 'temp_' . Str::random(10);

        // ===== 2. Auto-generate password: 5 random digits =====
        $plainPassword = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        // ===== 3. Create the user (no store_id — cashiers access all stores) =====
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

        // ===== 4. Build official username: first 5 letters of name + ID =====
        $cleanName = preg_replace('/[^A-Za-z]/', '', $validated['full_name']);
        $prefix = strtolower(substr($cleanName, 0, 5));
        $username = $prefix . $user->id;

        // Ensure uniqueness (edge case)
        while (User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
            $username = $prefix . $user->id . random_int(1, 99);
        }

        // ===== 5. Save the final username =====
        $user->update(['username' => $username]);

        // ===== 6. Activity log =====
        ActivityLog::log(
            $currentUser->id,
            $currentUser->shop_id,
            null,
            'CREATE_USER',
            'users',
            "Created new user: {$user->full_name} ({$user->role}) with username {$username}",
            $user->id,
            'users',
            null,
            ['role' => $user->role, 'username' => $username]
        );

        // ===== 7. Show credentials once =====
        return redirect()
            ->route('users.index')
            ->with('success', "User {$user->full_name} created successfully!")
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
     * Change a user's password.
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
     * Reset a user's password to a new random 5-digit code.
     */
    public function resetPassword(User $user)
    {
        $this->authorizeUser($user);
        $currentUser = Auth::user();

        // Generate new 5-digit password
        $plainPassword = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        $user->update(['password' => Hash::make($plainPassword)]);

        ActivityLog::log(
            $currentUser->id, $currentUser->shop_id, null,
            'RESET_PASSWORD', 'users',
            "Reset password for: {$user->full_name}",
            $user->id, 'users'
        );

        return back()
            ->with('success', "Password reset for {$user->full_name}!")
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
     * Remove (soft-delete: block only) the specified user.
     */
    public function destroy(User $user)
    {
        $this->authorizeUser($user);
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $name = $user->full_name;
        $user->update(['is_active' => false]);

        ActivityLog::log(
            $currentUser->id, $currentUser->shop_id, null,
            'DELETE_USER', 'users',
            "Deleted (blocked) user: {$name}",
            $user->id, 'users'
        );

        return redirect()->route('users.index')->with('success', "User {$name} deleted!");
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

        // Admin can manage Owners, but not other Admins
        if ($current->isAdmin()) {
            if ($target->isAdmin() && $target->id !== $current->id) {
                abort(403, 'You cannot manage another Admin.');
            }
            return;
        }

        // Owner can manage Cashiers only
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