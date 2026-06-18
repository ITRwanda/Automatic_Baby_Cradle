@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Caregiver Dashboard</h2>
            <p class="text-muted mb-0">Your assigned devices, at a glance.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('caregiver.reports') }}" class="btn btn-primary fw-semibold shadow-sm">
                Incident Reports
            </a>
            <a href="{{ route('caregiver.notifications') }}" class="btn btn-outline-info fw-semibold shadow-sm">
                Notifications
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-header border-0 text-white" style="border-radius:16px 16px 0 0; background: linear-gradient(135deg, #0ea5e9 0%, #22d3ee 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold">Assigned Devices</span>
                        <span class="badge bg-white text-dark">{{ $devices->count() }} device(s)</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($devices->count() === 0)
                        <div class="alert alert-warning mb-0">
                            No devices assigned yet.
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($devices as $device)
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: linear-gradient(90deg, rgba(16,185,129,.12) 0%, rgba(34,211,238,.10) 100%); border: 1px solid rgba(0,0,0,.06);">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                            <div>
                                                <div class="fw-bold fs-5" style="color:#065f46;">{{ $device->device_name }}</div>
                                                <div class="text-muted small">Token: <span class="font-monospace">{{ $device->device_token }}</span></div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <span class="badge" style="background: rgba(99,102,241,.12); color:#4338ca;">Assigned</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-header border-0 text-white" style="border-radius:16px 16px 0 0; background: linear-gradient(135deg, #198754 0%, #34d399 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold">Notifications</span>
                        <span class="badge bg-white text-dark">{{ is_array($notifications) ? count($notifications) : ($notifications->count() ?? 0) }} item(s)</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(empty($notifications) || (is_object($notifications) && $notifications->count() === 0) || (is_array($notifications) && count($notifications) === 0))
                        <div class="alert alert-light border" style="background: #f0fff7; border-color: rgba(16,185,129,.2);">
                            No notifications yet.
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($notifications as $note)
                                <div class="p-3 rounded-3" style="background: #ffffff; border: 1px solid rgba(0,0,0,.06);">
                                    <div class="small text-muted">Update</div>
                                    <div class="fw-semibold">{{ $note->message ?? $note }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(14,165,233,.10) 0%, rgba(99,102,241,.10) 100%);">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <div class="fw-bold fs-5">Quick action</div>
                            <div class="text-muted">Review recent incidents and filter by device or date.</div>
                        </div>
                        <a class="btn btn-dark fw-semibold shadow-sm" href="{{ route('caregiver.reports') }}">
                            Open Incident Center
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .font-monospace{font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;}
</style>
@endsection

