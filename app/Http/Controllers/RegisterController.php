<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:farmer,driver,transport_owner,admin'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'email' => $data['email'],
            'role' => $data['role'],
            'name' => explode('@', $data['email'])[0],
            'is_active' => true,
            'password' => Hash::make($data['password']),
        ]);

        \Illuminate\Support\Facades\Auth::login($user);
        $request->session()->regenerate();

        $route = match ($user->role) {
            'farmer' => 'farmer.dashboard',
            'driver' => 'driver.dashboard',
            'transport_owner' => 'owner.dashboard',
            'admin' => 'admin.dashboard',
            default => 'home',
        };

        return redirect()->route($route)->with('status', 'Account created and logged in successfully.');
    }
}
