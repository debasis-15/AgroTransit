@extends('layouts.app', ['title' => 'Fleet Owner Dashboard - AgroTransit'])

@section('content')
<div class="owner-dashboard-shell py-4">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:var(--forest);">{{ $owner->company_name ?? 'GreenLine Transport Hub' }} 🏢</h2>
                <p class="text-muted mb-0">Manage fleet vehicles, driver assignments, and log revenue metrics.</p>
            </div>
            <button class="btn btn-leaf d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                <i class="bi bi-plus-circle"></i> Register New Vehicle
            </button>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Widgets Row -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--forest);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Total Vehicles</p>
                                <h2 class="fw-bold mb-0" style="color:var(--forest);">{{ $vehicles->count() }}</h2>
                            </div>
                            <div class="bg-light rounded p-2 text-success"><i class="bi bi-truck fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            {{ $vehicles->where('tracking_status', 'available')->count() }} online, {{ $vehicles->where('tracking_status', 'maintenance')->count() }} offline
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--leaf);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Active Trips</p>
                                <h2 class="fw-bold mb-0 text-success">{{ $vehicles->where('tracking_status', 'busy')->count() }}</h2>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded p-2"><i class="bi bi-geo-fill fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            Fleet utilization rate today
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--sprout);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Fleet Capacity</p>
                                <h2 class="fw-bold mb-0 text-dark">{{ number_format($vehicles->sum('capacity_kg')) }}kg</h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded p-2"><i class="bi bi-cash-stack fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            Combined haulage capability
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--ink);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Driver Contracts</p>
                                <h2 class="fw-bold mb-0" style="color:var(--ink);">{{ $vehicles->whereNotNull('driver_id')->count() }}</h2>
                            </div>
                            <div class="bg-info bg-opacity-10 text-info rounded p-2"><i class="bi bi-people-fill fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            Verified driver assignments
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Fleet List -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-card-checklist me-2 text-success"></i> Fleet Status</h5>
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-sm btn-outline-template"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
                    </div>
                    <div class="card-body p-0">
                        @if($vehicles->isEmpty())
                            <div class="p-5 text-center text-muted">
                                <i class="bi bi-truck fs-1 d-block mb-3 text-muted"></i>
                                <span>No vehicles registered in your fleet registry yet.</span>
                            </div>
                        @else
                            @foreach($vehicles as $vehicle)
                                <div class="p-4 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="fw-bold mb-1 text-dark">
                                                @if($vehicle->cold_storage) ❄️ @endif {{ $vehicle->type->name ?? 'Vehicle' }} - {{ $vehicle->registration_number }}
                                            </h5>
                                            <small class="text-muted d-block mb-2">Max Capacity: {{ number_format($vehicle->capacity_kg) }} kg | Tracking Status: 
                                                <span class="badge @if($vehicle->tracking_status === 'busy') bg-info @elseif($vehicle->tracking_status === 'available') bg-success @else bg-secondary @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $vehicle->tracking_status)) }}
                                                </span>
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <button class="btn btn-sm btn-outline-template me-1" onclick="simulateOwnerAction('Fetching GPS telemetry log...')">🗺️ GPS Details</button>
                                        </div>
                                    </div>
                                    <div class="row small text-muted mt-2 g-2">
                                        <div class="col-sm-4"><strong>Assigned Driver:</strong> {{ $vehicle->driver?->user->name ?? 'Unassigned' }}</div>
                                        <div class="col-sm-4"><strong>Fuel Type:</strong> {{ ucfirst($vehicle->fuel_type ?? 'Diesel') }}</div>
                                        <div class="col-sm-4"><strong>Current Load:</strong> {{ $vehicle->current_load }} kg</div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- Driver Assignment Requests -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-badge text-success me-2"></i> Driver Assignment Requests</h6>
                    </div>
                    <div class="card-body p-0">
                        @if($incomingRequests->isEmpty())
                            <div class="p-4 text-center text-muted small">
                                No pending requests from drivers.
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($incomingRequests as $req)
                                    <div class="list-group-item p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="fw-bold text-dark small d-block">{{ $req->driver->user->name }}</span>
                                                <small class="text-muted d-block">Experience: {{ $req->driver->experience_years }} years | Rating: ⭐ {{ $req->driver->rating }}</small>
                                                <small class="text-muted d-block">For: <strong>{{ $req->vehicle->registration_number }}</strong> ({{ $req->vehicle->type->name ?? 'Vehicle' }})</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-2">
                                            <form method="post" action="{{ route('owner.requests.decide', $req) }}" class="flex-fill">
                                                @csrf
                                                <input type="hidden" name="decision" value="accept">
                                                <button class="btn btn-xs btn-success w-100 py-1" type="submit">Accept</button>
                                            </form>
                                            <form method="post" action="{{ route('owner.requests.decide', $req) }}" class="flex-fill">
                                                @csrf
                                                <input type="hidden" name="decision" value="reject">
                                                <button class="btn btn-xs btn-outline-danger w-100 py-1" type="submit">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Finance -->
                <div class="card border-0 shadow-sm text-white mb-4" style="background: linear-gradient(135deg, var(--forest) 0%, #355343 100%);">
                    <div class="card-body">
                        <span class="text-white-50 small d-block mb-1">Company Wallet Escrow</span>
                        <h2 class="fw-bold mb-3">₹5,76,000</h2>
                        <div class="d-grid gap-2">
                            <button class="btn btn-light btn-sm fw-bold text-success py-2" onclick="simulateOwnerAction('Processing settlement withdraw of ₹5,76,000...')">💳 Withdraw Earnings</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal to Register New Vehicle -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content rounded-4 border-0" method="post" action="{{ route('owner.vehicles.store') }}">
            @csrf
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark"><i class="bi bi-truck me-2 text-success"></i> Register New Fleet Vehicle</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Registration Number (Vehicle Number)</label>
                    <input type="text" name="registration_number" class="form-control" placeholder="e.g. PB10-XY-1234" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Vehicle Category / Type</label>
                    <select name="vehicle_type_id" class="form-select" required>
                        @foreach($vehicleTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} (Rate: ₹{{ $type->base_rate_per_km }}/km)</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Load Carrying Capacity (kg)</label>
                    <input type="number" name="capacity_kg" class="form-control" placeholder="e.g. 1200" min="50" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Fuel Type</label>
                    <select name="fuel_type" class="form-select" required>
                        <option value="diesel">Diesel</option>
                        <option value="electric">Electric (EV)</option>
                        <option value="cng">CNG</option>
                    </select>
                </div>
                <div class="form-check mb-3 ms-1">
                    <input class="form-check-input" type="checkbox" name="cold_storage" value="1" id="coldStorageCheck">
                    <label class="form-check-label fw-semibold" for="coldStorageCheck">
                        ❄️ Reefer/Cold Storage Support
                    </label>
                </div>
            </div>
            <div class="modal-footer border-top py-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-leaf">Register Vehicle</button>
            </div>
        </form>
    </div>
</div>

<!-- Simple Owner Toast Alert -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="ownerToast" class="toast align-items-center text-white bg-dark border-0 rounded-4 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-warning"></i>
                <span id="ownerToastMsg">Updating fleet status!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
function simulateOwnerAction(msg) {
    document.getElementById('ownerToastMsg').textContent = msg;
    const toast = new bootstrap.Toast(document.getElementById('ownerToast'));
    toast.show();
}
</script>

<style>
.owner-dashboard-shell {
    background-color: var(--bg-warm);
    min-height: calc(100vh - 56px);
}
.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 6px;
}
</style>
@endsection
