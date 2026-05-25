<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Vehicle;

class TransportOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'gst_number',
        'fleet_size',
        'office_address',
        'verified',
    ];

    protected $casts = [
        'fleet_size' => 'integer',
        'verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }
}
