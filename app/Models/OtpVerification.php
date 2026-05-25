<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'otp', 'expires_at', 'verified'];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
