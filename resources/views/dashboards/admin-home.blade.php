@extends('layouts.app', ['title' => 'Admin Dashboard - AgroTransit'])

@section('content')
<div class="admin-dashboard-shell py-4">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:var(--forest);">System Administration 📊</h2>
                <p class="text-muted mb-0">Platform overview, database statistics, and logistics system control.</p>
            </div>
            <button class="btn btn-leaf d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#systemConfigModal">
                <i class="bi bi-gear-fill"></i> System Configuration
            </button>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $rolePercent = fn ($count) => $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
        @endphp

        <!-- KPI Widgets -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--forest);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Total Farmers</p>
                                <h2 class="fw-bold mb-0" style="color:var(--forest);">{{ $totalFarmers }}</h2>
                            </div>
                            <div class="bg-light rounded p-2 text-success"><i class="bi bi-people-fill fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            Active database records
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--leaf);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Active Vehicles</p>
                                <h2 class="fw-bold mb-0 text-success">{{ $activeVehicles }}</h2>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded p-2"><i class="bi bi-truck fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            Tracking telemetry online
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--sprout);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Today's Revenue</p>
                                <h2 class="fw-bold mb-0 text-dark">₹{{ number_format($todayRevenue, 0) }}</h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded p-2"><i class="bi bi-cash-stack fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            10% platform commission: ₹{{ number_format($platformCommission, 0) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid var(--ink);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Active Shipments</p>
                                <h2 class="fw-bold mb-0" style="color:var(--ink);">{{ $activeShipments }}</h2>
                            </div>
                            <div class="bg-info bg-opacity-10 text-info rounded p-2"><i class="bi bi-box-seam fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            In transit or pending
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Distribution -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill text-success me-2"></i> User Base Analytics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">🌾 Farmers</small>
                                <small>{{ $totalFarmers }} ({{ $rolePercent($totalFarmers) }}%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $rolePercent($totalFarmers) }}%;"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">🚚 Drivers</small>
                                <small>{{ $totalDrivers }} ({{ $rolePercent($totalDrivers) }}%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" style="width: {{ $rolePercent($totalDrivers) }}%;"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">🏢 Vehicle Owners</small>
                                <small>{{ $totalOwners }} ({{ $rolePercent($totalOwners) }}%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-secondary" style="width: {{ $rolePercent($totalOwners) }}%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">👨‍💼 Admin Operators</small>
                                <small>{{ $totalAdmins }} ({{ $rolePercent($totalAdmins) }}%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-dark" style="width: {{ $rolePercent($totalAdmins) }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Metrics -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-wallet2 text-success me-2"></i> Financial Commissions</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="p-4 rounded-4 text-center mb-3" style="background-color:rgba(174,183,132,0.12);">
                            <p class="small text-muted mb-1">Total Escrow Vault Balance</p>
                            <h3 class="fw-bold text-success mb-0">₹{{ number_format($recentRequests->sum('estimated_cost'), 0) }}</h3>
                        </div>
                        <div class="row text-center mb-2">
                            <div class="col-6">
                                <span class="text-muted small">Platform Net Share</span>
                                <h5 class="fw-bold text-dark">₹{{ number_format($recentRequests->sum('estimated_cost') * 0.10, 0) }}</h5>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small">Fleet Records</span>
                                <h5 class="fw-bold text-danger">{{ $totalVehicles }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Active Requests & Driver Verifications -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-card-checklist me-2 text-success"></i> Recent Active Bookings</h5>
                        <button class="btn btn-sm btn-outline-template" data-bs-toggle="modal" data-bs-target="#auditLogsModal">Audit Logs</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Request ID</th>
                                    <th>Farmer</th>
                                    <th>Route Path</th>
                                    <th>System Status</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequests as $transportRequest)
                                    @php
                                        $statusClass = match($transportRequest->status) {
                                            'delivered' => 'bg-success',
                                            'in_transit', 'assigned', 'matched', 'pooled' => 'bg-info',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">#TRN-{{ str_pad($transportRequest->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $transportRequest->farmer?->user?->name ?? 'N/A' }}</td>
                                        <td>{{ $transportRequest->pickup }} → {{ $transportRequest->destination }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $transportRequest->status)) }}</span></td>
                                        <td>₹{{ number_format($transportRequest->estimated_cost ?? 0, 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No booking requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- STEP 2: Drivers Awaiting Verification -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-lock me-2 text-warning"></i> Drivers Awaiting Verification</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($unverifiedDrivers->isEmpty())
                            <div class="p-4 text-center text-muted small">
                                All drivers are approved and verified.
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($unverifiedDrivers as $driverProfile)
                                    <div class="list-group-item p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $driverProfile->user->name }}</h6>
                                            <small class="text-muted d-block">License: {{ $driverProfile->license_number }} | Exp: {{ $driverProfile->experience_years }} years</small>
                                            <small class="text-muted d-block">Phone: {{ $driverProfile->user->phone ?? 'N/A' }} | Email: {{ $driverProfile->user->email }}</small>
                                        </div>
                                        <form method="post" action="{{ route('admin.approve-driver', $driverProfile) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-leaf px-3 py-1 fw-bold" type="submit">Verify & Approve</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Driver Assignment / Appointment Requests -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-check me-2 text-success"></i> Driver Assignment Requests</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Driver</th>
                                    <th>Vehicle</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignmentRequests as $assignmentRequest)
                                    @php
                                        $requestBadge = match($assignmentRequest->status) {
                                            'accepted' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $assignmentRequest->driver?->user?->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $assignmentRequest->vehicle?->registration_number ?? 'N/A' }}</span>
                                            <small class="text-muted d-block">{{ $assignmentRequest->vehicle?->type?->name ?? 'Vehicle' }}</small>
                                        </td>
                                        <td>{{ $assignmentRequest->vehicle?->owner?->user?->name ?? 'N/A' }}</td>
                                        <td><span class="badge {{ $requestBadge }}">{{ ucfirst($assignmentRequest->status) }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No driver assignment requests yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Owners -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-building me-2 text-success"></i> Transport Owners</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Company</th>
                                    <th>Contact</th>
                                    <th>Fleet</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($owners as $owner)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $owner->company_name }}</span>
                                            <small class="text-muted d-block">{{ $owner->office_address }}</small>
                                        </td>
                                        <td>
                                            {{ $owner->user?->phone ?? 'N/A' }}
                                            <small class="text-muted d-block">{{ $owner->user?->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $owner->vehicles_count }}</td>
                                        <td>
                                            <span class="badge {{ $owner->verified ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $owner->verified ? 'Verified' : 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No transport owners found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Tools -->
            <div class="col-lg-4">
                <!-- System Status -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-hdd-network-fill me-2 text-success"></i> Cluster Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold text-dark">App API Server:</span>
                            <span class="badge bg-success">Operational</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold text-dark">DB Node Cluster:</span>
                            <span class="badge bg-success">Healthy</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold text-dark">Escrow Gateway:</span>
                            <span class="badge bg-success">Connected</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small fw-semibold text-dark">Server Uptime:</span>
                            <span class="badge bg-success">99.9%</span>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Registry -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-truck-front-fill me-2 text-success"></i> Vehicle Registry</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($vehicles as $vehicle)
                            @php
                                $vehicleBadge = match($vehicle->tracking_status) {
                                    'available' => 'bg-success',
                                    'in_transit', 'busy' => 'bg-info',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $vehicle->registration_number }}</div>
                                        <small class="text-muted d-block">{{ $vehicle->type?->name ?? 'Vehicle' }} · {{ number_format($vehicle->capacity_kg) }} kg</small>
                                        <small class="text-muted d-block">Owner: {{ $vehicle->owner?->user?->name ?? 'N/A' }}</small>
                                        <small class="text-muted d-block">Driver: {{ $vehicle->driver?->user?->name ?? 'Unassigned' }}</small>
                                    </div>
                                    <span class="badge {{ $vehicleBadge }} align-self-start">{{ ucfirst(str_replace('_', ' ', $vehicle->tracking_status)) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small">No vehicles found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Vehicle Drivers -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-vcard-fill me-2 text-success"></i> Vehicle Drivers</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($drivers as $driver)
                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $driver->user?->name ?? 'N/A' }}</div>
                                        <small class="text-muted d-block">License: {{ $driver->license_number }}</small>
                                        <small class="text-muted d-block">Vehicles: {{ $driver->vehicles->pluck('registration_number')->join(', ') ?: 'Unassigned' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $driver->verified ? 'bg-success' : 'bg-warning text-dark' }}">{{ $driver->verified ? 'Verified' : 'Pending' }}</span>
                                        <small class="text-muted d-block mt-1">{{ number_format((float) $driver->rating, 1) }} ★</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small">No drivers found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Admin Tools -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark">⚙️ Admin Utilities</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-sm btn-outline-template py-2" data-bs-toggle="modal" data-bs-target="#userRegistryModal">👥 Manage User Registry</button>
                            <a class="btn btn-sm btn-outline-template py-2" href="{{ route('admin.report') }}">📊 View Platform Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Configuration Modal -->
<div class="modal fade" id="systemConfigModal" tabindex="-1" aria-labelledby="systemConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="systemConfigModalLabel"><i class="bi bi-gear-fill text-success me-2"></i> System Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @foreach($systemConfig as $label => $value)
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 border bg-light h-100">
                                <small class="text-muted fw-bold text-uppercase">{{ $label }}</small>
                                <div class="fw-semibold text-dark mt-1">{{ $value }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 border h-100">
                            <small class="text-muted fw-bold text-uppercase">Users</small>
                            <h4 class="fw-bold mb-0 text-success">{{ $totalUsers }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 border h-100">
                            <small class="text-muted fw-bold text-uppercase">Vehicles</small>
                            <h4 class="fw-bold mb-0 text-success">{{ $totalVehicles }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 border h-100">
                            <small class="text-muted fw-bold text-uppercase">Open Requests</small>
                            <h4 class="fw-bold mb-0 text-success">{{ $recentRequests->whereNotIn('status', ['delivered', 'cancelled'])->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-template" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Audit Logs Modal -->
<div class="modal fade" id="auditLogsModal" tabindex="-1" aria-labelledby="auditLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="auditLogsModalLabel"><i class="bi bi-clock-history text-success me-2"></i> Audit Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">Time</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>IP Address</th>
                                <th>Device</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td class="fw-semibold">{{ $log->login_time?->format('d M Y, h:i A') }}</td>
                                    <td>{{ $log->user?->name ?? 'Deleted user' }}</td>
                                    <td><span class="badge bg-secondary">{{ $log->user?->role ?? 'unknown' }}</span></td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td class="small text-muted">{{ \Illuminate\Support\Str::limit($log->device ?? 'N/A', 70) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No login activity recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-template" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- User Registry Modal -->
<div class="modal fade" id="userRegistryModal" tabindex="-1" aria-labelledby="userRegistryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="userRegistryModalLabel"><i class="bi bi-people-fill text-success me-2"></i> User Registry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="fw-bold">#{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></td>
                                    <td>
                                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top">
                <a class="btn btn-leaf" href="{{ route('admin.report') }}"><i class="bi bi-download me-1"></i> Export Registry</a>
                <button type="button" class="btn btn-outline-template" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Admin Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="adminToast" class="toast align-items-center text-white bg-dark border-0 rounded-4 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-warning"></i>
                <span id="adminToastMsg">Admin task triggered!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
function simulateAdminAction(msg) {
    document.getElementById('adminToastMsg').textContent = msg;
    const toast = new bootstrap.Toast(document.getElementById('adminToast'));
    toast.show();
}
</script>

<style>
.admin-dashboard-shell {
    background-color: var(--bg-warm);
    min-height: calc(100vh - 56px);
}
</style>
@endsection
