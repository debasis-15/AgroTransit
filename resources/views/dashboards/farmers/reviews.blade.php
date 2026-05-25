<!-- SECTION 9: REVIEW & RATING PAGE -->
<div class="tab-pane fade" id="reviews" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">⭐ Feedback, Ratings & Reviews</h2>
        <p class="text-muted">Rate your driver's transport speed, cargo handling, and overall platform satisfaction.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-4 text-center">Rate Your Recent Trip with Ravi Kumar</h5>
                    
                    <!-- Stars Selection -->
                    <div class="d-flex justify-content-center gap-3 fs-1 text-muted mb-4" id="ratingStarsContainer">
                        <i class="bi bi-star cursor-pointer star-btn" data-val="1" onclick="selectRatingStar(1)"></i>
                        <i class="bi bi-star cursor-pointer star-btn" data-val="2" onclick="selectRatingStar(2)"></i>
                        <i class="bi bi-star cursor-pointer star-btn" data-val="3" onclick="selectRatingStar(3)"></i>
                        <i class="bi bi-star cursor-pointer star-btn" data-val="4" onclick="selectRatingStar(4)"></i>
                        <i class="bi bi-star cursor-pointer star-btn" data-val="5" onclick="selectRatingStar(5)"></i>
                    </div>

                    <form onsubmit="event.preventDefault(); submitFarmerReview();">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cargo Condition on Delivery</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <input type="checkbox" class="btn-check" id="tagSafe" checked>
                                <label class="btn btn-sm btn-outline-secondary" for="tagSafe">📦 Safe & Undamaged</label>

                                <input type="checkbox" class="btn-check" id="tagTime" checked>
                                <label class="btn btn-sm btn-outline-secondary" for="tagTime">🕐 Extremely On-Time</label>

                                <input type="checkbox" class="btn-check" id="tagCold" checked>
                                <label class="btn btn-sm btn-outline-secondary" for="tagCold">❄️ Cold Chain Maintained</label>

                                <input type="checkbox" class="btn-check" id="tagPolite" checked>
                                <label class="btn btn-sm btn-outline-secondary" for="tagPolite">🤝 Polite Driver</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Detailed Review Comments (Optional)</label>
                            <textarea id="reviewText" class="form-control" rows="4" placeholder="How was the transportation speed, route compliance, and safety of your crop cargo?"></textarea>
                        </div>

                        <button type="submit" class="btn btn-leaf w-100 py-3 fw-bold"><i class="bi bi-send-check me-1"></i> Submit Review & Rating</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
