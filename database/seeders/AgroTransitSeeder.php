<?php

namespace Database\Seeders;

use App\Models\PooledTrip;
use App\Models\Rating;
use App\Models\RecommendationRule;
use App\Models\TransportRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\FarmerProfile;
use App\Models\Driver;
use App\Models\Shipment;
use App\Models\TransportOwner;
use App\Models\VehicleRequest;
use Illuminate\Database\Seeder;

class AgroTransitSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $farmerA = User::firstOrCreate(['email' => 'amandeep@agro.test'], ['name' => 'Amandeep Singh', 'role' => 'farmer', 'language' => 'pa', 'phone' => '9000000001', 'email_verified_at' => now(), 'password' => bcrypt('password')]);
        $farmerB = User::firstOrCreate(['email' => 'meera@agro.test'], ['name' => 'Meera Devi', 'role' => 'farmer', 'language' => 'hi', 'phone' => '9000000002', 'email_verified_at' => now(), 'password' => bcrypt('password')]);
        $ownerUser = User::firstOrCreate(['email' => 'owner@agro.test'], ['name' => 'GreenLine Transport', 'role' => 'transport_owner', 'language' => 'en', 'phone' => '9000000003', 'email_verified_at' => now(), 'password' => bcrypt('password')]);
        $driverUser = User::firstOrCreate(['email' => 'driver@agro.test'], ['name' => 'Ravi Kumar', 'role' => 'driver', 'language' => 'hi', 'phone' => '9000000004', 'email_verified_at' => now(), 'password' => bcrypt('password')]);
        User::firstOrCreate(['email' => 'admin@agro.test'], ['name' => 'AgroTransit Admin', 'role' => 'admin', 'language' => 'en', 'phone' => '9000000005', 'email_verified_at' => now(), 'password' => bcrypt('password')]);

        // Profiles
        $profileA = FarmerProfile::firstOrCreate(['user_id' => $farmerA->id], ['farm_name' => 'Amandeep Farms', 'primary_crop' => 'Wheat', 'location' => 'Ludhiana Farm Gate', 'district' => 'Ludhiana', 'state' => 'Punjab', 'farm_size' => 12.5, 'verified' => true]);
        $profileB = FarmerProfile::firstOrCreate(['user_id' => $farmerB->id], ['farm_name' => 'Meera Organic Crops', 'primary_crop' => 'Tomato', 'location' => 'Khanna Collection Point', 'district' => 'Khanna', 'state' => 'Punjab', 'farm_size' => 8.2, 'verified' => true]);
        $owner = TransportOwner::firstOrCreate(['user_id' => $ownerUser->id], ['company_name' => 'GreenLine Transport', 'gst_number' => '03AAAAG2026A1Z0', 'fleet_size' => 8, 'office_address' => 'Ludhiana Bypass Rd, Ludhiana', 'verified' => true]);
        $driver = Driver::firstOrCreate(['user_id' => $driverUser->id], ['license_number' => 'DL-19-202600189', 'license_expiry' => '2036-05-25', 'experience_years' => 7, 'rating' => 4.8, 'available' => true, 'verified' => true]);

        // Vehicle Types
        $mini = VehicleType::firstOrCreate(['name' => 'Mini Truck'], ['min_capacity_kg' => 200, 'max_capacity_kg' => 1200, 'refrigerated' => false, 'base_rate_per_km' => 22]);
        $reefer = VehicleType::firstOrCreate(['name' => 'Refrigerated Truck'], ['min_capacity_kg' => 500, 'max_capacity_kg' => 4000, 'refrigerated' => true, 'base_rate_per_km' => 46]);
        $pickup = VehicleType::firstOrCreate(['name' => 'Pickup Van'], ['min_capacity_kg' => 50, 'max_capacity_kg' => 700, 'refrigerated' => false, 'base_rate_per_km' => 16]);
        $cargo = VehicleType::firstOrCreate(['name' => 'Cargo Truck'], ['min_capacity_kg' => 1000, 'max_capacity_kg' => 8000, 'refrigerated' => false, 'base_rate_per_km' => 32]);

        // Seed requested Drivers 201 to 210
        $driverNames = [
            201 => 'Satnam Singh',
            202 => 'Baljit Singh',
            203 => 'Gurpreet Singh',
            204 => 'Manpreet Singh',
            205 => 'Sukhwinder Singh',
            206 => 'Jagjit Singh',
            207 => 'Rajesh Kumar',
            208 => 'Vijay Kumar',
            209 => 'Amit Sharma',
            210 => 'Sanjay Dutt',
        ];

        foreach ($driverNames as $id => $name) {
            $u = User::firstOrCreate(
                ['id' => $id],
                [
                    'name' => $name,
                    'email' => strtolower(str_replace(' ', '', $name)) . '@agro.test',
                    'phone' => '98765020' . str_pad($id - 200, 2, '0', STR_PAD_LEFT),
                    'role' => 'driver',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                ]
            );

            Driver::firstOrCreate(
                ['id' => $id],
                [
                    'user_id' => $u->id,
                    'license_number' => 'DL-19-' . (202600000 + $id),
                    'license_expiry' => '2036-05-25',
                    'experience_years' => rand(3, 15),
                    'rating' => 4.5 + (rand(0, 5) / 10),
                    'available' => true,
                    'verified' => true,
                ]
            );
        }

        // Seed requested Owners 101 to 110
        $ownersData = [
            101 => ['name' => 'Punjab Agro Logistics', 'email' => 'punjabagro@gmail.com', 'phone' => '9876501001'],
            102 => ['name' => 'GreenRoute Transport', 'email' => 'greenroute@gmail.com', 'phone' => '9876501002'],
            103 => ['name' => 'FreshMove Carriers', 'email' => 'freshmove@gmail.com', 'phone' => '9876501003'],
            104 => ['name' => 'Kisan Cargo Services', 'email' => 'kisancargo@gmail.com', 'phone' => '9876501004'],
            105 => ['name' => 'Harvest Wheels', 'email' => 'harvestwheels@gmail.com', 'phone' => '9876501005'],
            106 => ['name' => 'AgroLink Transport', 'email' => 'agrolink@gmail.com', 'phone' => '9876501006'],
            107 => ['name' => 'FarmHaul India', 'email' => 'farmhaul@gmail.com', 'phone' => '9876501007'],
            108 => ['name' => 'Golden Field Movers', 'email' => 'goldenfield@gmail.com', 'phone' => '9876501008'],
            109 => ['name' => 'Rural Freight Network', 'email' => 'ruralfreight@gmail.com', 'phone' => '9876501009'],
            110 => ['name' => 'SmartAgri Transport', 'email' => 'smartagri@gmail.com', 'phone' => '9876501010'],
        ];

        foreach ($ownersData as $id => $data) {
            $u = User::firstOrCreate(
                ['id' => $id],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'role' => 'transport_owner',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                ]
            );

            TransportOwner::firstOrCreate(
                ['id' => $id],
                [
                    'user_id' => $u->id,
                    'company_name' => $data['name'],
                    'gst_number' => '03AAAAG2026' . $id . 'Z',
                    'fleet_size' => 3,
                    'office_address' => 'Bypass Rd, Ludhiana',
                    'verified' => true,
                ]
            );
        }

        // Seed requested Vehicles
        $vehiclesData = [
            [1001, 101, null, 'PB10AG1001', 'Mini Truck', 1000, 0, 'available'],
            [1002, 101, null, 'PB10AG1002', 'Refrigerated Truck', 2500, 1, 'available'],
            [1003, 101, null, 'PB10AG1003', 'Cargo Truck', 5000, 0, 'in_transit'],
            [1004, 102, null, 'PB11BG1004', 'Mini Truck', 1200, 0, 'available'],
            [1005, 102, null, 'PB11BG1005', 'Cargo Truck', 4000, 0, 'maintenance'],
            [1006, 102, null, 'PB11BG1006', 'Refrigerated Truck', 3000, 1, 'available'],
            [1007, 103, null, 'HR22CG1007', 'Mini Truck', 1500, 0, 'available'],
            [1008, 103, null, 'HR22CG1008', 'Cargo Truck', 6000, 0, 'available'],
            [1009, 103, null, 'HR22CG1009', 'Refrigerated Truck', 3500, 1, 'in_transit'],
            [1010, 104, null, 'RJ14DG1010', 'Mini Truck', 1300, 0, 'available'],
            [1011, 104, null, 'RJ14DG1011', 'Cargo Truck', 4500, 0, 'available'],
            [1012, 104, null, 'RJ14DG1012', 'Refrigerated Truck', 3200, 1, 'maintenance'],
            [1013, 105, null, 'UP32EG1013', 'Mini Truck', 1000, 0, 'available'],
            [1014, 105, null, 'UP32EG1014', 'Cargo Truck', 5500, 0, 'in_transit'],
            [1015, 105, null, 'UP32EG1015', 'Refrigerated Truck', 2800, 1, 'available'],
            [1016, 106, null, 'DL08FG1016', 'Mini Truck', 1400, 0, 'available'],
            [1017, 106, null, 'DL08FG1017', 'Cargo Truck', 5000, 0, 'available'],
            [1018, 106, null, 'DL08FG1018', 'Refrigerated Truck', 3000, 1, 'maintenance'],
            [1019, 107, null, 'PB13GG1019', 'Mini Truck', 1200, 0, 'available'],
            [1020, 107, null, 'PB13GG1020', 'Cargo Truck', 4800, 0, 'available'],
            [1021, 107, null, 'PB13GG1021', 'Refrigerated Truck', 3500, 1, 'in_transit'],
            [1022, 108, null, 'HR45HG1022', 'Mini Truck', 1000, 0, 'available'],
            [1023, 108, null, 'HR45HG1023', 'Cargo Truck', 5200, 0, 'maintenance'],
            [1024, 108, null, 'HR45HG1024', 'Refrigerated Truck', 2700, 1, 'available'],
            [1025, 109, null, 'RJ20IG1025', 'Mini Truck', 1100, 0, 'available'],
            [1026, 109, null, 'RJ20IG1026', 'Cargo Truck', 6100, 0, 'available'],
            [1027, 109, null, 'RJ20IG1027', 'Refrigerated Truck', 3400, 1, 'in_transit'],
            [1028, 110, null, 'DL04JG1028', 'Mini Truck', 1000, 0, 'available'],
            [1029, 110, null, 'DL04JG1029', 'Cargo Truck', 5700, 0, 'available'],
            [1030, 110, null, 'DL04JG1030', 'Refrigerated Truck', 2900, 1, 'maintenance'],
        ];

        foreach ($vehiclesData as $v) {
            $typeId = match ($v[4]) {
                'Mini Truck' => $mini->id,
                'Refrigerated Truck' => $reefer->id,
                'Cargo Truck' => $cargo->id,
                default => $pickup->id,
            };

            Vehicle::firstOrCreate(
                ['id' => $v[0]],
                [
                    'owner_id' => $v[1],
                    'driver_id' => $v[2],
                    'registration_number' => $v[3],
                    'vehicle_type_id' => $typeId,
                    'capacity_kg' => $v[5],
                    'cold_storage' => (bool) $v[6],
                    'tracking_status' => $v[7],
                    'fuel_type' => 'Diesel',
                ]
            );
        }

        $acceptedAssignments = [
            1001 => 201,
            1002 => 202,
            1003 => 203,
            1004 => 204,
            1006 => 205,
            1007 => 206,
            1008 => 207,
            1009 => 208,
        ];

        foreach ($acceptedAssignments as $vehicleId => $driverId) {
            Vehicle::whereKey($vehicleId)->update(['driver_id' => $driverId]);
            VehicleRequest::firstOrCreate(
                ['vehicle_id' => $vehicleId, 'driver_id' => $driverId],
                ['status' => 'accepted']
            );
        }

        foreach ([[1020, 209], [1024, 210]] as [$vehicleId, $driverId]) {
            VehicleRequest::firstOrCreate(
                ['vehicle_id' => $vehicleId, 'driver_id' => $driverId],
                ['status' => 'pending']
            );
        }

        // Recommendation Rules
        RecommendationRule::firstOrCreate(['produce_type' => 'tomato', 'vehicle_type_id' => $reefer->id], ['max_weight_kg' => 3500, 'max_distance_km' => 650, 'temperature_sensitive' => true, 'priority' => 10]);
        RecommendationRule::firstOrCreate(['produce_type' => 'wheat', 'vehicle_type_id' => $mini->id], ['max_weight_kg' => 1200, 'max_distance_km' => 300, 'temperature_sensitive' => false, 'priority' => 7]);
        RecommendationRule::firstOrCreate(['produce_type' => '*', 'vehicle_type_id' => $pickup->id], ['max_weight_kg' => 700, 'max_distance_km' => 120, 'temperature_sensitive' => false, 'priority' => 1]);

        // Vehicles
        $vehicle = Vehicle::firstOrCreate(
            ['registration_number' => 'PB10-AG-2026'],
            [
                'owner_id' => $owner->id,
                'driver_id' => $driver->id,
                'vehicle_type_id' => $reefer->id,
                'capacity_kg' => 3200,
                'tracking_status' => 'in_transit'
            ]
        );

        // Transport Requests
        $requestA = TransportRequest::firstOrCreate(
            ['farmer_id' => $profileA->id, 'crop_name' => 'Tomato'],
            [
                'vehicle_type_id' => $reefer->id,
                'weight_kg' => 200,
                'pickup' => 'Ludhiana Farm Gate',
                'destination' => 'Azadpur Mandi',
                'distance_km' => 310,
                'temperature_sensitive' => true,
                'priority' => 'emergency',
                'status' => 'pooled',
                'estimated_cost' => 14610
            ]
        );

        $requestB = TransportRequest::firstOrCreate(
            ['farmer_id' => $profileB->id, 'crop_name' => 'Tomato'],
            [
                'vehicle_type_id' => $reefer->id,
                'weight_kg' => 400,
                'pickup' => 'Khanna Collection Point',
                'destination' => 'Azadpur Mandi',
                'distance_km' => 285,
                'temperature_sensitive' => true,
                'priority' => 'normal',
                'status' => 'pooled',
                'estimated_cost' => 13110
            ]
        );

        Shipment::firstOrCreate(
            ['tracking_code' => 'TRK-DEMO-001'],
            [
                'request_id' => $requestA->id,
                'vehicle_id' => $vehicle->id,
                'driver_id' => $driver->id,
                'shipment_status' => 'in_transit',
                'estimated_arrival' => now()->addHours(5),
                'current_latitude' => 30.9038,
                'current_longitude' => 75.8573,
            ]
        );

        // Pooled Trips
        $trip = PooledTrip::firstOrCreate(
            ['vehicle_id' => $vehicle->id, 'driver_id' => $driverUser->id],
            [
                'route' => ['Ludhiana Farm Gate', 'Khanna Collection Point', 'Azadpur Mandi'],
                'total_cost' => 1800,
                'status' => 'in_transit',
                'started_at' => now()
            ]
        );

        // Trip Members
        $trip->members()->firstOrCreate(['transport_request_id' => $requestA->id], ['farmer_id' => $farmerA->id, 'weight_kg' => 200, 'cost_share' => 600]);
        $trip->members()->firstOrCreate(['transport_request_id' => $requestB->id], ['farmer_id' => $farmerB->id, 'weight_kg' => 400, 'cost_share' => 1200]);

        // Rating
        Rating::firstOrCreate(
            ['driver_id' => $driverUser->id, 'farmer_id' => $farmerA->id],
            [
                'pooled_trip_id' => $trip->id,
                'rating' => 5,
                'review' => 'Careful delivery and good updates.',
                'on_time' => true,
                'safe_delivery' => true
            ]
        );
    }
}
