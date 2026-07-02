<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111827; }
        .header { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; margin-bottom: 18px; }
        h1 { font-size: 18px; margin: 0 0 6px 0; }
        .meta { font-size: 12px; color: #374151; line-height: 1.4; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; font-size: 12px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; border: 1px solid #c7d2fe; background: #eef2ff; color: #3730a3; font-size: 11px; }
        .small { font-size: 11px; color: #6b7280; }
        .page { margin: 18px; }
        @media print {
            .noprint { display: none !important; }
            body { margin: 0; }
            .page { margin: 0; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <h1>{{ $reportTitle }}</h1>
            <div class="meta">Generated at: {{ now()->format('Y-m-d H:i') }}</div>
        </div>
        <div class="meta" style="text-align: right;">
            <div><b>Filters</b></div>
            <div>Family ID: {{ $filters['family_id'] ?? '' }}</div>
            <div>Search: {{ $filters['q'] ?? '' }}</div>
            <div>From: {{ $filters['from'] ?? '' }}</div>
            <div>To: {{ $filters['to'] ?? '' }}</div>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th style="width: 170px;">Time</th>
            <th style="width: 200px;">Device</th>
            <th style="width: 200px;">Family</th>
            <th style="width: 160px;">Event</th>
            <th>Payload</th>
        </tr>
        </thead>
        <tbody>
        @forelse($activities as $a)
            <tr>
                <td>{{ $a->created_at?->format('Y-m-d H:i:s') }}</td>
                <td>
                    <div><b>{{ $a->device->device_name ?? '—' }}</b></div>
                    <div class="small">Token: {{ $a->device->device_token ?? '' }}</div>
                </td>
                <td>{{ optional($a->device->family)->family_name ?? 'Unassigned' }}</td>
                <td>
                    @if(!empty($a->event_type))
                        <span class="badge">{{ $a->event_type }}</span>
                    @else
                        <span class="small">Incident recorded</span>
                    @endif
                </td>
                <td style="white-space: pre-wrap;">{{ $a->payload ?? '' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="small">No activities found for selected filters.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="meta" style="margin-top: 12px;">Total rows: {{ ($activities ?? collect())->count() }}</div>
</div>

<div class="noprint" style="position: fixed; bottom: 18px; right: 18px;">
    <button onclick="window.print();" style="background:#111827;color:white;border:none;border-radius:10px;padding:10px 14px;font-weight:700;cursor:pointer;">Print / Save as PDF</button>
</div>
</body>
</html>

