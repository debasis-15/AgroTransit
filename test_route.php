<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate the request as a logged in farmer
$user = App\Models\User::where('role', 'farmer')->first();
if (!$user) {
    echo "No farmer user found!\n";
    exit;
}

echo "Farmer user: " . $user->name . " (id: " . $user->id . ")\n";

// Check the route exists
$routes = app('router')->getRoutes();
$found = false;
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'vehicles/available')) {
        $found = true;
        echo "Route found: " . $route->uri() . " | methods: " . implode(',', $route->methods()) . "\n";
        echo "Middleware: " . implode(', ', $route->gatherMiddleware()) . "\n";
    }
}
if (!$found) echo "Route NOT FOUND!\n";
