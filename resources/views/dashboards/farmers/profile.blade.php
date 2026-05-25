<!-- SECTION 10: PROFILE PAGE -->
<div class="tab-pane fade" id="profile" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">👤 My Farm & Profile Settings</h2>
        <p class="text-muted">Keep your agricultural logistics preferences, locations, and personal files updated.</p>
    </div>

    <div class="row">
        <!-- Profile Forms -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->phone ?? '+91 9000000001' }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Preferred Language</label>
                            <select class="form-select">
                                <option selected>English</option>
                                <option>Punjabi (ਪੰਜਾਬੀ)</option>
                                <option>Hindi (हिन्दी)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">Farm & Produce Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Village / Farm Location</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->village ?? 'Ludhiana Rural' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Farm Size (Acres)</label>
                            <input type="number" class="form-control" value="12">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Primary Crop Produced</label>
                            <input type="text" class="form-control" value="Tomato, Broccoli">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Preferred Mandi Markets</label>
                            <input type="text" class="form-control" value="Azadpur Mandi (Delhi), Ludhiana Mandi">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark">Notification Preferences</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notifySMS" checked>
                        <label class="form-check-label fw-semibold text-dark" for="notifySMS">SMS Mobile Alerts</label>
                        <small class="text-muted d-block">Receive instant SMS alerts when drivers are nearby or delayed.</small>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyEmail" checked>
                        <label class="form-check-label fw-semibold text-dark" for="notifyEmail">Email Notifications</label>
                        <small class="text-muted d-block">Invoices and trip receipts delivered directly to your inbox.</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="notifyPush" checked>
                        <label class="form-check-label fw-semibold text-dark" for="notifyPush">Web Push Alerts</label>
                        <small class="text-muted d-block">Real-time status updates while using the AgroTransit portal.</small>
                    </div>
                    
                    <hr class="my-4">
                    <button class="btn btn-leaf w-100" onclick="triggerToastNotification('Profile preferences updated successfully!')">Save Preferences</button>
                </div>
            </div>
        </div>
    </div>
</div>
