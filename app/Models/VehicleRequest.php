<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRequest extends Model
{
    protected $fillable = ['driver_id', 'vehicle_id', 'status'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
