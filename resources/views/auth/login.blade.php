<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - AgroTransit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --forest:#41431B; --leaf:#AEB784; --sprout:#E3DBBB; --cream:#F8F3E1; --ink:#262713; --surface:#fffaf0; }
        * { box-sizing:border-box; }
        body { height:100vh; margin:0; color:var(--ink); font-family:Inter, system-ui, -apple-system, Segoe UI, sans-serif; background:var(--cream); overflow:hidden; }
        .login-shell { height:100vh; display:grid; grid-template-columns:minmax(0, 1.08fr) minmax(430px, .92fr); }
        .visual-panel { position:relative; overflow:hidden; display:flex; align-items:flex-end; padding:clamp(1.5rem, 4vw, 3rem); color:var(--cream); background:linear-gradient(to right, rgba(65,67,27,.88), rgba(65,67,27,.46)), url('https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1600&q=80') center/cover; }
        .visual-panel:before, .visual-panel:after { content:""; position:absolute; border-radius:999px; filter:blur(80px); opacity:.32; animation:float 9s ease-in-out infinite; }
        .visual-panel:before { width:360px; height:360px; left:4%; top:14%; background:var(--leaf); }
        .visual-panel:after { width:280px; height:280px; right:10%; top:20%; background:var(--cream); animation-delay:1.6s; }
        .route-stage { position:relative; height:88px; margin:1.2rem 0 .75rem; width:min(100%, 620px); }
        .route-track { position:absolute; z-index:1; left:28px; right:28px; top:45px; height:0; border-top:3px dashed rgba(248,243,225,.62); }
        .route-pin { position:absolute; z-index:3; width:28px; height:28px; border-radius:50% 50% 50% 0; background:var(--cream); transform:rotate(-45deg); box-shadow:0 0 0 8px rgba(248,243,225,.15), 0 10px 26px rgba(0,0,0,.16); }
        .route-pin:after { content:""; position:absolute; width:10px; height:10px; border-radius:50%; background:var(--forest); left:9px; top:9px; }
        .pin-start { left:0; top:31px; }
        .pin-end { right:0; top:31px; }
        .truck { position:absolute; z-index:2; top:27px; left:44px; width:76px; height:34px; border-radius:7px; background:var(--cream); box-shadow:inset -24px 0 0 var(--sprout), 0 16px 40px rgba(0,0,0,.18); animation:driveTruck 1.8s ease-in-out infinite alternate; }
        .truck:before { content:""; position:absolute; right:-18px; top:9px; width:24px; height:25px; border-radius:5px 8px 5px 3px; background:var(--cream); }
        .truck:after { content:""; position:absolute; left:12px; bottom:-9px; width:14px; height:14px; border-radius:50%; background:var(--forest); box-shadow:56px 0 0 var(--forest), 86px 0 0 var(--forest); }
        .visual-content { position:relative; z-index:3; max-width:660px; }
        .visual-brand { display:flex; align-items:center; gap:.75rem; margin-bottom:1rem; }
        .visual-logo { width:58px; height:58px; border-radius:17px; object-fit:contain; box-shadow:0 14px 30px rgba(0,0,0,.2); }
        .visual-brand strong { display:block; color:white; font-size:1.35rem; line-height:1; }
        .visual-brand span { color:rgba(248,243,225,.82); font-weight:700; font-size:.88rem; }
           .visual-title { font-size:clamp(2.2rem, 4.9vw, 4.8rem); line-height:.95; letter-spacing:0; font-weight:900; margin:.9rem 0 .75rem; }
        .visual-title span { display:block; }
        .visual-title .soft-title { font-weight:500; color:#f8f3e1; }
        .typing-text { min-height:1.6rem; font-size:1.12rem; color:#fff8dc; font-weight:700; }
        .stats-grid { display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:.75rem; margin-top:.6rem; }
        .stat-card { padding:.8rem; border-radius:8px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); backdrop-filter:blur(14px); box-shadow:0 8px 30px rgba(0,0,0,.15); transition:transform .24s ease, background .24s ease; }
        .stat-card:hover { transform:translateY(-3px); background:rgba(255,255,255,.12); }
        .stat-card strong { display:block; font-size:1.25rem; color:white; }
        .stat-card span { font-size:.86rem; }
        .form-panel { position:relative; display:grid; place-items:center; padding:1.1rem; background:radial-gradient(circle at 80% 12%, rgba(174,183,132,.38), transparent 28%), var(--cream); }

        .login-card { width:min(100%, 500px); padding:1.35rem; border-radius:22px; background:rgba(255,250,240,.58); border:1px solid rgba(255,255,255,.78); box-shadow:0 24px 70px rgba(65,67,27,.18); backdrop-filter:blur(18px); animation:rise .7s ease both; }
        .role-grid { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:.65rem; margin:.9rem 0; }
        .role-option input { position:absolute; opacity:0; pointer-events:none; }
        .role-card { height:62px; border:1px solid rgba(65,67,27,.18); border-radius:15px; display:flex; flex-direction:column; justify-content:center; padding:.75rem; background:rgba(248,243,225,.68); cursor:pointer; transition:all .24s ease; }
        .role-card span { font-weight:800; color:var(--forest); }
        .role-card small { color:#6d6f4c; }
        .role-option input:checked + .role-card { background:var(--forest); color:var(--cream); transform:translateY(-2px); box-shadow:0 14px 28px rgba(65,67,27,.22); }
        .role-option input:checked + .role-card span, .role-option input:checked + .role-card small { color:var(--cream); }
        .floating-field { position:relative; margin-bottom:.75rem; }
        .floating-field input { height:52px; border-radius:15px; border:1px solid rgba(65,67,27,.18); background:rgba(248,243,225,.82); color:var(--forest); padding:1.25rem 3rem .4rem 1rem; }
        .floating-field label { position:absolute; left:1rem; top:.55rem; font-size:.78rem; color:#6d6f4c; font-weight:700; }
        .field-action { position:absolute; right:.7rem; top:.55rem; height:34px; min-width:36px; border:0; border-radius:10px; background:var(--sprout); color:var(--forest); font-weight:800; }
        .secure-btn { width:100%; height:52px; border:0; border-radius:16px; background:linear-gradient(135deg, var(--forest), #5d6231); color:var(--cream); font-weight:800; box-shadow:0 16px 30px rgba(65,67,27,.26); transition:all .24s ease; }
        .secure-btn:hover { transform:translateY(-2px) scale(1.01); background:linear-gradient(135deg, var(--leaf), var(--forest)); }
        .btn-outline-template { border:1px solid var(--leaf); color:var(--forest); background:rgba(248,243,225,.76); border-radius:14px; font-weight:800; }
        .btn-outline-template:hover { background:var(--forest); color:var(--cream); border-color:var(--forest); }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-16px); } }
        @keyframes driveTruck { from { left:44px; } to { left:calc(100% - 104px); } }
        @keyframes rise { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        @media (max-width: 1240px) { .visual-content { max-width:720px; } }
        @media (max-width: 992px) {
            body { height:auto; min-height:100vh; overflow:auto; }
            .login-shell { grid-template-columns:1fr; }
            .visual-panel { min-height:48vh; align-items:center; }
            .visual-brand { margin-bottom:.75rem; }
            .stats-grid { grid-template-columns:repeat(2, minmax(0, 1fr)); }
            .form-panel { padding:5rem 1rem 2rem; }
            .route-stage { display:none; }
        }
        @media (max-width: 576px) {
            .visual-panel { padding:1.5rem; }
            .login-card { padding:1.25rem; border-radius:18px; }
            .role-grid { grid-template-columns:1fr; }
            .stats-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<main class="login-shell">
    <section class="visual-panel">
        <div class="visual-content">
            <div class="visual-brand">
                <img class="visual-logo" src="{{ asset('images/agrotransit-logo.png') }}" alt="AgroTransit logo">
                <div>
                    <strong>AgroTransit</strong>
                    <span>Smart agricultural logistics</span>
                </div>
            </div>
            <h1 class="visual-title"><span>Smart Transportation</span><span class="soft-title">for Modern Agriculture</span></h1>
            <p class="typing-text" id="typingText">Connecting farmers, drivers and markets efficiently.</p>
            <div class="route-stage" aria-hidden="true">
                <div class="route-track"></div>
                <div class="route-pin pin-start"></div>
                <div class="route-pin pin-end"></div>
                <div class="truck"></div>
            </div>
            <div class="stats-grid">
                <div class="stat-card"><strong>1200+</strong><span>Farmers Connected</span></div>
                <div class="stat-card"><strong>450+</strong><span>Vehicles Active</span></div>
                <div class="stat-card"><strong>20+</strong><span>Markets Covered</span></div>
                <div class="stat-card"><strong>35%</strong><span>Cost Saved</span></div>
            </div>
        </div>
    </section>

    <section class="form-panel">
        <form class="login-card" method="post" action="{{ route('login.store') }}" id="loginForm">
            @csrf
            @php
                $redirectTo = request('redirect_to');
            @endphp
            @if($redirectTo)
                <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
            @endif
            @if(session('status'))
                <div class="alert alert-success py-2">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif
            <h1 class="h4 fw-bold mb-1">Welcome Back</h1>
            <p class="text-muted">Login to manage your agricultural transport operations.</p>

            <div class="role-grid" aria-label="Role selection">
                <label class="role-option">
                    <input type="radio" name="role" value="farmer" checked>
                    <div class="role-card"><span>Farmer</span><small>Book and track produce</small></div>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="driver">
                    <div class="role-card"><span>Driver</span><small>Trips and proof upload</small></div>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="transport_owner">
                    <div class="role-card"><span>Owner</span><small>Vehicles and earnings</small></div>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="admin">
                    <div class="role-card"><span>Admin</span><small>Analytics and control</small></div>
                </label>
            </div>

            <div class="floating-field">
                <label for="email">Email or phone</label>
                <input id="email" class="form-control" name="email" type="text" value="{{ old('email', 'amandeep@agro.test') }}" required>
            </div>

            <div class="floating-field">
                <label for="password">Password</label>
                <input id="password" class="form-control" name="password" type="password" value="password" required>
                <button class="field-action" type="button" id="passwordToggle">Show</button>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-check-label"><input class="form-check-input me-1" type="checkbox" name="remember"> Remember me</label>
                <button class="btn btn-link link-dark fw-semibold p-0 text-decoration-none" type="button" id="forgotPasswordBtn">Forgot password?</button>
            </div>

            <button class="secure-btn" type="submit">Login Securely -></button>

            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-outline-template w-100" type="button" id="googleLoginBtn">Google Login</button>
            </div>
            <p class="text-center text-muted mt-3 mb-0">New to AgroTransit? <a class="link-dark fw-bold" href="{{ route('register') }}">Create account</a></p>

        </form>
    </section>
</main>

<script>
    const phrases = ['Reducing Transport Cost...', 'Connecting Farmers...', 'Live Vehicle Tracking...', 'AI Route Optimization...'];
    let phraseIndex = 0;
    let charIndex = 0;
    let deleting = false;
    const typingText = document.getElementById('typingText');

    function typePhrase() {
        const phrase = phrases[phraseIndex];
        typingText.textContent = phrase.slice(0, charIndex);

        if (!deleting && charIndex < phrase.length) {
            charIndex++;
            setTimeout(typePhrase, 58);
            return;
        }

        if (!deleting && charIndex === phrase.length) {
            deleting = true;
            setTimeout(typePhrase, 1200);
            return;
        }

        if (deleting && charIndex > 0) {
            charIndex--;
            setTimeout(typePhrase, 30);
            return;
        }

        deleting = false;
        phraseIndex = (phraseIndex + 1) % phrases.length;
        setTimeout(typePhrase, 220);
    }

    typePhrase();

    document.getElementById('passwordToggle').addEventListener('click', function () {
        const input = document.getElementById('password');
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        this.textContent = isPassword ? 'Hide' : 'Show';
    });

    document.getElementById('forgotPasswordBtn').addEventListener('click', function () {
        alert('Password reset is handled by the AgroTransit admin team for this demo. Contact tech2edge01@gmail.com.');
    });

    document.getElementById('googleLoginBtn').addEventListener('click', function () {
        alert('Google login is not connected for this build. Use email/phone and password to sign in.');
    });




</script>
</body>
</html>
