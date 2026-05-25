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
            <button class="btn btn-leaf d-flex align-items-center gap-2" onclick="simulateAdminAction('Opening platform configuration console...')">
                <i class="bi bi-gear-fill"></i> System Configuration
            </button>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                <h2 class="fw-bold mb-0 text-dark">₹4,87,200</h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded p-2"><i class="bi bi-cash-stack fs-4"></i></div>
                        </div>
                        <div class="mt-3 text-muted small">
                            10% platform commission: ₹48,720
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
                                <h2 class="fw-bold mb-0" style="color:var(--ink);">384</h2>
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
                                <small>1,420 (50%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: 50%;"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">🚚 Drivers</small>
                                <small>847 (30%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" style="width: 30%;"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">🏢 Vehicle Owners</small>
                                <small>512 (18%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-secondary" style="width: 18%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold">👨‍💼 Admin Operators</small>
                                <small>68 (2%)</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-dark" style="width: 2%;"></div>
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
                            <h3 class="fw-bold text-success mb-0">₹14,587,200</h3>
                        </div>
                        <div class="row text-center mb-2">
                            <div class="col-6">
                                <span class="text-muted small">Platform Net Share</span>
                                <h5 class="fw-bold text-dark">₹1,458,720</h5>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small">Subsidy Expenses</span>
                                <h5 class="fw-bold text-danger">₹487,200</h5>
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
                        <button class="btn btn-sm btn-outline-template" onclick="simulateAdminAction('Auditing recent platform transactions...')">Audit Logs</button>
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
                                <tr>
                                    <td class="fw-bold">#TRN-001</td>
                                    <td>Amandeep Singh</td>
                                    <td>Ludhiana → Delhi</td>
                                    <td><span class="badge bg-info">In Transit</span></td>
                                    <td>₹1,800</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">#TRN-042</td>
                                    <td>Harpreet Singh</td>
                                    <td>Sangrur → Jaipur</td>
                                    <td><span class="badge bg-success">Delivered</span></td>
                                    <td>₹1,200</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">#TRN-089</td>
                                    <td>Meera Devi</td>
                                    <td>Khanna → Delhi</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>₹2,100</td>
                                </tr>
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

                <!-- Admin Tools -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark">⚙️ Admin Utilities</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-sm btn-outline-template py-2" onclick="simulateAdminAction('Launching User Management console...')">👥 Manage User Registry</button>
                            <button class="btn btn-sm btn-outline-template py-2" onclick="simulateAdminAction('Exporting platform CSV reports...')">📊 Export PDF Reports</button>
                        </div>
                    </div>
                </div>
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
