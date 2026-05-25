<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\FarmerProfile;
use App\Models\Shipment;
use App\Models\PoolingGroup;

class TransportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'crop_name',
        'weight_kg',
        'pickup',
        'destination',
        'pickup_date',
        'transport_type',
        'special_requirements',
        'distance_km',
        'temperature_sensitive',
        'priority',
        'estimated_cost',
        'vehicle_type_id',
        'status',
    ];

    protected $casts = [
        'weight_kg' => 'integer',
        'pickup_date' => 'date',
        'distance_km' => 'integer',
        'temperature_sensitive' => 'boolean',
        'estimated_cost' => 'decimal:2',
    ];

    public function farmer()
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'request_id');
    }
}
