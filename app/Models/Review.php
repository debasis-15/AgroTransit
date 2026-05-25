<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shipment;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'reviewer_id',
        'reviewed_user_id',
        'rating',
        'comment',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewedUser()
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }
}
