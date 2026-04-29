<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Display the account dashboard overview.
     */
    public function index()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->latest()->take(3)->get();
        return view('account.index', compact('user', 'recentOrders'));
    }

    /**
     * Display the user's profile information form.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female,other'],
            'birthday' => ['nullable', 'date'],
        ]);

        $user->update($request->only('full_name', 'phone', 'gender', 'birthday'));

        return redirect()->route('account.profile')->with('status', 'profile-updated');
    }

    /**
     * Display the user's addresses.
     */
    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->latest('is_default')->latest()->get();
        return view('account.addresses.index', compact('user', 'addresses'));
    }

    public function createAddress()
    {
        return view('account.addresses.create');
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->has('is_default') ? true : false;

        if ($isDefault || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $isDefault = true;
        }

        $validated['is_default'] = $isDefault;

        $user->addresses()->create($validated);

        return redirect()->route('account.addresses')->with('status', 'address-added');
    }

    public function editAddress(\App\Models\Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);
        return view('account.addresses.edit', compact('address'));
    }

    public function updateAddress(Request $request, \App\Models\Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->has('is_default') ? true : false;
        
        if ($isDefault) {
            Auth::user()->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        } else {
            // Cannot unset default if it's the only one
            if ($address->is_default && Auth::user()->addresses()->count() > 1) {
                $validated['is_default'] = false;
                // Make the latest added address default
                /** @var \App\Models\Address|null $latest */
                $latest = Auth::user()->addresses()->where('id', '!=', $address->id)->latest()->first();
                if($latest) $latest->update(['is_default' => true]);
            } else {
                $validated['is_default'] = $address->is_default;
            }
        }

        $address->update($validated);

        return redirect()->route('account.addresses')->with('status', 'address-updated');
    }

    public function destroyAddress(\App\Models\Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault && Auth::user()->addresses()->count() > 0) {
            /** @var \App\Models\Address|null $latest */
            $latest = Auth::user()->addresses()->latest()->first();
            if ($latest) $latest->update(['is_default' => true]);
        }

        return redirect()->route('account.addresses')->with('status', 'address-deleted');
    }

    public function setDefaultAddress(\App\Models\Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('status', 'address-updated');
    }

    /**
     * Display the password update form.
     */
    public function password()
    {
        return view('account.password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
