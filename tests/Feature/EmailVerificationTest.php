<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_otp_and_requires_verification(): void
    {
        Mail::fake();

        $response = $this->post(route('register.store'), [
            'name' => 'New Farmer',
            'email' => 'newfarmer@example.test',
            'role' => 'farmer',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'newfarmer@example.test')->firstOrFail();

        $response->assertRedirect(route('otp.show', $user));
        $this->assertFalse($user->is_active);
        $this->assertNull($user->email_verified_at);
        $this->assertDatabaseHas('otp_verifications', [
            'user_id' => $user->id,
            'verified' => false,
        ]);

        Mail::assertSent(OtpMail::class);
    }

    public function test_user_can_verify_email_with_valid_otp(): void
    {
        $user = User::create([
            'name' => 'New Driver',
            'email' => 'newdriver@example.test',
            'password' => bcrypt('password'),
            'role' => 'driver',
            'is_active' => false,
        ]);

        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->post(route('otp.verify', $user), [
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('driver.dashboard'));

        $user->refresh();
        $this->assertTrue($user->is_active);
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_is_sent_to_otp_verification_on_login(): void
    {
        Mail::fake();

        $user = User::create([
            'name' => 'Pending Owner',
            'email' => 'pendingowner@example.test',
            'password' => bcrypt('password'),
            'role' => 'transport_owner',
            'is_active' => false,
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'pendingowner@example.test',
            'password' => 'password',
            'role' => 'transport_owner',
        ]);

        $response->assertRedirect(route('otp.show', $user));
        $this->assertGuest();
        $this->assertDatabaseHas('otp_verifications', [
            'user_id' => $user->id,
            'verified' => false,
        ]);
        Mail::assertSent(OtpMail::class);
    }
}
