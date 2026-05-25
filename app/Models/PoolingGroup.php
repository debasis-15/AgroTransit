<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransportRequest;
use App\Models\Vehicle;

class PoolingGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'route',
        'max_capacity',
        'current_capacity',
        'departure_time',
        'status',
    ];

    protected $casts = [
        'max_capacity' => 'integer',
        'current_capacity' => 'integer',
        'departure_time' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tripMembers()
    {
        return $this->hasMany(TripMember::class, 'pooled_trip_id');
    }
}
