@extends('layouts.app', ['title' => 'Farmer Dashboard - AgroTransit'])

@section('content')
<div class="farmer-dashboard-shell">
    @include('dashboards.farmers.styles')
    
    <div class="row g-0">
        <!-- Sidebar Navigation -->
        @include('dashboards.farmers.sidebar')

        <!-- Main Content Area -->
        <div class="col px-4 py-4 content-column" data-dashboard-url="{{ route('farmer.dashboard.data') }}">
            <!-- Alert Banner (dynamic messages) -->
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div id="liveNotifications" class="mb-4"></div>

            <!-- Dashboard sections -->
            <div class="tab-content" id="dashboardTabs">
                <!-- TAB 1: Dashboard Home -->
                @include('dashboards.farmers.dashboard-home')

                <!-- TAB 2: Create Request -->
                @include('dashboards.farmers.create-request')

                <!-- TAB 3: Pooling -->
                @include('dashboards.farmers.pooling')

                <!-- TAB 4: Shipments -->
                @include('dashboards.farmers.shipments')

                <!-- TAB 5: Tracking -->
                @include('dashboards.farmers.tracking')

                <!-- TAB 6: Chat -->
                @include('dashboards.farmers.chat')

                <!-- TAB 7: Payments -->
                @include('dashboards.farmers.payments')

                <!-- TAB 8: Verification -->
                @include('dashboards.farmers.verification')

                <!-- TAB 9: Reviews -->
                @include('dashboards.farmers.reviews')

                <!-- TAB 10: Profile -->
                @include('dashboards.farmers.profile')
            </div>
        </div>
    </div>
</div>

<!-- Floating Notification Toasts Manager -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="agroToast" class="toast align-items-center text-white bg-dark border-0 rounded-4 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-warning"></i>
                <span id="agroToastMsg">Real-time update triggered!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Route visual overlay map modal -->
<div class="modal fade" id="routeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark"><i class="bi bi-map me-2 text-success"></i> Proposed Pooling Route Map</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="map-modal-placeholder rounded-4 mb-3 bg-light d-flex flex-column align-items-center justify-content-center border" style="height:250px;">
                    <i class="bi bi-signpost-split fs-1 text-success mb-2"></i>
                    <p class="small text-dark fw-bold mb-1" id="routeModalPath">Loading Route...</p>
                    <span class="text-muted small">Optimized path based on traffic & fuel division</span>
                </div>
                <button class="btn btn-leaf w-100" data-bs-dismiss="modal">Close Route</button>
            </div>
        </div>
    </div>
</div>

<!-- Load Dashboard Controller Script -->
<script src="{{ asset('js/dashboard/dashboard-controller.js') }}"></script>

@endsection
