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

    // Load all available vehicles for the create-request tab
    loadAvailableVehicles();
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

        // Reload available vehicles when create-request tab is opened
        if (tabId === 'create-request') {
            loadAvailableVehicles();
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

        const costDistance = distance * baseRate;
        const costWeight = Math.round(weight * distance * 0.005);
        const costColdStorage = tempSensitive ? 500 : 0;
        const costPriority = isEmergency ? 350 : 0;
        const tripBaseCost = 300 + costDistance + costWeight + costColdStorage;
        const poolingDiscount = tempSensitive ? Math.round(tripBaseCost * 0.4) : Math.round(tripBaseCost * 0.2);
        const total = (tripBaseCost + costPriority) - poolingDiscount;

        const labelDistanceEl = document.getElementById('labelDistance');
        const labelWeightEl = document.getElementById('labelWeight');

        if (labelDistanceEl) labelDistanceEl.innerHTML = `Distance Charge <span class="badge bg-secondary font-monospace small px-2">${distance} km @ ₹${baseRate}/km</span>:`;
        if (labelWeightEl) labelWeightEl.innerHTML = `Weight Surcharge <span class="badge bg-secondary font-monospace small px-2">${weight} kg</span>:`;

        const distanceEl = document.getElementById('calcDistance');
        const weightEl = document.getElementById('calcWeight');
        const coldStorageEl = document.getElementById('calcColdStorage');
        const priorityEl = document.getElementById('calcPriority');
        const discountEl = document.getElementById('calcDiscount');
        const totalEl = document.getElementById('calcTotal');

        if (distanceEl) distanceEl.textContent = '₹' + costDistance.toLocaleString();
        if (weightEl) weightEl.textContent = '₹' + costWeight.toLocaleString();
        if (coldStorageEl) {
            coldStorageEl.textContent = '₹' + costColdStorage.toLocaleString();
            const row = document.getElementById('rowColdStorage');
            if (row) {
                if (tempSensitive) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            }
        }
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

// === ALL AVAILABLE VEHICLES CATALOG ===
function loadAvailableVehicles() {
    const grid = document.getElementById('allAvailableVehiclesGrid');
    const countBadge = document.getElementById('availableVehicleCount');
    if (!grid) return;

    grid.innerHTML = `
        <div class="col-12 text-center py-5 text-muted">
            <div class="spinner-border text-success" role="status"></div>
            <p class="mt-2 small">Fetching available vehicles...</p>
        </div>
    `;

    fetch('/api/vehicles/available')
        .then(res => {
            if (!res.ok) throw new Error('Failed to load');
            return res.json();
        })
        .then(data => {
            renderAllAvailableVehicles(data.vehicles || []);
            const availCount = (data.vehicles || []).filter(v => v.is_available).length;
            const totalCount = (data.vehicles || []).length;
            if (countBadge) {
                countBadge.textContent = availCount + ' Available / ' + totalCount + ' Total';
                countBadge.className = availCount > 0
                    ? 'badge bg-success px-3 py-2 rounded-pill'
                    : 'badge bg-danger px-3 py-2 rounded-pill';
            }
        })
        .catch(err => {
            console.warn('Available vehicles fetch failed:', err);
            if (grid) grid.innerHTML = `<div class="col-12"><div class="alert alert-warning">Could not load vehicles. Please try again.</div></div>`;
            if (countBadge) countBadge.textContent = '0 Available';
        });
}

function renderAllAvailableVehicles(vehicles) {
    const grid = document.getElementById('allAvailableVehiclesGrid');
    if (!grid) return;

    if (!vehicles.length) {
        grid.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5">
                    <div style="width:80px;height:80px;margin:0 auto 16px;border-radius:50%;background:linear-gradient(135deg,rgba(174,183,132,.15),rgba(65,67,27,.08));display:grid;place-items:center;">
                        <i class="bi bi-truck fs-1" style="color:#AEB784;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No vehicles registered yet</h5>
                    <p class="text-muted small mb-0">Vehicles will appear here once transport owners add them to the fleet.</p>
                </div>
            </div>
        `;
        return;
    }

    grid.innerHTML = vehicles.map(v => {
        const isAvail = v.is_available;
        const coldBadge = v.cold_storage
            ? `<div style="position:absolute;top:12px;right:0;background:linear-gradient(135deg,#0dcaf0,#0aa2c0);color:#fff;font-size:10px;font-weight:700;padding:3px 12px 3px 10px;border-radius:20px 0 0 20px;letter-spacing:.3px;"><i class="bi bi-snow2 me-1"></i>COLD CHAIN</div>`
            : '';
        const fuelIcon = v.fuel_type === 'Electric' ? '⚡' : (v.fuel_type === 'CNG' ? '🟢' : '⛽');

        // Star rating visual
        const rating = parseFloat(v.driver_rating) || 4.5;
        const fullStars = Math.floor(rating);
        const halfStar = (rating % 1) >= 0.3;
        let starsHtml = '';
        for (let i = 0; i < 5; i++) {
            if (i < fullStars) starsHtml += '<i class="bi bi-star-fill" style="color:#f5a623;font-size:11px;"></i>';
            else if (i === fullStars && halfStar) starsHtml += '<i class="bi bi-star-half" style="color:#f5a623;font-size:11px;"></i>';
            else starsHtml += '<i class="bi bi-star" style="color:#ddd;font-size:11px;"></i>';
        }

        // ━━ Header gradient based on vehicle type
        const typeGradients = {
            'Mini Truck': 'linear-gradient(135deg,#41431B 0%,#6b6e2c 100%)',
            'Cargo Truck': 'linear-gradient(135deg,#2c4a1e 0%,#4a7c34 100%)',
            'Refrigerated Truck': 'linear-gradient(135deg,#1a5276 0%,#2e86c1 100%)',
            'Pickup Van': 'linear-gradient(135deg,#5b370a 0%,#9c6b30 100%)',
        };
        const headerBg = typeGradients[v.vehicle_type] || typeGradients['Mini Truck'];
        const typeIcons = {
            'Mini Truck': 'bi-truck',
            'Cargo Truck': 'bi-truck-front',
            'Refrigerated Truck': 'bi-thermometer-snow',
            'Pickup Van': 'bi-truck-flatbed',
        };
        const typeIcon = typeIcons[v.vehicle_type] || 'bi-truck';

        // ━━ BOOKED / UNAVAILABLE CARD ━━━━━━━━━━━━━━━━━━━━━━━━━
        if (!isAvail) {
            const statusLabel = {
                busy: 'On Trip',
                in_transit: 'In Transit',
                maintenance: 'Maintenance',
            }[v.tracking_status] || 'Busy';

            const bookingBlock = v.booked_date ? `
                <div style="background:linear-gradient(135deg,rgba(220,53,69,.04),rgba(220,53,69,.08));border:1px solid rgba(220,53,69,.12);border-radius:12px;padding:12px 14px;margin-bottom:16px;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div style="width:28px;height:28px;border-radius:8px;background:rgba(220,53,69,.1);display:grid;place-items:center;">
                            <i class="bi bi-calendar-x-fill" style="color:#dc3545;font-size:13px;"></i>
                        </div>
                        <span style="font-weight:700;font-size:13px;color:#dc3545;">Booked: ${v.booked_date}</span>
                    </div>
                    ${v.booked_destination ? `<div style="font-size:12px;color:#555;padding-left:36px;"><i class="bi bi-pin-map-fill me-1 text-danger"></i>Route → <strong>${v.booked_destination}</strong></div>` : ''}
                    ${v.estimated_return ? `<div style="font-size:11px;color:#888;padding-left:36px;margin-top:4px;"><i class="bi bi-clock-history me-1"></i>Available by: ${v.estimated_return}</div>` : ''}
                </div>
            ` : `
                <div style="background:rgba(255,193,7,.08);border:1px solid rgba(255,193,7,.2);border-radius:12px;padding:10px 14px;margin-bottom:16px;font-size:12px;color:#856404;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Currently ${statusLabel} — check back soon
                </div>
            `;

            return `
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="vehicle-catalog-card" style="border-radius:20px;overflow:hidden;background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.06);transition:transform .2s,box-shadow .2s;opacity:.75;position:relative;height:100%;display:flex;flex-direction:column;">
                        ${coldBadge}
                        <!-- Header -->
                        <div style="background:${headerBg};padding:20px 22px 18px;position:relative;filter:grayscale(60%);">
                            <div style="position:absolute;top:0;right:0;bottom:0;left:0;background:rgba(0,0,0,.25);"></div>
                            <div style="position:relative;z-index:1;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,.18);display:grid;place-items:center;">
                                            <i class="bi ${typeIcon}" style="color:#fff;font-size:16px;"></i>
                                        </div>
                                        <span style="color:rgba(255,255,255,.85);font-size:12px;font-weight:600;letter-spacing:.4px;text-transform:uppercase;">${v.vehicle_type}</span>
                                    </div>
                                    <span style="background:rgba(220,53,69,.9);color:#fff;font-size:10px;font-weight:700;padding:4px 12px;border-radius:20px;letter-spacing:.3px;"><i class="bi bi-lock-fill me-1"></i>${statusLabel.toUpperCase()}</span>
                                </div>
                                <h4 style="color:#fff;font-weight:800;font-size:1.35rem;margin:0;letter-spacing:1px;font-family:'Outfit',sans-serif;">${v.registration_number}</h4>
                                <div style="color:rgba(255,255,255,.6);font-size:12px;margin-top:4px;"><i class="bi bi-geo-alt-fill me-1"></i>${v.current_location}</div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div style="padding:20px 22px;flex:1;display:flex;flex-direction:column;">
                            <!-- Stats -->
                            <div class="d-flex gap-2 mb-4">
                                <div style="flex:1;text-align:center;background:#f8f9fa;border-radius:12px;padding:12px 6px;">
                                    <i class="bi bi-box-seam" style="color:#41431B;font-size:16px;display:block;margin-bottom:4px;"></i>
                                    <div style="font-weight:800;font-size:15px;color:#1a1a1a;">${v.capacity_kg}<span style="font-size:11px;font-weight:400;color:#888;"> kg</span></div>
                                    <div style="font-size:10px;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">Capacity</div>
                                </div>
                                <div style="flex:1;text-align:center;background:#f8f9fa;border-radius:12px;padding:12px 6px;">
                                    <i class="bi bi-currency-rupee" style="color:#41431B;font-size:16px;display:block;margin-bottom:4px;"></i>
                                    <div style="font-weight:800;font-size:15px;color:#1a1a1a;">${v.base_rate_per_km}<span style="font-size:11px;font-weight:400;color:#888;">/km</span></div>
                                    <div style="font-size:10px;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">Rate</div>
                                </div>
                                <div style="flex:1;text-align:center;background:#f8f9fa;border-radius:12px;padding:12px 6px;">
                                    <span style="font-size:16px;display:block;margin-bottom:4px;">${fuelIcon}</span>
                                    <div style="font-weight:700;font-size:13px;color:#1a1a1a;">${v.fuel_type}</div>
                                    <div style="font-size:10px;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">Fuel</div>
                                </div>
                            </div>

                            ${bookingBlock}

                            <!-- Driver -->
                            <div style="border-top:1px solid #f0f0f0;padding-top:14px;margin-top:auto;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#e8e8e8,#d4d4d4);display:grid;place-items:center;flex-shrink:0;">
                                        <i class="bi bi-person-fill" style="color:#999;font-size:20px;"></i>
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="font-weight:700;font-size:14px;color:#1a1a1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${v.driver_name}</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div>${starsHtml}</div>
                                            <span style="font-size:11px;color:#999;">${v.driver_rating}</span>
                                        </div>
                                    </div>
                                </div>
                                <div style="font-size:11px;color:#aaa;margin-top:8px;padding-left:2px;"><i class="bi bi-building me-1"></i>${v.owner_name}</div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div style="padding:0 22px 20px;">
                            <button class="btn w-100 py-2" style="background:#e9ecef;color:#999;font-weight:700;border-radius:12px;border:none;font-size:13px;cursor:not-allowed;" disabled>
                                <i class="bi bi-x-circle me-1"></i> Not Available
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // ━━ AVAILABLE CARD ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        return `
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="vehicle-catalog-card" style="border-radius:20px;overflow:hidden;background:#fff;box-shadow:0 4px 20px rgba(0,0,0,.07);transition:transform .25s cubic-bezier(.4,0,.2,1),box-shadow .25s cubic-bezier(.4,0,.2,1);position:relative;height:100%;display:flex;flex-direction:column;border:2px solid transparent;" onmouseenter="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 40px rgba(65,67,27,.15)';this.style.borderColor='rgba(174,183,132,.4)';" onmouseleave="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(0,0,0,.07)';this.style.borderColor='transparent';">
                    ${coldBadge}
                    <!-- Header -->
                    <div style="background:${headerBg};padding:20px 22px 18px;position:relative;">
                        <div style="position:absolute;top:12px;right:12px;width:10px;height:10px;border-radius:50%;background:#4ade80;box-shadow:0 0 0 3px rgba(74,222,128,.3);animation:pulse-dot 2s infinite;"></div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,.18);backdrop-filter:blur(4px);display:grid;place-items:center;">
                                <i class="bi ${typeIcon}" style="color:#fff;font-size:16px;"></i>
                            </div>
                            <span style="color:rgba(255,255,255,.85);font-size:12px;font-weight:600;letter-spacing:.4px;text-transform:uppercase;">${v.vehicle_type}</span>
                        </div>
                        <h4 style="color:#fff;font-weight:800;font-size:1.35rem;margin:0;letter-spacing:1px;font-family:'Outfit',sans-serif;">${v.registration_number}</h4>
                        <div style="color:rgba(255,255,255,.6);font-size:12px;margin-top:4px;"><i class="bi bi-geo-alt-fill me-1"></i>${v.current_location}</div>
                    </div>

                    <!-- Body -->
                    <div style="padding:20px 22px;flex:1;display:flex;flex-direction:column;">
                        <!-- Stats -->
                        <div class="d-flex gap-2 mb-4">
                            <div style="flex:1;text-align:center;background:linear-gradient(135deg,rgba(174,183,132,.08),rgba(174,183,132,.15));border-radius:12px;padding:12px 6px;border:1px solid rgba(174,183,132,.1);">
                                <i class="bi bi-box-seam" style="color:#41431B;font-size:16px;display:block;margin-bottom:4px;"></i>
                                <div style="font-weight:800;font-size:15px;color:#1a1a1a;">${v.capacity_kg}<span style="font-size:11px;font-weight:400;color:#888;"> kg</span></div>
                                <div style="font-size:10px;color:#999;text-transform:uppercase;letter-spacing:.5px;">Capacity</div>
                            </div>
                            <div style="flex:1;text-align:center;background:linear-gradient(135deg,rgba(174,183,132,.08),rgba(174,183,132,.15));border-radius:12px;padding:12px 6px;border:1px solid rgba(174,183,132,.1);">
                                <i class="bi bi-currency-rupee" style="color:#41431B;font-size:16px;display:block;margin-bottom:4px;"></i>
                                <div style="font-weight:800;font-size:15px;color:#1a1a1a;">₹${v.base_rate_per_km}<span style="font-size:11px;font-weight:400;color:#888;">/km</span></div>
                                <div style="font-size:10px;color:#999;text-transform:uppercase;letter-spacing:.5px;">Rate</div>
                            </div>
                            <div style="flex:1;text-align:center;background:linear-gradient(135deg,rgba(174,183,132,.08),rgba(174,183,132,.15));border-radius:12px;padding:12px 6px;border:1px solid rgba(174,183,132,.1);">
                                <span style="font-size:16px;display:block;margin-bottom:4px;">${fuelIcon}</span>
                                <div style="font-weight:700;font-size:13px;color:#1a1a1a;">${v.fuel_type}</div>
                                <div style="font-size:10px;color:#999;text-transform:uppercase;letter-spacing:.5px;">Fuel</div>
                            </div>
                        </div>

                        <!-- Driver -->
                        <div style="border-top:1px solid #f0f0f0;padding-top:14px;margin-top:auto;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#41431B,#6b6e2c);display:grid;place-items:center;flex-shrink:0;">
                                    <i class="bi bi-person-fill" style="color:#fff;font-size:20px;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-weight:700;font-size:14px;color:#1a1a1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${v.driver_name}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>${starsHtml}</div>
                                        <span style="font-size:11px;color:#999;">${v.driver_rating}</span>
                                    </div>
                                </div>
                            </div>
                            <div style="font-size:11px;color:#aaa;margin-top:8px;padding-left:2px;"><i class="bi bi-building me-1"></i>${v.owner_name}</div>
                        </div>
                    </div>

                    <!-- Button -->
                    <div style="padding:0 22px 20px;">
                        <button
                            onclick="selectVehicleFromCatalog(${v.id}, '${v.registration_number}', '${v.vehicle_type}', ${v.capacity_kg}, ${v.base_rate_per_km})"
                            class="btn w-100 py-2"
                            style="background:linear-gradient(135deg,#41431B,#5a5d24);color:#fff;font-weight:700;border-radius:12px;border:none;font-size:13px;letter-spacing:.3px;transition:all .2s;"
                            onmouseenter="this.style.background='linear-gradient(135deg,#5a5d24,#41431B)';this.style.boxShadow='0 4px 16px rgba(65,67,27,.3)';"
                            onmouseleave="this.style.background='linear-gradient(135deg,#41431B,#5a5d24)';this.style.boxShadow='none';">
                            <i class="bi bi-check-circle-fill me-1"></i> Select this Vehicle
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}


function selectVehicleFromCatalog(vehicleId, regNumber, vehicleType, capacityKg, ratePerKm) {
    // Scroll to form
    document.getElementById('bookingForm')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Highlight the selected vehicle
    document.querySelectorAll('.vehicle-catalog-card').forEach(c => {
        c.style.outline = '';
        c.style.boxShadow = '';
    });

    // Store selected vehicle id globally for booking
    window._selectedCatalogVehicleId = vehicleId;

    triggerToastNotification(`✓ Selected: ${regNumber} (${vehicleType}). Fill in your crop details and click "Scan" to book.`);

    // Update scan button to show the vehicle is pre-selected
    const scanBtn = document.querySelector('[onclick="searchMatchingVehicles()"]');
    if (scanBtn) {
        scanBtn.innerHTML = `<i class="bi bi-check-circle me-1"></i> Confirm Booking with ${regNumber}`;
        scanBtn.onclick = () => bookVehicle(vehicleId, null);
    }
}
