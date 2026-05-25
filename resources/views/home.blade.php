@extends('layouts.app', ['title' => 'AgroTransit'])

@section('content')

<style>

:root{
    --forest:#41431B;
    --leaf:#AEB784;
    --sprout:#E3DBBB;
    --cream:#F8F3E1;
    --ink:#262713;
}

/* =========================================================
GLOBAL
========================================================= */

body{
    background:
    linear-gradient(135deg,#f8f3e1 0%,#f2ecd9 100%);
    overflow-x:hidden;
    font-family:'Inter',sans-serif;
    color:var(--ink);
}

.home-wrapper{
    position:relative;
    overflow:hidden;
}

/* =========================================================
BACKGROUND EFFECTS
========================================================= */

.hero-bg-grid{
    position:absolute;
    inset:0;

    background-image:
    linear-gradient(rgba(65,67,27,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(65,67,27,.05) 1px, transparent 1px);

    background-size:50px 50px;

    mask-image:linear-gradient(to bottom, transparent, black);

    animation:gridMove 18s linear infinite;
}

@keyframes gridMove{
    0%{
        transform:translateY(0);
    }

    100%{
        transform:translateY(50px);
    }
}

.hero-blur{
    position:absolute;
    border-radius:50%;
    filter:blur(120px);
    opacity:.35;
}

.hero-blur-1{
    width:450px;
    height:450px;
    background:#AEB784;
    top:-120px;
    left:-120px;
    animation:floatBlur 8s ease-in-out infinite;
}

.hero-blur-2{
    width:520px;
    height:520px;
    background:#E3DBBB;
    right:-180px;
    bottom:-180px;
    animation:floatBlur 10s ease-in-out infinite;
}

@keyframes floatBlur{
    0%,100%{
        transform:translateY(0);
    }

    50%{
        transform:translateY(-30px);
    }
}

/* =========================================================
HERO
========================================================= */

.premium-hero{
    position:relative;
    min-height:100vh;
    display:flex;
    align-items:center;
    overflow:hidden;
    padding:4rem 0;
}

.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:.7rem;

    padding:.8rem 1.3rem;

    border-radius:999px;

    background:rgba(255,255,255,.5);

    backdrop-filter:blur(14px);

    border:1px solid rgba(255,255,255,.45);

    color:#41431B;

    font-size:.82rem;
    font-weight:700;

    margin-bottom:2rem;

    box-shadow:
    0 10px 30px rgba(0,0,0,.05);
}

.live-dot{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#22c55e;
    animation:pulse 1.5s infinite;
}

@keyframes pulse{
    0%{
        box-shadow:0 0 0 0 rgba(34,197,94,.5);
    }

    100%{
        box-shadow:0 0 0 12px rgba(34,197,94,0);
    }
}

.hero-title{
    font-size:clamp(2.9rem,5vw,5rem);

    line-height:1;

    font-weight:800;

    letter-spacing:-0.05em;

    color:#41431B;

    margin-bottom:1.8rem;
}

.hero-title span{
    display:block;
    color:#68703d;
}

.hero-desc{
    font-size:1.05rem;
    line-height:1.9;
    color:#666b57;
    max-width:560px;
    margin-bottom:2.5rem;
}

/* =========================================================
BUTTONS
========================================================= */

.hero-btn-group{
    margin-bottom:3rem;
}

.hero-btn{
    position:relative;
    overflow:hidden;

    height:58px;

    padding:0 2rem;

    border-radius:20px;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:.7rem;

    font-weight:700;

    transition:.4s cubic-bezier(.16,1,.3,1);

    text-decoration:none;
}

.hero-btn-main{
    background:
    linear-gradient(
    135deg,
    #41431B,
    #5f6640
    );

    color:white;

    box-shadow:
    0 20px 40px rgba(65,67,27,.18);
}

.hero-btn-main:hover{
    transform:
    translateY(-6px)
    scale(1.03);

    color:white;

    box-shadow:
    0 28px 50px rgba(65,67,27,.25);
}

.hero-btn-main::before{
    content:'';

    position:absolute;
    inset:0;

    left:-120%;

    background:
    linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.25),
    transparent
    );

    transition:.6s;
}

.hero-btn-main:hover::before{
    left:120%;
}

.hero-btn-outline{
    background:
    rgba(255,255,255,.5);

    backdrop-filter:blur(14px);

    border:
    1px solid rgba(255,255,255,.5);

    color:#41431B;
}

.hero-btn-outline:hover{
    transform:
    translateY(-6px);

    background:white;

    color:#41431B;

    box-shadow:
    0 18px 35px rgba(65,67,27,.08);
}

