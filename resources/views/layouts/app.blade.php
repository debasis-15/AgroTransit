<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AgroTransit' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --forest: #41431B;
            --forest-light: #5B5E28;
            --leaf: #AEB784;
            --sprout: #E3DBBB;
            --cream: #F8F3E1;
            --ink: #262713;
            --surface: #ffffff;
            --bg-warm: #FAF8F0;
            --accent: #E07A5F;
            --border: rgba(65, 67, 27, 0.12);
            --shadow-sm: 0 4px 12px rgba(65, 67, 27, 0.04);
            --shadow-md: 0 10px 25px rgba(65, 67, 27, 0.08);
            --shadow-lg: 0 20px 45px rgba(65, 67, 27, 0.12);
        }
        
        body {
            background-color: var(--bg-warm);
            color: var(--ink);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
            font-size: 0.95rem;
            letter-spacing: -0.01em;
        }
        
        /* Premium Card Styles */
        .card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-leaf {
            background: linear-gradient(135deg, var(--forest) 0%, var(--forest-light) 100%);
            color: var(--cream);
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.4rem;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        
        .btn-leaf:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(65, 67, 27, 0.2);
            color: var(--cream);
            opacity: 0.95;
        }
        
        .btn-outline-template {
            border: 1px solid var(--forest);
            color: var(--forest);
            background: transparent;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        
        .btn-outline-template:hover {
            background-color: var(--forest);
            color: var(--cream);
        }
        .navbar-brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: var(--cream);
            border: 1px solid rgba(174, 183, 132, 0.65);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            box-shadow: 0 12px 24px rgba(65, 67, 27, 0.12);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <div class="navbar-brand-logo me-2">
                <img src="{{ asset('images/agrotransit-logo.png') }}" alt="AgroTransit logo" width="28" height="28" />
            </div>
            <span style="color: var(--forest);">Agro</span><span style="color: var(--leaf);">Transit</span>
        </a>
        <div class="navbar-nav ms-auto flex-row gap-3 align-items-center">
            @auth
                <span class="text-muted d-none d-md-inline small me-2"><i class="bi bi-circle-fill text-success" style="font-size: 8px;"></i> Connected: {{ auth()->user()->role }}</span>
                <form method="post" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <a class="nav-link btn btn-sm btn-outline-template px-3" href="{{ route('register') }}">Signup</a>
            @endauth
        </div>
    </div>
</nav>
@yield('content')

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
