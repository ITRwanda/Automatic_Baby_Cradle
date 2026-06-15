@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Member Reports (Incidents)</h2>
        <span class="text-muted small">Mega / general incident report restricted to your assigned devices.</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white fw-semibold">Filters</div>
        <div class="card-body">
            <form method="GET" action="{{ route('member.reports') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Device search</label>
                    <input type="text" name="q" class="form-control" placeholder="Device name or token" value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>

                <div class="col-md-12 d-flex gap-2 justify-content-end">
                    <button class="btn btn-primary" type="submit">Apply</button>
                    <a href="{{ route('member.reports') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-semibold">Incidents</div>
        <div class="card-body">
            @if(($activities ?? collect())->count() === 0)
                <div class="alert alert-warning mb-0">No incident activities found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>Time</th>
                            <th>Device</th>
                            <th>Family</th>
                            <th>Event</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td class="text-muted">{{ $activity->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="fw-semibold">{{ $activity->device->device_name ?? '—' }}</td>
                                <td>{{ optional($activity->device->family)->family_name ?? 'Unassigned' }}</td>
                                <td>
                                    @if(!empty($activity->event_type))
                                        <span class="badge bg-primary">{{ $activity->event_type }}</span>
                                    @else
                                        <span class="text-muted">Incident recorded</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

