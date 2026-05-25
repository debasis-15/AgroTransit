<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationRule extends Model
{
    protected $fillable = ['vehicle_type_id', 'produce_type', 'max_weight_kg', 'max_distance_km', 'temperature_sensitive', 'priority'];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
