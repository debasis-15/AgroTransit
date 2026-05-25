<!-- SECTION 7: PAYMENT PAGE -->
<div class="tab-pane fade" id="payments" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">💳 Payments & Billings</h2>
        <p class="text-muted">Settle balances, select secure gateways, and retrieve PDF tax invoices.</p>
    </div>

    <div class="row">
        <!-- Invoice Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-spreadsheet me-2"></i> Current Invoice Details</h5>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded-4 bg-light mb-4 border">
                        <div class="d-flex justify-content-between mb-1 small text-muted"><span>Invoice ID:</span><span class="text-dark fw-bold">#INV-2026-0894</span></div>
                        <div class="d-flex justify-content-between mb-1 small text-muted"><span>Billing Date:</span><span class="text-dark">May 20, 2026</span></div>
                        <div class="d-flex justify-content-between small text-muted"><span>Shipment Details:</span><span class="text-dark">Tomato - 500 kg (Reefer)</span></div>
                    </div>

                    <h6 class="fw-bold mb-3 text-dark">Cost Breakdown</h6>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Base Fare Rate (310 km * ₹46):</span>
                        <span class="text-dark">₹14,260</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Fuel Surcharge adjustment:</span>
                        <span class="text-dark">₹750</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Express / Emergency Handling fee:</span>
                        <span class="text-dark">₹350</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small text-success">
                        <span>Agricultural Pooling Discount (40% discount):</span>
                        <span>-₹6,160</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Platform service tax:</span>
                        <span class="text-dark">₹180</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark mb-0">Total Due Amount:</h5>
                        <h3 class="fw-bold text-success mb-0">₹9,380</h3>
                    </div>

                    <button class="btn btn-outline-template w-100 py-2" onclick="simulateInvoiceDownload()"><i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> Download Tax Invoice (PDF)</button>
                </div>
            </div>
        </div>

        <!-- Gateway Selection -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-lock-fill me-2 text-success"></i> Secure Checkout Gateways</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-4">Select from verified transaction processors. Transactions are covered under AgroTransit Produce Protection Guarantee.</p>

                    <!-- Option UPI -->
                    <div class="payment-method-box p-3 rounded-4 mb-3 border d-flex align-items-center justify-content-between cursor-pointer active-gateway" id="payUPI" onclick="selectPaymentGateway('payUPI')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-primary"><i class="bi bi-phone-vibrate"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">UPI Instant Transfer</h6>
                                <p class="small text-muted mb-0">Google Pay, PhonePe, BHIM, Paytm</p>
                            </div>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    </div>

                    <!-- Option Razorpay -->
                    <div class="payment-method-box p-3 rounded-4 mb-3 border d-flex align-items-center justify-content-between cursor-pointer" id="payRP" onclick="selectPaymentGateway('payRP')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-info"><i class="bi bi-credit-card"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Net Banking / Card (Razorpay)</h6>
                                <p class="small text-muted mb-0">Credit / Debit Cards, Net Banking, Wallets</p>
                            </div>
                        </div>
                        <i class="bi bi-circle text-muted fs-5"></i>
                    </div>

                    <!-- Option Stripe -->
                    <div class="payment-method-box p-3 rounded-4 mb-4 border d-flex align-items-center justify-content-between cursor-pointer" id="payStripe" onclick="selectPaymentGateway('payStripe')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-indigo" style="color:#635bff;"><i class="bi bi-globe"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Stripe International Checkout</h6>
                                <p class="small text-muted mb-0">For international export buyers & exporters</p>
                            </div>
                        </div>
                        <i class="bi bi-circle text-muted fs-5"></i>
                    </div>

                    <button class="btn btn-leaf w-100 py-3 fw-bold" onclick="simulatePaymentProcessing()"><i class="bi bi-credit-card-2-front-fill me-1"></i> Pay ₹9,380 Securely</button>
                </div>
            </div>
        </div>
    </div>
</div>