/* =========================================================
MINI STATS
========================================================= */

.hero-mini-stats{
    display:flex;
    gap:1rem;
    flex-wrap:wrap;
}

.mini-stat-card{
    position:relative;
    overflow:hidden;

    padding:1.4rem 1.5rem;

    border-radius:24px;

    background:
    rgba(255,255,255,.45);

    backdrop-filter:blur(20px);

    border:
    1px solid rgba(255,255,255,.5);

    min-width:160px;

    transition:.4s ease;
}

.mini-stat-card::before{
    content:'';

    position:absolute;
    inset:0;

    background:
    linear-gradient(
    135deg,
    rgba(255,255,255,.3),
    transparent
    );

    opacity:0;

    transition:.4s;
}

.mini-stat-card:hover::before{
    opacity:1;
}

.mini-stat-card:hover{
    transform:
    translateY(-10px)
    rotate(-1deg);

    box-shadow:
    0 20px 40px rgba(65,67,27,.08);
}

.mini-stat-card h4{
    font-size:1.9rem;
    font-weight:800;
    color:#41431B;
}

.mini-stat-card span{
    color:#666b57;
    font-size:.92rem;
}

/* =========================================================
RIGHT VISUAL
========================================================= */

.hero-visual-wrapper{
    position:relative;
    height:720px;
}

/* =========================================================
MAIN DASHBOARD
========================================================= */

.main-dashboard-card{
    position:absolute;

    top:50%;
    left:50%;

    transform:
    translate(-50%,-50%);

    width:440px;

    padding:2rem;

    border-radius:34px;

    background:
    rgba(255,255,255,.52);

    backdrop-filter:blur(30px);

    border:
    1px solid rgba(255,255,255,.55);

    box-shadow:
    0 30px 60px rgba(65,67,27,.12);

    z-index:5;

    animation:floatCard 8s ease-in-out infinite;
}

.dashboard-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:2rem;
}

.dashboard-top h2{
    font-size:3rem;
    font-weight:800;
    color:#41431B;
}

.dashboard-icon{
    width:72px;
    height:72px;

    border-radius:24px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:
    linear-gradient(
    135deg,
    #AEB784,
    #d6cfaa
    );

    color:#41431B;

    font-size:1.8rem;
}

/* =========================================================
LIVE SHIPMENT
========================================================= */

.live-shipment-card{
    display:flex;
    justify-content:space-between;
    align-items:center;

    padding:1.3rem;

    border-radius:24px;

    background:white;

    margin-bottom:1.4rem;

    transition:.35s ease;
}

.live-shipment-card:hover{
    transform:
    translateY(-6px)
    scale(1.02);

    box-shadow:
    0 20px 35px rgba(65,67,27,.08);
}

.shipment-left{
    display:flex;
    align-items:center;
    gap:1rem;
}

.shipment-icon{
    width:56px;
    height:56px;

    border-radius:18px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:
    rgba(174,183,132,.16);

    color:#41431B;

    font-size:1.2rem;
}

.shipment-badge{
    padding:.5rem 1rem;

    border-radius:999px;

    background:
    rgba(34,197,94,.14);

    color:#15803d;

    font-size:.8rem;
    font-weight:700;
}

/* =========================================================
STATUS ROW
========================================================= */

.live-status-row{
    display:flex;
    gap:1rem;
    flex-wrap:wrap;
}

.status-pill{
    display:flex;
    align-items:center;
    gap:.5rem;

    padding:.8rem 1rem;

    border-radius:999px;

    background:white;

    font-size:.85rem;
    font-weight:600;

    transition:.3s ease;
}

.status-pill:hover{
    transform:translateY(-4px);
}

.pulse-dot{
    width:9px;
    height:9px;
    border-radius:50%;
    background:#22c55e;
    animation:pulse 1.5s infinite;
}

/* =========================================================
FLOATING CARDS
========================================================= */

.floating-card{
    position:absolute;

    padding:1.4rem;

    border-radius:28px;

    background:
    rgba(255,255,255,.55);

    backdrop-filter:blur(24px);

    border:
    1px solid rgba(255,255,255,.5);

    box-shadow:
    0 20px 45px rgba(65,67,27,.08);

    transition:.35s ease;
}

.floating-card:hover{
    transform:
    translateY(-10px)
    rotate(-2deg);

    box-shadow:
    0 30px 50px rgba(65,67,27,.15);
}

/* =========================================================
ROUTE CARD
========================================================= */

.card-route{
    width:320px;

    top:40px;
    right:20px;

    z-index:10;

    animation:floatCard 7s ease-in-out infinite;
}

