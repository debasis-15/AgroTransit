<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ip_address', 'device', 'login_time'];

    protected $casts = [
        'login_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
