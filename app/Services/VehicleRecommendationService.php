<?php

namespace App\Services;

use App\Models\RecommendationRule;
use App\Models\VehicleType;

class VehicleRecommendationService
{
    public function recommend(string $produce, int $weightKg, int $distanceKm, bool $temperatureSensitive): ?VehicleType
    {
        $rule = RecommendationRule::query()
            ->with('vehicleType')
            ->where(function ($query) use ($produce) {
                $query->where('produce_type', strtolower($produce))->orWhere('produce_type', '*');
            })
            ->where('max_weight_kg', '>=', $weightKg)
            ->where('max_distance_km', '>=', $distanceKm)
            ->where(function ($query) use ($temperatureSensitive) {
                $query->where('temperature_sensitive', $temperatureSensitive)->orWhere('temperature_sensitive', false);
            })
            ->orderByDesc('temperature_sensitive')
            ->orderByDesc('priority')
            ->first();

        return $rule?->vehicleType
            ?? VehicleType::query()
                ->where('max_capacity_kg', '>=', $weightKg)
                ->when($temperatureSensitive, fn ($query) => $query->where('refrigerated', true))
                ->orderBy('max_capacity_kg')
                ->first();
    }
}
