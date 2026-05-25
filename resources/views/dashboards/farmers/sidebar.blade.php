<div class="col-auto sidebar-column">
    <div class="premium-sidebar">
        <div class="sidebar-profile-card">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                <span class="avatar-online-dot"></span>
            </div>
            <div class="profile-details">
                <h6 class="profile-name mb-0">{{ auth()->user()->name }}</h6>
                <span class="profile-role">Premium Farmer</span>
            </div>
        </div>

        <div class="sidebar-menu-wrapper">
            <span class="sidebar-menu-label">MAIN MENU</span>

            <ul class="nav flex-column sidebar-menu" id="menu">

                <li class="nav-item">

                    <a href="#dashboard"
                       class="sidebar-link active"
                       data-tab="dashboard">

                        <i class="bi bi-grid"></i>

                        <span>Dashboard</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#create-request"
                       class="sidebar-link"
                       data-tab="create-request">

                        <i class="bi bi-plus-circle"></i>

                        <span>Create Request</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#shipments"
                       class="sidebar-link"
                       data-tab="shipments">

                        <i class="bi bi-box-seam"></i>

                        <span>Shipments</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#pooling"
                       class="sidebar-link"
                       data-tab="pooling">

                        <i class="bi bi-people"></i>

                        <span>Pooling</span>

                        <span class="sidebar-badge">
                            2
                        </span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#tracking"
                       class="sidebar-link"
                       data-tab="tracking">

                        <i class="bi bi-geo-alt"></i>

                        <span>Tracking</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#payments"
                       class="sidebar-link"
                       data-tab="payments">

                        <i class="bi bi-credit-card"></i>

                        <span>Payments</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#chat"
                       class="sidebar-link"
                       data-tab="chat">

                        <i class="bi bi-chat-dots"></i>

                        <span>Messages</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#verification"
                       class="sidebar-link"
                       data-tab="verification">

                        <i class="bi bi-qr-code"></i>

                        <span>Verification</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#reviews"
                       class="sidebar-link"
                       data-tab="reviews">

                        <i class="bi bi-star"></i>

                        <span>Reviews</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#profile"
                       class="sidebar-link"
                       data-tab="profile">

                        <i class="bi bi-person-circle"></i>

                        <span>Profile</span>

                    </a>

                </li>

            </ul>

        </div>

        <div class="sidebar-status-card">
            <div class="status-item">
                <div class="status-dot"></div>
                System Active
            </div>
            <div class="small text-white-50 mt-2">
                <span id="sidebarVehiclesNearby">--</span> vehicles nearby
            </div>
            <div class="small text-white-50">
                <span id="sidebarActiveShipments">--</span> active shipments
            </div>
        </div>
    </div>
</div>
