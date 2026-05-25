<!-- SECTION 4: MY SHIPMENTS PAGE -->
<div class="tab-pane fade" id="shipments" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">📦 My Shipment History</h2>
        <p class="text-muted">Track the historical lifecycle of your transport bookings.</p>
    </div>

    <!-- Filters -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 bg-white p-3 rounded-4 shadow-sm">
        <div class="btn-group flex-wrap gap-1" role="group" id="shipmentFilters">
            <button type="button" class="btn btn-sm btn-leaf filter-btn active" data-filter="all">All ({{ count($requests) }})</button>
            <button type="button" class="btn btn-sm btn-outline-secondary filter-btn" data-filter="pending">Pending</button>
            <button type="button" class="btn btn-sm btn-outline-secondary filter-btn" data-filter="transit">In Transit / Active</button>
            <button type="button" class="btn btn-sm btn-outline-secondary filter-btn" data-filter="delivered">Delivered</button>
        </div>
        <div class="text-muted small">Showing records dynamically from database</div>
    </div>

    <!-- Shipments Grid -->
    <div class="row" id="shipmentsListContainer">
        @if(count($requests) > 0)
            @foreach($requests as $request)
                <div class="col-md-6 mb-4 shipment-card-item" data-status="{{ optional($request)->status }}">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-left: 5px solid @if(optional($request)->status === 'delivered') var(--forest) @elseif(optional($request)->status === 'in_transit' || optional($request)->status === 'pooled') var(--leaf) @else var(--sprout) @endif;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">{{ $request->crop_name }} - {{ $request->weight_kg }}kg</h5>
                                <span class="text-muted small">Booking ID: #AGR-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 @if(optional($request)->status === 'delivered') bg-success @elseif(optional($request)->status === 'in_transit' || optional($request)->status === 'pooled') bg-info @else bg-warning text-dark @endif">
                                {{ ucfirst(optional($request)->status) }}
                            </span>
                        </div>

                        <hr class="my-3">

                        <!-- Status timeline -->
                        <div class="mb-3 px-2">
                            <div class="shipment-status-timeline">
                                <div class="status-step active">
                                    <span class="status-marker"></span>
                                    <span class="status-label">Booked</span>
                                </div>
                                <div class="status-step @if(optional($request)->status !== 'pending') active @endif">
                                    <span class="status-marker"></span>
                                    <span class="status-label">Assigned</span>
                                </div>
                                <div class="status-step @if(optional($request)->status === 'in_transit' || optional($request)->status === 'delivered') active @endif">
                                    <span class="status-marker"></span>
                                    <span class="status-label">In Transit</span>
                                </div>
                                <div class="status-step @if(optional($request)->status === 'delivered') active @endif">
                                    <span class="status-marker"></span>
                                    <span class="status-label">Delivered</span>
                                </div>
                            </div>
                        </div>

                        <div class="row small text-muted mb-3 g-2">
                            <div class="col-6"><strong>📍 Pickup:</strong> <span class="text-dark d-block text-truncate">{{ $request->pickup }}</span></div>
                            <div class="col-6"><strong>🏪 Market:</strong> <span class="text-dark d-block text-truncate">{{ $request->destination }}</span></div>
                            <div class="col-6"><strong>📏 Distance:</strong> <span class="text-dark d-block">{{ $request->distance_km }} km</span></div>
                            <div class="col-6"><strong>💰 cost:</strong> <span class="text-dark d-block">₹{{ number_format($request->estimated_cost) }}</span></div>
                        </div>

                        <div class="d-flex gap-2 mt-auto pt-2">
                            @if(optional($request)->status === 'delivered')
                                <button onclick="switchTab('payments')" class="btn btn-sm btn-outline-template w-100">📄 Receipt</button>
                                <button onclick="switchTab('reviews')" class="btn btn-sm btn-outline-template w-100">⭐ Rate Driver</button>
                            @else
                                <button onclick="switchTab('tracking')" class="btn btn-sm btn-leaf w-100">🗺️ Live Track</button>
                                <button onclick="openDriverChat('Ravi Kumar')" class="btn btn-sm btn-outline-template w-100">💬 Chat</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <i class="bi bi-box-seam fs-1 text-muted"></i>
                <h5 class="mt-3 fw-bold text-muted">No transport requests found</h5>
                <p class="text-muted small">Create a new transport request to see it listed here.</p>
            </div>
        @endif
    </div>
</div>
