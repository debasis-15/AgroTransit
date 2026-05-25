<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TransportRequest;

class FarmerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'farm_name',
        'primary_crop',
        'location',
        'district',
        'state',
        'farm_size',
        'verified',
    ];

    protected $casts = [
        'farm_size' => 'decimal:2',
        'verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transportRequests()
    {
        return $this->hasMany(TransportRequest::class, 'farmer_id');
    }
}
