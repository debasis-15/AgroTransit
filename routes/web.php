<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerDashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransportRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'home'])->name('home');
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
Route::get('/signup', [RegisterController::class, 'create'])->name('register');
Route::post('/signup', [RegisterController::class, 'store'])->name('register.store');

Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer/dashboard', [DashboardController::class, 'farmer'])->name('farmer.dashboard');
    Route::get('/farmer/dashboard-data', [FarmerDashboardController::class, 'dashboardData'])->name('farmer.dashboard.data');
    Route::get('/api/vehicles/available', [TransportRequestController::class, 'availableVehicles'])->name('api.vehicles.available');
    Route::post('/api/vehicles/match', [TransportRequestController::class, 'matchVehicles'])->name('api.vehicles.match');
    Route::post('/api/bookings', [TransportRequestController::class, 'storeBookedRequest'])->name('api.bookings');

});

Route::middleware(['auth', 'role:driver'])->group(function () {
    Route::get('/driver/dashboard', [DashboardController::class, 'driver'])->name('driver.dashboard');
    Route::post('/driver/request-vehicle/{vehicle}', [DashboardController::class, 'requestVehicle'])->name('driver.request-vehicle');
});

Route::middleware(['auth', 'role:transport_owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
    Route::post('/owner/vehicles', [DashboardController::class, 'addVehicle'])->name('owner.vehicles.store');
    Route::post('/owner/requests/{assignmentRequest}/decide', [DashboardController::class, 'decideVehicleRequest'])->name('owner.requests.decide');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::post('/admin/approve-driver/{driver}', [DashboardController::class, 'approveDriver'])->name('admin.approve-driver');
});

Route::redirect('/farmer', '/farmer/dashboard');
Route::redirect('/driver', '/driver/dashboard');
Route::redirect('/admin', '/admin/dashboard');

Route::get('/requests/create', [TransportRequestController::class, 'create'])->name('requests.create');
Route::post('/requests', [TransportRequestController::class, 'store'])->name('requests.store');
