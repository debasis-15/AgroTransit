<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Models\User;
use App\Models\LoginLog;
use App\Mail\OtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function show(User $user): View
    {
        return view('auth.verify-otp', compact('user'));
    }

    public function verify(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $verification = OtpVerification::query()
            ->where('user_id', $user->id)
            ->where('verified', false)
            ->latest()
            ->first();

        if (! $verification || $verification->expires_at->isPast() || ! Hash::check($data['otp'], $verification->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $verification->update(['verified' => true]);
        $user->update(['is_active' => true]);

        Auth::login($user);
        $request->session()->regenerate();

        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
            'login_time' => now(),
        ]);

        $dashboardRoute = match ($user->role) {
            'farmer' => 'farmer.dashboard',
            'driver' => 'driver.dashboard',
            'transport_owner' => 'owner.dashboard',
            'admin' => 'admin.dashboard',
            default => 'home',
        };

        return redirect()->intended(route($dashboardRoute))->with('status', 'Email verified and logged in successfully!');
    }

    public function resend(User $user): RedirectResponse
    {
        $otp = (string) random_int(100000, 999999);

        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            logger()->error('Failed to send resend OTP email: ' . $e->getMessage());
        }

        return back()->with('status', 'A new OTP has been sent. Demo OTP: '.$otp);
    }
}
