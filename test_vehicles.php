<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate authenticated request with farmer user
$user = App\Models\User::where('role', 'farmer')->first();
Illuminate\Support\Facades\Auth::login($user);

// Directly call the controller
$controller = new App\Http\Controllers\TransportRequestController();
$response = $controller->availableVehicles();
$data = json_decode($response->getContent(), true);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Vehicle count: " . count($data['vehicles'] ?? []) . "\n";
foreach (($data['vehicles'] ?? []) as $v) {
    echo "  - " . $v['registration_number'] . " | available: " . ($v['is_available'] ? 'YES' : 'NO') . " | booked_date: " . ($v['booked_date'] ?? 'null') . "\n";
}
if (isset($data['error'])) echo "ERROR: " . $data['error'] . "\n";
