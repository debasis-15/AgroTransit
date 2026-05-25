<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TransportRequest;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Payment;
use App\Models\QrVerification;
use App\Models\Review;
use App\Models\TrackingLog;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'vehicle_id',
        'driver_id',
        'tracking_code',
        'shipment_status',
        'estimated_arrival',
        'current_latitude',
        'current_longitude',
    ];

    protected $casts = [
        'estimated_arrival' => 'datetime',
        'current_latitude' => 'decimal:7',
        'current_longitude' => 'decimal:7',
    ];

    public function request()
    {
        return $this->belongsTo(TransportRequest::class, 'request_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function trackingLogs()
    {
        return $this->hasMany(TrackingLog::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function qrVerification()
    {
        return $this->hasOne(QrVerification::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
