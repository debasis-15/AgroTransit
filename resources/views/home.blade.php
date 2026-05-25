@extends('layouts.app', ['title' => 'AgroTransit'])

@section('content')
<section class="hero">
    <div class="container">
        <div class="col-lg-8">
            <span class="badge badge-accent mb-3">Laravel 11 Full Stack Platform</span>
            <h1 class="display-3 fw-bold">{{ __('messages.welcome') }}</h1>
            <p class="lead fs-3">{{ __('messages.tagline') }}</p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="{{ route('requests.create') }}" class="btn btn-leaf btn-lg">Create Booking</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-template btn-lg">View Analytics</a>
            </div>
        </div>
    </div>
</section>

<main class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-3"><div class="metric"><strong>AI Recommendation</strong><p class="mb-0 text-muted">Rules match crop, weight, distance, and cold-chain need.</p></div></div>
        <div class="col-md-3"><div class="metric"><strong>Pooling Cost Split</strong><p class="mb-0 text-muted">Weight-based sharing updates in real time.</p></div></div>
        <div class="col-md-3"><div class="metric"><strong>Live Tracking</strong><p class="mb-0 text-muted">ETA, route progress, and driver updates.</p></div></div>
        <div class="col-md-3"><div class="metric"><strong>QR Proof</strong><p class="mb-0 text-muted">Pickup and delivery verification with upload proof.</p></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="panel p-4 h-100">
                <h2 class="h4">Presentation Demo Flow</h2>
                <div class="map-strip my-3">
                    <span class="map-pin pin-a"></span><span class="map-pin pin-b"></span><span class="map-pin pin-c"></span>
                </div>
                <ol class="mb-0">
                    <li>Farmer posts produce and urgency.</li>
                    <li>Rule-based AI recommends the best vehicle.</li>
                    <li>Compatible requests are pooled and cost is split.</li>
                    <li>Driver accepts, shares live tracking, scans QR, and uploads delivery proof.</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel p-4 h-100">
                <h2 class="h4">Recent Requests</h2>
                @foreach($requests as $request)
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $request->crop_name }} - {{ $request->weight_kg }}kg</strong>
                            @if($request->priority === 'emergency')<span class="badge badge-emergency">Emergency</span>@endif
                        </div>
                        <div class="text-muted">{{ $request->pickup }} to {{ $request->destination }}</div>
                        <small>Recommended: {{ $request->vehicleType?->name ?? 'Pending' }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection
