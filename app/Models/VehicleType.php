<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = ['name', 'min_capacity_kg', 'max_capacity_kg', 'refrigerated', 'base_rate_per_km'];
}
