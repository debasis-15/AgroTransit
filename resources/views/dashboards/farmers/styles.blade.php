<style>
/* Notification Cards */
#liveNotifications .notification-card {transition: transform .25s ease, box-shadow .25s ease;}
#liveNotifications .notification-card:hover {transform: translateY(-2px);}

/* Shipment Progress */
.recent-shipment-progress {height: 6px; border-radius: 12px; overflow: hidden;}
.recent-shipment-progress .progress-bar {transition: width .6s ease;}

/* Cards & Buttons */
.card.border-left-leaf {border-left: 5px solid #8fbf5f;}
.btn-outline-template {border-color: #ced4da; color: #34413d;}
.btn-outline-template:hover {background-color: rgba(52,65,61,.08);}

/* Chat Styling */
.chat-avatar {width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center;}

/* Farmer Dashboard Custom Styles */
.farmer-dashboard-shell {
    background-color: var(--bg-warm);
    min-height: calc(100vh - 56px);
}

.dashboard-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    min-height: 88px;
    padding: 1.25rem 1.5rem;
    border-radius: 24px;
    background: rgba(255,255,255,.24) !important;
    border: 1px solid rgba(255,255,255,.22) !important;
    box-shadow: 0 14px 40px rgba(0,0,0,.12) !important;
    backdrop-filter: blur(18px);
    position: relative;
    z-index: 2;
}

.dashboard-topbar .topbar-text {
    min-width: 0;
}

.dashboard-topbar .topbar-title {
    margin: 0;
    color: #41431B;
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.dashboard-topbar .topbar-subtitle {
    margin: 0;
    color: rgba(65,67,27,.72);
    font-size: 1rem;
    line-height: 1.5;
}

.dashboard-topbar .topbar-actions {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    flex-wrap: wrap;
}

.dashboard-topbar .topbar-icon-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.22);
    background: rgba(255,255,255,.22);
    color: #41431B;
    display: grid;
    place-items: center;
    transition: transform .25s ease, background .25s ease, box-shadow .25s ease;
}

.dashboard-topbar .topbar-icon-btn:hover {
    transform: translateY(-1px);
    background: rgba(255,255,255,.32);
    box-shadow: 0 10px 20px rgba(0,0,0,.14);
}

.dashboard-topbar .btn-topbar-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.9rem 1.35rem;
    background: linear-gradient(135deg, #AEB784 0%, #E3DBBB 100%);
    color: #41431B;
    border: none;
    border-radius: 999px;
    font-weight: 700;
    box-shadow: 0 18px 38px rgba(174,183,132,.22);
    transition: transform .25s ease, box-shadow .25s ease, opacity .25s ease;
}

.dashboard-topbar .btn-topbar-cta:hover {
    transform: translateY(-1px);
    box-shadow: 0 20px 40px rgba(174,183,132,.28);
}

.dashboard-topbar .topbar-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    font-weight: 900;
    font-size: 1rem;
    color: #41431B;
    background: linear-gradient(145deg, #F8F3E1 0%, #E3DBBB 100%);
    border: 1px solid rgba(255,255,255,.95);
    box-shadow: 0 12px 20px rgba(0,0,0,.1);
}

@media (max-width: 991.98px) {
    .dashboard-topbar {
        flex-direction: column;
        align-items: stretch;
        padding: 0.95rem 1rem;
        min-height: auto;
    }

    .dashboard-topbar .topbar-actions {
        justify-content: flex-start;
        width: 100%;
    }
}

.sidebar-column {
    width: 304px;
    min-height: calc(100vh - 64px);
    background:
        radial-gradient(circle at 24px 40px, rgba(248,243,225,.12), transparent 120px),
        linear-gradient(180deg, var(--forest) 0%, #2f3016 52%, #20210f 100%);
    border-right: 1px solid rgba(248,243,225,.12);
    box-shadow: 18px 0 45px rgba(65, 67, 27, .12);
}

.premium-sidebar {
    position: sticky;
    top: 64px;
    height: calc(100vh - 64px);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.1rem;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(248,243,225,.26) transparent;
}

.premium-sidebar::-webkit-scrollbar {
    width: 6px;
}

.premium-sidebar::-webkit-scrollbar-thumb {
    background: rgba(248,243,225,.26);
    border-radius: 999px;
}

.sidebar-profile-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: .7rem;
    min-height: 100px;
    padding: .75rem .9rem;
    border-radius: 24px;
    overflow: hidden;
    background:
        radial-gradient(circle at 19% 22%, rgba(248,243,225,.26), transparent 34%),
        radial-gradient(circle at 82% 16%, rgba(227,219,187,.24), transparent 30%),
        radial-gradient(circle at 72% 96%, rgba(174,183,132,.15), transparent 36%),
        linear-gradient(135deg, rgba(248,243,225,.15), rgba(174,183,132,.07));
    border: 1px solid rgba(248,243,225,.38);
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.42),
        inset 0 -1px 0 rgba(0,0,0,.08),
        0 14px 28px rgba(0,0,0,.18);
}

