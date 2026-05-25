// Dashboard Tab Switching Controller (SPA Experience)
document.addEventListener('DOMContentLoaded', () => {
    // Check URL fragment for navigation
    const hash = window.location.hash || '#dashboard';
    const tabName = hash.replace('#', '');
    switchTab(tabName);

    // Sidebar listeners
    document.querySelectorAll('#menu a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = link.getAttribute('data-tab');
            switchTab(target);
        });
    });

    // Render Dynamic Savings Chart
    renderSavingsChart();

    // Setup Live Tracking Canvas
    initLiveTrackingMap();

    // Load live farmer dashboard summary and ship statuses
    fetchDashboardData();
    setInterval(fetchDashboardData, 12000);
});

// === TAB SWITCHING ===
function switchTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-pane').forEach(tab => {
        tab.classList.remove('show', 'active');
    });

    // Show selected tab
    const targetTab = document.getElementById(tabId);
    if (targetTab) {
        targetTab.classList.add('show', 'active');
        window.location.hash = tabId;

        // Update sidebar links active class
        document.querySelectorAll('#menu a').forEach(link => {
            if (link.getAttribute('data-tab') === tabId) {
                link.classList.add('active');
                link.classList.remove('text-white-50');
            } else {
                link.classList.remove('active');
                link.classList.add('text-white-50');
            }
        });

        // Custom hooks for tracking/canvas resize
        if (tabId === 'tracking') {
            setTimeout(drawTrackingMap, 100);
        }
    }
}

// === AUTOCOMPLETE SIMULATION ===
const mockVillages = ['Ludhiana Rural Farm Gate', 'Khanna Agricultural Hub', 'Sangrur Wheat Warehouses', 'Patiala Rice Fields', 'Jalandhar Vegetable Market'];
const mockMarkets = ['Azadpur Mandi, Delhi', 'Jaipur Fruits Bazaar', 'Ludhiana Main Mandi', 'Chandigarh Grain Yard', 'Mumbai Exports Terminal'];

function simulateGoogleAutocomplete(inputId, suggestId) {
    const query = document.getElementById(inputId).value.toLowerCase();
    const suggestBox = document.getElementById(suggestId);
    suggestBox.innerHTML = '';

    if (!query) return;

    const pools = inputId === 'pickupAddr' ? mockVillages : mockMarkets;
    const matches = pools.filter(p => p.toLowerCase().includes(query));

    matches.forEach(match => {
        const item = document.createElement('a');
        item.href = '#';
        item.className = 'list-group-item list-group-item-action py-2 small';
        item.innerHTML = `<i class="bi bi-geo-alt-fill text-muted me-1"></i> ${match}`;
        item.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById(inputId).value = match;
            suggestBox.innerHTML = '';

            if (inputId === 'destAddr') {
                updatePoolingSuggestions(match);
            }
            triggerAIRecommendation();
        });
        suggestBox.appendChild(item);
    });
}

function updatePoolingSuggestions(destination) {
    const poolingBox = document.getElementById('poolingMatchSidebar');
    if (!poolingBox) return;
    poolingBox.innerHTML = `
        <div class="pool-option-preview p-3 rounded-3 border border-success mb-2" style="background-color:rgba(174,183,132,0.12);">
            <div class="d-flex justify-content-between mb-1">
                <strong class="small text-dark">Shared Truck (Mini)</strong>
                <span class="text-success small fw-bold">Save ₹850</span>
            </div>
            <p class="small text-muted mb-2">Going to ${destination} tomorrow</p>
            <button class="btn btn-xs btn-success w-100" onclick="switchTab('pooling')">Join Shared Pool</button>
        </div>
    `;
}

