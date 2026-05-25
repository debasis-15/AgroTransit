<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['driver_id', 'farmer_id', 'pooled_trip_id', 'rating', 'review', 'on_time', 'safe_delivery'];
}
