<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Driver;
use App\Models\TransportOwner;
use App\Models\PooledTrip;
use App\Models\VehicleRequest;
use App\Models\FarmerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_driver_can_request_vehicle_and_owner_can_approve_it()
    {
        // 1. Create Driver and Owner
        $driverUser = User::create([
            'name' => 'Driver Dave',
            'email' => 'dave@driver.test',
            'password' => bcrypt('password'),
            'role' => 'driver',
        ]);
        
        $driver = Driver::create([
            'user_id' => $driverUser->id,
            'license_number' => 'DL-19-12345678',
            'license_expiry' => now()->addYears(5),
            'experience_years' => 3,
            'rating' => 4.5,
            'available' => true,
            'verified' => true,
        ]);

        $ownerUser = User::create([
            'name' => 'Owner Owen',
            'email' => 'owen@owner.test',
            'password' => bcrypt('password'),
            'role' => 'transport_owner',
        ]);

        $owner = TransportOwner::create([
            'user_id' => $ownerUser->id,
            'company_name' => 'Owen Fleet',
            'gst_number' => '03AAAAG2026A1Z1',
            'fleet_size' => 1,
            'office_address' => 'Ludhiana',
            'verified' => true,
        ]);

        $vehicleType = VehicleType::create([
            'name' => 'Mini Truck',
            'min_capacity_kg' => 100,
            'max_capacity_kg' => 1000,
            'refrigerated' => false,
            'base_rate_per_km' => 20,
        ]);

        $vehicle = Vehicle::create([
            'owner_id' => $owner->id,
            'driver_id' => null,
            'registration_number' => 'PB10-XX-1234',
            'vehicle_type_id' => $vehicleType->id,
            'capacity_kg' => 1000,
            'cold_storage' => false,
            'fuel_type' => 'Diesel',
            'tracking_status' => 'maintenance',
        ]);

        // 2. Request assignment as driver
        $this->actingAs($driverUser);
        $response = $this->post(route('driver.request-vehicle', $vehicle->id));
        $response->assertStatus(302); // Redirect back

        $this->assertDatabaseHas('vehicle_requests', [
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'pending',
        ]);

        $vehicleRequest = VehicleRequest::first();

        // 3. Approve request as owner
        $this->actingAs($ownerUser);
        $response = $this->post(route('owner.requests.decide', $vehicleRequest->id), [
            'decision' => 'accept'
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('vehicle_requests', [
            'id' => $vehicleRequest->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'driver_id' => $driver->id,
            'tracking_status' => 'available',
        ]);
    }

    public function test_farmer_can_match_and_book_shared_capacity()
    {
        // 1. Create Farmer
        $farmerUser = User::create([
            'name' => 'Farmer Frank',
            'email' => 'frank@farmer.test',
            'password' => bcrypt('password'),
            'role' => 'farmer',
        ]);

        $farmerProfile = FarmerProfile::create([
            'user_id' => $farmerUser->id,
            'farm_name' => 'Frank Farm',
            'primary_crop' => 'Potato',
            'location' => 'Ludhiana',
            'district' => 'Ludhiana',
            'state' => 'Punjab',
            'verified' => true,
        ]);

        // 2. Create Owner, Driver, Vehicle
        $ownerUser = User::create([
            'name' => 'Owner Owen',
            'email' => 'owen@owner.test',
            'password' => bcrypt('password'),
            'role' => 'transport_owner',
        ]);
        $owner = TransportOwner::create([
            'user_id' => $ownerUser->id,
            'company_name' => 'Owen Fleet',
            'gst_number' => '03AAAAG2026A1Z1',
            'fleet_size' => 1,
            'office_address' => 'Ludhiana',
            'verified' => true,
        ]);

        $driverUser = User::create([
            'name' => 'Driver Dave',
            'email' => 'dave@driver.test',
            'password' => bcrypt('password'),
            'role' => 'driver',
        ]);
        $driver = Driver::create([
            'user_id' => $driverUser->id,
            'license_number' => 'DL-19-12345678',
            'license_expiry' => now()->addYears(5),
            'experience_years' => 3,
            'rating' => 4.5,
            'available' => true,
            'verified' => true,
        ]);

        $vehicleType = VehicleType::create([
            'name' => 'Refrigerated Truck',
            'min_capacity_kg' => 500,
            'max_capacity_kg' => 4000,
            'refrigerated' => true,
            'base_rate_per_km' => 40,
        ]);

        $vehicle = Vehicle::create([
            'owner_id' => $owner->id,
            'driver_id' => $driver->id,
            'registration_number' => 'PB10-XX-1234',
            'vehicle_type_id' => $vehicleType->id,
            'capacity_kg' => 3000,
            'cold_storage' => true,
            'fuel_type' => 'Diesel',
            'tracking_status' => 'available',
        ]);

        // 3. Match vehicles API
        $this->actingAs($farmerUser);
        $response = $this->postJson(route('api.vehicles.match'), [
            'crop_name' => 'Tomato',
            'weight_kg' => 1000,
            'pickup' => 'Ludhiana',
            'destination' => 'Delhi',
            'distance_km' => 300,
            'temperature_sensitive' => true,
            'priority' => 'normal',
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'vehicles'); // Should find our available vehicle

        // 4. Book vehicle to start new pool
        $response = $this->postJson(route('api.bookings'), [
            'crop_name' => 'Tomato',
            'weight_kg' => 1000,
            'pickup' => 'Ludhiana',
            'destination' => 'Delhi',
            'distance_km' => 300,
            'temperature_sensitive' => true,
            'priority' => 'normal',
            'vehicle_id' => $vehicle->id,
            'pooled_trip_id' => null,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // Verify PooledTrip was created
        $this->assertDatabaseHas('pooled_trips', [
            'vehicle_id' => $vehicle->id,
            'status' => 'pending',
        ]);

        $pooledTrip = PooledTrip::first();

        $this->assertDatabaseHas('trip_members', [
            'pooled_trip_id' => $pooledTrip->id,
            'farmer_id' => $farmerUser->id,
            'weight_kg' => 1000,
        ]);

        // Verify vehicle current load updated
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'current_load' => 1000,
            'tracking_status' => 'busy',
        ]);
    }
}