.sidebar-profile-card::before {
    content: "";
    position: absolute;
    left: .58rem;
    top: .72rem;
    width: 70px;
    height: 84px;
    border-radius: 28px;
    background:
        radial-gradient(rgba(248,243,225,.16) 1px, transparent 1px),
        radial-gradient(ellipse at 28% 64%, rgba(227,219,187,.32) 0 18%, transparent 19%);
    background-size: 8px 8px, 100% 100%;
    opacity: .46;
}

.sidebar-profile-card::after {
    content: "";
    position: absolute;
    right: -24px;
    bottom: -22px;
    width: 122px;
    height: 96px;
    border-radius: 65% 20% 70% 25%;
    background:
        radial-gradient(ellipse at 68% 52%, rgba(227,219,187,.22) 0 11%, transparent 12%),
        radial-gradient(ellipse at 49% 38%, rgba(174,183,132,.18) 0 14%, transparent 15%),
        radial-gradient(ellipse at 31% 56%, rgba(227,219,187,.12) 0 13%, transparent 14%),
        linear-gradient(135deg, rgba(174,183,132,.12), rgba(248,243,225,.02));
    transform: rotate(-18deg);
    box-shadow:
        -18px -8px 0 rgba(174,183,132,.08),
        -42px 7px 0 rgba(227,219,187,.06);
}

.profile-details::after {
    content: "";
    display: block;
    width: 82px;
    height: 2px;
    margin-top: .54rem;
    background: linear-gradient(90deg, rgba(174,183,132,0), rgba(227,219,187,.95), rgba(174,183,132,0));
    box-shadow: 0 0 18px rgba(227,219,187,.7);
}

.profile-avatar {
    position: relative;
    z-index: 1;
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    background:
        linear-gradient(135deg, rgba(248,243,225,.96), rgba(227,219,187,.68));
    color: var(--forest);
    border: 2px solid rgba(248,243,225,.82);
    box-shadow:
        0 0 22px rgba(248,243,225,.34),
        0 10px 18px rgba(0,0,0,.2);
    font-weight: 900;
    font-size: 1.85rem;
    line-height: 1;
}

.profile-details {
    position: relative;
    z-index: 1;
    min-width: 0;
}

.profile-name {
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #fff;
    font-size: 1rem;
    font-weight: 900;
    text-shadow: 0 3px 14px rgba(0,0,0,.28);
}

.profile-role {
    display: flex;
    align-items: center;
    gap: .48rem;
    color: rgba(248,243,225,.78);
    font-size: .9rem;
    font-weight: 700;
    margin-top: .18rem;
}

.live-dot-small {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #53e14a;
    box-shadow:
        0 0 0 6px rgba(83,225,74,.12),
        0 0 18px rgba(83,225,74,.8);
}

.avatar-online-dot {
    position: absolute;
    right: -5px;
    bottom: -5px;
    width: 23px;
    height: 23px;
    border-radius: 50%;
    background: #53e14a;
    border: 4px solid rgba(248,243,225,.96);
    box-shadow: 0 0 18px rgba(83,225,74,.9);
}

.sidebar-menu-wrapper {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    padding-right: .2rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(248,243,225,.22) transparent;
}

.sidebar-menu-wrapper::-webkit-scrollbar {
    width: 5px;
}

.sidebar-menu-wrapper::-webkit-scrollbar-thumb {
    background: rgba(248,243,225,.22);
    border-radius: 999px;
}

.sidebar-menu-label {
    display: block;
    padding: .25rem .8rem .55rem;
    color: rgba(248,243,225,.48);
    font-size: .68rem;
    font-weight: 900;
    letter-spacing: .14em;
}

.sidebar-menu {
    gap: .28rem;
    padding-bottom: .4rem;
}

.sidebar-menu .nav-item {
    margin: 0;
}

#menu .sidebar-link {
    min-height: 42px;
    display: grid;
    grid-template-columns: 36px minmax(0, 1fr) auto;
    align-items: center;
    gap: .45rem;
    padding: .42rem .62rem;
    border-radius: 14px;
    color: rgba(248,243,225,.72);
    text-decoration: none;
    font-weight: 700;
    transition: background .2s ease, color .2s ease, transform .2s ease, box-shadow .2s ease;
}

