<!-- SECTION 2: CREATE TRANSPORT REQUEST PAGE -->
<div class="tab-pane fade" id="create-request" role="tabpanel">
    <div class="mb-4">
        <button onclick="switchTab('dashboard')" class="btn btn-sm btn-outline-secondary mb-2">← Back to Dashboard</button>
        <h2 class="fw-bold" style="color:var(--forest);">📝 New Transport Request</h2>
        <p class="text-muted">Enter crop details, pickup/drop addresses and receive immediate AI Vehicle Recommendations.</p>
    </div>

    <div class="row">
        <!-- Request Form -->
        <div class="col-lg-8">
            <form id="bookingForm" onsubmit="event.preventDefault(); submitBooking();">
                <!-- Crop Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-egg-fried me-2 text-success"></i> Produce Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Crop Name *</label>
                                <input type="text" id="cropName" class="form-control" placeholder="e.g. Tomato, Wheat, Broccoli" required oninput="triggerAIRecommendation()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Weight (kg) *</label>
                                <input type="number" id="cropWeight" class="form-control" min="50" placeholder="e.g. 500" required oninput="triggerAIRecommendation()">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Crop Category</label>
                                <select id="cropCategory" class="form-select" onchange="triggerAIRecommendation()">
                                    <option value="vegetables">Fresh Vegetables</option>
                                    <option value="grains">Grains / Cereals</option>
                                    <option value="fruits">Fruits</option>
                                    <option value="other">Other / General</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Packaging Type</label>
                                <select class="form-select">
                                    <option>Wooden Crates</option>
                                    <option>Plastic Boxes</option>
                                    <option>Bags</option>
                                    <option>Bulk (Loose)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pickup / Destination -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-geo-alt me-2 text-danger"></i> Route & Destination</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 position-relative">
                            <label class="form-label fw-semibold">Pickup Address / Village *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                                <input type="text" id="pickupAddr" class="form-control" placeholder="Type village name (e.g. Ludhiana, Khanna)" required oninput="simulateGoogleAutocomplete('pickupAddr', 'pickupSuggest')">
                            </div>
                            <div id="pickupSuggest" class="autocomplete-dropdown list-group"></div>
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label fw-semibold">Market / Destination Mandi *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shop"></i></span>
                                <input type="text" id="destAddr" class="form-control" placeholder="Type destination market (e.g. Azadpur Mandi, Delhi)" required oninput="simulateGoogleAutocomplete('destAddr', 'destSuggest')">
                            </div>
                            <div id="destSuggest" class="autocomplete-dropdown list-group"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Estimated Distance (km)</label>
                                <input type="number" id="distanceKm" class="form-control" value="150" min="1" oninput="triggerAIRecommendation()">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Pickup Date</label>
                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Expected Delivery Date</label>
                                <input type="date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-gear me-2"></i> Transport Preferences</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Transport Mode</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="priority" id="prefNormal" value="normal" checked onchange="triggerAIRecommendation()">
                                <label class="btn btn-outline-secondary" for="prefNormal">Normal Delivery</label>

                                <input type="radio" class="btn-check" name="priority" id="prefExpress" value="express" onchange="triggerAIRecommendation()">
                                <label class="btn btn-outline-secondary" for="prefExpress">Express Delivery</label>

                                <input type="radio" class="btn-check" name="priority" id="prefEmergency" value="emergency" onchange="triggerAIRecommendation()">
                                <label class="btn btn-outline-secondary" for="prefEmergency">Emergency Transport</label>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="tempSensitive" onchange="triggerAIRecommendation()">
                            <label class="form-check-label fw-semibold" for="tempSensitive">
                                ❄️ Requires Refrigerated Transport (Reefer Truck)
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Additional Driver Notes</label>
                            <textarea class="form-control" rows="2" placeholder="e.g. Handle tomatoes carefully, avoid bumpy roads..."></textarea>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="searchMatchingVehicles()" class="btn btn-lg btn-leaf w-100 py-3 fw-bold mb-4 shadow"><i class="bi bi-search me-1"></i> Scan for Matching Vehicles & Pools</button>
            </form>

            <!-- All Available Vehicles (loaded on tab open) -->
            <div id="allAvailableVehiclesSection" class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="fw-bold mb-0" style="color:var(--forest);"><i class="bi bi-truck me-2 text-success"></i> All Available Vehicles</h4>
                    <span class="badge bg-success px-3 py-2 rounded-pill" id="availableVehicleCount">Loading...</span>
                </div>
                <div id="allAvailableVehiclesGrid" class="row">
                    <div class="col-12 text-center py-5 text-muted">
                        <div class="spinner-border text-success" role="status"></div>
                        <p class="mt-2 small">Fetching available vehicles...</p>
                    </div>
                </div>
            </div>

            <!-- Matching Vehicles Grid Container (after Scan) -->
            <div id="matchingVehiclesSection" class="d-none mb-5">
                <h4 class="fw-bold mb-3" style="color:var(--forest);"><i class="bi bi-shuffle text-success me-2"></i> Available Matches &amp; Shared Pools</h4>
                <div id="matchingVehiclesGrid" class="row">
                    <!-- Dynamic matching cards populate here -->
                </div>
            </div>

        </div>

        <!-- Recommendations & Estimation Sidebar -->
        <div class="col-lg-4">
            <!-- AI Vehicle Recommendation -->
            <div class="card border-0 shadow-sm text-white mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--forest) 0%, #355343 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <h5 class="fw-bold mb-0">✨ AI Vehicle Recommendation</h5>
                        <span class="spinner-border spinner-border-sm text-success d-none" id="aiSpinner"></span>
                    </div>
                    <div id="aiRecomContent">
                        <div class="text-center py-4">
                            <p class="text-white-50 small mb-2">Enter produce and weight details to calculate recommendation</p>
                            <i class="bi bi-robot fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Estimation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-calculator me-2"></i> Dynamic Cost Breakdown</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Base Booking Fee:</span>
                        <span class="fw-bold text-dark" id="calcBaseFee">₹300</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted" id="labelDistance">Distance Charge:</span>
                        <span class="fw-bold text-dark" id="calcDistance">₹0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted" id="labelWeight">Weight Surcharge:</span>
                        <span class="fw-bold text-dark" id="calcWeight">₹0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" id="rowColdStorage">
                        <span class="text-muted">Cold Storage Fee:</span>
                        <span class="fw-bold text-dark" id="calcColdStorage">₹0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Priority Fee:</span>
                        <span class="fw-bold text-dark" id="calcPriority">₹0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-success">
                        <span>Pooling Discount:</span>
                        <span class="fw-bold" id="calcDiscount">-₹0</span>
                    </div>
                    <hr>
                    <div class="p-3 rounded-3" style="background-color:rgba(174,183,132,0.12);">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark mb-0">Estimated Cost:</span>
                            <h4 class="fw-bold mb-0" style="color:var(--forest);" id="calcTotal">₹0</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pooling suggestion box -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-shuffle me-2"></i> Intelligent Pooling Matches</h6>
                </div>
                <div class="card-body" id="poolingMatchSidebar">
                    <div class="text-center text-muted small py-3">
                        Enter route address to scan database for matches.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
