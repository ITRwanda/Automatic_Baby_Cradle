<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle ?? 'Caregiver Incident Report' }}</title>
    <style>
        /* ── Reset & base ─────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #0f172a;
            background: #fff;
            font-size: 12px;
            line-height: 1.5;
        }

        /* ── Page wrapper ─────────────────────────────────── */
        .page { max-width: 960px; margin: 0 auto; padding: 28px 32px; }

        /* ── Cover header ─────────────────────────────────── */
        .cover {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #0f766e;
            padding-bottom: 18px;
            margin-bottom: 22px;
            gap: 16px;
        }
        .cover-brand {
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            color: #0f766e; margin-bottom: 4px;
        }
        .cover-title { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.15; }
        .cover-sub   { font-size: 11px; color: #64748b; margin-top: 4px; }
        .cover-right { text-align: right; flex-shrink: 0; }
        .cover-meta  { font-size: 11px; color: #475569; line-height: 1.8; }
        .cover-meta strong { color: #0f172a; }

        /* ── KPI row ──────────────────────────────────────── */
        .kpi-row { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 22px; }
        .kpi-box {
            flex: 1 1 130px;
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
        }
        .kpi-box-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; opacity: .6; margin-bottom: 3px; }
        .kpi-box-value { font-size: 24px; font-weight: 800; line-height: 1; }
        .kpi-box-hint  { font-size: 9px; opacity: .55; margin-top: 3px; }

        .kpi-teal  { background: #f0fdfa; color: #0f766e; border-color: #99f6e4; }
        .kpi-rose  { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
        .kpi-sky   { background: #f0f9ff; color: #0369a1; border-color: #bae6fd; }
        .kpi-lime  { background: #f7fee7; color: #4d7c0f; border-color: #d9f99d; }
        .kpi-gray  { background: #f8fafc; color: #334155; border-color: #e2e8f0; }

        /* ── Alert notice ─────────────────────────────────── */
        .alert-notice {
            background: #fff1f2;
            border: 1.5px solid #fecdd3;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 11px;
            color: #9f1239;
            font-weight: 600;
        }

        /* ── Filter pills ─────────────────────────────────── */
        .filter-row { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; align-items: center; }
        .filter-head { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; }
        .filter-pill {
            background: #f1f5f9; color: #334155;
            border: 1px solid #e2e8f0; border-radius: 20px;
            padding: 2px 10px; font-size: 10px; font-weight: 600;
        }

        /* ── Section heading ──────────────────────────────── */
        .section-head {
            font-size: 9px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .1em; color: #94a3b8;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px; margin-bottom: 12px;
        }

        /* ── Table ────────────────────────────────────────── */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f8fafc; }
        thead th {
            padding: 9px 11px;
            font-size: 9px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            text-align: left; white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr.row-alert { background: #fff9f9; }
        tbody td { padding: 9px 11px; font-size: 11px; vertical-align: middle; }

        /* ── Event badges ─────────────────────────────────── */
        .ev { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700; }
        .ev-cry   { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
        .ev-dht   { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
        .ev-other { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        /* ── Sensor pills ─────────────────────────────────── */
        .sp { display: inline-block; padding: 1px 7px; border-radius: 20px; font-size: 10px; font-weight: 600; margin: 1px; }
        .sp-ok   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .sp-warn { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .sp-crit { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }

        /* ── Footer ───────────────────────────────────────── */
        .report-footer {
            margin-top: 28px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #94a3b8;
        }

        /* ── Print bar ────────────────────────────────────── */
        .print-bar {
            position: fixed; bottom: 20px; right: 20px;
            display: flex; gap: 8px;
        }
        .print-bar button {
            background: #0f172a; color: #fff; border: none;
            border-radius: 10px; padding: 10px 18px;
            font-weight: 700; cursor: pointer; font-size: 13px;
        }
        .print-bar button.sec { background: #f1f5f9; color: #334155; }

        /* ── Print media ──────────────────────────────────── */
        @media print {
            .print-bar { display: none !important; }
            body { font-size: 11px; }
            .page { padding: 16px 20px; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Cover header --}}
    <div class="cover">
        <div>
            <div class="cover-brand">IoT Baby Monitor — Caregiver Report</div>
            <div class="cover-title">{{ $reportTitle ?? 'Caregiver Incident Report' }}</div>
            <div class="cover-sub">Devices assigned to this caregiver only</div>
        </div>
        <div class="cover-right">
            <div class="cover-meta">
                <strong>Generated:</strong> {{ now()->format('d M Y, H:i') }}<br>
                @if(!empty($filters['from']) || !empty($filters['to']))
                    <strong>Period:</strong>
                    {{ $filters['from'] ?? '∞' }} → {{ $filters['to'] ?? now()->format('Y-m-d') }}<br>
                @endif
                @if(!empty($filters['q']))
                    <strong>Device filter:</strong> {{ $filters['q'] }}<br>
                @endif
            </div>
        </div>
    </div>

    {{-- KPI row --}}
    @php
        $activities = $activities ?? collect();
        $total      = $activities->count();
        $cryCount   = $activities->where('event_type','cry_detected')->count();
        $dhtCount   = $activities->where('event_type','dht')->count();
        $devCount   = $activities->pluck('device_id')->unique()->count();
        $openAlerts = $activities->filter(fn($a) =>
            $a->event_type === 'cry_detected' ||
            !empty(json_decode($a->payload ?? '', true)['temp_alert']) ||
            !empty(json_decode($a->payload ?? '', true)['humid_alert'])
        )->count();
    @endphp

    @if($openAlerts > 0)
    <div class="alert-notice">
        🚨 {{ $openAlerts }} alert{{ $openAlerts > 1 ? 's' : '' }} detected in this report period — please review immediately.
    </div>
    @endif

    <div class="kpi-row">
        <div class="kpi-box kpi-teal">
            <div class="kpi-box-label">Total Events</div>
            <div class="kpi-box-value">{{ $total }}</div>
        </div>
        <div class="kpi-box kpi-rose">
            <div class="kpi-box-label">Cry Alerts</div>
            <div class="kpi-box-value">{{ $cryCount }}</div>
        </div>
        <div class="kpi-box kpi-sky">
            <div class="kpi-box-label">DHT Events</div>
            <div class="kpi-box-value">{{ $dhtCount }}</div>
        </div>
        <div class="kpi-box kpi-lime">
            <div class="kpi-box-label">Devices</div>
            <div class="kpi-box-value">{{ $devCount }}</div>
        </div>
        <div class="kpi-box kpi-gray">
            <div class="kpi-box-label">Alerts</div>
            <div class="kpi-box-value">{{ $openAlerts }}</div>
            <div class="kpi-box-hint">requiring action</div>
        </div>
    </div>

    {{-- Active filters --}}
    @if(!empty($filters['q']) || !empty($filters['from']) || !empty($filters['to']))
    <div class="filter-row">
        <span class="filter-head">Filters:</span>
        @if(!empty($filters['q']))    <span class="filter-pill">Device: {{ $filters['q'] }}</span> @endif
        @if(!empty($filters['from'])) <span class="filter-pill">From: {{ $filters['from'] }}</span> @endif
        @if(!empty($filters['to']))   <span class="filter-pill">To: {{ $filters['to'] }}</span> @endif
    </div>
    @endif

    {{-- Table --}}
    <div class="section-head">Incident Timeline ({{ $total }} record{{ $total !== 1 ? 's' : '' }})</div>

    <table>
        <thead>
            <tr>
                <th style="width:120px;">Date &amp; Time</th>
                <th style="width:170px;">Device</th>
                <th style="width:140px;">Family</th>
                <th style="width:105px;">Event</th>
                <th>Sensor Readings</th>
            </tr>
        </thead>
        <tbody>
        @forelse($activities as $a)
            @php
                $evt     = $a->event_type ?? '';
                $payload = $a->payload;
                $decoded = null;
                if (is_string($payload)) {
                    $t = trim($payload);
                    if (str_starts_with($t, '{')) $decoded = json_decode($t, true);
                }
                $temp   = $decoded['temperature'] ?? null;
                $hum    = $decoded['humidity']    ?? null;
                $sound  = $decoded['sound_level'] ?? null;
                $tAlert = !empty($decoded['temp_alert']);
                $hAlert = !empty($decoded['humid_alert']);
                $rowAlert = $evt === 'cry_detected' || $tAlert || $hAlert;
            @endphp
            <tr class="{{ $rowAlert ? 'row-alert' : '' }}">
                <td>
                    <strong>{{ $a->created_at?->format('d M Y') }}</strong><br>
                    <span style="color:#94a3b8;">{{ $a->created_at?->format('H:i:s') }}</span>
                </td>
                <td>
                    <strong>{{ $a->device->device_name ?? '—' }}</strong><br>
                    <span style="font-size:9px;color:#94a3b8;font-family:monospace;">{{ $a->device->device_token ?? '' }}</span>
                </td>
                <td>{{ optional($a->device->family)->family_name ?? 'Unassigned' }}</td>
                <td>
                    @if($evt === 'cry_detected')
                        <span class="ev ev-cry">🔔 Cry</span>
                    @elseif($evt === 'dht')
                        <span class="ev ev-dht">🌡 DHT</span>
                    @elseif($evt)
                        <span class="ev ev-other">{{ $evt }}</span>
                    @else
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    @if($decoded)
                        @if($temp !== null)
                            <span class="sp {{ $tAlert ? 'sp-crit' : 'sp-ok' }}">🌡 {{ $temp }}°C</span>
                        @endif
                        @if($hum !== null)
                            <span class="sp {{ $hAlert ? 'sp-warn' : 'sp-ok' }}">💧 {{ $hum }}%</span>
                        @endif
                        @if($sound !== null)
                            <span class="sp sp-crit">🔊 {{ $sound }}</span>
                        @endif
                        @if(!$tAlert && !$hAlert && $sound === null && ($temp !== null || $hum !== null))
                            <span class="sp sp-ok">✓ Normal</span>
                        @endif
                    @elseif($payload)
                        <span style="font-size:10px;color:#64748b;">{{ Str::limit($payload, 90) }}</span>
                    @else
                        —
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;color:#94a3b8;padding:24px;">
                    No activities found for selected filters.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="report-footer">
        <span>IoT Baby Monitor — Caregiver Incident Report</span>
        <span>Total: {{ $total }} record{{ $total !== 1 ? 's' : '' }} &nbsp;|&nbsp; Generated {{ now()->format('d M Y H:i') }}</span>
    </div>

</div>

{{-- Print / close bar (screen only) --}}
<div class="print-bar">
    <button class="sec" onclick="window.close();">✕ Close</button>
    <button onclick="window.print();">🖨 Print / Save PDF</button>
</div>
</body>
</html>
