@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Caregiver Incident Center</h2>
            <p class="text-muted mb-0">Filter alerts and review cradle activity from your assigned devices.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('caregiver.dashboard') }}" class="btn btn-outline-primary fw-semibold shadow-sm">Back to Dashboard</a>
        </div>
    </div>

    {{-- Summary cards (simple + fast; no backend aggregation needed) --}}
    @php
        $count = ($activities ?? collect())->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(14,165,233,.12) 0%, rgba(99,102,241,.10) 100%);">
                <div class="card-body">
                    <div class="small text-muted">Total incidents</div>
                    <div class="display-6 fw-bold" style="color:#2563eb;">{{ $count }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(16,185,129,.12) 0%, rgba(34,211,238,.10) 100%);">
                <div class="card-body">
                    <div class="small text-muted">Devices monitored</div>
                    <div class="display-6 fw-bold" style="color:#0891b2;">{{ ($activities ?? collect())->pluck('device_id')->unique()->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:16px; background: linear-gradient(135deg, rgba(251,191,36,.12) 0%, rgba(217,119,6,.10) 100%);">
                <div class="card-body">
                    <div class="small text-muted">Date range</div>
                    <div class="fw-bold" style="color:#b45309;">
                        @if($count > 0)
                            {{ ($activities->min('created_at'))?->format('Y-m-d') }} → {{ ($activities->max('created_at'))?->format('Y-m-d') }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters + quick chart placeholder --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color:white; font-weight:700;">Filters & Export</div>
        <div class="card-body">
            @php
                $qs = request()->query();
                $qs = is_array($qs) ? $qs : [];
                $csvUrl = route('caregiver.reports.exportCsv', $qs);
                $pdfUrl = route('caregiver.reports.exportPdf', $qs);
            @endphp

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="text-muted small">Export the current filtered result</div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ $csvUrl }}" class="btn btn-success btn-sm fw-semibold">Export CSV</a>
                    <a href="{{ $pdfUrl }}" class="btn btn-outline-danger btn-sm fw-semibold" target="_blank">Export PDF</a>
                </div>
            </div>

            <form method="GET" action="{{ route('caregiver.reports') }}" class="row g-3">

                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Device search</label>
                            <input type="text" name="q" class="form-control" placeholder="Device name or token" value="{{ request('q') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">From</label>
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">To</label>
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <button class="btn btn-primary fw-semibold" type="submit">Apply</button>
                            <a href="{{ route('caregiver.reports') }}" class="btn btn-outline-secondary fw-semibold">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #0ea5e9 0%, #22d3ee 100%); color:white; font-weight:700;">
                    Activity Graph (Preview)
                </div>
                <div class="card-body">
                    <div class="alert" style="background: rgba(14,165,233,.08); border: 1px solid rgba(14,165,233,.20); margin-bottom: 0;">
                        Chart rendering needs a JS chart library + aggregated incident data.
                        This dashboard currently shows a professional graph placeholder.
                    </div>
                    <div class="mt-3 p-3 rounded-3" style="background: linear-gradient(135deg, rgba(99,102,241,.08) 0%, rgba(16,185,129,.08) 100%); border: 1px solid rgba(0,0,0,.06);">
                        <div class="small text-muted mb-2">Top devices</div>
                        @php
                            $topDevices = ($activities ?? collect())->groupBy(fn($a)=>$a->device->device_name ?? 'Unknown')->take(4);
                        @endphp
                        @foreach($topDevices as $name => $items)
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-semibold">{{ $name }}</div>
                                <div class="badge bg-primary">{{ $items->count() }}</div>
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                @php
                                    $percent = $count > 0 ? round(($items->count() / max(1,$count))*100) : 0;
                                @endphp
                                <div class="progress-bar" style="width: {{ $percent }}%; background: linear-gradient(90deg, #4f46e5 0%, #06b6d4 100%);"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #111827 0%, #334155 100%); color:white; font-weight:700;">Incidents</div>
        <div class="card-body">
            @if(($activities ?? collect())->count() === 0)
                <div class="alert alert-warning mb-0" style="border-radius:14px;">No incident activities found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 760px;">
                        <thead>
                        <tr>
                            <th style="width: 190px;">Time</th>
                            <th>Device</th>
                            <th style="width: 180px;">Family</th>
                            <th style="width: 160px;">Event</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td class="text-muted">{{ $activity->created_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $activity->device->device_name ?? '—' }}</div>
                                    <div class="text-muted small font-monospace">{{ $activity->device->device_token ?? '' }}</div>
                                </td>
                                <td>{{ optional($activity->device->family)->family_name ?? 'Unassigned' }}</td>
                                <td>
                                    @if(!empty($activity->event_type))
                                        <span class="badge" style="background: rgba(79,70,229,.12); color:#4338ca; border: 1px solid rgba(79,70,229,.20);">{{ $activity->event_type }}</span>
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


