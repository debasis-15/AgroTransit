<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shipment;
use App\Models\User;

class QrVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'qr_code',
        'verified_by',
        'status',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
