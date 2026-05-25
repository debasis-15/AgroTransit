<!-- SECTION 3: POOLING REQUEST PAGE -->
<div class="tab-pane fade" id="pooling" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">🤝 Shared Trips (Pooling Requests)</h2>
        <p class="text-muted">Share your transport vehicle with nearby farmers going to the same Mandis and save up to 40% on fuel and base rates.</p>
    </div>

    <div class="row">
        <!-- Active Pools -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">Active Pooling Vehicles Nearby</h5>
                </div>
                <div class="card-body p-0" id="poolingMarketplaceGrid">
                    
                    <!-- Pool Item 1 -->
                    <div class="p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-1">🚚 Mini Truck - Ludhiana to Delhi Mandi</h5>
                                <span class="text-muted small"><i class="bi bi-calendar-event"></i> Departs: Tomorrow, 7:00 AM | Route via NH44</span>
                            </div>
                            <span class="badge bg-success px-3 py-2 text-white">Save ~₹850</span>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>Capacity Filled: <strong>700kg / 1000kg</strong></span>
                                <span class="text-success fw-bold">70% Filled</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 70%;"></div>
                            </div>
                        </div>

                        <div class="row text-muted small mb-3">
                            <div class="col-6 col-sm-3"><strong>Primary Farmer:</strong> Harpreet Singh</div>
                            <div class="col-6 col-sm-3"><strong>Current Farmers:</strong> 3 Joined</div>
                            <div class="col-6 col-sm-3"><strong>Available Space:</strong> 300 kg</div>
                            <div class="col-6 col-sm-3"><strong>Base Fee Share:</strong> ₹1,200</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-leaf px-4" onclick="confirmJoinPool('Ludhiana to Delhi Mandi')">Join Pool</button>
                            <button class="btn btn-sm btn-outline-template" onclick="showRouteModal('Ludhiana -> Khanna -> Ambala -> Delhi')">View Route Map</button>
                        </div>
                    </div>

                    <!-- Pool Item 2 -->
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-1">🚚 Refrigerated Truck - Khanna to Delhi Mandi</h5>
                                <span class="text-muted small"><i class="bi bi-calendar-event"></i> Departs: May 22, 6:00 AM | Cold Chain Pool</span>
                            </div>
                            <span class="badge bg-success px-3 py-2 text-white">Save ~₹1,450</span>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>Capacity Filled: <strong>2200kg / 4000kg</strong></span>
                                <span class="text-info fw-bold">55% Filled</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 55%;"></div>
                            </div>
                        </div>

                        <div class="row text-muted small mb-3">
                            <div class="col-6 col-sm-3"><strong>Primary Farmer:</strong> Meera Devi</div>
                            <div class="col-6 col-sm-3"><strong>Current Farmers:</strong> 2 Joined</div>
                            <div class="col-6 col-sm-3"><strong>Available Space:</strong> 1800 kg</div>
                            <div class="col-6 col-sm-3"><strong>Base Fee Share:</strong> ₹2,800</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-leaf px-4" onclick="confirmJoinPool('Khanna to Delhi Cold Chain')">Join Pool</button>
                            <button class="btn btn-sm btn-outline-template" onclick="showRouteModal('Khanna -> Kurukshetra -> Delhi')">View Route Map</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ/Info Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="background-color: var(--surface);">
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-3">How Pooling Works</h5>
                    <div class="d-flex gap-3 mb-3">
                        <div class="text-success fs-3"><i class="bi bi-1-circle-fill"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">AI Matching</h6>
                            <p class="small text-muted mb-0">Our system identifies other farmers with overlapping pickup locations and dates going to the same Mandi.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="text-success fs-3"><i class="bi bi-2-circle-fill"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Dynamic Cost Division</h6>
                            <p class="small text-muted mb-0">The vehicle base rate and fuel costs are divided proportionally based on the crop weight you transport.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="text-success fs-3"><i class="bi bi-3-circle-fill"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">CO2 Reduction</h6>
                            <p class="small text-muted mb-0">Fewer truck trips mean lower carbon footprints and cleaner rural environments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
