@extends('layouts.app')

@section('content')
@php
    $activities  = $activities ?? collect();
    $total       = $activities->count();
    $cryCount    = $activities->where('event_type', 'cry_detected')->count();
    $dhtCount    = $activities->where('event_type', 'dht')->count();
    $otherCount  = $total - $cryCount - $dhtCount;
    $devicesHit  = $activities->pluck('device_id')->unique()->count();
    $dateMin     = $total ? $activities->min('created_at')?->format('d M Y') : null;
    $dateMax     = $total ? $activities->max('created_at')?->format('d M Y') : null;

    $topDevices  = $activities
        ->groupBy(fn($a) => $a->device->device_name ?? 'Unknown')
        ->map(fn($g) => $g->count())
        ->sortDesc()
        ->take(5);

    $qs     = is_array(request()->query()) ? request()->query() : [];
    $csvUrl = route('family.reports.exportCsv', $qs);
    $pdfUrl = route('family.reports.exportPdf', $qs);
@endphp

<style>
    /* ── Page shell ──────────────────────────────────────── */
    .rpt-page { max-width: 1380px; margin: 0 auto; padding: 0 4px; }

    /* ── Page header ─────────────────────────────────────── */
    .rpt-page-header {
        display: flex; flex-wrap: wrap; gap: 12px;
        align-items: flex-end; justify-content: space-between;
        margin-bottom: 28px;
    }
    .rpt-page-title { font-size: 1.55rem; font-weight: 800; color: #0f172a; margin: 0; line-height: 1.2; }
    .rpt-page-sub   { font-size: .875rem; color: #64748b; margin: 4px 0 0; }

    /* ── KPI cards ───────────────────────────────────────── */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px,1fr)); gap: 16px; margin-bottom: 28px; }
    .kpi-card {
        border-radius: 14px; padding: 18px 20px;
        display: flex; flex-direction: column; gap: 4px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .kpi-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; opacity: .65; }
    .kpi-value { font-size: 2rem; font-weight: 800; line-height: 1; }
    .kpi-hint  { font-size: .75rem; opacity: .6; margin-top: 2px; }

    .kpi-blue   { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #1d4ed8; }
    .kpi-red    { background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #be123c; }
    .kpi-amber  { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #b45309; }
    .kpi-green  { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #15803d; }
    .kpi-slate  { background: linear-gradient(135deg, #f8fafc, #f1f5f9); color: #334155; }

    /* ── Main content columns ─────────────────────────────── */
    .rpt-content-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 20px;
        margin-bottom: 20px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .rpt-content-grid { grid-template-columns: 1fr; }
    }

    /* ── Panel card ──────────────────────────────────────── */
    .rpt-panel {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 14px rgba(0,0,0,.07);
        overflow: hidden;
    }
    .rpt-panel-header {
        padding: 14px 20px;
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        display: flex; align-items: center; gap: 8px;
    }
    .rpt-panel-body { padding: 20px; }

    /* ── Filter form ─────────────────────────────────────── */
    .filter-field label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #475569; margin-bottom: 5px; display: block; }
    .filter-field input, .filter-field select {
        border: 1.5px solid #e2e8f0; border-radius: 8px;
        padding: 7px 11px; font-size: .86rem; width: 100%;
        transition: border-color .15s;
    }
    .filter-field input:focus, .filter-field select:focus { border-color: #006633; outline: none; box-shadow: 0 0 0 3px rgba(0,102,51,.1); }

    .btn-filter-apply {
        background: linear-gradient(135deg, #006633, #009944);
        color: #fff; border: none; border-radius: 8px;
        padding: 8px 20px; font-size: .86rem; font-weight: 700;
        cursor: pointer; transition: opacity .15s;
    }
    .btn-filter-apply:hover { opacity: .88; }
    .btn-filter-reset {
        background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0;
        border-radius: 8px; padding: 8px 16px; font-size: .86rem; font-weight: 600;
        text-decoration: none; display: inline-block; transition: background .15s;
    }
    .btn-filter-reset:hover { background: #e2e8f0; }

    /* ── Export bar ──────────────────────────────────────── */
    .export-row { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
    .btn-exp {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 8px;
        font-size: .8rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer;
    }
    .btn-exp-csv { background: #16a34a; color: #fff; }
    .btn-exp-csv:hover { background: #15803d; color: #fff; }
    .btn-exp-pdf { background: #dc2626; color: #fff; }
    .btn-exp-pdf:hover { background: #b91c1c; color: #fff; }

    /* ── Breakdown bars ──────────────────────────────────── */
    .breakdown-row { display: flex; flex-direction: column; gap: 10px; }
    .breakdown-item-label { display: flex; justify-content: space-between; font-size: .82rem; font-weight: 600; margin-bottom: 3px; }
    .breakdown-bar-track { height: 7px; border-radius: 99px; background: #f1f5f9; overflow: hidden; }
    .breakdown-bar-fill  { height: 100%; border-radius: 99px; }

    /* ── Table ───────────────────────────────────────────── */
    .inc-table { width: 100%; border-collapse: collapse; }
    .inc-table thead tr {
        background: #f8fafc;
        border-bottom: 2px solid #e8eaf0;
    }
    .inc-table thead th {
        padding: 10px 14px;
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: #64748b; white-space: nowrap;
    }
    .inc-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .1s; }
    .inc-table tbody tr:hover { background: #f8fafc; }
    .inc-table tbody td { padding: 11px 14px; font-size: .875rem; vertical-align: middle; }

    /* ── Event badges ────────────────────────────────────── */
    .evt-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: .74rem; font-weight: 700; white-space: nowrap;
    }
    .evt-cry    { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
    .evt-dht    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .evt-other  { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

    /* ── Sensor pill row ─────────────────────────────────── */
    .sensor-row { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
    .sensor-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 9px; border-radius: 20px;
        font-size: .74rem; font-weight: 600;
    }
    .s-normal { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .s-warn   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .s-crit   { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }

    /* ── Empty state ─────────────────────────────────────── */
    .empty-state { text-align: center; padding: 48px 24px; color: #94a3b8; }
    .empty-state svg { margin-bottom: 12px; opacity: .35; }
    .empty-state p { margin: 0; font-size: .925rem; }

    /* ── Divider ─────────────────────────────────────────── */
    .rpt-divider { border: none; border-top: 1px solid #f1f5f9; margin: 14px 0; }
</style>

<div class="rpt-page">

    {{-- ── Page header ── --}}
    <div class="rpt-page-header">
        <div>
            <h1 class="rpt-page-title">
                <span style="color:#006633;">&#9679;</span>
                Family Incident Report
            </h1>
            <p class="rpt-page-sub">All sensor events from your family's cradle devices</p>
        </div>
        <div class="export-row" style="margin-bottom:0;">
            <a href="{{ $csvUrl }}" class="btn-exp btn-exp-csv">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
            <a href="{{ $pdfUrl }}" class="btn-exp btn-exp-pdf" target="_blank">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- ── KPI row ── --}}
    <div class="kpi-grid">
        <div class="kpi-card kpi-blue">
            <div class="kpi-label">Total Events</div>
            <div class="kpi-value">{{ $total }}</div>
            <div class="kpi-hint">All time (filtered)</div>
        </div>
        <div class="kpi-card kpi-red">
            <div class="kpi-label">Cry Detected</div>
            <div class="kpi-value">{{ $cryCount }}</div>
            <div class="kpi-hint">Sound alerts</div>
        </div>
        <div class="kpi-card kpi-amber">
            <div class="kpi-label">DHT Events</div>
            <div class="kpi-value">{{ $dhtCount }}</div>
            <div class="kpi-hint">Temp / humidity</div>
        </div>
        <div class="kpi-card kpi-green">
            <div class="kpi-label">Devices</div>
            <div class="kpi-value">{{ $devicesHit }}</div>
            <div class="kpi-hint">Involved in period</div>
        </div>
        <div class="kpi-card kpi-slate">
            <div class="kpi-label">Period</div>
            <div class="kpi-value" style="font-size:1rem; line-height:1.4;">
                @if($dateMin)
                    {{ $dateMin }}<br><span style="font-size:.8rem; opacity:.6;">to {{ $dateMax }}</span>
                @else
                    —
                @endif
            </div>
        </div>
    </div>

    {{-- ── Sidebar + Table grid ── --}}
    <div class="rpt-content-grid">

        {{-- Left: filters + breakdown ──────────────────────────── --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- Filters panel --}}
            <div class="rpt-panel">
                <div class="rpt-panel-header" style="background: linear-gradient(135deg,#006633,#009944); color:#fff;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Filters
                </div>
                <div class="rpt-panel-body">
                    <form method="GET" action="{{ route('family.reports') }}" style="display:flex; flex-direction:column; gap:14px;">
                        <div class="filter-field">
                            <label>Device search</label>
                            <input type="text" name="q" placeholder="Name or token…" value="{{ request('q') }}">
                        </div>
                        <div class="filter-field">
                            <label>From date</label>
                            <input type="date" name="from" value="{{ request('from') }}">
                        </div>
                        <div class="filter-field">
                            <label>To date</label>
                            <input type="date" name="to" value="{{ request('to') }}">
                        </div>
                        <div style="display:flex; gap:8px; margin-top:4px;">
                            <button type="submit" class="btn-filter-apply">Apply</button>
                            <a href="{{ route('family.reports') }}" class="btn-filter-reset">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Device breakdown panel --}}
            @if($total > 0)
            <div class="rpt-panel">
                <div class="rpt-panel-header" style="background: linear-gradient(135deg,#0f172a,#1e293b); color:#fff;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Device Breakdown
                </div>
                <div class="rpt-panel-body">
                    <div class="breakdown-row">
                        @foreach($topDevices as $name => $count)
                        @php $pct = $total > 0 ? round(($count/$total)*100) : 0; @endphp
                        <div>
                            <div class="breakdown-item-label">
                                <span>{{ $name }}</span>
                                <span style="color:#64748b;">{{ $count }}</span>
                            </div>
                            <div class="breakdown-bar-track">
                                <div class="breakdown-bar-fill"
                                     style="width:{{ $pct }}%; background: linear-gradient(90deg,#006633,#22c55e);">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <hr class="rpt-divider" style="margin-top:18px;">

                    {{-- Event type mini breakdown --}}
                    <div style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; margin-bottom:10px;">Event Types</div>
                    @foreach($activities->groupBy(fn($a)=>$a->event_type??'unknown')->map(fn($g)=>$g->count())->sortDesc()->take(5) as $evtLabel => $evtCount)
                    @php
                        $evtPct = $total > 0 ? round(($evtCount/$total)*100) : 0;
                        $evtColor = $evtLabel==='cry_detected'
                            ? 'linear-gradient(90deg,#be123c,#f43f5e)'
                            : ($evtLabel==='dht'
                                ? 'linear-gradient(90deg,#1d4ed8,#60a5fa)'
                                : 'linear-gradient(90deg,#475569,#94a3b8)');
                    @endphp
                    <div style="margin-bottom:8px;">
                        <div class="breakdown-item-label">
                            <span>{{ $evtLabel }}</span>
                            <span style="color:#64748b;">{{ $evtCount }}</span>
                        </div>
                        <div class="breakdown-bar-track">
                            <div class="breakdown-bar-fill" style="width:{{ $evtPct }}%; background:{{ $evtColor }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>{{-- /left --}}

        {{-- Right: incident table ────────────────────────────── --}}
        <div class="rpt-panel" style="min-width:0;">
            <div class="rpt-panel-header" style="background: linear-gradient(135deg,#0f172a,#1e293b); color:#fff; justify-content:space-between;">
                <span style="display:flex;align-items:center;gap:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Incident Timeline
                </span>
                <span style="font-size:.75rem; opacity:.6; font-weight:400; text-transform:none; letter-spacing:0;">
                    {{ $total }} record{{ $total !== 1 ? 's' : '' }}
                </span>
            </div>
            <div class="rpt-panel-body" style="padding:0;">
                @if($total === 0)
                    <div class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <p>No incidents found for the selected filters.</p>
                    </div>
                @else
                <div style="overflow-x:auto;">
                    <table class="inc-table">
                        <thead>
                            <tr>
                                <th style="width:155px;">Time</th>
                                <th style="width:190px;">Device</th>
                                <th style="width:140px;">Event</th>
                                <th>Sensor Readings</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $activity)
                            @php
                                $evt      = $activity->event_type ?? '';
                                $payload  = $activity->payload;
                                $decoded  = null;
                                if (is_string($payload)) {
                                    $t = trim($payload);
                                    if (str_starts_with($t, '{')) $decoded = json_decode($t, true);
                                }
                                $temp       = $decoded['temperature'] ?? null;
                                $hum        = $decoded['humidity']    ?? null;
                                $sound      = $decoded['sound_level'] ?? null;
                                $tempAlert  = !empty($decoded['temp_alert']);
                                $humAlert   = !empty($decoded['humid_alert']);
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:600; color:#0f172a;">
                                        {{ $activity->created_at?->format('d M Y') }}
                                    </div>
                                    <div style="font-size:.75rem; color:#94a3b8;">
                                        {{ $activity->created_at?->format('H:i:s') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:600; color:#0f172a;">
                                        {{ $activity->device->device_name ?? '—' }}
                                    </div>
                                    <div style="font-size:.72rem; color:#94a3b8; font-family:monospace;">
                                        {{ Str::limit($activity->device->device_token ?? '', 18) }}
                                    </div>
                                </td>
                                <td>
                                    @if($evt === 'cry_detected')
                                        <span class="evt-badge evt-cry">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                                            Cry Detected
                                        </span>
                                    @elseif($evt === 'dht')
                                        <span class="evt-badge evt-dht">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                            DHT Sensor
                                        </span>
                                    @else
                                        <span class="evt-badge evt-other">
                                            {{ $evt ?: 'Event' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($decoded)
                                        <div class="sensor-row">
                                            @if($temp !== null)
                                                <span class="sensor-pill {{ $tempAlert ? 's-crit' : 's-normal' }}">
                                                    🌡 {{ $temp }}°C
                                                    @if($tempAlert) ⚠ @endif
                                                </span>
                                            @endif
                                            @if($hum !== null)
                                                <span class="sensor-pill {{ $humAlert ? 's-warn' : 's-normal' }}">
                                                    💧 {{ $hum }}%
                                                    @if($humAlert) ⚠ @endif
                                                </span>
                                            @endif
                                            @if($sound !== null)
                                                <span class="sensor-pill s-crit">
                                                    🔊 {{ $sound }}
                                                </span>
                                            @endif
                                            @if(!$tempAlert && !$humAlert && $sound === null && ($temp !== null || $hum !== null))
                                                <span class="sensor-pill s-normal">✓ Normal</span>
                                            @endif
                                        </div>
                                    @elseif($payload)
                                        <span style="font-size:.8rem; color:#64748b;">{{ Str::limit($payload, 80) }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>{{-- /right panel --}}

    </div>{{-- /grid --}}

</div>{{-- /rpt-page --}}
@endsection
