@extends('layouts.app')

@section('content')
@php
    $activities = $activities ?? collect();
    $families   = $families   ?? collect();
    $total      = $activities->count();
    $cryCount   = $activities->where('event_type','cry_detected')->count();
    $dhtCount   = $activities->where('event_type','dht')->count();
    $otherCount = $total - $cryCount - $dhtCount;
    $devsHit    = $activities->pluck('device_id')->unique()->count();
    $famsHit    = $activities->map(fn($a)=>optional($a->device->family)->id)->filter()->unique()->count();
    $dateMin    = $total ? $activities->min('created_at')?->format('d M Y') : null;
    $dateMax    = $total ? $activities->max('created_at')?->format('d M Y') : null;

    $topDevices = $activities
        ->groupBy(fn($a) => $a->device->device_name ?? 'Unknown')
        ->map(fn($g) => $g->count())
        ->sortDesc()->take(5);

    $topFamilies = $activities
        ->groupBy(fn($a) => optional($a->device->family)->family_name ?? 'Unassigned')
        ->map(fn($g) => $g->count())
        ->sortDesc()->take(5);

    $qs     = is_array(request()->query()) ? request()->query() : [];
    $csvUrl = route('admin.megaReports.exportCsv', $qs);
    $pdfUrl = route('admin.megaReports.exportPdf', $qs);
@endphp