@keyframes floatCard{
    0%,100%{
        transform:translateY(0);
    }

    50%{
        transform:translateY(-15px);
    }
}

.route-status{
    padding:.45rem .9rem;

    border-radius:999px;

    background:
    rgba(34,197,94,.15);

    color:#15803d;

    font-size:.78rem;
    font-weight:700;
}

.route-line-wrapper{
    position:relative;
    margin-top:2rem;
    height:50px;
}

.route-line{
    position:absolute;

    top:50%;
    left:10%;
    right:10%;

    height:4px;

    border-radius:999px;

    background:
    rgba(174,183,132,.3);
}

.route-node{
    position:absolute;

    left:0;
    top:50%;

    transform:translateY(-50%);

    width:16px;
    height:16px;

    border-radius:50%;

    background:#41431B;

    box-shadow:
    0 0 0 8px rgba(174,183,132,.2);
}

.node-end{
    left:auto;
    right:0;
}

.truck-move{
    position:absolute;

    top:50%;

    transform:translateY(-50%);

    font-size:1.6rem;

    animation:truckMove 4s linear infinite;
}

@keyframes truckMove{
    0%{
        left:5%;
    }

    100%{
        left:78%;
    }
}

.custom-progress{
    height:10px;
    border-radius:999px;
    overflow:hidden;
    background:rgba(174,183,132,.18);
}

.progress-bar{
    width:78%;
    height:100%;

    border-radius:999px;

    background:
    linear-gradient(
    90deg,
    #AEB784,
    #41431B
    );

    animation:progressMove 2s ease-in-out infinite;
}

@keyframes progressMove{
    0%,100%{
        opacity:1;
    }

    50%{
        opacity:.75;
    }
}

/* =========================================================
SAVINGS CARD
========================================================= */

.savings-card{
    width:260px;

    bottom:50px;
    left:10px;

    z-index:9;

    animation:floatCard 9s ease-in-out infinite;
}

.savings-card h3{
    font-size:2.2rem;
    font-weight:800;
    color:#41431B;
    margin-bottom:.6rem;
}

.savings-card p{
    color:#666b57;
    line-height:1.7;
}

/* =========================================================
FEATURE SECTION
========================================================= */

.features-section{
    position:relative;
    padding:8rem 0;
}

.section-title{
    font-size:clamp(2rem,4vw,3.4rem);
    font-weight:800;
    color:#41431B;
    letter-spacing:-0.04em;
}

.section-subtitle{
    max-width:620px;
    margin:auto;
    line-height:1.9;
    color:#666b57;
}

.feature-card{
    position:relative;
    overflow:hidden;

    padding:2.3rem;

    border-radius:34px;

    background:
    rgba(255,255,255,.5);

    backdrop-filter:blur(20px);

    border:
    1px solid rgba(255,255,255,.55);

    transition:.45s cubic-bezier(.16,1,.3,1);

    height:100%;
}

.feature-card::before{
    content:'';

    position:absolute;
    inset:0;

    background:
    linear-gradient(
    135deg,
    rgba(255,255,255,.35),
    transparent
    );

    opacity:0;

    transition:.45s;
}

.feature-card:hover::before{
    opacity:1;
}

.feature-card:hover{
    transform:
    translateY(-12px)
    scale(1.02);

    box-shadow:
    0 30px 50px rgba(65,67,27,.1);
}

.feature-icon{
    width:74px;
    height:74px;

    border-radius:24px;

    display:flex;
    align-items:center;
    justify-content:center;

    margin-bottom:1.6rem;

    font-size:1.7rem;

    background:
    linear-gradient(
    135deg,
    rgba(174,183,132,.25),
    rgba(174,183,132,.08)
    );

    color:#41431B;
}

.feature-title{
    font-size:1.4rem;
    font-weight:700;
    color:#41431B;
    margin-bottom:1rem;
}

.feature-desc{
    line-height:1.9;
    color:#666b57;
}

/* =========================================================
RESPONSIVE
========================================================= */

@media(max-width:991px){

    .hero-visual-wrapper{
        height:620px;
        margin-top:4rem;
    }

    .main-dashboard-card{
        width:100%;
        max-width:420px;
    }

    .card-route{
        width:280px;
    }

    .hero-title{
        font-size:3.3rem;
    }
}

</style>

<div class="home-wrapper">

<div class="hero-bg-grid"></div>

<div class="hero-blur hero-blur-1"></div>
<div class="hero-blur hero-blur-2"></div>

<!-- =========================================================
HERO SECTION
========================================================= -->

<section class="premium-hero">

<div class="container position-relative z-3">

<div class="row align-items-center min-vh-100 py-4">

<!-- LEFT -->

