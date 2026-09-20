<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Display a listing of stores.
     */
    public function index()
    {
        $user = Auth::user();

        $stores = Store::where('shop_id', $user->shop_id)
            ->withCount('products')
            ->orderBy('name')
            ->paginate(15);

        return view('stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store a newly created store.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'type'      => ['nullable', 'string', 'max:50'],
            'location'  => ['nullable', 'string', 'max:200'],
            'is_active' => ['boolean'],
        ]);

        $store = Store::create([
            'shop_id'   => $user->shop_id,
            'name'      => $validated['name'],
            'type'      => $validated['type'] ?? null,
            'location'  => $validated['location'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log(
            $user->id, $user->shop_id, $store->id,
            'CREATE_STORE', 'stores',
            "Created store: {$store->name}",
            $store->id, 'stores',
            null,
            ['name' => $store->name, 'type' => $store->type]
        );

        return redirect()
            ->route('stores.index')
            ->with('success', "Store '{$store->name}' created successfully!");
    }

    /**
     * Display the specified store.
     */
    public function show(Store $store)
    {
        $this->authorizeStore($store);

        $store->loadCount('products');

        return view('stores.show', compact('store'));
    }

    /**
     * Show the form for editing a store.
     */
    public function edit(Store $store)
    {
        $this->authorizeStore($store);

        return view('stores.edit', compact('store'));
    }

    /**
     * Update the specified store.
     */
    public function update(Request $request, Store $store)
    {
        $this->authorizeStore($store);
        $user = Auth::user();

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'type'      => ['nullable', 'string', 'max:50'],
            'location'  => ['nullable', 'string', 'max:200'],
            'is_active' => ['boolean'],
        ]);

        $oldValues = $store->only(['name', 'type', 'location', 'is_active']);

        $store->update([
            'name'      => $validated['name'],
            'type'      => $validated['type'] ?? null,
            'location'  => $validated['location'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log(
            $user->id, $user->shop_id, $store->id,
            'UPDATE_STORE', 'stores',
            "Updated store: {$store->name}",
            $store->id, 'stores',
            $oldValues,
            $store->only(['name', 'type', 'location', 'is_active'])
        );

        return redirect()
            ->route('stores.index')
            ->with('success', "Store '{$store->name}' updated successfully!");
    }

    /**
     * Remove the specified store.
     */
    public function destroy(Store $store)
    {
        $this->authorizeStore($store);
        $user = Auth::user();

        // ✅ Only Admin & Owner can delete
        if (!$user->isAdmin() && !$user->isOwner()) {
            abort(403, 'Only Admin or Owner can delete stores.');
        }

        // Prevent delete if store has products
        if ($store->products()->count() > 0) {
            return back()->withErrors([
                'error' => "Cannot delete '{$store->name}'. It has {$store->products()->count()} product(s) assigned to it."
            ]);
        }

        $name = $store->name;
        $store->delete();

        ActivityLog::log(
            $user->id, $user->shop_id, null,
            'DELETE_STORE', 'stores',
            "Deleted store: {$name}",
            null, 'stores'
        );

        return redirect()
            ->route('stores.index')
            ->with('success', "Store '{$name}' deleted successfully!");
    }

    /**
     * Toggle store active status.
     */
    public function toggleActive(Store $store)
    {
        $this->authorizeStore($store);
        $user = Auth::user();

        $store->update(['is_active' => !$store->is_active]);
        $status = $store->is_active ? 'activated' : 'deactivated';

        ActivityLog::log(
            $user->id, $user->shop_id, $store->id,
            $store->is_active ? 'ACTIVATE_STORE' : 'DEACTIVATE_STORE',
            'stores',
            "Store {$store->name} {$status}",
            $store->id, 'stores'
        );

        return back()->with('success', "Store '{$store->name}' {$status}!");
    }

    /**
     * Ensure store belongs to same shop as user.
     */
    private function authorizeStore(Store $store): void
    {
        if ($store->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Unauthorized.');
        }
    }
}