<style>
.adm-page { max-width:1440px; margin:0 auto; }
.adm-hdr  { display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:26px; }
.adm-title{ font-size:1.5rem;font-weight:800;color:#0f172a;margin:0;line-height:1.2; }
.adm-sub  { font-size:.875rem;color:#64748b;margin:4px 0 0; }

/* KPI */
.adm-kpi { display:grid;grid-template-columns:repeat(auto-fit,minmax(155px,1fr));gap:14px;margin-bottom:26px; }
.adm-kpi-card { border-radius:14px;padding:16px 18px;box-shadow:0 2px 10px rgba(0,0,0,.06);
                display:flex;flex-direction:column;gap:4px; }
.adm-kpi-lbl  { font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;opacity:.6; }
.adm-kpi-val  { font-size:1.9rem;font-weight:800;line-height:1; }
.adm-kpi-hint { font-size:.72rem;opacity:.55; }
.ak-blue  { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8; }
.ak-rose  { background:linear-gradient(135deg,#fff1f2,#ffe4e6); color:#be123c; }
.ak-sky   { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); color:#0369a1; }
.ak-green { background:linear-gradient(135deg,#f0fdf4,#dcfce7); color:#15803d; }
.ak-amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#b45309; }
.ak-slate { background:linear-gradient(135deg,#f8fafc,#f1f5f9); color:#334155; }

/* layout */
.adm-grid { display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start; }
@media(max-width:900px){ .adm-grid{ grid-template-columns:1fr; } }

/* panel */
.adm-panel { background:#fff;border-radius:16px;box-shadow:0 2px 14px rgba(0,0,0,.07);overflow:hidden; }
.adm-panel-hdr { padding:13px 20px;font-size:.78rem;font-weight:700;text-transform:uppercase;
                 letter-spacing:.08em;display:flex;align-items:center;justify-content:space-between;gap:8px; }
.adm-panel-body{ padding:20px; }

/* filter form */
.ff-lbl { font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;
          color:#475569;margin-bottom:5px;display:block; }
.ff-inp { border:1.5px solid #e2e8f0;border-radius:8px;padding:7px 11px;
          font-size:.86rem;width:100%;transition:border-color .15s; }
.ff-inp:focus { border-color:#1d4ed8;outline:none;box-shadow:0 0 0 3px rgba(29,78,216,.1); }
.ff-sel { border:1.5px solid #e2e8f0;border-radius:8px;padding:7px 11px;
          font-size:.86rem;width:100%;background:#fff; }
.btn-apply  { background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff;border:none;
              border-radius:8px;padding:8px 20px;font-size:.86rem;font-weight:700;cursor:pointer; }
.btn-reset  { background:#f1f5f9;color:#475569;border:1.5px solid #e2e8f0;border-radius:8px;
              padding:8px 14px;font-size:.86rem;font-weight:600;text-decoration:none;display:inline-block; }

/* export buttons */
.exp-row { display:flex;gap:8px;flex-wrap:wrap; }
.btn-exp  { display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;
            font-size:.8rem;font-weight:700;text-decoration:none;border:none;cursor:pointer; }
.btn-csv  { background:#16a34a;color:#fff; }
.btn-csv:hover{ background:#15803d;color:#fff; }
.btn-pdf  { background:#dc2626;color:#fff; }
.btn-pdf:hover{ background:#b91c1c;color:#fff; }

/* breakdown bars */
.bkd-row  { display:flex;flex-direction:column;gap:9px; }
.bkd-lbl  { display:flex;justify-content:space-between;font-size:.82rem;font-weight:600;margin-bottom:3px; }
.bkd-trk  { height:7px;border-radius:99px;background:#f1f5f9;overflow:hidden; }
.bkd-fill { height:100%;border-radius:99px; }

/* table */
.adm-tbl { width:100%;border-collapse:collapse; }
.adm-tbl thead tr { background:#f8fafc;border-bottom:2px solid #e2e8f0; }
.adm-tbl thead th { padding:10px 13px;font-size:.72rem;font-weight:700;text-transform:uppercase;
                    letter-spacing:.07em;color:#64748b;white-space:nowrap; }
.adm-tbl tbody tr { border-bottom:1px solid #f1f5f9;transition:background .1s; }
.adm-tbl tbody tr:hover { background:#f8fafc; }
.adm-tbl tbody td { padding:11px 13px;font-size:.875rem;vertical-align:middle; }

/* event badges */
.ev-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;
            font-size:.74rem;font-weight:700;white-space:nowrap; }
.ev-cry   { background:#fff1f2;color:#be123c;border:1px solid #fecdd3; }
.ev-dht   { background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe; }
.ev-other { background:#f8fafc;color:#475569;border:1px solid #e2e8f0; }

/* sensor pills */
.sp-row { display:flex;flex-wrap:wrap;gap:5px; }
.sp-pill{ display:inline-flex;align-items:center;gap:4px;padding:2px 8px;
          border-radius:20px;font-size:.74rem;font-weight:600; }
.sp-ok   { background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0; }
.sp-warn { background:#fff7ed;color:#c2410c;border:1px solid #fed7aa; }
.sp-crit { background:#fff1f2;color:#be123c;border:1px solid #fecdd3; }

.adm-divider { border:none;border-top:1px solid #f1f5f9;margin:14px 0; }
.adm-empty { text-align:center;padding:48px 24px;color:#94a3b8; }
</style>

<div class="adm-page">

    {{-- ── Page header ── --}}
    <div class="adm-hdr">
        <div>
            <h1 class="adm-title">
                <span style="color:#dc2626;">&#9679;</span> Mega Incident Report
            </h1>
            <p class="adm-sub">All sensor events across every family and device — admin view</p>
        </div>
        <div class="exp-row">
            <a href="{{ $csvUrl }}" class="btn-exp btn-csv">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
            <a href="{{ $pdfUrl }}" class="btn-exp btn-pdf" target="_blank">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- ── KPI strip ── --}}
    <div class="adm-kpi">
        <div class="adm-kpi-card ak-blue">
            <div class="adm-kpi-lbl">Total Events</div>
            <div class="adm-kpi-val">{{ $total }}</div>
            <div class="adm-kpi-hint">All devices, all families</div>
        </div>
        <div class="adm-kpi-card ak-rose">
            <div class="adm-kpi-lbl">Cry Detected</div>
            <div class="adm-kpi-val">{{ $cryCount }}</div>
            <div class="adm-kpi-hint">Sound alerts</div>
        </div>
        <div class="adm-kpi-card ak-sky">
            <div class="adm-kpi-lbl">DHT Events</div>
            <div class="adm-kpi-val">{{ $dhtCount }}</div>
            <div class="adm-kpi-hint">Temp / humidity</div>
        </div>
        <div class="adm-kpi-card ak-green">
            <div class="adm-kpi-lbl">Devices</div>
            <div class="adm-kpi-val">{{ $devsHit }}</div>
            <div class="adm-kpi-hint">With activity</div>
        </div>
        <div class="adm-kpi-card ak-amber">
            <div class="adm-kpi-lbl">Families</div>
            <div class="adm-kpi-val">{{ $famsHit }}</div>
            <div class="adm-kpi-hint">With activity</div>
        </div>
        <div class="adm-kpi-card ak-slate">
            <div class="adm-kpi-lbl">Period</div>
            <div class="adm-kpi-val" style="font-size:1rem;line-height:1.4;">
                @if($dateMin) {{ $dateMin }}<br><span style="font-size:.8rem;opacity:.6;">to {{ $dateMax }}</span>
                @else —
                @endif
            </div>
        </div>
    </div>

    {{-- ── Sidebar + table ── --}}
    <div class="adm-grid">

        {{-- ── LEFT: filters + breakdown ── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            <div class="adm-panel">
                <div class="adm-panel-hdr" style="background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;">
                    <span>🔍 Filters</span>
                </div>
                <div class="adm-panel-body">
                    <form method="GET" action="{{ route('admin.megaReports') }}"
                          style="display:flex;flex-direction:column;gap:13px;">
                        <div>
                            <label class="ff-lbl">Family</label>
                            <select name="family_id" class="ff-sel">
                                <option value="">All families</option>
                                @foreach($families as $fam)
                                    <option value="{{ $fam->id }}" {{ request('family_id')==$fam->id?'selected':'' }}>
                                        {{ $fam->family_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="ff-lbl">Device search</label>
                            <input class="ff-inp" type="text" name="q"
                                   placeholder="Name or token…" value="{{ request('q') }}">
                        </div>
                        <div>
                            <label class="ff-lbl">From date</label>
                            <input class="ff-inp" type="date" name="from" value="{{ request('from') }}">
                        </div>
                        <div>
                            <label class="ff-lbl">To date</label>
                            <input class="ff-inp" type="date" name="to" value="{{ request('to') }}">
                        </div>
                        <div style="display:flex;gap:8px;margin-top:4px;">
                            <button type="submit" class="btn-apply">Apply</button>
                            <a href="{{ route('admin.megaReports') }}" class="btn-reset">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            @if($total > 0)
            <div class="adm-panel">
                <div class="adm-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
                    <span>📊 Breakdown</span>
                </div>
                <div class="adm-panel-body">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:9px;">By Device</div>
                    <div class="bkd-row" style="margin-bottom:16px;">
                        @foreach($topDevices as $nm => $cnt)
                        @php $pct = $total>0?round(($cnt/$total)*100):0; @endphp
                        <div>
                            <div class="bkd-lbl"><span>{{ $nm }}</span><span style="color:#64748b;">{{ $cnt }}</span></div>
                            <div class="bkd-trk"><div class="bkd-fill" style="width:{{ $pct }}%;background:linear-gradient(90deg,#1d4ed8,#60a5fa);"></div></div>
                        </div>
                        @endforeach
                    </div>
                    <hr class="adm-divider">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:9px;">By Family</div>
                    <div class="bkd-row" style="margin-bottom:16px;">
                        @foreach($topFamilies as $fn => $fc)
                        @php $fp = $total>0?round(($fc/$total)*100):0; @endphp
                        <div>
                            <div class="bkd-lbl"><span>{{ $fn }}</span><span style="color:#64748b;">{{ $fc }}</span></div>
                            <div class="bkd-trk"><div class="bkd-fill" style="width:{{ $fp }}%;background:linear-gradient(90deg,#006633,#22c55e);"></div></div>
                        </div>
                        @endforeach
                    </div>
                    <hr class="adm-divider">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:9px;">Event Types</div>
                    <div class="bkd-row">
                        @foreach($activities->groupBy(fn($a)=>$a->event_type??'unknown')->map(fn($g)=>$g->count())->sortDesc()->take(5) as $el=>$ec)
                        @php
                            $ep=$total>0?round(($ec/$total)*100):0;
                            $ec_color=$el==='cry_detected'?'linear-gradient(90deg,#be123c,#f43f5e)':($el==='dht'?'linear-gradient(90deg,#1d4ed8,#60a5fa)':'linear-gradient(90deg,#475569,#94a3b8)');
                        @endphp
                        <div>
                            <div class="bkd-lbl"><span>{{ $el }}</span><span style="color:#64748b;">{{ $ec }}</span></div>
                            <div class="bkd-trk"><div class="bkd-fill" style="width:{{ $ep }}%;background:{{ $ec_color }};"></div></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- /left --}}

        {{-- ── RIGHT: incident table ── --}}
        <div class="adm-panel" style="min-width:0;">
            <div class="adm-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;justify-content:space-between;">
                <span style="display:flex;align-items:center;gap:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Incident Timeline
                </span>
                <span style="font-size:.75rem;opacity:.6;font-weight:400;text-transform:none;letter-spacing:0;">
                    {{ $total }} record{{ $total!==1?'s':'' }}
                </span>
            </div>
            <div class="adm-panel-body" style="padding:0;">
                @if($total===0)
                    <div class="adm-empty">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.3;margin-bottom:10px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <p>No incidents match the current filters.</p>
                    </div>
                @else
                <div style="overflow-x:auto;">
                    <table class="adm-tbl">
                        <thead>
                            <tr>
                                <th style="width:148px;">Time</th>
                                <th style="width:185px;">Device</th>
                                <th style="width:155px;">Family</th>
                                <th style="width:140px;">Caregiver</th>
                                <th style="width:125px;">Event</th>
                                <th>Sensor Readings</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $activity)
                        @php
                            $evt     = $activity->event_type ?? '';
                            $payload = $activity->payload;
                            $decoded = null;
                            if(is_string($payload)){$t=trim($payload);if(str_starts_with($t,'{'))$decoded=json_decode($t,true);}
                            $temp   = $decoded['temperature'] ?? null;
                            $hum    = $decoded['humidity']    ?? null;
                            $sound  = $decoded['sound_level'] ?? null;
                            $tAlert = !empty($decoded['temp_alert']);
                            $hAlert = !empty($decoded['humid_alert']);
                            $caregiver = $activity->device?->user;
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $activity->created_at?->format('d M Y') }}</div>
                                <div style="font-size:.74rem;color:#94a3b8;">{{ $activity->created_at?->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $activity->device->device_name ?? '—' }}</div>
                                <div style="font-size:.7rem;color:#94a3b8;font-family:monospace;">{{ Str::limit($activity->device->device_token??'',16) }}</div>
                            </td>
                            <td style="font-size:.85rem;color:#334155;">
                                {{ optional($activity->device->family)->family_name ?? 'Unassigned' }}
                            </td>
                            <td style="font-size:.83rem;color:#334155;">
                                @if($caregiver)
                                    <div style="font-weight:600;">{{ $caregiver->name }}</div>
                                    <div style="font-size:.71rem;color:#94a3b8;">{{ $caregiver->email }}</div>
                                @else
                                    <span style="color:#f59e0b;font-size:.8rem;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($evt==='cry_detected')
                                    <span class="ev-badge ev-cry">🔔 Cry</span>
                                @elseif($evt==='dht')
                                    <span class="ev-badge ev-dht">🌡 DHT</span>
                                @elseif($evt)
                                    <span class="ev-badge ev-other">{{ $evt }}</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($decoded)
                                    <div class="sp-row">
                                        @if($temp!==null)<span class="sp-pill {{ $tAlert?'sp-crit':'sp-ok' }}">🌡 {{ $temp }}°C @if($tAlert) ⚠ @endif</span>@endif
                                        @if($hum!==null) <span class="sp-pill {{ $hAlert?'sp-warn':'sp-ok' }}">💧 {{ $hum }}% @if($hAlert) ⚠ @endif</span>@endif
                                        @if($sound!==null)<span class="sp-pill sp-crit">🔊 {{ $sound }}</span>@endif
                                        @if(!$tAlert&&!$hAlert&&$sound===null&&($temp!==null||$hum!==null))<span class="sp-pill sp-ok">✓ Normal</span>@endif
                                    </div>
                                @elseif($payload)
                                    <span style="font-size:.8rem;color:#64748b;">{{ Str::limit($payload,70) }}</span>
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
        </div>{{-- /right --}}

    </div>{{-- /grid --}}

</div>{{-- /adm-page --}}
@endsection