#menu .sidebar-link i {
    width: 32px;
    height: 32px;
    border-radius: 11px;
    display: grid;
    place-items: center;
    background: rgba(248,243,225,.08);
    color: rgba(248,243,225,.72);
    font-size: 1rem;
    transition: background .2s ease, color .2s ease;
}

#menu .sidebar-link span:not(.sidebar-badge) {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

#menu .sidebar-link:hover {
    background: rgba(248,243,225,.08);
    color: #fff;
    transform: translateX(2px);
}

#menu .sidebar-link:hover i {
    background: rgba(248,243,225,.14);
    color: #fff;
}

#menu .sidebar-link.active {
    background: linear-gradient(135deg, var(--cream), var(--sprout));
    color: var(--forest);
    box-shadow: 0 14px 28px rgba(0,0,0,.18);
}

#menu .sidebar-link.active i {
    background: rgba(65,67,27,.12);
    color: var(--forest);
}

.sidebar-badge {
    min-width: 24px;
    height: 24px;
    padding: 0 .45rem;
    border-radius: 999px;
    display: inline-grid;
    place-items: center;
    background: var(--leaf);
    color: var(--forest);
    font-size: .72rem;
    font-weight: 900;
}

#menu .sidebar-link.active .sidebar-badge {
    background: var(--forest);
    color: var(--cream);
}

.sidebar-status-card {
    margin-top: auto;
    flex: 0 0 auto;
    padding: .9rem;
    border-radius: 18px;
    background:
        linear-gradient(135deg, rgba(248,243,225,.13), rgba(174,183,132,.08));
    border: 1px solid rgba(248,243,225,.14);
    color: #fff;
}

.status-item {
    display: flex;
    align-items: center;
    gap: .55rem;
    font-weight: 800;
    font-size: .9rem;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #9ee37d;
    box-shadow: 0 0 0 5px rgba(158,227,125,.12);
}

@media (max-width: 991.98px) {
    .sidebar-column {
        width: 100%;
        min-height: auto;
    }

    .premium-sidebar {
        position: relative;
        top: auto;
        height: auto;
    }

    .sidebar-menu {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sidebar-status-card {
        display: none;
    }
}

.border-left-leaf {
    border-left: 5px solid var(--leaf) !important;
}

/* Timeline Shipments */
.shipment-status-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}

.shipment-status-timeline::before {
    content: '';
    position: absolute;
    top: 9px;
    left: 10px;
    right: 10px;
    height: 3px;
    background-color: var(--sprout);
    z-index: 1;
}

.status-step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex-grow: 1;
}

.status-marker {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #ddd;
    border: 3px solid white;
    display: block;
    margin: 0 auto 5px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.status-step.active .status-marker {
    background-color: var(--leaf);
    box-shadow: 0 0 8px var(--leaf);
}

.status-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: #666;
}

.status-step.active .status-label {
    color: var(--forest);
    font-weight: 700;
}

/* Chat Styles */
.chat-message {
    max-width: 75%;
    clear: both;
}

.chat-message .message-bubble {
    padding: 10px 16px;
    border-radius: 18px;
    font-size: 0.92rem;
    line-height: 1.4;
    box-shadow: var(--shadow-sm);
}

.chat-message.sent {
    float: right;
}

.chat-message.sent .message-bubble {
    background-color: var(--forest);
    color: var(--cream);
    border-bottom-right-radius: 4px;
}

.chat-message.received {
    float: left;
}

.chat-message.received .message-bubble {
    background-color: white;
    color: var(--ink);
    border-bottom-left-radius: 4px;
    border: 1px solid var(--border);
}

.chat-message .message-time {
    font-size: 0.7rem;
    color: #888;
    margin-top: 3px;
    display: block;
    text-align: right;
}

.chat-message.received .message-time {
    text-align: left;
}

/* QR Scanning Laser Effect */
.qr-scanning-laser {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--leaf);
    box-shadow: 0 0 10px var(--leaf);
    animation: scanLaser 2s linear infinite;
    z-index: 10;
}

@keyframes scanLaser {
    0% { top: 10px; }
    50% { top: 230px; }
    100% { top: 10px; }
}

.cursor-pointer {
    cursor: pointer;
}

.autocomplete-dropdown {
    position: absolute;
    z-index: 100;
    width: 100%;
    box-shadow: var(--shadow-md);
    background: white;
    border-radius: 8px;
}

.payment-method-box {
    transition: all 0.25s ease;
}

.payment-method-box:hover {
    background-color: rgba(174,183,132,0.06);
    border-color: var(--forest) !important;
}

.active-gateway {
    border-color: var(--forest) !important;
    background-color: rgba(174,183,132,0.08) !important;
    font-weight: bold;
}

.btn-xs {
    padding: 0.25rem 0.6rem;
    font-size: 0.75rem;
    border-radius: 10px;
}
</style>