<div class="col-lg-6 pe-lg-5">

<div class="hero-badge">

<span class="live-dot"></span>

Smart Agricultural Transport Platform

</div>

<h1 class="hero-title">

Shared Logistics
<span>for Modern Agriculture</span>

</h1>

<p class="hero-desc">

AgroTransit connects farmers, transport owners, and drivers through a premium logistics ecosystem with pooling, live tracking, and optimized transportation management.

</p>

<div class="d-flex flex-wrap gap-3 hero-btn-group">

<a href="{{ route('login') }}"
class="hero-btn hero-btn-main">

<i class="bi bi-plus-circle"></i>

Create Shipment

</a>

<a href="{{ route('login') }}"
class="hero-btn hero-btn-outline">

Explore Platform

</a>

</div>

<div class="hero-mini-stats">

<div class="mini-stat-card">

<h4>1200+</h4>

<span>Farmers Connected</span>

</div>

<div class="mini-stat-card">

<h4>350+</h4>

<span>Vehicles Active</span>

</div>

<div class="mini-stat-card">

<h4>35%</h4>

<span>Cost Reduction</span>

</div>

</div>

</div>

<!-- RIGHT -->

<div class="col-lg-6">

<div class="hero-visual-wrapper">

<!-- ROUTE CARD -->

<div class="floating-card card-route">

<div class="d-flex justify-content-between align-items-center mb-3">

<div>

<small class="text-muted">
Current Route
</small>

<h6 class="fw-bold mb-0">
Ludhiana → Delhi
</h6>

</div>

<div class="route-status">

Live

</div>

</div>

<div class="route-line-wrapper">

<div class="route-node"></div>

<div class="route-line"></div>

<div class="truck-move">

🚚

</div>

<div class="route-node node-end"></div>

</div>

<div class="mt-4 d-flex justify-content-between">

<small class="text-muted">

Capacity Filled

</small>

<small class="fw-bold text-success">

78%

</small>

</div>

<div class="progress custom-progress mt-2">

<div class="progress-bar"></div>

</div>

</div>

<!-- MAIN DASHBOARD -->

<div class="main-dashboard-card">

<div class="dashboard-top">

<div>

<small class="text-muted">
Live Shipments
</small>

<h2>128</h2>

</div>

<div class="dashboard-icon">

<i class="bi bi-truck"></i>

</div>

</div>

<div class="live-shipment-card">

<div class="shipment-left">

<div class="shipment-icon">

<i class="bi bi-box-seam"></i>

</div>

<div>

<h6 class="mb-1">
Tomato Shipment
</h6>

<small class="text-muted">
850kg · Refrigerated
</small>

</div>

</div>

<div class="shipment-badge">

Transit

</div>

</div>

<div class="live-status-row">

<div class="status-pill">

<span class="pulse-dot"></span>

Driver Active

</div>

<div class="status-pill">

ETA · 2h 15m

</div>

</div>

</div>

<!-- SAVINGS -->

<div class="floating-card savings-card">

<small class="text-muted d-block mb-2">

Pooling Savings

</small>

<h3>₹4,250</h3>

<p class="mb-0">

Saved this month through shared transportation.

</p>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- =========================================================
FEATURES
========================================================= -->

<section class="features-section">

<div class="container">

<div class="text-center mb-5">

<h2 class="section-title mb-3">

Built for Smarter Agricultural Logistics

</h2>

<p class="section-subtitle">

AgroTransit helps farmers reduce transportation cost through shared logistics, live tracking, vehicle pooling, and optimized transport management.

</p>

</div>

<div class="row g-4">

<div class="col-lg-4">

<div class="feature-card">

<div class="feature-icon">

<i class="bi bi-diagram-3"></i>

</div>

<h3 class="feature-title">

Transport Pooling

</h3>

<p class="feature-desc">

Farmers can share unused truck capacity and reduce transportation expenses through smart shipment pooling.

</p>

</div>

</div>

<div class="col-lg-4">

<div class="feature-card">

<div class="feature-icon">

<i class="bi bi-geo-alt"></i>

</div>

<h3 class="feature-title">

Live Tracking

</h3>

<p class="feature-desc">

Monitor shipments, drivers, and transport progress with realtime logistics tracking.

</p>

</div>

</div>

<div class="col-lg-4">

<div class="feature-card">

<div class="feature-icon">

<i class="bi bi-shield-check"></i>

</div>

<h3 class="feature-title">

Secure Verification

</h3>

<p class="feature-desc">

QR-based verification ensures safe pickup and delivery confirmation for every shipment.

</p>

</div>

</div>

</div>

</div>

</section>

</div>

@endsection