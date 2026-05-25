<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripMember extends Model
{
    protected $fillable = ['pooled_trip_id', 'transport_request_id', 'farmer_id', 'weight_kg', 'cost_share'];

    public function request()
    {
        return $this->belongsTo(TransportRequest::class, 'transport_request_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
