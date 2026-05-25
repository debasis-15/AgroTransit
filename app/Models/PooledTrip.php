<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PooledTrip extends Model
{
    protected $fillable = ['vehicle_id', 'driver_id', 'route', 'total_cost', 'status', 'started_at', 'completed_at'];

    protected $casts = ['route' => 'array', 'started_at' => 'datetime', 'completed_at' => 'datetime'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function members()
    {
        return $this->hasMany(TripMember::class);
    }
}
