<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $reportTitle ?? 'Mega Incident Report' }}</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Segoe UI',Arial,sans-serif;color:#0f172a;background:#fff;font-size:12px;line-height:1.5;}
.page{max-width:1050px;margin:0 auto;padding:28px 32px;}
/* cover */
.cover{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:3px solid #dc2626;padding-bottom:18px;margin-bottom:22px;gap:16px;}
.cover-brand{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#dc2626;margin-bottom:4px;}
.cover-title{font-size:22px;font-weight:800;color:#0f172a;line-height:1.15;}
.cover-sub{font-size:11px;color:#64748b;margin-top:4px;}
.cover-meta{font-size:11px;color:#475569;line-height:1.8;text-align:right;}
.cover-meta strong{color:#0f172a;}
/* kpi */
.kpi-row{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;}
.kpi-box{flex:1 1 120px;border-radius:10px;padding:11px 13px;border:1px solid #e2e8f0;}
.kpi-box-lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;opacity:.6;margin-bottom:3px;}
.kpi-box-val{font-size:22px;font-weight:800;line-height:1;}
.kpi-blue {background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;}
.kpi-rose {background:#fff1f2;color:#be123c;border-color:#fecdd3;}
.kpi-sky  {background:#f0f9ff;color:#0369a1;border-color:#bae6fd;}
.kpi-green{background:#f0fdf4;color:#15803d;border-color:#bbf7d0;}
.kpi-amber{background:#fffbeb;color:#b45309;border-color:#fde68a;}
/* filters */
.filter-row{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;align-items:center;}
.filter-head{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;}
.filter-pill{background:#f1f5f9;color:#334155;border:1px solid #e2e8f0;border-radius:20px;padding:2px 10px;font-size:10px;font-weight:600;}
/* section head */
.section-head{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;border-bottom:1px solid #e2e8f0;padding-bottom:5px;margin-bottom:12px;}
/* table */
table{width:100%;border-collapse:collapse;}
thead tr{background:#f8fafc;}
thead th{padding:8px 10px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;border-bottom:2px solid #e2e8f0;text-align:left;white-space:nowrap;}
tbody tr{border-bottom:1px solid #f1f5f9;}
tbody td{padding:8px 10px;font-size:11px;vertical-align:middle;}
/* event badges */
.ev{display:inline-block;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;}
.ev-cry  {background:#fff1f2;color:#be123c;border:1px solid #fecdd3;}
.ev-dht  {background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;}
.ev-other{background:#f8fafc;color:#475569;border:1px solid #e2e8f0;}
/* sensor pills */
.sp{display:inline-block;padding:1px 7px;border-radius:20px;font-size:10px;font-weight:600;margin:1px;}
.sp-ok  {background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;}
.sp-warn{background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;}
.sp-crit{background:#fff1f2;color:#be123c;border:1px solid #fecdd3;}
/* footer */
.report-footer{margin-top:24px;padding-top:11px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;font-size:10px;color:#94a3b8;}
/* print bar */
.print-bar{position:fixed;bottom:20px;right:20px;display:flex;gap:8px;}
.print-bar button{background:#0f172a;color:#fff;border:none;border-radius:10px;padding:10px 18px;font-weight:700;cursor:pointer;font-size:13px;}
.print-bar button.sec{background:#f1f5f9;color:#334155;}
@media print{
  .print-bar{display:none!important;}
  body{font-size:10px;}
  .page{padding:14px 16px;}
  thead{display:table-header-group;}
  tr{page-break-inside:avoid;}
}
</style>
</head>
<body>
<div class="page">
@php
    $activities = $activities ?? collect();
    $total      = $activities->count();
    $cryCount   = $activities->where('event_type','cry_detected')->count();
    $dhtCount   = $activities->where('event_type','dht')->count();
    $devsHit    = $activities->pluck('device_id')->unique()->count();
    $famsHit    = $activities->map(fn($a)=>optional($a->device->family)->id)->filter()->unique()->count();
    $filters    = $filters ?? [];
    $famName    = '';
    if(!empty($filters['family_id'])){
        $famName = ($families??collect())->firstWhere('id',$filters['family_id'])?->family_name ?? $filters['family_id'];
    }
@endphp

<div class="cover">
    <div>
        <div class="cover-brand">IoT Baby Monitor — Admin Report</div>
        <div class="cover-title">{{ $reportTitle ?? 'Mega Incident Report' }}</div>
        <div class="cover-sub">All devices · All families · Admin view</div>
    </div>
    <div class="cover-meta">
        <strong>Generated:</strong> {{ now()->format('d M Y, H:i') }}<br>
        @if($famName)   <strong>Family:</strong> {{ $famName }}<br> @endif
        @if(!empty($filters['q']))    <strong>Device:</strong> {{ $filters['q'] }}<br> @endif
        @if(!empty($filters['from'])) <strong>From:</strong> {{ $filters['from'] }}<br> @endif
        @if(!empty($filters['to']))   <strong>To:</strong> {{ $filters['to'] }}<br> @endif
    </div>
</div>

<div class="kpi-row">
    <div class="kpi-box kpi-blue"><div class="kpi-box-lbl">Total</div><div class="kpi-box-val">{{ $total }}</div></div>
    <div class="kpi-box kpi-rose"><div class="kpi-box-lbl">Cry Alerts</div><div class="kpi-box-val">{{ $cryCount }}</div></div>
    <div class="kpi-box kpi-sky"><div class="kpi-box-lbl">DHT Events</div><div class="kpi-box-val">{{ $dhtCount }}</div></div>
    <div class="kpi-box kpi-green"><div class="kpi-box-lbl">Devices</div><div class="kpi-box-val">{{ $devsHit }}</div></div>
    <div class="kpi-box kpi-amber"><div class="kpi-box-lbl">Families</div><div class="kpi-box-val">{{ $famsHit }}</div></div>
</div>

@if(array_filter($filters))
<div class="filter-row">
    <span class="filter-head">Filters:</span>
    @if($famName)                        <span class="filter-pill">Family: {{ $famName }}</span> @endif
    @if(!empty($filters['q']))           <span class="filter-pill">Device: {{ $filters['q'] }}</span> @endif
    @if(!empty($filters['from']))        <span class="filter-pill">From: {{ $filters['from'] }}</span> @endif
    @if(!empty($filters['to']))          <span class="filter-pill">To: {{ $filters['to'] }}</span> @endif
</div>
@endif

<div class="section-head">Incident Timeline ({{ $total }} record{{ $total!==1?'s':'' }})</div>

<table>
    <thead>
        <tr>
            <th style="width:110px;">Date & Time</th>
            <th style="width:150px;">Device</th>
            <th style="width:130px;">Family</th>
            <th style="width:120px;">Caregiver</th>
            <th style="width:90px;">Event</th>
            <th>Sensor Readings</th>
        </tr>
    </thead>
    <tbody>
    @forelse($activities as $a)
    @php
        $evt    =$a->event_type??'';
        $raw    =$a->payload;
        $dec    =null;
        if(is_string($raw)){$t=trim($raw);if(str_starts_with($t,'{'))$dec=json_decode($t,true);}
        $temp   =$dec['temperature']??null;
        $hum    =$dec['humidity']??null;
        $sound  =$dec['sound_level']??null;
        $tAlert =!empty($dec['temp_alert']);
        $hAlert =!empty($dec['humid_alert']);
        $cg     =$a->device?->user;
    @endphp
    <tr>
        <td>
            <strong>{{ $a->created_at?->format('d M Y') }}</strong><br>
            <span style="color:#94a3b8;">{{ $a->created_at?->format('H:i:s') }}</span>
        </td>
        <td>
            <strong>{{ $a->device->device_name??'—' }}</strong><br>
            <span style="font-size:9px;color:#94a3b8;font-family:monospace;">{{ $a->device->device_token??'' }}</span>
        </td>
        <td>{{ optional($a->device->family)->family_name??'Unassigned' }}</td>
        <td>
            @if($cg) <strong>{{ $cg->name }}</strong><br><span style="font-size:9px;color:#94a3b8;">{{ $cg->email }}</span>
            @else <span style="color:#f59e0b;">—</span>
            @endif
        </td>
        <td>
            @if($evt==='cry_detected') <span class="ev ev-cry">🔔 Cry</span>
            @elseif($evt==='dht')      <span class="ev ev-dht">🌡 DHT</span>
            @elseif($evt)              <span class="ev ev-other">{{ $evt }}</span>
            @else                      <span style="color:#94a3b8;">—</span>
            @endif
        </td>
        <td>
            @if($dec)
                @if($temp!==null)<span class="sp {{ $tAlert?'sp-crit':'sp-ok' }}">🌡 {{ $temp }}°C</span>@endif
                @if($hum!==null) <span class="sp {{ $hAlert?'sp-warn':'sp-ok' }}">💧 {{ $hum }}%</span>@endif
                @if($sound!==null)<span class="sp sp-crit">🔊 {{ $sound }}</span>@endif
                @if(!$tAlert&&!$hAlert&&$sound===null&&($temp!==null||$hum!==null))<span class="sp sp-ok">✓ Normal</span>@endif
            @elseif($raw)
                <span style="font-size:10px;color:#64748b;">{{ Str::limit($raw,80) }}</span>
            @else —
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:22px;">No activities found.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="report-footer">
    <span>IoT Baby Monitor — Admin Mega Incident Report</span>
    <span>{{ $total }} record{{ $total!==1?'s':'' }} · Generated {{ now()->format('d M Y H:i') }}</span>
</div>
</div>

<div class="print-bar">
    <button class="sec" onclick="window.close();">✕ Close</button>
    <button onclick="window.print();">🖨 Print / Save PDF</button>
</div>
</body>
</html>
