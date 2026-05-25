<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Vehicle;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_expiry',
        'experience_years',
        'rating',
        'available',
        'verified',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'experience_years' => 'integer',
        'rating' => 'decimal:1',
        'available' => 'boolean',
        'verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
