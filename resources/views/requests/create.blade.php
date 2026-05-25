@extends('layouts.app', ['title' => 'Book Transport'])

@section('content')
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="panel p-4">
                <h1 class="h3 mb-4">Create Transport Request</h1>
                <form method="post" action="{{ route('requests.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6"><label class="form-label">Crop Name</label><input class="form-control" name="crop_name" value="Tomato" required></div>
                    <div class="col-md-6"><label class="form-label">Weight in kg</label><input class="form-control" name="weight_kg" type="number" value="600" required></div>
                    <div class="col-md-6"><label class="form-label">Pickup</label><input class="form-control" name="pickup" value="Ludhiana Farm Gate" required></div>
                    <div class="col-md-6"><label class="form-label">Destination</label><input class="form-control" name="destination" value="Azadpur Mandi" required></div>
                    <div class="col-md-6"><label class="form-label">Distance in km</label><input class="form-control" name="distance_km" type="number" value="310" required></div>
                    <div class="col-md-6"><label class="form-label">Priority</label><select class="form-select" name="priority"><option value="normal">Normal</option><option value="emergency">Emergency</option></select></div>
                    <div class="col-12 form-check ms-2"><input class="form-check-input" type="checkbox" name="temperature_sensitive" value="1" checked id="cold"><label class="form-check-label" for="cold">Temperature sensitive produce</label></div>
                    <div class="col-12"><button class="btn btn-leaf">Get Recommendation and Book</button></div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