// === AI RECOMMENDATION LOGIC ===
function triggerAIRecommendation() {
    const crop = document.getElementById('cropName')?.value || '';
    const weight = parseInt(document.getElementById('cropWeight')?.value) || 0;
    const distance = parseInt(document.getElementById('distanceKm')?.value) || 0;
    const tempSensitive = document.getElementById('tempSensitive')?.checked || false;
    const isEmergency = document.getElementById('prefEmergency')?.checked || false;

    if (!crop || weight <= 0) return;

    const recomBox = document.getElementById('aiRecomContent');
    const spinner = document.getElementById('aiSpinner');

    if (!recomBox) return;

    spinner.classList.remove('d-none');

    setTimeout(() => {
        spinner.classList.add('d-none');

        let recommendedVehicle = "Mini Truck";
        let baseRate = 22;
        let icon = "🚛";
        let features = "Best for standard weights and low distances.";

        if (tempSensitive || crop.toLowerCase().includes('tomato') || crop.toLowerCase().includes('strawberry')) {
            recommendedVehicle = "Refrigerated Truck";
            baseRate = 46;
            icon = "❄️ 🚛";
            features = "Deep cooling zones maintained at 8°C - 14°C.";
        } else if (weight > 1200) {
            recommendedVehicle = "Cargo Truck";
            baseRate = 32;
            icon = "🚛 (Large)";
            features = "Required for high load wheat/grains distribution.";
        } else if (weight < 200) {
            recommendedVehicle = "Pickup Van";
            baseRate = 16;
            icon = "🛻";
            features = "Fast loading and delivery for small batch cargo.";
        }

        recomBox.innerHTML = `
            <div class="text-center py-2">
                <span class="fs-1">${icon}</span>
                <h5 class="fw-bold mt-2 text-white">${recommendedVehicle}</h5>
                <p class="small text-white-50 mb-3">${features}</p>
                <div class="p-2 rounded bg-white bg-opacity-10 mb-2">
                    <span class="small d-block">Base Rate: ₹${baseRate}/km</span>
                </div>
                <small class="text-white-50">AI recommends this vehicle to preserve produce shelf-life.</small>
            </div>
        `;

        const costBase = distance * baseRate;
        const costFuel = Math.round(distance * 5.2);
        const costPriority = isEmergency ? 350 : 0;
        const poolingDiscount = tempSensitive ? Math.round(costBase * 0.4) : Math.round(costBase * 0.2);
        const total = (costBase + costFuel + costPriority) - poolingDiscount;

        const baseCostEl = document.getElementById('calcBaseCost');
        const fuelEl = document.getElementById('calcFuel');
        const priorityEl = document.getElementById('calcPriority');
        const discountEl = document.getElementById('calcDiscount');
        const totalEl = document.getElementById('calcTotal');

        if (baseCostEl) baseCostEl.textContent = '₹' + costBase.toLocaleString();
        if (fuelEl) fuelEl.textContent = '₹' + costFuel.toLocaleString();
        if (priorityEl) priorityEl.textContent = '₹' + costPriority.toLocaleString();
        if (discountEl) discountEl.textContent = '-₹' + poolingDiscount.toLocaleString();
        if (totalEl) totalEl.textContent = '₹' + total.toLocaleString();

    }, 300);
}

// === CHART.JS CONFIGURATION ===
function renderSavingsChart() {
    const ctx = document.getElementById('savingsChart');
    if (!ctx) return;

    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Pooling Saved', 'Paid Transport'],
            datasets: [{
                data: [4250, 7800],
                backgroundColor: ['#AEB784', '#41431B'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: { family: 'Outfit', size: 11 }
                    }
                }
            },
            cutout: '70%'
        }
    });
}

// === GPS MAP SIMULATION ===
let mapCanvas, mapCtx;
let truckX = 50;
let truckY = 200;
let targetX = 500;
let targetY = 200;
let mapAnimationId;

function initLiveTrackingMap() {
    mapCanvas = document.getElementById('trackingCanvas');
    if (!mapCanvas) return;
    mapCtx = mapCanvas.getContext('2d');

    mapCanvas.width = mapCanvas.parentElement.clientWidth;
    mapCanvas.height = 400;

    animateTruck();
}

