<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * New accounts are always created as "customer" - admins and
     * receptionists are added by another admin from the admin panel, they
     * can never self-register through this public form.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_CUSTOMER,
        ]);

        Customer::create(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Welcome to '.config('app.salon_name').'! Your account has been created.');
    }
}
