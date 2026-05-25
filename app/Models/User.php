<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\FarmerProfile;
use App\Models\Driver;
use App\Models\TransportOwner;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Review;
use App\Models\QrVerification;
use App\Models\OtpVerification;
use App\Models\LoginLog;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'profile_photo',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function farmerProfile()
    {
        return $this->hasOne(FarmerProfile::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function transportOwner()
    {
        return $this->hasOne(TransportOwner::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payer_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reviewsWritten()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function qrVerifications()
    {
        return $this->hasMany(QrVerification::class, 'verified_by');
    }

    public function otpVerifications()
    {
        return $this->hasMany(OtpVerification::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }
}