function animateTruck() {
    if (!mapCanvas) return;

    truckX += 0.45;
    if (truckX > mapCanvas.width - 60) {
        truckX = 50;
    }

    drawTrackingMap();
    mapAnimationId = requestAnimationFrame(animateTruck);
}

function drawTrackingMap() {
    if (!mapCtx) return;
    const w = mapCanvas.width;
    const h = mapCanvas.height;

    mapCtx.fillStyle = '#eef4f1';
    mapCtx.fillRect(0, 0, w, h);

    mapCtx.strokeStyle = '#AEB784';
    mapCtx.lineWidth = 6;
    mapCtx.lineCap = 'round';
    mapCtx.lineJoin = 'round';

    mapCtx.beginPath();
    mapCtx.moveTo(50, 200);
    mapCtx.lineTo(180, 150);
    mapCtx.lineTo(320, 260);
    mapCtx.lineTo(w - 70, 200);
    mapCtx.stroke();

    drawMapPin(50, 200, '🌾', 'Origin');
    drawMapPin(w - 70, 200, '🏪', 'Mandi');

    let currentY = 200;
    if (truckX < 180) {
        let t = (truckX - 50) / (180 - 50);
        currentY = 200 + t * (150 - 200);
    } else if (truckX < 320) {
        let t = (truckX - 180) / (320 - 180);
        currentY = 150 + t * (260 - 150);
    } else {
        let t = (truckX - 320) / ((w - 70) - 320);
        currentY = 260 + t * (200 - 260);
    }

    mapCtx.fillStyle = '#41431B';
    mapCtx.beginPath();
    mapCtx.arc(truckX, currentY, 16, 0, Math.PI * 2);
    mapCtx.fill();

    mapCtx.font = '14px Arial';
    mapCtx.fillText('🚚', truckX - 10, currentY + 5);
}

function drawMapPin(x, y, emoji, label) {
    mapCtx.fillStyle = 'white';
    mapCtx.beginPath();
    mapCtx.arc(x, y, 14, 0, Math.PI * 2);
    mapCtx.fill();
    mapCtx.lineWidth = 2;
    mapCtx.strokeStyle = '#41431B';
    mapCtx.stroke();

    mapCtx.font = '14px Arial';
    mapCtx.fillText(emoji, x - 9, y + 5);

    mapCtx.fillStyle = '#41431B';
    mapCtx.font = 'bold 11px Outfit';
    mapCtx.fillText(label, x - 20, y - 20);
}

// === CHAT FUNCTIONALITY ===
function openDriverChat(name) {
    switchTab('chat');
    const el = document.getElementById('chatActiveUser');
    if (el) el.textContent = name + ' (Driver)';
}

function sendChatMessage() {
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg) return;

    appendMsg(msg, 'sent');
    input.value = '';

    const lastMsg = document.getElementById('chatContactLastMsg');
    if (lastMsg) lastMsg.textContent = msg;

    setTimeout(simulateDriverResponse, 1500);
}

function sendQuickMessage(text) {
    appendMsg(text, 'sent');
    const lastMsg = document.getElementById('chatContactLastMsg');
    if (lastMsg) lastMsg.textContent = text;
    setTimeout(simulateDriverResponse, 1200);
}

function appendMsg(text, type) {
    const viewport = document.getElementById('chatMessagesViewport');
    if (!viewport) return;

    const date = new Date();
    const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    const msgDiv = document.createElement('div');
    msgDiv.className = `chat-message ${type} mb-3`;
    msgDiv.innerHTML = `
        <div class="message-bubble">${text}</div>
        <small class="message-time">${time}</small>
    `;
    viewport.appendChild(msgDiv);
    viewport.scrollTop = viewport.scrollHeight;
}

function simulateDriverResponse() {
    const responses = [
        "Received. Keeping the refrigeration parameters constant.",
        "Yes, road cleared. Estimating to arrive within 1 hour 45 minutes.",
        "I have arrived at the outer circle of Delhi. Reaching Mandi soon.",
        "Yes ji, everything is secured properly.",
        "Checked temperature, stable at 11.5°C."
    ];
    const randMsg = responses[Math.floor(Math.random() * responses.length)];
    appendMsg(randMsg, 'received');
    const lastMsg = document.getElementById('chatContactLastMsg');
    if (lastMsg) lastMsg.textContent = randMsg;
    triggerToastNotification("New message received from driver Ravi Kumar.");
}

