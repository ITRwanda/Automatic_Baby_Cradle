@extends('layouts.app')

@section('content')
@php
    $devices       = $devices       ?? collect();
    $members       = $members       ?? collect();
    $notifications = $notifications ?? collect();
    $unreadCount   = $unreadCount   ?? 0;

    $cryCnt  = $notifications->where('event_type','cry_detected')->count();
    $dhtCnt  = $notifications->where('event_type','dht')->count();
@endphp

<style>
    .fd-page { max-width: 1280px; margin: 0 auto; }

    /* ── Header ─────────────────────────────────────────────── */
    .fd-header { display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:28px; }
    .fd-title  { font-size:1.45rem; font-weight:800; color:#0f172a; margin:0; }
    .fd-sub    { font-size:.875rem; color:#64748b; margin:4px 0 0; }

    /* ── KPI strip ───────────────────────────────────────────── */
    .fd-kpi { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:28px; }
    .fd-kpi-card {
        border-radius:14px; padding:16px 18px;
        box-shadow:0 2px 10px rgba(0,0,0,.06);
        display:flex; flex-direction:column; gap:4px;
    }
    .fd-kpi-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; opacity:.6; }
    .fd-kpi-value { font-size:1.85rem; font-weight:800; line-height:1; }
    .fd-kpi-hint  { font-size:.73rem; opacity:.55; }

    .fk-blue  { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8; }
    .fk-teal  { background:linear-gradient(135deg,#f0fdfa,#ccfbf1); color:#0f766e; }
    .fk-rose  { background:linear-gradient(135deg,#fff1f2,#ffe4e6); color:#be123c; }
    .fk-amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#b45309; }
    .fk-green { background:linear-gradient(135deg,#f0fdf4,#dcfce7); color:#15803d; }

    /* ── Main grid ───────────────────────────────────────────── */
    .fd-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
    .fd-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; }
    @media(max-width:1100px){ .fd-grid-3{ grid-template-columns:1fr 1fr; } }
    @media(max-width:700px){
        .fd-grid, .fd-grid-3 { grid-template-columns:1fr; }
    }

    /* ── Panel ───────────────────────────────────────────────── */
    .fd-panel { background:#fff; border-radius:16px; box-shadow:0 2px 14px rgba(0,0,0,.07); overflow:hidden; }
    .fd-panel-hdr {
        padding:13px 20px; font-size:.78rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.08em;
        display:flex; align-items:center; justify-content:space-between; gap:8px;
    }
    .fd-panel-body { padding:20px; }

    /* ── Device row ──────────────────────────────────────────── */
    .fd-device {
        display:flex; align-items:center; justify-content:space-between;
        padding:12px 14px; border-radius:10px; margin-bottom:8px;
        background:linear-gradient(90deg,rgba(29,78,216,.06),rgba(99,102,241,.04));
        border:1px solid rgba(29,78,216,.1); gap:10px;
    }
    .fd-device-name  { font-size:.9rem; font-weight:700; color:#0f172a; }
    .fd-device-token { font-size:.7rem; color:#94a3b8; font-family:monospace; margin-top:2px; }

    /* ── Member row ──────────────────────────────────────────── */
    .fd-member {
        display:flex; align-items:center; gap:12px;
        padding:10px 14px; border-radius:10px; margin-bottom:8px;
        background:#f8fafc; border:1px solid #f1f5f9;
    }
    .fd-avatar {
        width:36px; height:36px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg,#0f766e,#14b8a6);
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-weight:800; font-size:.9rem;
    }
    .fd-member-name  { font-size:.88rem; font-weight:700; color:#0f172a; }
    .fd-member-email { font-size:.74rem; color:#94a3b8; }

    /* ── Notification mini ───────────────────────────────────── */
    .fd-notif {
        padding:11px 14px; border-radius:10px; margin-bottom:8px;
        border:1.5px solid #f1f5f9; background:#fff;
    }
    .fd-notif.n-cry   { border-color:#fecdd3; background:#fff9f9; }
    .fd-notif.n-dht   { border-color:#bae6fd; background:#f0f9ff; }
    .fd-notif.n-other { border-color:#e0f2fe; background:#f8fafc; }
    .fd-notif-title { font-size:.85rem; font-weight:700; color:#0f172a; }
    .fd-notif-body  { font-size:.78rem; color:#64748b; margin-top:2px; line-height:1.4; }
    .fd-notif-time  { font-size:.72rem; color:#94a3b8; margin-top:4px; display:flex; flex-wrap:wrap; gap:10px; }

    /* ── Unread badge ────────────────────────────────────────── */
    .u-badge { background:#be123c; color:#fff; border-radius:20px; padding:1px 8px; font-size:.72rem; font-weight:700; }

    /* ── Action buttons ──────────────────────────────────────── */
    .fd-btn-primary {
        background:linear-gradient(135deg,#006633,#009944);
        color:#fff; border:none; border-radius:8px;
        padding:7px 16px; font-size:.82rem; font-weight:700;
        text-decoration:none; display:inline-flex; align-items:center; gap:6px;
        transition:opacity .15s;
    }
    .fd-btn-primary:hover { opacity:.88; color:#fff; }
    .fd-btn-outline {
        background:#f8fafc; color:#334155; border:1.5px solid #e2e8f0;
        border-radius:8px; padding:7px 14px; font-size:.82rem; font-weight:700;
        text-decoration:none; display:inline-flex; align-items:center; gap:6px;
        transition:background .15s;
    }
    .fd-btn-outline:hover { background:#e2e8f0; color:#334155; }

    /* ── Empty ───────────────────────────────────────────────── */
    .fd-empty { text-align:center; padding:24px; color:#94a3b8; font-size:.84rem; }

    /* ── Role badge ──────────────────────────────────────────── */
    .role-pill {
        background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;
        border-radius:20px; padding:2px 9px; font-size:.72rem; font-weight:700;
    }
</style>

<div class="fd-page">

    {{-- ── Header ── --}}
    <div class="fd-header">
        <div>
            <h1 class="fd-title">
                <span style="color:#006633;">&#9679;</span>
                Family Dashboard
            </h1>
            <p class="fd-sub">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('family.reports') }}" class="fd-btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                View Reports
            </a>
            <a href="{{ route('family.caregivers') }}" class="fd-btn-outline">Manage Caregivers</a>
        </div>
    </div>

    {{-- ── KPI strip ── --}}
    <div class="fd-kpi">
        <div class="fd-kpi-card fk-blue">
            <div class="fd-kpi-label">Devices</div>
            <div class="fd-kpi-value">{{ $devices->count() }}</div>
            <div class="fd-kpi-hint">Assigned to family</div>
        </div>
        <div class="fd-kpi-card fk-teal">
            <div class="fd-kpi-label">Caregivers</div>
            <div class="fd-kpi-value">{{ $members->count() }}</div>
            <div class="fd-kpi-hint">Family members</div>
        </div>
        <div class="fd-kpi-card fk-rose">
            <div class="fd-kpi-label">Unread Alerts</div>
            <div class="fd-kpi-value">{{ $unreadCount }}</div>
            <div class="fd-kpi-hint">Requiring attention</div>
        </div>
        <div class="fd-kpi-card fk-amber">
            <div class="fd-kpi-label">Cry Alerts</div>
            <div class="fd-kpi-value">{{ $cryCnt }}</div>
            <div class="fd-kpi-hint">Recent sound events</div>
        </div>
        <div class="fd-kpi-card fk-green">
            <div class="fd-kpi-label">Env. Alerts</div>
            <div class="fd-kpi-value">{{ $dhtCnt }}</div>
            <div class="fd-kpi-hint">Temp / humidity</div>
        </div>
    </div>

    {{-- ── Devices + Members ── --}}
    <div class="fd-grid" style="margin-bottom:20px;">

        {{-- Devices --}}
        <div class="fd-panel">
            <div class="fd-panel-hdr" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff;">
                <span style="display:flex;align-items:center;gap:7px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Assigned Devices
                </span>
                <span style="font-size:.73rem;opacity:.7;font-weight:400;text-transform:none;letter-spacing:0;">
                    {{ $devices->count() }} total
                </span>
            </div>
            <div class="fd-panel-body">
                @forelse($devices as $device)
                    <div class="fd-device">
                        <div>
                            <div class="fd-device-name">{{ $device->device_name }}</div>
                            <div class="fd-device-token">{{ $device->device_token }}</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                            <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Active</span>
                            @if($device->user)
                                <span style="font-size:.72rem;color:#64748b;">{{ $device->user->name }}</span>
                            @else
                                <span style="font-size:.72rem;color:#f59e0b;">Unassigned</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="fd-empty">No devices assigned yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Members / Caregivers --}}
        <div class="fd-panel">
            <div class="fd-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
                <span style="display:flex;align-items:center;gap:7px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Caregivers
                </span>
                <a href="{{ route('family.caregivers') }}" style="font-size:.73rem;color:#94a3b8;font-weight:600;text-decoration:none;text-transform:none;letter-spacing:0;">
                    Manage →
                </a>
            </div>
            <div class="fd-panel-body">
                @forelse($members as $member)
                    <div class="fd-member">
                        <div class="fd-avatar">{{ strtoupper(substr($member->name ?? '?', 0, 1)) }}</div>
                        <div style="flex:1;min-width:0;">
                            <div class="fd-member-name">{{ $member->name }}</div>
                            <div class="fd-member-email">{{ $member->email }}</div>
                        </div>
                        <span class="role-pill">
                            {{ is_string($member->role) ? $member->role : ($member->role?->name ?? 'member') }}
                        </span>
                    </div>
                @empty
                    <div class="fd-empty">No members added yet.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── Notifications ── --}}
    <div class="fd-panel">
        <div class="fd-panel-hdr" style="background:linear-gradient(135deg,#006633,#009944);color:#fff;">
            <span style="display:flex;align-items:center;gap:7px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                Recent Incident Notifications
            </span>
            <div style="display:flex;align-items:center;gap:8px;">
                @if($unreadCount > 0)
                    <span class="u-badge">{{ $unreadCount }} unread</span>
                    <form method="POST" action="{{ route('notifications.markAllRead') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:6px;padding:3px 10px;font-size:.72rem;font-weight:700;cursor:pointer;">
                            Mark all read
                        </button>
                    </form>
                @else
                    <span style="font-size:.73rem;opacity:.6;font-weight:400;text-transform:none;letter-spacing:0;">All read</span>
                @endif
            </div>
        </div>
        <div class="fd-panel-body">
            @if($notifications->isEmpty())
                <div class="fd-empty" style="padding:32px;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" style="opacity:.3;margin-bottom:8px;display:block;margin:0 auto 8px;">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    No notifications yet. Alerts will appear here when the cradle detects an incident.
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:10px;">
                    @foreach($notifications as $note)
                        @php
                            $evt    = strtolower($note->event_type ?? '');
                            $isCry  = $evt === 'cry_detected';
                            $isDht  = $evt === 'dht';
                            $icon   = $isCry ? '🔔' : ($isDht ? '🌡️' : '⚠️');
                            $isUnread = is_null($note->read_at);
                            $cls    = '';
                            if ($isUnread) {
                                $cls = $isCry ? 'n-cry' : ($isDht ? 'n-dht' : 'n-other');
                            }
                        @endphp
                        <div class="fd-notif {{ $cls }}">
                            <div class="fd-notif-title">{{ $icon }} {{ $note->title }}</div>
                            <div class="fd-notif-body">{{ Str::limit($note->body, 90) }}</div>
                            <div class="fd-notif-time">
                                @if($note->device)
                                    <span>📟 {{ $note->device->device_name }}</span>
                                @endif
                                <span>{{ $note->created_at?->diffForHumans() }}</span>
                                @if(!$isUnread)
                                    <span style="color:#15803d;">✓ read</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:16px;text-align:right;">
                    <a href="{{ route('family.reports') }}" class="fd-btn-outline" style="font-size:.78rem;padding:6px 14px;">
                        View full incident report →
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
