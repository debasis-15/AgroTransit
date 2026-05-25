<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shipment;
use App\Models\User;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'payer_id',
        'amount',
        'payment_method',
        'transaction_id',
        'payment_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }
}