function simulateFileUpload() {
    triggerToastNotification("Photo / Invoice attachments simulated successfully.");
}

function selectChatContact(name) {
    document.querySelectorAll('.chat-contacts-list a').forEach(link => {
        link.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
}

// === PAYMENT GATEWAYS ===
function selectPaymentGateway(id) {
    document.querySelectorAll('.payment-method-box').forEach(box => {
        box.classList.remove('active-gateway');
        const icon = box.querySelector('.bi-check-circle-fill, .bi-circle');
        if (icon) icon.className = 'bi bi-circle text-muted fs-5';
    });
    const selected = document.getElementById(id);
    if (selected) {
        selected.classList.add('active-gateway');
        const icon = selected.querySelector('i');
        if (icon) icon.className = 'bi bi-check-circle-fill text-success fs-5';
    }
}

function simulatePaymentProcessing() {
    triggerToastNotification("Processing secure escrow payment transaction...");
    setTimeout(() => {
        triggerToastNotification("Escrow locked successfully! Transferred ₹9,380.");
        switchTab('dashboard');
    }, 1500);
}

function simulateInvoiceDownload() {
    triggerToastNotification("Downloading receipt invoice PDF...");
    const win = window.open("", "_blank");
    if (win) {
        win.document.write(`
            <html>
            <head><title>AgroTransit Invoice #INV-2026-0894</title>
            <style>body{font-family:Arial;padding:40px;} .header{text-align:center;} .table{width:100%;border-collapse:collapse;margin-top:30px;} .table th,.table td{border:1px solid #ddd;padding:8px;} .total{font-size:20px;font-weight:bold;margin-top:30px;text-align:right;}</style>
            </head>
            <body>
                <div class="header"><h1>AgroTransit Tax Invoice</h1><p>Escrow Security Locked</p></div>
                <hr>
                <p><strong>Invoice ID:</strong> #INV-2026-0894</p>
                <p><strong>Farmer:</strong> Amandeep Singh</p>
                <p><strong>Driver Assigned:</strong> Ravi Kumar (DL-19-7894)</p>
                <table class="table">
                    <thead><tr><th>Service Item Description</th><th>Amount Due</th></tr></thead>
                    <tbody>
                        <tr><td>Base Fare Transport (310 km)</td><td>₹14,260</td></tr>
                        <tr><td>Fuel Surcharge</td><td>₹750</td></tr>
                        <tr><td>Emergency Charge</td><td>₹350</td></tr>
                        <tr><td>Agricultural Pooling Discount (40%)</td><td>-₹6,160</td></tr>
                    </tbody>
                </table>
                <div class="total">Total Paid Amount: ₹9,380</div>
            </body>
            </html>
        `);
    }
}

// === QR VERIFICATION ===
function simulateQRScan() {
    triggerToastNotification("Initiating camera scanner...");
    setTimeout(() => {
        triggerToastNotification("✓ QR Code verified successfully. Escrow funds released to Driver.");
        setTimeout(() => {
            switchTab('reviews');
        }, 800);
    }, 1500);
}

// === STAR RATING ===
let activeRating = 5;
function selectRatingStar(val) {
    activeRating = val;
    document.querySelectorAll('.star-btn').forEach(star => {
        const starVal = parseInt(star.getAttribute('data-val'));
        if (starVal <= val) {
            star.className = 'bi bi-star-fill cursor-pointer text-warning star-btn';
        } else {
            star.className = 'bi bi-star cursor-pointer star-btn';
        }
    });
}

function submitFarmerReview() {
    triggerToastNotification(`Thank you! Submitted ${activeRating} stars review for Ravi Kumar.`);
    setTimeout(() => {
        switchTab('dashboard');
    }, 1000);
}

// === ROUTE & POOLING ===
let preselectedPoolId = null;

function confirmJoinPool(poolId, destination) {
    preselectedPoolId = poolId;
    switchTab('create-request');
    const destInput = document.getElementById('destAddr');
    if (destInput) {
        destInput.value = destination;
        triggerAIRecommendation();
    }
    triggerToastNotification(`Pool selected! Please fill in your crop details and click Scan to join.`);
}

function renderPoolingMarketplace(pools) {
    const container = document.getElementById('poolingMarketplaceGrid');
    if (!container || !pools) return;

    if (!pools.length) {
        container.innerHTML = `<div class="p-4 text-center text-muted">No active pooling requests found. Create a new request to start a pool!</div>`;
        return;
    }

    container.innerHTML = pools.map((p, idx) => {
        const isLast = idx === pools.length - 1;
        const borderClass = isLast ? '' : 'border-bottom';
        return `
            <div class="p-4 ${borderClass}">
                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1">🚚 ${p.vehicle_type} - ${p.crop}</h5>
                        <span class="text-muted small"><i class="bi bi-calendar-event"></i> Departs: Tomorrow, 7:00 AM | Route: ${p.route_description}</span>
                    </div>
                    <span class="badge bg-success px-3 py-2 text-white">Save ~₹${p.savings}</span>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Capacity Filled: <strong>${p.current_load}kg / ${p.capacity}kg</strong></span>
                        <span class="text-success fw-bold">${p.filled_percentage}% Filled</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: ${p.filled_percentage}%;"></div>
                    </div>
                </div>

                <div class="row text-muted small mb-3">
                    <div class="col-6 col-sm-3"><strong>Operator:</strong> ${p.driver_name}</div>
                    <div class="col-6 col-sm-3"><strong>Current Farmers:</strong> ${p.farmers} Joined</div>
                    <div class="col-6 col-sm-3"><strong>Available Space:</strong> ${p.remaining_space} kg</div>
                    <div class="col-6 col-sm-3"><strong>Driver:</strong> ${p.driver_name} (${p.driver_rating} ★)</div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-leaf px-4" onclick="confirmJoinPool('${p.id}', '${p.destination}')">Join Pool</button>
                    <button class="btn btn-sm btn-outline-template" onclick="showRouteModal('${p.route_description}')">View Route Map</button>
                </div>
            </div>
        `;
    }).join('');
}

function showRouteModal(routeDescription) {
    const modalPath = document.getElementById('routeModalPath');
    if (modalPath) {
        modalPath.textContent = routeDescription;
    }
    const modalEl = document.getElementById('routeModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

// === FORM BOOKING ===
function bookVehicle(vehicleId, pooledTripId) {
    const crop_name = document.getElementById('cropName').value;
    const weight_kg = document.getElementById('cropWeight').value;
    const pickup = document.getElementById('pickupAddr').value;
    const destination = document.getElementById('destAddr').value;
    const distance_km = document.getElementById('distanceKm').value;
    const temperature_sensitive = document.getElementById('tempSensitive').checked ? 1 : 0;

    let priority = 'normal';
    if (document.getElementById('prefExpress') && document.getElementById('prefExpress').checked) priority = 'express';
    if (document.getElementById('prefEmergency') && document.getElementById('prefEmergency').checked) priority = 'emergency';

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    triggerToastNotification("Submitting booking request...");

    fetch('/api/bookings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            crop_name,
            weight_kg,
            pickup,
            destination,
            distance_km,
            temperature_sensitive,
            priority,
            vehicle_id: vehicleId,
            pooled_trip_id: pooledTripId || preselectedPoolId
        })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            triggerToastNotification("✓ Booking confirmed! Created shipment.");
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            triggerToastNotification("Booking failed: " + (data.error || "Unknown error"));
        }
    })
    .catch(err => {
        console.error(err);
        triggerToastNotification("Booking error: " + (err.message || "Failed to connect"));
    });
}

