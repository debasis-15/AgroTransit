@extends('layouts.app')

@section('content')
<div style="background-color:#F8F3E1; min-height:100vh; padding:2rem 0;">
    <div class="container">
        <nav class="mb-3">
            <a href="{{ route('farmer.dashboard') }}" class="btn btn-sm btn-outline-secondary">← Back</a>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color:#41431B;">🔔 Notifications</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="markAllRead()">Mark all read</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="clearAll()">Clear</button>
                        </div>
                    </div>
                    <div class="list-group list-group-flush" id="notificationsList">
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Driver en route</strong>
                                <div class="small text-muted">Ravi Kumar is 30 mins away from pickup point.</div>
                            </div>
                            <div class="text-end small text-muted">10m</div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-start bg-light">
                            <div>
                                <strong>Pooling match found</strong>
                                <div class="small text-muted">Save ₹420 by joining shared trip to Azadpur.</div>
                            </div>
                            <div class="text-end small text-muted">1h</div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Payment received</strong>
                                <div class="small text-muted">₹1,800 received for TRN042.</div>
                            </div>
                            <div class="text-end small text-muted">2d</div>
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="small text-muted">Notification settings are controlled from your profile.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAllRead() {
    const list = document.getElementById('notificationsList');
    [...list.children].forEach(item => item.classList.remove('bg-light'));
}
function clearAll() {
    document.getElementById('notificationsList').innerHTML = '<div class="p-4 text-center text-muted">No notifications</div>';
}
</script>

<style>
.card { transition: all .2s ease }
.card:hover { transform: translateY(-3px) }
</style>
@endsection
