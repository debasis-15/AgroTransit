<!-- SECTION 5: LIVE TRACKING PAGE -->
<div class="tab-pane fade" id="tracking" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">🗺️ Live Cargo Tracking</h2>
        <p class="text-muted">Real-time GPS vehicle location, routing paths, and meteorological alerts.</p>
    </div>

    <div class="row">
        <!-- Simulated Map -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm overflow-hidden mb-3">
                <div class="card-body p-0 position-relative" style="height: 400px; background-color:#eef4f1;">
                    <canvas id="trackingCanvas" style="width: 100%; height: 100%; display:block;"></canvas>
                    
                    <div class="position-absolute top-0 start-0 m-3 p-3 bg-white rounded-4 shadow-sm" style="max-width:250px;">
                        <h6 class="fw-bold mb-1 text-dark">Ludhiana → Delhi Route</h6>
                        <p class="small text-muted mb-0"><i class="bi bi-circle-fill text-success" style="font-size:8px;"></i> GPS Signal: Excellent</p>
                    </div>
                </div>
            </div>

            <!-- Route Alerts -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Route & Traffic Alerts</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-25 rounded-circle p-2 text-warning"><i class="bi bi-info-circle fs-4"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Traffic delay near Ambala</h6>
                            <p class="small text-muted mb-0">Heavy vehicles backlog on NH44. Driver Ravi Kumar has diverted through bypass road to save 15 minutes of transit time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Column -->
        <div class="col-lg-4">
            <!-- ETA widget -->
            <div class="card border-0 shadow-sm text-white mb-4" style="background: linear-gradient(135deg, var(--forest) 0%, var(--forest-light) 100%);">
                <div class="card-body text-center py-4">
                    <h6 class="text-white-50 text-uppercase small mb-2 fw-bold">Estimated Arrival (ETA)</h6>
                    <h1 class="fw-bold mb-2 display-6">2h 15m</h1>
                    <p class="small text-white-50 mb-3">Expected: Today at 2:45 PM</p>
                    
                    <div class="progress mb-2" style="height: 10px; background-color: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%;"></div>
                    </div>
                    <span class="small text-white-50">65% of route completed (195 km / 310 km)</span>
                </div>
            </div>

            <!-- Driver Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-badge me-2"></i> Driver Profile</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="font-size:2.5rem;" class="bg-light p-2 rounded-circle">👨🏽‍✈️</div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Ravi Kumar</h6>
                            <div class="text-warning small"><i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i> <span class="text-muted ms-1">(4.8/5)</span></div>
                            <span class="badge bg-success bg-opacity-10 text-success small mt-1">Verified driver</span>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-light rounded-4 mb-3">
                        <div class="d-flex justify-content-between mb-1 small text-muted"><span>Vehicle Number:</span><strong class="text-dark">PB10-AG-2026</strong></div>
                        <div class="d-flex justify-content-between mb-1 small text-muted"><span>Mobile Number:</span><strong class="text-dark">+91 9800000004</strong></div>
                        <div class="d-flex justify-content-between small text-muted"><span>Vehicle Capacity:</span><strong class="text-dark">3.5 Tons (Refrigerated)</strong></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="tel:+919000000004" class="btn btn-leaf btn-sm py-2"><i class="bi bi-telephone-fill"></i> Call Driver</a>
                        <button onclick="openDriverChat('Ravi Kumar')" class="btn btn-outline-template btn-sm py-2"><i class="bi bi-chat-fill"></i> Chat with Driver</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
