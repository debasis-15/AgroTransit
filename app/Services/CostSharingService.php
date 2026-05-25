<?php

namespace App\Services;

class CostSharingService
{
    /**
     * @param array<int, array{name:string, weight:int|float}> $farmers
     * @return array<int, array{name:string, weight:int|float, share:float}>
     */
    public function split(array $farmers, float $totalTripCost): array
    {
        $totalWeight = array_sum(array_column($farmers, 'weight'));

        if ($totalWeight <= 0) {
            return [];
        }

        return array_map(function (array $farmer) use ($totalWeight, $totalTripCost) {
            $farmer['share'] = round(($farmer['weight'] / $totalWeight) * $totalTripCost, 2);

            return $farmer;
        }, $farmers);
    }
}
