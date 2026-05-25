<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\OtpVerification;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:farmer,driver,transport_owner,admin'],
        ]);

        $rateKey = Str::lower($data['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in '.RateLimiter::availableIn($rateKey).' seconds.',
            ]);
        }

        $user = User::query()
            ->where('email', $data['email'])
            ->orWhere('phone', $data['email'])
            ->first();

        if (! $user || ! Auth::attempt(['email' => $user->email, 'password' => $data['password']], $request->boolean('remember'))) {
            RateLimiter::hit($rateKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Invalid login credentials.',
            ]);
        }

        if ($user->role !== $data['role']) {
            Auth::logout();

            throw ValidationException::withMessages([
                'role' => 'Selected role does not match this account.',
            ]);
        }

        if (! $user->is_active) {
            Auth::logout();
            $this->sendLoginOtp($user);

            return redirect()
                ->route('otp.show', $user)
                ->with('status', 'Please verify your email. We sent a fresh OTP to your inbox.');
        }

        RateLimiter::clear($rateKey);
        $request->session()->regenerate();

        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
            'login_time' => now(),
        ]);

        $redirectUrl = $request->input('redirect_to');

        if ($redirectUrl && $this->isSafeRedirect($redirectUrl)) {
            return redirect($redirectUrl);
        }

        return redirect()->intended($this->dashboardUrl($user->role));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Logged out securely.');
    }

    private function dashboardUrl(string $role): string
    {
        return match ($role) {
            'farmer' => route('farmer.dashboard'),
            'driver' => route('driver.dashboard'),
            'transport_owner' => route('owner.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('home'),
        };
    }

    private function isSafeRedirect(?string $redirect): bool
    {
        if (! $redirect) {
            return false;
        }

        return str_starts_with($redirect, '/')
            && ! str_starts_with($redirect, '//')
            && ! str_contains($redirect, '://');
    }

    private function sendLoginOtp(User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        OtpVerification::where('user_id', $user->id)
            ->where('verified', false)
            ->update(['verified' => true]);

        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
