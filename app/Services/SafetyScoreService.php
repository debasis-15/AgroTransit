<?php

namespace App\Services;

use App\Models\Rating;

class SafetyScoreService
{
    public function forDriver(int $driverId): int
    {
        $ratings = Rating::query()->where('driver_id', $driverId)->get();
        $total = $ratings->count();

        if ($total === 0) {
            return 100;
        }

        $positiveReviews = $ratings->where('rating', '>=', 4)->count();
        $onTimeTrips = $ratings->where('on_time', true)->count();

        return (int) round((($onTimeTrips + $positiveReviews) / ($total * 2)) * 100);
    }
}