function searchMatchingVehicles() {
    const crop_name = document.getElementById('cropName').value;
    const weight_kg = document.getElementById('cropWeight').value;
    const pickup = document.getElementById('pickupAddr').value;
    const destination = document.getElementById('destAddr').value;
    const distance_km = document.getElementById('distanceKm').value;
    const temperature_sensitive = document.getElementById('tempSensitive').checked ? 1 : 0;

    let priority = 'normal';
    if (document.getElementById('prefExpress') && document.getElementById('prefExpress').checked) priority = 'express';
    if (document.getElementById('prefEmergency') && document.getElementById('prefEmergency').checked) priority = 'emergency';

    if (!crop_name || !weight_kg || !pickup || !destination || !distance_km) {
        triggerToastNotification("Please fill in all required fields marked with *");
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    triggerToastNotification("Scanning database for matching vehicles and pools...");

    fetch('/api/vehicles/match', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            crop_name,
            weight_kg,
            pickup,
            destination,
            distance_km,
            temperature_sensitive,
            priority
        })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(data => {
        renderMatchingResults(data);
    })
    .catch(err => {
        console.error(err);
        triggerToastNotification("Failed to scan: " + (err.message || "Unknown error"));
    });
}

function renderMatchingResults(data) {
    const section = document.getElementById('matchingVehiclesSection');
    const grid = document.getElementById('matchingVehiclesGrid');
    if (!section || !grid) return;

    section.classList.remove('d-none');
    grid.innerHTML = '';

    const pools = data.pools || [];
    const vehicles = data.vehicles || [];

    if (pools.length === 0 && vehicles.length === 0) {
        grid.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info border-0 rounded-4 p-4 text-center">
                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                    No matching vehicles or active pools found for the selected weight and refrigeration requirements.
                </div>
            </div>
        `;
        return;
    }

    // Render Pools
    pools.forEach(pool => {
        const poolCol = document.createElement('div');
        poolCol.className = 'col-md-6 mb-4';

        const membersHtml = pool.members.map(m => `
            <tr>
                <td class="small py-1 text-dark">${m.name}</td>
                <td class="small py-1 text-muted">${m.weight} kg (${m.percentage}%)</td>
                <td class="small py-1 fw-bold text-success">₹${m.cost}</td>
            </tr>
        `).join('');

        poolCol.innerHTML = `
            <div class="card h-100 border-success shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold">🤝 Join Active Pool</span>
                            <span class="badge bg-danger rounded-pill px-2 py-1 text-white small">${pool.discount}% Discount Active</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">🚚 ${pool.vehicle_type}</h5>
                        <p class="text-muted small mb-3">Reg: <strong>${pool.vehicle_number}</strong> | Driver: ${pool.driver_name} (${pool.driver_rating} ★)</p>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>Capacity Load:</span>
                                <strong>${pool.current_load}kg / ${pool.capacity}kg</strong>
                            </div>
                            <div class="progress rounded-pill bg-light" style="height: 10px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%; transition: width 1s ease-out;" id="bar-pool-${pool.id}"></div>
                            </div>
                            <small class="text-muted mt-1 d-block font-monospace">Remaining: ${pool.remaining_space} kg available</small>
                        </div>

                        <div class="p-2 rounded bg-light mb-3">
                            <span class="small fw-semibold text-dark mb-1 d-block"><i class="bi bi-people me-1"></i> Cost Splitting (Proportional):</span>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>${membersHtml}</tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Your Cost Share:</span>
                            <h4 class="fw-bold mb-0 text-success">₹${pool.estimated_cost}</h4>
                        </div>
                        <button onclick="bookVehicle(null, ${pool.id})" class="btn btn-leaf w-100 py-2"><i class="bi bi-plus-circle me-1"></i> Confirm & Join Pool</button>
                    </div>
                </div>
            </div>
        `;
        grid.appendChild(poolCol);

        setTimeout(() => {
            const bar = document.getElementById(`bar-pool-${pool.id}`);
            if (bar) bar.style.width = `${pool.filled_percentage}%`;
        }, 100);
    });

    // Render New Vehicles
    vehicles.forEach(vehicle => {
        const vehicleCol = document.createElement('div');
        vehicleCol.className = 'col-md-6 mb-4';

        vehicleCol.innerHTML = `
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold">🚛 New Pool Option</span>
                            ${vehicle.cold_storage ? '<span class="badge bg-info text-white rounded-pill px-2 py-1 small">❄️ Cold Storage</span>' : ''}
                        </div>
                        <h5 class="fw-bold text-dark mb-1">🚚 ${vehicle.vehicle_type}</h5>
                        <p class="text-muted small mb-3">Reg: <strong>${vehicle.vehicle_number}</strong> | Driver: ${vehicle.driver_name} (${vehicle.driver_rating} ★)</p>

                        <div class="mb-3">
                            <span class="text-muted small d-block">Max Load Capacity:</span>
                            <strong class="text-dark">${vehicle.capacity} kg</strong>
                        </div>

                        <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1"></i> Book this vehicle to start a new pool. Other farmers heading to your destination can join later to lower your cost by up to 40%!</p>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Initial Base Price:</span>
                            <h4 class="fw-bold mb-0" style="color:var(--forest);">₹${vehicle.estimated_cost}</h4>
                        </div>
                        <button onclick="bookVehicle(${vehicle.id}, null)" class="btn btn-outline-template w-100 py-2"><i class="bi bi-box-seam me-1"></i> Book & Start Pool</button>
                    </div>
                </div>
            </div>
        `;
        grid.appendChild(vehicleCol);
    });
}

function submitBooking() {
    searchMatchingVehicles();
}

// === TOAST NOTIFICATIONS ===
function triggerToastNotification(msg) {
    const msgEl = document.getElementById('agroToastMsg');
    if (msgEl) msgEl.textContent = msg;

    const toastEl = document.getElementById('agroToast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
}

// === FETCH DASHBOARD DATA ===
function fetchDashboardData() {
    const dashboardDataUrl = document.querySelector('[data-dashboard-url]')?.getAttribute('data-dashboard-url');
    if (!dashboardDataUrl) return;

    fetch(dashboardDataUrl)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load dashboard data');
            return response.json();
        })
        .then(data => {
            const activeCount = document.getElementById('activeShipmentsCount');
            const poolSavings = document.getElementById('poolSavingsAmount');
            const pendingCount = document.getElementById('pendingRequestsCount');
            const vehiclesCount = document.getElementById('vehiclesNearbyCount');
            const sidebarVehicles = document.getElementById('sidebarVehiclesNearby');
            const sidebarActive = document.getElementById('sidebarActiveShipments');

            if (activeCount) activeCount.textContent = data.summary.activeShipments;
            if (poolSavings) poolSavings.textContent = data.summary.poolSavings;
            if (pendingCount) pendingCount.textContent = data.summary.pendingRequests;
            if (vehiclesCount) vehiclesCount.textContent = data.summary.vehiclesNearby;
            if (sidebarVehicles) sidebarVehicles.textContent = data.summary.vehiclesNearby;
            if (sidebarActive) sidebarActive.textContent = data.summary.activeShipments;

            renderRecentShipments(data.shipments);
            renderPoolingMarketplace(data.pooling);

            if (data.notifications && data.notifications.length) {
                setupNotificationFeed(data.notifications);
            }
        })
        .catch(err => {
            console.warn('Dashboard data fetch failed:', err.message);
        });
}

function renderRecentShipments(shipments) {
    const container = document.getElementById('recentShipmentsList');
    if (!container) return;

    if (!shipments || !shipments.length) {
        container.innerHTML = `<div class="list-group-item p-4 text-center text-muted"><div>No active shipments found yet.</div><div class="small text-muted mt-2">Create a new request to see it here.</div></div>`;
        return;
    }

    container.innerHTML = '';
    shipments.forEach(trip => {
        const statusInfo = getStatusClasses(trip.status);
        const item = document.createElement('div');
        item.className = 'list-group-item p-4';
        item.innerHTML = `
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1 text-dark">${trip.crop_name} - ${trip.weight}kg</h5>
                    <div class="text-muted small"><i class="bi bi-geo-alt-fill text-danger"></i> ${trip.pickup || trip.origin} <i class="bi bi-arrow-right mx-1"></i> ${trip.destination}</div>
                </div>
                <span class="badge ${statusInfo.badgeClass} px-3 py-2 rounded-pill fw-bold">${trip.status_label}</span>
            </div>
            <div class="recent-shipment-progress mb-3 bg-light rounded-pill">
                <div class="progress-bar ${statusInfo.barClass}" role="progressbar" style="width: ${trip.progress}%"></div>
            </div>
            <div class="row text-muted small mb-3">
                <div class="col-6 col-sm-3"><strong>Driver:</strong> ${trip.driver_name || 'Driver'}</div>
                <div class="col-6 col-sm-3"><strong>Vehicle:</strong> ${trip.vehicle_number || 'TBD'}</div>
                <div class="col-6 col-sm-3"><strong>Cost:</strong> ${trip.cost ? '₹' + trip.cost : 'TBD'}</div>
                <div class="col-6 col-sm-3"><strong>ETA:</strong> ${trip.eta || 'TBD'}</div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button onclick="switchTab('tracking')" class="btn btn-sm btn-outline-template">🗺️ Live Track</button>
                <button onclick="openDriverChat('${trip.driver_name || 'Driver'}')" class="btn btn-sm btn-outline-template">💬 Chat</button>
            </div>
        `;
        container.appendChild(item);
    });
}

function getStatusClasses(status) {
    if (status === 'in_transit') {
        return { badgeClass: 'bg-info bg-opacity-10 text-info', barClass: 'bg-info', status_label: 'In Transit' };
    }
    if (status === 'pickup_assigned' || status === 'vehicle_assigned') {
        return { badgeClass: 'bg-warning bg-opacity-10 text-warning', barClass: 'bg-warning', status_label: 'Pickup Assigned' };
    }
    if (status === 'pickup_started' || status === 'loading') {
        return { badgeClass: 'bg-primary bg-opacity-10 text-primary', barClass: 'bg-primary', status_label: 'Loading / Started' };
    }
    if (status === 'delivered') {
        return { badgeClass: 'bg-success bg-opacity-10 text-success', barClass: 'bg-success', status_label: 'Delivered' };
    }
    return { badgeClass: 'bg-secondary bg-opacity-10 text-secondary', barClass: 'bg-secondary', status_label: 'Scheduled' };
}

function setupNotificationFeed(notifications = []) {
    const feed = document.getElementById('liveNotifications');
    if (!feed) return;

    feed.innerHTML = notifications.map(note => {
        const time = note.timestamp || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        return `
            <div class="notification-card card border-0 shadow-sm mb-3 p-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-bell-fill text-success"></i>
                    </div>
                    <div>
                        <div class="small text-muted">${time}</div>
                        <div class="fw-semibold text-dark">${note.message}</div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    if (!notifications.length) {
        feed.innerHTML = `<div class="card border-0 shadow-sm p-4 text-center text-muted"><div>No recent notifications yet. Your logistics updates appear here.</div></div>`;
    }
}

function addNotification(message) {
    const feed = document.getElementById('liveNotifications');
    if (!feed) return;
    const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const card = document.createElement('div');
    card.className = 'notification-card card border-0 shadow-sm mb-3 p-3';
    card.innerHTML = `
        <div class="d-flex align-items-start gap-3">
            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                <i class="bi bi-bell-fill text-success"></i>
            </div>
            <div>
                <div class="small text-muted">${now}</div>
                <div class="fw-semibold text-dark">${message}</div>
            </div>
        </div>
    `;
    feed.prepend(card);
    setTimeout(() => card.classList.add('shadow-lg'), 10);
}
