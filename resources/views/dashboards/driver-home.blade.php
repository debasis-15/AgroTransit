@extends('layouts.app', ['title' => 'Driver Dashboard - AgroTransit'])

@section('content')
<div class="driver-dashboard-shell py-4">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:var(--forest);">Welcome back, {{ $driver->user->name }}! 🚚</h2>
                <p class="text-muted mb-0">
                    @if($assignedVehicle)
                        Vehicle Assigned: <strong>{{ $assignedVehicle->registration_number }}</strong> ({{ $assignedVehicle->type->name ?? 'Vehicle' }} @if($assignedVehicle->cold_storage) - Reefer ❄️ @endif)
                    @else
                        Status: <strong>No Vehicle Assigned</strong>
                    @endif
                </p>
            </div>
            @if($assignedVehicle)
                <button class="btn btn-leaf d-flex align-items-center gap-2" onclick="showQrModal()">
                    <i class="bi bi-qr-code"></i> Show Verification QR Code
                </button>
            @endif
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(!$isVerified)
            <!-- STEP 2: Verification Pending State Banner -->
            <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 p-4 d-flex align-items-center gap-3">
                <i class="bi bi-shield-exclamation fs-1 text-warning"></i>
                <div>
                    <h5 class="fw-bold mb-1">Verification Status: Pending Admin Approval</h5>
                    <p class="mb-0 text-muted small">Your profile credentials (license and ID) are currently undergoing security verification. You will be able to access the vehicle marketplace and accept shipments once approved by the administrator.</p>
                </div>
            </div>
        @elseif(!$assignedVehicle)
            <!-- STEP 3: Vehicle Marketplace (Verified but no vehicle assigned) -->
            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 p-4 d-flex align-items-center gap-3">
                <i class="bi bi-info-circle-fill fs-2 text-info"></i>
                <div>
                    <h6 class="fw-bold mb-1">Marketplace Active</h6>
                    <p class="mb-0 text-muted small">Please browse available fleet vehicles from verified owners below and request assignment to start operating.</p>
                </div>
            </div>

            <!-- Marketplace Search / Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="get" action="{{ route('driver.dashboard') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Filter by Location</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Ludhiana, Khanna" value="{{ request('location') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">Min Capacity (kg)</label>
                            <input type="number" name="capacity" class="form-control" placeholder="e.g. 1000" value="{{ request('capacity') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="refrigerated" value="1" id="reeferFilter" @if(request('refrigerated')) checked @endif>
                                <label class="form-check-label fw-bold small text-muted" for="reeferFilter">
                                    ❄️ Requires Reefer
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-leaf w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Vehicles Available -->
            <h4 class="fw-bold mb-3" style="color:var(--forest);">🚚 Available Fleet Vehicles</h4>
            <div class="row">
                @if($availableVehicles->isEmpty())
                    <div class="col-12 text-center py-5">
                        <div class="card border-0 shadow-sm p-5 text-muted">
                            <i class="bi bi-emoji-frown fs-1 mb-3 text-muted"></i>
                            <span>No matching available vehicles found in the marketplace right now.</span>
                        </div>
                    </div>
                @else
                    @foreach($availableVehicles as $vehicle)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between mb-3 align-items-center">
                                        <h5 class="fw-bold mb-0 text-dark">{{ $vehicle->registration_number }}</h5>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Available</span>
                                    </div>
                                    <h6 class="fw-bold text-muted small mb-1">{{ $vehicle->type->name ?? 'Vehicle' }}</h6>
                                    <p class="small text-muted mb-3"><i class="bi bi-building"></i> Owner: {{ $vehicle->owner->company_name ?? 'GreenLine Transport' }}</p>
                                    
                                    <ul class="list-unstyled small text-muted mb-4">
                                        <li><strong>Capacity:</strong> {{ number_format($vehicle->capacity_kg) }} kg</li>
                                        <li><strong>Cold Storage:</strong> @if($vehicle->cold_storage) ❄️ Enabled @else ❌ No @endif</li>
                                        <li><strong>Fuel Type:</strong> {{ ucfirst($vehicle->fuel_type ?? 'Diesel') }}</li>
                                    </ul>

                                    @if(in_array($vehicle->id, $pendingRequests))
                                        <button class="btn btn-secondary w-100 rounded-3 py-2 fw-bold" disabled>
                                            <i class="bi bi-clock-history me-1"></i> Assignment Request Pending
                                        </button>
                                    @else
                                        <form method="post" action="{{ route('driver.request-vehicle', $vehicle) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-leaf w-100 rounded-3 py-2 fw-bold">
                                                Request Assignment →
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <!-- STEP 6: Active Operator Dashboard (Verified & Vehicle Assigned) -->
            <!-- Widgets Row -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--forest);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Active Jobs</p>
                                    <h2 class="fw-bold mb-0" style="color:var(--forest);">{{ $activeShipments->count() }}</h2>
                                </div>
                                <div class="bg-light rounded p-2 text-success"><i class="bi bi-geo-alt-fill fs-4"></i></div>
                            </div>
                            <div class="mt-3 text-muted small">
                                Loaded cargo monitoring
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--leaf);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Today's Earnings</p>
                                    <h2 class="fw-bold mb-0 text-success">₹3,600</h2>
                                </div>
                                <div class="bg-success bg-opacity-10 text-success rounded p-2"><i class="bi bi-cash-stack fs-4"></i></div>
                            </div>
                            <div class="mt-3 text-muted small">
                                Proportional shares completed
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--sprout);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Safety Score</p>
                                    <h2 class="fw-bold mb-0 text-dark">98/100</h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 text-warning rounded p-2"><i class="bi bi-shield-check fs-4"></i></div>
                            </div>
                            <div class="mt-3 text-muted small">
                                Top 5% operators in Ludhiana
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--ink);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Compartment Load</p>
                                    <h2 class="fw-bold mb-0" style="color:var(--ink);">{{ $assignedVehicle->current_load }}kg</h2>
                                </div>
                                <div class="bg-info bg-opacity-10 text-info rounded p-2"><i class="bi bi-truck-flatbed fs-4"></i></div>
                            </div>
                            <div class="mt-3 text-muted small">
                                Maximum Carry: {{ number_format($assignedVehicle->capacity_kg) }} kg
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Active Jobs List -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-truck me-2 text-success"></i> Active Transport Schedule</h5>
                        </div>
                        <div class="card-body p-0">
                            @if($activeShipments->isEmpty())
                                <div class="p-5 text-center text-muted">
                                    <i class="bi bi-box-seam fs-1 mb-3 text-muted"></i>
                                    <span>No active shipments assigned. Standing by for bookings.</span>
                                </div>
                            @else
                                @foreach($activeShipments as $shipment)
                                    <div class="p-4 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                            <div>
                                                <h5 class="fw-bold mb-1 text-dark">{{ $shipment->request->crop_name ?? 'Produce' }} to {{ $shipment->request->destination ?? 'Mandi' }}</h5>
                                                <div class="text-muted small"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $shipment->request->pickup ?? 'Pickup' }} <i class="bi bi-arrow-right mx-1"></i> {{ $shipment->request->destination ?? 'Mandi' }}</div>
                                            </div>
                                            <span class="badge bg-info px-3 py-2 text-white">{{ ucfirst($shipment->shipment_status) }}</span>
                                        </div>

                                        <div class="row text-muted small mb-3 g-2 mt-2">
                                            <div class="col-6 col-sm-3"><strong>Farmer Name:</strong> {{ $shipment->request->farmer->user->name ?? 'Farmer Partner' }}</div>
                                            <div class="col-6 col-sm-3"><strong>Load Weight:</strong> {{ $shipment->request->weight_kg }} kg</div>
                                            <div class="col-6 col-sm-3"><strong>Priority:</strong> {{ ucfirst($shipment->request->priority) }}</div>
                                            <div class="col-6 col-sm-3"><strong>Trip Value:</strong> ₹{{ number_format($shipment->request->estimated_cost) }}</div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-leaf" onclick="triggerDriverToast('📍 Live telemetry coordinates transmitted successfully.')">📍 Transmit Telemetry</button>
                                            <a href="tel:{{ $shipment->request->farmer->user->phone ?? '9000000001' }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-telephone-fill"></i> Call Farmer</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-lg-4">
                    <!-- Wallet -->
                    <div class="card border-0 shadow-sm text-white mb-4" style="background: linear-gradient(135deg, var(--forest) 0%, #355343 100%);">
                        <div class="card-body">
                            <span class="text-white-50 small d-block mb-1">Driver Balance Wallet</span>
                            <h2 class="fw-bold mb-3">₹18,750</h2>
                            <div class="d-grid gap-2">
                                <button class="btn btn-light btn-sm fw-bold text-success py-2" onclick="simulateWithdrawal()">💳 Withdraw Balance</button>
                            </div>
                        </div>
                    </div>

                    <!-- Compartment Telematics -->
                    @if($assignedVehicle->cold_storage)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-thermometer-snow me-2 text-info"></i> Cold Chain Telematics</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Current Compartment Temp:</span>
                                    <span class="text-success fw-bold">11.8°C (Stable)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Compressor Status:</span>
                                    <span class="text-dark">Running (Eco Mode)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 small">
                                    <span class="text-muted">Door sensor:</span>
                                    <span class="text-dark">Locked & Sealed</span>
                                </div>
                                <div class="p-2 rounded bg-light border text-center small text-muted">
                                    <i class="bi bi-info-circle text-info"></i> Logs updated automatically every 5 minutes via IoT sensor.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal presentation for QR Verification code -->
