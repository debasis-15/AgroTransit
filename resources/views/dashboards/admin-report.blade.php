@extends('layouts.app', ['title' => 'Platform Report - AgroTransit'])

@section('content')
<div class="admin-dashboard-shell py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:var(--forest);">Platform Report</h2>
                <p class="text-muted mb-0">Live registry export for users, owners, vehicles, bookings, and driver requests.</p>
            </div>
            <a class="btn btn-leaf" href="{{ route('admin.dashboard') }}"><i class="bi bi-arrow-left me-1"></i> Back to Admin</a>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Users</p>
                        <h3 class="fw-bold mb-0 text-success">{{ $users->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Owners</p>
                        <h3 class="fw-bold mb-0 text-success">{{ $owners->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Vehicles</p>
                        <h3 class="fw-bold mb-0 text-success">{{ $vehicles->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Bookings</p>
                        <h3 class="fw-bold mb-0 text-success">{{ $requests->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill text-success me-2"></i> Users</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></td>
                                <td><span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-truck text-success me-2"></i> Vehicles</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Registration</th>
                            <th>Type</th>
                            <th>Owner</th>
                            <th>Driver</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicles as $vehicle)
                            <tr>
                                <td class="fw-bold">{{ $vehicle->registration_number }}</td>
                                <td>{{ $vehicle->type?->name ?? 'N/A' }}</td>
                                <td>{{ $vehicle->owner?->user?->name ?? 'N/A' }}</td>
                                <td>{{ $vehicle->driver?->user?->name ?? 'Unassigned' }}</td>
                                <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $vehicle->tracking_status)) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-card-checklist text-success me-2"></i> Bookings & Driver Requests</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Type</th>
                            <th>Party</th>
                            <th>Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>Booking</td>
                                <td>{{ $request->farmer?->user?->name ?? 'N/A' }}</td>
                                <td>{{ $request->pickup }} → {{ $request->destination }}</td>
                                <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span></td>
                            </tr>
                        @endforeach
                        @foreach($assignmentRequests as $assignmentRequest)
                            <tr>
                                <td>Driver Request</td>
                                <td>{{ $assignmentRequest->driver?->user?->name ?? 'N/A' }}</td>
                                <td>{{ $assignmentRequest->vehicle?->registration_number ?? 'N/A' }} · {{ $assignmentRequest->vehicle?->owner?->user?->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($assignmentRequest->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.admin-dashboard-shell {
    background-color: var(--bg-warm);
    min-height: calc(100vh - 56px);
}
</style>
@endsection
