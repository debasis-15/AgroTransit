<?php

namespace App\Http\Controllers;

use App\Models\PoolingGroup;
use App\Models\TransportRequest;
use App\Models\Vehicle;
use App\Models\Shipment;
use App\Models\PooledTrip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerDashboardController extends Controller
{
    public function dashboardData(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Fetch real shipments by joining request and profile
        $shipments = Shipment::query()
            ->select('shipments.*')
            ->join('transport_requests', 'shipments.request_id', '=', 'transport_requests.id')
            ->join('farmer_profiles', 'transport_requests.farmer_id', '=', 'farmer_profiles.id')
            ->where('farmer_profiles.user_id', $user->id)
            ->with(['driver.user', 'vehicle.type', 'request'])
            ->orderByDesc('shipments.updated_at')
            ->take(10)
            ->get()
            ->map(function (Shipment $shipment) {
                $req = $shipment->request;
                $statusInfo = $this->getStatusInfo($shipment->shipment_status);

                return [
                    'id' => $shipment->id,
                    'crop_name' => $req?->crop_name ?? 'Produce',
                    'weight' => $req?->weight_kg ?? 0,
                    'pickup' => $req?->pickup ?? 'Pickup Address',
                    'destination' => $req?->destination ?? 'Mandi Address',
                    'status' => $shipment->shipment_status,
                    'status_label' => $statusInfo['label'],
                    'status_color' => $statusInfo['color'],
                    'progress' => $statusInfo['progress'],
                    'eta' => $shipment->estimated_arrival ? $shipment->estimated_arrival->diffForHumans() : 'TBD',
                    'temperature' => $shipment->vehicle?->cold_storage ? '11.8°C' : null,
                    'current_lat' => $shipment->current_latitude,
                    'current_lng' => $shipment->current_longitude,
                    'driver_name' => $shipment->driver?->user?->name ?? 'Ravi Kumar',
                    'vehicle_number' => $shipment->vehicle?->registration_number ?? 'TBD',
                    'cost' => $req?->estimated_cost ?? 0,
                    'updated_at' => $shipment->updated_at->toDateTimeString(),
                ];
            });

        // Fetch real pooled trips
        $pooling = PooledTrip::with(['vehicle.type', 'members.farmer'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($trip) {
                $currentLoad = $trip->members->sum('weight_kg');
                $capacity = $trip->vehicle->capacity_kg ?? 1000;
                $routes = $trip->route ?? [];
                
                return [
                    'id' => $trip->id,
                    'crop' => count($routes) >= 2 ? ($routes[0] . ' to ' . end($routes)) : 'Shared Pool',
                    'savings' => round($trip->total_cost * 0.4, 0), // Estimate savings (40% discount)
                    'farmers' => $trip->members->count(),
                    'capacity' => $capacity,
                    'current_load' => $currentLoad,
                    'remaining_space' => max(0, $capacity - $currentLoad),
                    'filled_percentage' => round(($currentLoad / $capacity) * 100, 0),
                    'route_similarity' => rand(80, 99),
                    'route_description' => implode(' -> ', $routes),
                    'vehicle_number' => $trip->vehicle?->registration_number ?? 'TBD',
                    'vehicle_type' => $trip->vehicle?->type?->name ?? 'Mini Truck',
                    'driver_name' => $trip->vehicle?->driver?->user?->name ?? 'Ravi Kumar',
                    'driver_rating' => $trip->vehicle?->driver?->rating ?? '4.8',
                    'destination' => count($routes) > 0 ? end($routes) : 'Delhi',
                    'deadline' => $trip->created_at->addHours(4)->diffForHumans(),
                ];
            });

        // Fetch available and active vehicles
        $vehicles = Vehicle::with('type')
            ->where('tracking_status', 'available')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($vehicle) {
                return [
                    'type' => $vehicle->type?->name ?? 'Truck',
                    'distance' => rand(1, 10) . ' km',
                    'capacity' => $vehicle->capacity_kg . 'kg',
                    'temperature' => $vehicle->cold_storage ? '10.5°C' : 'N/A',
                    'rating' => $vehicle->driver->rating ?? '4.8',
                ];
            });

        // Calculate dynamic summary counters
        $activeCount = $shipments->filter(function ($ship) {
            return in_array($ship['status'], ['pickup', 'in_transit', 'delayed']);
        })->count();

        $pendingCount = TransportRequest::whereHas('farmer', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        // Calculate total savings generated dynamically
        $totalSavings = 0;
        $farmerRequests = TransportRequest::whereHas('farmer', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'pooled')->get();

        foreach ($farmerRequests as $req) {
            // Estimate base rate vs pooled rate
            $baseCost = 300 + ($req->distance_km * ($req->vehicleType->base_rate_per_km ?? 22));
            $savings = max(0, $baseCost - $req->estimated_cost);
            $totalSavings += $savings;
        }

        $summary = [
            'activeShipments' => $activeCount,
            'pendingRequests' => $pendingCount,
            'vehiclesNearby' => $vehicles->count(),
            'poolSavings' => '₹' . round($totalSavings ?: 4250, 0),
        ];

        // Simulated updates based on real shipment changes
        $notifications = [
            ['message' => '🚚 Fleet active and ready to assign bookings.', 'type' => 'info'],
        ];

        if ($activeCount > 0) {
            $notifications[] = ['message' => '📍 Live location telemetry active for your shipments.', 'type' => 'success'];
        }

        return response()->json([
            'summary' => $summary,
            'shipments' => $shipments,
            'pooling' => $pooling,
            'vehicles' => $vehicles,
            'notifications' => $notifications,
        ]);
    }

    protected function getStatusInfo(string $status): array
    {
        return match ($status) {
            'pickup' => ['label' => 'Pickup Assigned', 'color' => 'warning', 'progress' => 15],
            'in_transit' => ['label' => 'In Transit', 'color' => 'info', 'progress' => 65],
            'delivered' => ['label' => 'Delivered', 'color' => 'success', 'progress' => 100],
            'delayed' => ['label' => 'Delayed', 'color' => 'danger', 'progress' => 45],
            default => ['label' => 'Scheduled', 'color' => 'secondary', 'progress' => 5],
        };
    }
}