@if($assignedVehicle)
    <div class="modal fade" id="driverQrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-bottom py-3">
                    <h6 class="modal-title fw-bold text-dark"><i class="bi bi-qr-code me-2 text-success"></i> Verification QR Code</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="small text-muted mb-4">Present this QR code to the matching farmers at the Mandi to verify delivery and release escrow payment.</p>
                    <div class="p-3 bg-white border border-2 border-dashed border-success rounded-4 mx-auto mb-3" style="width:200px; height:200px;">
                        <div style="background-image: url('https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=AGROTRANSIT-VERIFY-{{ $assignedVehicle->registration_number }}'); background-size:cover; width:100%; height:100%;"></div>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Code: #AGRO-{{ $assignedVehicle->registration_number }}</h6>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Simple Driver Toast alert mechanism -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="driverToast" class="toast align-items-center text-white bg-dark border-0 rounded-4 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-warning"></i>
                <span id="driverToastMsg">Trip status updated!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
function showQrModal() {
    const qModal = new bootstrap.Modal(document.getElementById('driverQrModal'));
    qModal.show();
}

function triggerDriverToast(msg) {
    document.getElementById('driverToastMsg').textContent = msg;
    const toast = new bootstrap.Toast(document.getElementById('driverToast'));
    toast.show();
}

function simulateWithdrawal() {
    triggerDriverToast("Processing withdrawal request of ₹18,750...");
    setTimeout(() => {
        triggerDriverToast("✓ Withdrawal approved! Funds transferred to UPI account.");
    }, 1500);
}
</script>

<style>
.driver-dashboard-shell {
    background-color: var(--bg-warm);
    min-height: calc(100vh - 56px);
}
</style>
@endsection
