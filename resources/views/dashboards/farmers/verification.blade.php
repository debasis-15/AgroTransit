<!-- SECTION 8: DELIVERY VERIFICATION PAGE -->
<div class="tab-pane fade" id="verification" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">🛡️ Delivery Verification (Secure QR Release)</h2>
        <p class="text-muted">Upon successful arrival of your agricultural produce at the market Mandi, scan the driver's QR code or present your code to release escrow payments securely.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm text-center py-5 px-4">
                <div class="card-body">
                    <h4 class="fw-bold text-dark mb-3">Escrow Security Verification</h4>
                    <p class="text-muted mb-4">Confirm receipt and weight completeness. Scan the unique transport QR code displayed by the driver to unlock escrow and generate receipts.</p>
                    
                    <div class="qr-container-box mx-auto mb-4 p-3 bg-white rounded-4 shadow-sm border border-2 border-dashed border-success" style="width:240px; height:240px; position:relative;">
                        <div class="qr-scanning-laser"></div>
                        <!-- Mock QR representation -->
                        <div style="background-image: url('https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=AGROTRANSIT-VERIFY-TRN001'); background-size:cover; width: 100%; height:100%;"></div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">Verify Code: #AGR-001</h5>
                    <p class="text-muted small mb-4">Produce: Tomato (500kg) | Route: Ludhiana to Delhi</p>
                    
                    <button class="btn btn-leaf px-5 py-3 fw-bold" onclick="simulateQRScan()"><i class="bi bi-qr-code-scan me-1"></i> Scan & Verify Delivery Receipt</button>
                </div>
            </div>
        </div>
    </div>
</div>
