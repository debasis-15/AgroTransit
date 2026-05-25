<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shipment;

class TrackingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'latitude',
        'longitude',
        'speed',
        'recorded_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'speed' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
