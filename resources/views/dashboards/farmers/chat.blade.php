<!-- SECTION 6: CHAT PAGE -->
<div class="tab-pane fade" id="chat" role="tabpanel">
    <div class="mb-4">
        <h2 class="fw-bold" style="color:var(--forest);">💬 Instant Messaging Center</h2>
        <p class="text-muted">Chat in real-time with your assigned truck driver or fleet manager.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="row g-0">
            <!-- Contact list -->
            <div class="col-md-4 border-end">
                <div class="p-3 border-bottom">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-light border-0" placeholder="Search contacts...">
                    </div>
                </div>
                <div class="list-group list-group-flush chat-contacts-list" style="max-height: 400px; overflow-y: auto;">
                    <a href="#" class="list-group-item list-group-item-action active p-3 d-flex align-items-center gap-3" onclick="selectChatContact('Ravi Kumar')">
                        <div class="chat-avatar bg-success text-white">RK</div>
                        <div class="w-100 overflow-hidden">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-bold mb-0 text-truncate text-white">Ravi Kumar (Driver)</h6>
                                <small class="text-white-50">Just now</small>
                            </div>
                            <p class="small mb-0 text-white-50 text-truncate" id="chatContactLastMsg">On my way, crossed bypass road.</p>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3 d-flex align-items-center gap-3" onclick="selectChatContact('GreenLine Fleet')">
                        <div class="chat-avatar bg-secondary text-white">GL</div>
                        <div class="w-100 overflow-hidden">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-bold mb-0 text-truncate text-dark">GreenLine (Fleet Owner)</h6>
                                <small class="text-muted">2 hrs ago</small>
                            </div>
                            <p class="small text-muted mb-0 text-truncate">Vehicle PB10-AG-2026 dispatched.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Active Chat Box -->
            <div class="col-md-8 d-flex flex-column" style="height: 480px;">
                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" id="chatActiveUser">Ravi Kumar (Driver)</h6>
                        <small class="text-success"><i class="bi bi-circle-fill" style="font-size: 6px;"></i> Active on Route</small>
                    </div>
                    <div>
                        <a href="tel:+919000000004" class="btn btn-sm btn-outline-secondary"><i class="bi bi-telephone-fill"></i> Call</a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="simulateDriverResponse()"><i class="bi bi-lightning-fill text-warning"></i> Mock Reply</button>
                    </div>
                </div>

                <!-- Messages viewport -->
                <div class="p-3 flex-grow-1 overflow-y-auto" id="chatMessagesViewport" style="background-color:#FAF8F0;">
                    <div class="text-center text-muted small my-2">Today, 09:30 AM</div>
                    
                    <div class="chat-message sent mb-3">
                        <div class="message-bubble">Hello Ravi, did you pack the tomatoes properly? Fresh crop is sensitive.</div>
                        <small class="message-time">09:32 AM</small>
                    </div>

                    <div class="chat-message received mb-3">
                        <div class="message-bubble">Yes Amandeep ji, wooden crates are placed in the refrigerated zone. Temp set to 12°C.</div>
                        <small class="message-time">09:35 AM</small>
                    </div>

                    <div class="chat-message sent mb-3">
                        <div class="message-bubble">Great. Let me know when you reach NH1 bypass.</div>
                        <small class="message-time">09:40 AM</small>
                    </div>

                    <div class="chat-message received mb-3">
                        <div class="message-bubble">On my way, crossed bypass road. Moderate traffic but going smoothly.</div>
                        <small class="message-time">10:15 AM</small>
                    </div>
                </div>

                <!-- Quick buttons -->
                <div class="px-3 py-2 border-top bg-light d-flex gap-2 overflow-x-auto">
                    <button class="btn btn-xs btn-outline-secondary text-nowrap rounded-pill" onclick="sendQuickMessage('Please share your live location.')">📍 Request Location</button>
                    <button class="btn btn-xs btn-outline-secondary text-nowrap rounded-pill" onclick="sendQuickMessage('Is the refrigeration working properly?')">❄️ Check Temperature</button>
                    <button class="btn btn-xs btn-outline-secondary text-nowrap rounded-pill" onclick="sendQuickMessage('What is your current ETA?')">🕐 Ask ETA</button>
                </div>

                <!-- Input line -->
                <div class="p-3 border-top bg-white">
                    <form id="chatForm" onsubmit="event.preventDefault(); sendChatMessage();" class="d-flex gap-2">
                        <button type="button" class="btn btn-light" onclick="simulateFileUpload()"><i class="bi bi-paperclip"></i></button>
                        <input type="text" id="chatInput" class="form-control" placeholder="Type your message here..." required autocomplete="off">
                        <button type="submit" class="btn btn-leaf"><i class="bi bi-send-fill"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
