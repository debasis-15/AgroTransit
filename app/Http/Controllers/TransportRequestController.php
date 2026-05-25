<?php

namespace App\Http\Controllers;

use App\Models\TransportRequest;
use App\Models\User;
use App\Models\FarmerProfile;
use App\Models\Vehicle;
use App\Models\PooledTrip;
use App\Models\TripMember;
use App\Models\Shipment;
use App\Services\VehicleRecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TransportRequestController extends Controller
{
    public function create(): View
    {
        return view('requests.create');
    }

    public function store(Request $request, VehicleRecommendationService $recommendations): RedirectResponse
    {
        // Fallback for standard posting without AJAX
        $data = $request->validate([
            'crop_name' => ['required', 'string', 'max:80'],
            'weight_kg' => ['required', 'integer', 'min:1'],
            'pickup' => ['required', 'string', 'max:120'],
            'destination' => ['required', 'string', 'max:120'],
            'distance_km' => ['required', 'integer', 'min:1'],
            'temperature_sensitive' => ['nullable', 'boolean'],
            'priority' => ['required', 'in:normal,emergency'],
        ]);

        $vehicleType = $recommendations->recommend(
            $data['crop_name'],
            (int) $data['weight_kg'],
            (int) $data['distance_km'],
            (bool) ($data['temperature_sensitive'] ?? false)
        );

        $user = Auth::user() ?? User::where('role', 'farmer')->first();
        $farmerProfile = $user->farmerProfile ?? FarmerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'farm_name' => $user->name . ' Farms',
                'primary_crop' => $data['crop_name'],
                'location' => $data['pickup'],
                'district' => 'Ludhiana',
                'state' => 'Punjab',
            ]
        );

        $estimatedCost = $vehicleType
            ? 300 + ((int) $data['distance_km'] * ($vehicleType->base_rate_per_km ?? 22)) + ((int) $data['weight_kg'] * (int) $data['distance_km'] * 0.005) + (($data['temperature_sensitive'] ?? false) ? 500 : 0) + ($data['priority'] === 'emergency' ? 350 : 0)
            : 0;

        TransportRequest::create($data + [
            'farmer_id' => $farmerProfile->id,
            'vehicle_type_id' => $vehicleType?->id,
            'temperature_sensitive' => (bool) ($data['temperature_sensitive'] ?? false),
            'estimated_cost' => $estimatedCost,
        ]);

        return redirect()->route('farmer.dashboard')->with('status', 'Transport request created with smart vehicle recommendation.');
    }

    public function availableVehicles(): JsonResponse
    {
        // Fetch ALL vehicles (all statuses) with relations + latest active shipment
        $vehicles = Vehicle::with([
            'type',
            'driver.user',
            'owner.user',
            'shipments' => function ($q) {
                $q->whereIn('shipment_status', ['pickup', 'in_transit', 'loading'])
                  ->with(['request'])
                  ->orderByDesc('created_at')
                  ->limit(1);
            },
        ])
        ->whereNotNull('driver_id')
        ->whereHas('driver', function ($q) {
            $q->where('verified', true);
        })
        ->orderByRaw("
            CASE tracking_status
                WHEN 'available' THEN 1
                WHEN 'busy' THEN 2
                WHEN 'in_transit' THEN 3
                WHEN 'maintenance' THEN 4
                ELSE 5
            END
        ")
        ->get();

        $result = $vehicles->map(function ($vehicle) {
            $isAvailable = $vehicle->tracking_status === 'available';

            // For booked vehicles, pull booking info from latest shipment
            $latestShipment = $vehicle->shipments->first();
            $request        = $latestShipment?->request;

            $bookedDate        = null;
            $bookedDestination = null;
            $estimatedReturn   = null;

            if (!$isAvailable && $latestShipment) {
                $bookedDate = $request?->pickup_date
                    ? \Carbon\Carbon::parse($request->pickup_date)->format('d M Y')
                    : \Carbon\Carbon::parse($latestShipment->created_at)->format('d M Y');

                $bookedDestination = $request?->destination ?? null;

                $estimatedReturn = $request?->pickup_date
                    ? \Carbon\Carbon::parse($request->pickup_date)->addDay()->format('d M Y')
                    : \Carbon\Carbon::parse($latestShipment->created_at)->addDays(2)->format('d M Y');
            }

            return [
                'id'                  => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'vehicle_type'        => $vehicle->type->name ?? 'Mini Truck',
                'capacity_kg'         => $vehicle->capacity_kg,
                'cold_storage'        => (bool) $vehicle->cold_storage,
                'fuel_type'           => ucfirst($vehicle->fuel_type ?? 'Diesel'),
                'current_location'    => $vehicle->current_location ?? 'Punjab',
                'driver_name'         => $vehicle->driver->user->name ?? 'N/A',
                'driver_rating'       => $vehicle->driver->rating ?? '4.8',
                'owner_name'          => $vehicle->owner->user->name ?? 'N/A',
                'base_rate_per_km'    => $vehicle->type->base_rate_per_km ?? 22,
                'tracking_status'     => $vehicle->tracking_status,
                'is_available'        => $isAvailable,
                'booked_date'         => $bookedDate,
                'booked_destination'  => $bookedDestination,
                'estimated_return'    => $estimatedReturn,
            ];
        });

        return response()->json(['vehicles' => $result]);
    }

    public function matchVehicles(Request $request): JsonResponse
    {
        $data = $request->validate([
            'crop_name' => ['required', 'string'],
            'weight_kg' => ['required', 'integer', 'min:1'],
            'pickup' => ['required', 'string'],
            'destination' => ['required', 'string'],
            'distance_km' => ['required', 'integer', 'min:1'],
            'temperature_sensitive' => ['nullable', 'boolean'],
            'priority' => ['required', 'in:normal,express,emergency'],
        ]);

        $weightKg = (int) $data['weight_kg'];
        $distanceKm = (int) $data['distance_km'];
        $tempSensitive = (bool) ($data['temperature_sensitive'] ?? false);
        $priority = $data['priority'];

        // --- Option A: Find Existing Pools (PooledTrips open and matching) ---
        $matchingPools = [];
        $pooledTrips = PooledTrip::with(['vehicle.type', 'vehicle.driver.user', 'members.request'])
            ->where('status', 'pending')
            ->get();

        foreach ($pooledTrips as $trip) {
            $vehicle = $trip->vehicle;
            if (! $vehicle || ! $vehicle->driver_id) continue;
            
            // Check driver verified
            if (! $vehicle->driver || ! $vehicle->driver->verified) continue;

            // Check refrigeration compatibility
            if ($tempSensitive && ! $vehicle->cold_storage) continue;

            // Check remaining space
            $currentLoad = $trip->members->sum('weight_kg');
            $remainingSpace = $vehicle->capacity_kg - $currentLoad;
            if ($remainingSpace < $weightKg) continue;

            // Destination route match: check if the trip's destination is similar
            $tripDest = strtolower($trip->route[count($trip->route) - 1] ?? '');
            $reqDest = strtolower($data['destination']);
            if (! Str::contains($tripDest, $reqDest) && ! Str::contains($reqDest, $tripDest)) {
                continue;
            }

            // Calculations
            $baseRate = $vehicle->type->base_rate_per_km ?? 22;
            $newTotalWeight = $currentLoad + $weightKg;
            $tripBaseCost = 300 + ($distanceKm * $baseRate) + ($newTotalWeight * $distanceKm * 0.005) + ($vehicle->cold_storage ? 500 : 0);
            
            // Determine if emergency is requested by any member
            $hasEmergency = $priority === 'emergency';
            foreach ($trip->members as $member) {
                if ($member->request && $member->request->priority === 'emergency') {
                    $hasEmergency = true;
                }
            }
            $tripBaseCost += $hasEmergency ? 350 : 0;

            // Discount: 2 members = 20%, 3+ members = 40%
            $newMembersCount = $trip->members->count() + 1;
            $discountPercent = $newMembersCount == 2 ? 0.20 : ($newMembersCount >= 3 ? 0.40 : 0.0);
            $discountedTotal = $tripBaseCost * (1 - $discountPercent);

            // Proportional cost share split
            $myCostShare = ($weightKg / $newTotalWeight) * $discountedTotal;

            // Calculate preview of all members shares
            $membersList = [];
            foreach ($trip->members as $member) {
                $membersList[] = [
                    'name' => $member->farmer?->name ?? 'Farmer Partner',
                    'weight' => $member->weight_kg,
                    'percentage' => round(($member->weight_kg / $newTotalWeight) * 100, 0),
                    'cost' => round(($member->weight_kg / $newTotalWeight) * $discountedTotal, 0),
                ];
            }
            $membersList[] = [
                'name' => 'You',
                'weight' => $weightKg,
                'percentage' => round(($weightKg / $newTotalWeight) * 100, 0),
                'cost' => round($myCostShare, 0),
            ];

            // Pooling deadline (2 hours from creation for demo purposes)
            $deadline = $trip->created_at->addHours(4)->diffForHumans();

            $matchingPools[] = [
                'id' => $trip->id,
                'vehicle_number' => $vehicle->registration_number,
                'vehicle_type' => $vehicle->type->name ?? 'Mini Truck',
                'capacity' => $vehicle->capacity_kg,
                'current_load' => $currentLoad,
                'remaining_space' => $remainingSpace,
                'filled_percentage' => round(($currentLoad / $vehicle->capacity_kg) * 100, 0),
                'driver_name' => $vehicle->driver?->user?->name ?? 'Ravi Kumar',
                'driver_rating' => $vehicle->driver?->rating ?? '4.8',
                'estimated_cost' => round($myCostShare, 0),
                'discount' => $discountPercent * 100,
                'deadline' => $deadline,
                'members' => $membersList,
            ];
        }

        // --- Option B: Find Available New Vehicles ---
        $matchingVehicles = [];
        $availableVehicles = Vehicle::with(['type', 'driver.user'])
            ->where('tracking_status', 'available')
            ->whereNotNull('driver_id')
            ->whereHas('driver', function ($query) {
                $query->where('verified', true);
            })
            ->where('capacity_kg', '>=', $weightKg)
            ->when($tempSensitive, function ($q) {
                $q->where('cold_storage', true);
            })
            ->get();

        foreach ($availableVehicles as $vehicle) {
            $baseRate = $vehicle->type->base_rate_per_km ?? 22;
            $tripBaseCost = 300 + ($distanceKm * $baseRate) + ($weightKg * $distanceKm * 0.005) + ($vehicle->cold_storage ? 500 : 0) + ($priority === 'emergency' ? 350 : 0);

            $matchingVehicles[] = [
                'id' => $vehicle->id,
                'vehicle_number' => $vehicle->registration_number,
                'vehicle_type' => $vehicle->type->name ?? 'Mini Truck',
                'capacity' => $vehicle->capacity_kg,
                'driver_name' => $vehicle->driver->user->name ?? 'Ravi Kumar',
                'driver_rating' => $vehicle->driver->rating ?? '4.8',
                'estimated_cost' => round($tripBaseCost, 0),
                'cold_storage' => $vehicle->cold_storage,
            ];
        }

        return response()->json([
            'pools' => $matchingPools,
            'vehicles' => $matchingVehicles,
        ]);
    }

    public function storeBookedRequest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'crop_name' => ['required', 'string', 'max:80'],
            'weight_kg' => ['required', 'integer', 'min:1'],
            'pickup' => ['required', 'string', 'max:120'],
            'destination' => ['required', 'string', 'max:120'],
            'distance_km' => ['required', 'integer', 'min:1'],
            'temperature_sensitive' => ['nullable', 'boolean'],
            'priority' => ['required', 'in:normal,express,emergency'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'pooled_trip_id' => ['nullable', 'exists:pooled_trips,id'],
        ]);

        $weightKg = (int) $data['weight_kg'];
        $distanceKm = (int) $data['distance_km'];
        $tempSensitive = (bool) ($data['temperature_sensitive'] ?? false);
        $priority = $data['priority'];

        $user = Auth::user() ?? User::where('role', 'farmer')->first();
        
        // Auto-create farmer profile if missing
        if (! $user->farmerProfile) {
            $user->farmerProfile()->create([
                'farm_name' => $user->name . ' Farms',
                'primary_crop' => $data['crop_name'],
                'location' => $data['pickup'],
                'district' => 'Ludhiana',
                'state' => 'Punjab',
                'verified' => true,
            ]);
            $user->load('farmerProfile');
        }

        $farmerProfile = $user->farmerProfile;

        DB::beginTransaction();

        try {
            // Find selected vehicle and type
            $vehicle = null;
            $pooledTrip = null;

            if (! empty($data['pooled_trip_id'])) {
                $pooledTrip = PooledTrip::findOrFail($data['pooled_trip_id']);
                $vehicle = $pooledTrip->vehicle;
            } elseif (! empty($data['vehicle_id'])) {
                $vehicle = Vehicle::findOrFail($data['vehicle_id']);
            } else {
                return response()->json(['error' => 'No vehicle or pool selected.'], 422);
            }

            // Create transport request
            $transportRequest = TransportRequest::create([
                'farmer_id' => $farmerProfile->id,
                'crop_name' => $data['crop_name'],
                'weight_kg' => $weightKg,
                'pickup' => $data['pickup'],
                'destination' => $data['destination'],
                'pickup_date' => now()->addDay(),
                'distance_km' => $distanceKm,
                'temperature_sensitive' => $tempSensitive,
                'priority' => $priority === 'emergency' ? 'emergency' : 'normal',
                'vehicle_type_id' => $vehicle->vehicle_type_id,
                'status' => 'pooled',
            ]);

            if ($pooledTrip) {
                // Joining existing pool
                $newMembersCount = $pooledTrip->members()->count() + 1;
                $discountPercent = $newMembersCount == 2 ? 0.20 : ($newMembersCount >= 3 ? 0.40 : 0.0);

                // Add member
                $pooledTrip->members()->create([
                    'transport_request_id' => $transportRequest->id,
                    'farmer_id' => $user->id,
                    'weight_kg' => $weightKg,
                    'cost_share' => 0, // Recalculated below
                ]);

                // Update vehicle load
                $totalLoad = $pooledTrip->members()->sum('weight_kg');
                $vehicle->update(['current_load' => $totalLoad]);

                $baseRate = $vehicle->type->base_rate_per_km ?? 22;
                $tripBaseCost = 300 + ($distanceKm * $baseRate) + ($totalLoad * $distanceKm * 0.005) + ($vehicle->cold_storage ? 500 : 0);
                
                // Scan for priority
                $hasEmergency = $priority === 'emergency';
                foreach ($pooledTrip->members as $member) {
                    if ($member->request && $member->request->priority === 'emergency') {
                        $hasEmergency = true;
                    }
                }
                $tripBaseCost += $hasEmergency ? 350 : 0;
                $discountedTotal = $tripBaseCost * (1 - $discountPercent);

                // Recalculate cost shares for all members
                $allMembers = $pooledTrip->members;
                foreach ($allMembers as $m) {
                    $m->update([
                        'cost_share' => round(($m->weight_kg / $totalLoad) * $discountedTotal, 2)
                    ]);
                    // Update original request costs
                    if ($m->request) {
                        $m->request->update(['estimated_cost' => $m->cost_share]);
                    }
                }

                // Close pool if full
                if ($totalLoad >= $vehicle->capacity_kg) {
                    $pooledTrip->update(['status' => 'active']);
                    $vehicle->update(['tracking_status' => 'busy']);
                }

            } else {
                // Creating a new pool
                $baseRate = $vehicle->type->base_rate_per_km ?? 22;
                $tripBaseCost = 300 + ($distanceKm * $baseRate) + ($weightKg * $distanceKm * 0.005) + ($vehicle->cold_storage ? 500 : 0) + ($priority === 'emergency' ? 350 : 0);

                $pooledTrip = PooledTrip::create([
                    'vehicle_id' => $vehicle->id,
                    'driver_id' => $vehicle->driver->user_id,
                    'route' => [$data['pickup'], $data['destination']],
                    'total_cost' => $tripBaseCost,
                    'status' => 'pending',
                ]);

                $pooledTrip->members()->create([
                    'transport_request_id' => $transportRequest->id,
                    'farmer_id' => $user->id,
                    'weight_kg' => $weightKg,
                    'cost_share' => $tripBaseCost,
                ]);

                $vehicle->update([
                    'tracking_status' => 'busy',
                    'current_load' => $weightKg,
                ]);

                $transportRequest->update(['estimated_cost' => $tripBaseCost]);
            }

            // Create Shipment
            Shipment::create([
                'request_id' => $transportRequest->id,
                'vehicle_id' => $vehicle->id,
                'driver_id' => $vehicle->driver_id,
                'tracking_code' => 'TRK-' . strtoupper(Str::random(8)),
                'shipment_status' => 'pickup',
                'estimated_arrival' => now()->addHours(3),
                'current_latitude' => 30.9038,
                'current_longitude' => 75.8573,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shipment booked successfully!',
                'redirect' => route('farmer.dashboard'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Failed to book shipment request: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred during booking: ' . $e->getMessage()], 500);
        }
    }
}
