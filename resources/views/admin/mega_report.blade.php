@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Mega / General Incident Report (Admin)</h2>
        <span class="text-muted small">Shows all device activities (incidents). Includes device + family info.</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white fw-semibold">Filters</div>
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between mb-3">

                <div class="text-muted small">Export the current filtered result</div>
                <div class="d-flex gap-2 flex-wrap">
                    @php
                        $qs = request()->query();
                        $qs = is_array($qs) ? $qs : [];
                        $csvUrl = route('admin.megaReports.exportCsv', $qs);
                        $pdfUrl = route('admin.megaReports.exportPdf', $qs);
                    @endphp
                    <a href="{{ $csvUrl }}" class="btn btn-success btn-sm fw-semibold">Export CSV</a>
                    <a href="{{ $pdfUrl }}" class="btn btn-outline-danger btn-sm fw-semibold" target="_blank">Export PDF</a>
                </div>
            </div>

            @php
                $incidentsCount = ($activities ?? collect())->count();
                $devicesInvolvedCount = ($activities ?? collect())->pluck('device_id')->unique()->count();
                $dateFrom = ($activities ?? collect())->min('created_at');
                $dateTo = ($activities ?? collect())->max('created_at');

                $eventCounts = ($activities ?? collect())
                    ->groupBy(fn($a) => $a->event_type ?: 'incident_recorded')
                    ->map(fn($g) => $g->count())
                    ->sortDesc()
                    ->take(4);
            @endphp

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(14,165,233,.12) 0%, rgba(99,102,241,.10) 100%);">
                        <div class="card-body">
                            <div class="small text-muted">Total incidents</div>
                            <div class="display-6 fw-bold" style="color:#2563eb;">{{ $incidentsCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(16,185,129,.12) 0%, rgba(34,211,238,.10) 100%);">
                        <div class="card-body">
                            <div class="small text-muted">Devices involved</div>
                            <div class="display-6 fw-bold" style="color:#0891b2;">{{ $devicesInvolvedCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(251,191,36,.12) 0%, rgba(217,119,6,.10) 100%);">
                        <div class="card-body">
                            <div class="small text-muted">Date range</div>
                            <div class="fw-bold" style="color:#b45309;">
                                @if($incidentsCount > 0)
                                    {{ optional($dateFrom)->format('Y-m-d') }} → {{ optional($dateTo)->format('Y-m-d') }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(99,102,241,.12) 0%, rgba(14,165,233,.10) 100%);">
                        <div class="card-body">
                            <div class="small text-muted">Top event types</div>
                            @if($eventCounts->count() > 0)
                                <div class="fw-bold">
                                    @foreach($eventCounts as $label => $c)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="small">{{ is_string($label) ? $label : 'event' }}</span>
                                            <span class="badge bg-primary">{{ $c }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="fw-bold">—</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.megaReports') }}" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Family</label>
                    <select name="family_id" class="form-select">
                        <option value="">All</option>
                        @foreach(($families ?? collect()) as $family)
                            <option value="{{ $family->id }}" {{ request('family_id') == $family->id ? 'selected' : '' }}>
                                {{ $family->family_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Device</label>
                    <input type="text" name="q" class="form-control" placeholder="Device name or token" value="{{ request('q') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>

                <div class="col-md-12 d-flex gap-2 justify-content-end">
                    <button class="btn btn-primary" type="submit">Apply</button>
                    <a href="{{ route('admin.megaReports') }}" class="btn btn-outline-secondary">Reset</a>
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
                                <td>
                                    {{ optional($activity->device->family)->family_name ?? 'Unassigned' }}
                                </td>
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

