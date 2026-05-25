<!-- SECTION 1: DASHBOARD HOME -->
<div class="tab-pane fade show active" id="dashboard" role="tabpanel">
    <div class="dashboard-topbar mb-4">
        <div class="topbar-text">
            <h1 class="topbar-title">Good Morning, {{ auth()->user()->name }} 👋</h1>
            <p class="topbar-subtitle mb-0">Track your agricultural transport activity.</p>
        </div>
        <div class="topbar-actions d-flex align-items-center gap-3 flex-wrap">
            <button type="button" class="topbar-icon-btn" aria-label="Notifications">
                <i class="bi bi-bell"></i>
            </button>
            <button onclick="switchTab('create-request')" class="btn btn-topbar-cta">Create Request</button>
            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        </div>
    </div>

    <!-- Widgets Row -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, var(--forest) 0%, var(--forest-light) 100%); border-left: 5px solid var(--sprout);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1 small text-uppercase fw-bold">Active Shipments</p>
                            <h2 class="fw-bold mb-0" id="activeShipmentsCount">3</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded p-2"><i class="bi bi-truck fs-4"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="#tracking" onclick="switchTab('tracking')" class="text-white small text-decoration-none fw-semibold">Track active shipments →</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 bg-white border-left-leaf">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Money Saved (Pooling)</p>
                            <h2 class="fw-bold mb-0 text-success" id="poolSavingsAmount">₹4,250</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded p-2"><i class="bi bi-wallet2 fs-4"></i></div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <span class="text-success fw-bold"><i class="bi bi-arrow-up-right"></i> ₹850</span> saved last trip
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 bg-white" style="border-left: 5px solid var(--sprout);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Pending Requests</p>
                            <h2 class="fw-bold mb-0" style="color:var(--forest);" id="pendingRequestsCount">2</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-2"><i class="bi bi-hourglass-split fs-4"></i></div>
                    </div>
                    <div class="mt-3 text-muted small">
                        Matching vehicles near Ludhiana
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 bg-white" style="border-left: 5px solid var(--ink);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Vehicles Nearby</p>
                            <h2 class="fw-bold mb-0" style="color:var(--ink);" id="vehiclesNearbyCount">12</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2"><i class="bi bi-geo-fill fs-4"></i></div>
                    </div>
                    <div class="mt-3 text-muted small">
                        Within a 5km radius
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lower Dashboard section -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Recent Shipments -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color:var(--forest);"><i class="bi bi-card-checklist me-2"></i> Recent Shipment Details</h5>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active Live Trips</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="recentShipmentsList">
                        <div class="list-group-item p-4 text-center text-muted">
                            <div class="spinner-border text-success" role="status" aria-hidden="true"></div>
                            <div class="mt-3">Loading live shipment updates...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar widgets -->
        <div class="col-lg-4">
            <!-- Pooling Opportunities Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold" style="color:var(--forest);"><i class="bi bi-people-fill me-2"></i> Pooling Matches</h6>
                </div>
                <div class="card-body">
                    <div class="pool-box p-3 rounded-4 mb-3 border border-success border-opacity-25" style="background-color:rgba(174,183,132,0.08);">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold small text-dark">🥦 Vegetables to Delhi</span>
                            <span class="badge bg-success text-white">Save ₹850</span>
                        </div>
                        <p class="small text-muted mb-2">3 farmers pooling | Load: 850kg/1000kg</p>
                        <button onclick="switchTab('pooling')" class="btn btn-sm btn-success w-100">Join Shared Pool</button>
                    </div>
                    <div class="pool-box p-3 rounded-4 border border-success border-opacity-25" style="background-color:rgba(174,183,132,0.08);">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold small text-dark">🍅 Tomatoes to Mumbai</span>
                            <span class="badge bg-success text-white">Save ₹1,200</span>
                        </div>
                        <p class="small text-muted mb-2">2 farmers pooling | Load: 600kg/1200kg</p>
                        <button onclick="switchTab('pooling')" class="btn btn-sm btn-success w-100">Join Shared Pool</button>
                    </div>
                </div>
            </div>

            <!-- Interactive Cost Savings Dashboard -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold" style="color:var(--forest);"><i class="bi bi-graph-up-arrow me-2"></i> Savings Analytics</h6>
                </div>
                <div class="card-body text-center">
                    <div class="chart-container mb-3" style="position: relative; height:180px; width:100%">
                        <canvas id="savingsChart"></canvas>
                    </div>
                    <p class="small text-muted">You have saved <strong class="text-success">35%</strong> on average through shared transport logistics.</p>
                </div>
            </div>
        </div>
    </div>
</div>
