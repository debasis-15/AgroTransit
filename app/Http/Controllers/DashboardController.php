<?php

namespace App\Http\Controllers;

use App\Models\PoolingGroup;
use App\Models\TransportRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Driver;
use App\Models\LoginLog;
use App\Models\TransportOwner;
use App\Models\VehicleRequest;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'requests' => TransportRequest::latest()->take(4)->get(),
            'pooling_groups' => PoolingGroup::latest()->take(3)->get(),
        ]);
    }

    public function farmer(): View
    {
        $user = Auth::user();
        
        // Auto-create profile if missing
        if (! $user->farmerProfile) {
            $user->farmerProfile()->create([
                'farm_name' => $user->name . ' Farms',
                'primary_crop' => 'Wheat',
                'location' => 'Ludhiana Farm Gate',
                'district' => 'Ludhiana',
                'state' => 'Punjab',
                'verified' => true,
            ]);
            $user->load('farmerProfile');
        }

        $requests = TransportRequest::latest()->get();
        return view('dashboards.farmer-home', compact('requests'));
    }

    public function driver(): View
    {
        $user = Auth::user();
        
        // Auto-create profile if missing
        if (! $user->driver) {
            $user->driver()->create([
                'license_number' => 'DL-19-' . random_int(10000000, 99999999),
                'license_expiry' => now()->addYears(10),
                'experience_years' => 5,
                'rating' => 4.5,
                'available' => true,
                'verified' => true, // Driver is verified by default so they can access the marketplace immediately
            ]);
            $user->load('driver');
        }

        $driver = $user->driver;

        // Check verification status
        $isVerified = $driver->verified;

        // Find assigned vehicle
        $assignedVehicle = Vehicle::where('driver_id', $driver->id)->first();

        $availableVehicles = collect();
        $activeShipments = collect();
        $pendingRequests = collect();

        if ($isVerified) {
            if (! $assignedVehicle) {
                // If verified but unassigned, fetch marketplace vehicles
                $query = Vehicle::with(['type', 'owner.user'])->whereNull('driver_id');
                
                // Allow search/filtering by capacity, location, refrigeration
                if (request('capacity')) {
                    $query->where('capacity_kg', '>=', request('capacity'));
                }
                if (request('location')) {
                    $query->where('current_location', 'like', '%' . request('location') . '%');
                }
                if (request('refrigerated')) {
                    $query->whereHas('type', function ($q) {
                        $q->where('refrigerated', true);
                    });
                }
                
                $availableVehicles = $query->get();
                $pendingRequests = VehicleRequest::where('driver_id', $driver->id)
                    ->where('status', 'pending')
                    ->pluck('vehicle_id')
                    ->toArray();
            } else {
                // If assigned, fetch jobs/shipments
                $activeShipments = Shipment::query()
                    ->select('shipments.*')
                    ->join('transport_requests', 'shipments.request_id', '=', 'transport_requests.id')
                    ->where('shipments.driver_id', $driver->id)
                    ->where('shipments.shipment_status', '!=', 'delivered')
                    ->with('request')
                    ->get();
            }
        }

        return view('dashboards.driver-home', compact(
            'driver', 
            'isVerified', 
            'assignedVehicle', 
            'availableVehicles', 
            'activeShipments',
            'pendingRequests'
        ));
    }

    public function owner(): View
    {
        $user = Auth::user();

        // Auto-create profile if missing
        if (! $user->transportOwner) {
            $user->transportOwner()->create([
                'company_name' => $user->name . ' Logistics',
                'gst_number' => '03AAAAG' . random_int(1000, 9999) . 'A1Z0',
                'fleet_size' => 0,
                'office_address' => 'Ludhiana Bypass, Ludhiana',
                'verified' => true,
            ]);
            $user->load('transportOwner');
        }

        $owner = $user->transportOwner;
        $vehicles = $owner->vehicles()->with(['type', 'driver.user'])->latest()->get();
        $vehicleTypes = VehicleType::all();

        // Fetch driver requests for owner's vehicles
        $incomingRequests = VehicleRequest::with(['driver.user', 'vehicle.type'])
            ->whereIn('vehicle_id', $vehicles->pluck('id'))
            ->where('status', 'pending')
            ->get();

        return view('dashboards.owner-home', compact('owner', 'vehicles', 'vehicleTypes', 'incomingRequests'));
    }

    public function addVehicle(Request $request): RedirectResponse
    {
        $owner = Auth::user()->transportOwner;
        if (! $owner) {
            return back()->withErrors(['error' => 'Owner profile not found.']);
        }

        $data = $request->validate([
            'registration_number' => ['required', 'string', 'unique:vehicles,registration_number'],
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'capacity_kg' => ['required', 'integer', 'min:1'],
            'cold_storage' => ['nullable', 'boolean'],
            'fuel_type' => ['required', 'string'],
        ]);

        $vehicleType = VehicleType::find($data['vehicle_type_id']);

        Vehicle::create([
            'owner_id' => $owner->id,
            'driver_id' => null, // Stays unassigned until owner approves a request
            'registration_number' => $data['registration_number'],
            'vehicle_type_id' => $data['vehicle_type_id'],
            'capacity_kg' => $data['capacity_kg'],
            'cold_storage' => (bool) ($data['cold_storage'] ?? $vehicleType->refrigerated ?? false),
            'fuel_type' => $data['fuel_type'],
            'tracking_status' => 'maintenance', // No driver, so marked in maintenance/offline
        ]);

        return back()->with('status', 'New vehicle registered successfully. Awaiting driver assignment.');
    }

    public function decideVehicleRequest(Request $request, VehicleRequest $assignmentRequest): RedirectResponse
    {
        $decision = $request->input('decision');
        $vehicle = $assignmentRequest->vehicle;

        if ($decision === 'accept') {
            // Update request
            $assignmentRequest->update(['status' => 'accepted']);
            
            // Assign driver to vehicle and make available
            $vehicle->update([
                'driver_id' => $assignmentRequest->driver_id,
                'tracking_status' => 'available',
            ]);

            // Reject all other pending requests for the same vehicle
            VehicleRequest::where('vehicle_id', $vehicle->id)
                ->where('id', '!=', $assignmentRequest->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            return back()->with('status', 'Driver assignment request accepted. Vehicle is now online.');
        }

        $assignmentRequest->update(['status' => 'rejected']);
        return back()->with('status', 'Driver assignment request rejected.');
    }

    public function requestVehicle(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $driver = Auth::user()->driver;
        if (! $driver) {
            return back()->withErrors(['error' => 'Driver profile not found.']);
        }

        // Create assignment request
        VehicleRequest::create([
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Vehicle assignment request sent to owner.');
    }

    public function admin(): View
    {
        $roleCounts = User::query()
            ->selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $totalUsers = User::count();
        $totalFarmers = (int) ($roleCounts['farmer'] ?? 0);
        $totalDrivers = (int) ($roleCounts['driver'] ?? 0);
        $totalOwners = (int) ($roleCounts['transport_owner'] ?? 0);
        $totalAdmins = (int) ($roleCounts['admin'] ?? 0);
        $totalVehicles = Vehicle::count();
        $activeVehicles = Vehicle::where('tracking_status', '!=', 'maintenance')->count();
        $activeShipments = Shipment::whereIn('shipment_status', ['pickup', 'loading', 'in_transit'])->count();
        $todayRevenue = TransportRequest::whereDate('created_at', today())->sum('estimated_cost');
        $platformCommission = $todayRevenue * 0.10;

        $unverifiedDrivers = Driver::with('user')->where('verified', false)->get();
        $owners = TransportOwner::with('user')->withCount('vehicles')->latest()->take(12)->get();
        $drivers = Driver::with(['user', 'vehicles.type'])->latest()->take(12)->get();
        $vehicles = Vehicle::with(['type', 'owner.user', 'driver.user'])->latest()->take(12)->get();
        $recentRequests = TransportRequest::with(['farmer.user', 'vehicleType', 'shipments.driver.user', 'shipments.vehicle'])
            ->latest()
            ->take(10)
            ->get();
        $assignmentRequests = VehicleRequest::with(['driver.user', 'vehicle.type', 'vehicle.owner.user'])
            ->latest()
            ->take(10)
            ->get();
        $users = User::latest()->take(25)->get();
        $auditLogs = LoginLog::with('user')->latest('login_time')->take(15)->get();
        $systemConfig = [
            'Application Name' => config('app.name'),
            'Environment' => config('app.env'),
            'Debug Mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'Database' => config('database.default'),
            'Queue' => config('queue.default'),
            'Cache' => config('cache.default'),
            'Mail' => config('mail.default'),
            'Timezone' => config('app.timezone'),
        ];

        return view('dashboards.admin-home', compact(
            'activeShipments',
            'activeVehicles',
            'assignmentRequests',
            'auditLogs',
            'drivers',
            'owners',
            'platformCommission',
            'recentRequests',
            'roleCounts',
            'systemConfig',
            'todayRevenue',
            'totalAdmins',
            'totalDrivers',
            'totalFarmers',
            'totalOwners',
            'totalUsers',
            'totalVehicles',
            'unverifiedDrivers',
            'users',
            'vehicles',
        ));
    }

    public function approveDriver(Request $request, Driver $driver): RedirectResponse
    {
        $driver->update(['verified' => true]);
        return back()->with('status', 'Driver verified and approved successfully.');
    }

    public function exportAdminReport()
    {
        return view('dashboards.admin-report', [
            'users' => User::orderBy('role')->orderBy('name')->get(),
            'owners' => TransportOwner::with('user')->withCount('vehicles')->orderBy('company_name')->get(),
            'vehicles' => Vehicle::with(['type', 'owner.user', 'driver.user'])->orderBy('registration_number')->get(),
            'requests' => TransportRequest::with('farmer.user')->latest()->get(),
            'assignmentRequests' => VehicleRequest::with(['driver.user', 'vehicle.owner.user'])->latest()->get(),
        ]);
    }
}
