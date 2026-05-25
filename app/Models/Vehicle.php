<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Driver;
use App\Models\TransportOwner;
use App\Models\Shipment;
use App\Models\PoolingGroup;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'driver_id',
        'registration_number',
        'vehicle_type_id',
        'capacity_kg',
        'current_load',
        'cold_storage',
        'fuel_type',
        'current_location',
        'tracking_status',
        'insurance_expiry',
    ];

    protected $casts = [
        'capacity_kg' => 'integer',
        'current_load' => 'integer',
        'cold_storage' => 'boolean',
        'insurance_expiry' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(TransportOwner::class, 'owner_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function poolingGroups()
    {
        return $this->hasMany(PoolingGroup::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
