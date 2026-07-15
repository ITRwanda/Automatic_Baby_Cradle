@extends('layouts.app')

@section('content')
@php
    $notifications = $notifications ?? collect();
    $unreadCount   = $unreadCount   ?? 0;
    $total         = $notifications instanceof \Illuminate\Pagination\LengthAwarePaginator
                        ? $notifications->total()
                        : $notifications->count();
@endphp

<style>
    .notif-page { max-width: 860px; margin: 0 auto; }

    /* ── Page header ─────────────────────────────────────── */
    .notif-header { display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:28px; }
    .notif-title  { font-size:1.45rem; font-weight:800; color:#0f172a; margin:0; line-height:1.2; }
    .notif-sub    { font-size:.875rem; color:#64748b; margin:4px 0 0; }

    /* ── Unread badge ─────────────────────────────────────── */
    .unread-badge {
        display:inline-flex; align-items:center; gap:6px;
        background:#fff1f2; color:#be123c; border:1.5px solid #fecdd3;
        border-radius:20px; padding:4px 12px; font-size:.8rem; font-weight:700;
    }
    .unread-badge.none { background:#f0fdf4; color:#15803d; border-color:#bbf7d0; }

    /* ── Mark-all button ─────────────────────────────────── */
    .btn-mark-all {
        background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0;
        border-radius:8px; padding:7px 14px; font-size:.82rem; font-weight:700;
        cursor:pointer; display:inline-flex; align-items:center; gap:6px;
        transition:background .15s;
    }
    .btn-mark-all:hover { background:#e2e8f0; }

    /* ── Notification card ────────────────────────────────── */
    .notif-card {
        background:#fff;
        border-radius:14px;
        border:1.5px solid #f1f5f9;
        padding:16px 20px;
        margin-bottom:10px;
        display:flex; gap:14px; align-items:flex-start;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
        transition:border-color .15s, background .15s;
        position:relative;
    }
    .notif-card.unread {
        border-color:#bae6fd;
        background:linear-gradient(135deg,#f0f9ff 0%,#fafffe 100%);
    }
    .notif-card.unread-cry {
        border-color:#fecdd3;
        background:linear-gradient(135deg,#fff5f5 0%,#fffafa 100%);
    }
    .notif-card.unread-dht {
        border-color:#bae6fd;
        background:linear-gradient(135deg,#f0f9ff 0%,#fafffe 100%);
    }

    /* ── Unread dot ──────────────────────────────────────── */
    .notif-dot {
        width:9px; height:9px; border-radius:50%;
        background:#0ea5e9; flex-shrink:0; margin-top:6px;
    }
    .notif-dot.cry  { background:#be123c; }
    .notif-dot.dht  { background:#0369a1; }
    .notif-dot.read { background:transparent; }

    /* ── Icon bubble ─────────────────────────────────────── */
    .notif-icon {
        width:42px; height:42px; border-radius:12px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:1.3rem;
    }
    .notif-icon.cry   { background:#fff1f2; }
    .notif-icon.dht   { background:#f0f9ff; }
    .notif-icon.other { background:#f8fafc; }

    /* ── Card body ───────────────────────────────────────── */
    .notif-body { flex:1; min-width:0; }
    .notif-card-title { font-size:.95rem; font-weight:700; color:#0f172a; margin-bottom:3px; }
    .notif-card-body  { font-size:.84rem; color:#475569; line-height:1.55; }
    .notif-card-meta  { font-size:.75rem; color:#94a3b8; margin-top:6px; display:flex; flex-wrap:wrap; gap:12px; }
    .notif-card-meta span { display:flex; align-items:center; gap:4px; }

    /* ── Read label ──────────────────────────────────────── */
    .notif-read-label {
        position:absolute; top:12px; right:14px;
        font-size:.7rem; font-weight:700;
        color:#94a3b8;
    }

    /* ── Empty state ─────────────────────────────────────── */
    .notif-empty { text-align:center; padding:56px 24px; color:#94a3b8; }
    .notif-empty p { margin:10px 0 0; font-size:.925rem; }

    /* ── Section label ───────────────────────────────────── */
    .notif-section-label {
        font-size:.7rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.09em; color:#94a3b8;
        margin:20px 0 8px;
    }
</style>

<div class="notif-page">

    {{-- ── Header ── --}}
    <div class="notif-header">
        <div>
            <h1 class="notif-title">
                <span style="color:#0f766e;">&#9679;</span>
                Notifications
            </h1>
            <p class="notif-sub">Real-time alerts from your assigned cradle devices</p>
        </div>
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            @if($unreadCount > 0)
                <span class="unread-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                    {{ $unreadCount }} unread
                </span>
                <form method="POST" action="{{ route('notifications.markAllRead') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-mark-all">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Mark all as read
                    </button>
                </form>
            @else
                <span class="unread-badge none">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    All caught up
                </span>
            @endif
            <a href="{{ route('caregiver.dashboard') }}"
               style="background:#f8fafc;color:#334155;border:1.5px solid #e2e8f0;border-radius:8px;padding:7px 14px;font-size:.82rem;font-weight:700;text-decoration:none;">
                ← Dashboard
            </a>
        </div>
    </div>

    {{-- ── Notification list ── --}}
    @if($notifications->isEmpty())
        <div style="background:#fff; border-radius:16px; box-shadow:0 2px 14px rgba(0,0,0,.06); overflow:hidden;">
            <div class="notif-empty">
                <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" style="opacity:.3;">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <p>No notifications yet.</p>
                <p style="font-size:.8rem; margin-top:4px; color:#cbd5e1;">Alerts from your devices will appear here in real time.</p>
            </div>
        </div>
    @else
        @php
            $unreadItems = $notifications->filter(fn($n) => is_null($n->read_at));
            $readItems   = $notifications->filter(fn($n) => !is_null($n->read_at));
        @endphp

        @if($unreadItems->isNotEmpty())
            <div class="notif-section-label">Unread — {{ $unreadItems->count() }}</div>
            @foreach($unreadItems as $note)
                @php
                    $evt       = strtolower($note->event_type ?? '');
                    $isCry     = $evt === 'cry_detected';
                    $isDht     = $evt === 'dht';
                    $iconChar  = $isCry ? '🔔' : ($isDht ? '🌡️' : '⚠️');
                    $cardClass = $isCry ? 'unread-cry' : ($isDht ? 'unread-dht' : 'unread');
                    $dotClass  = $isCry ? 'cry' : ($isDht ? 'dht' : '');
                    $iconClass = $isCry ? 'cry' : ($isDht ? 'dht' : 'other');
                @endphp
                <div class="notif-card {{ $cardClass }}">
                    <div class="notif-dot {{ $dotClass }}"></div>
                    <div class="notif-icon {{ $iconClass }}">{{ $iconChar }}</div>
                    <div class="notif-body">
                        <div class="notif-card-title">{{ $note->title }}</div>
                        <div class="notif-card-body">{{ $note->body }}</div>
                        <div class="notif-card-meta">
                            @if($note->device)
                            <span>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                {{ $note->device->device_name }}
                            </span>
                            @endif
                            <span>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $note->created_at?->diffForHumans() }}
                            </span>
                            <span style="color:#94a3b8;">{{ $note->created_at?->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if($readItems->isNotEmpty())
            <div class="notif-section-label" style="margin-top:24px;">Earlier</div>
            @foreach($readItems as $note)
                @php
                    $evt      = strtolower($note->event_type ?? '');
                    $isCry    = $evt === 'cry_detected';
                    $isDht    = $evt === 'dht';
                    $iconChar = $isCry ? '🔔' : ($isDht ? '🌡️' : '⚠️');
                    $iconCls  = $isCry ? 'cry' : ($isDht ? 'dht' : 'other');
                @endphp
                <div class="notif-card" style="opacity:.7;">
                    <div class="notif-dot read"></div>
                    <div class="notif-icon {{ $iconCls }}" style="opacity:.6;">{{ $iconChar }}</div>
                    <div class="notif-body">
                        <div class="notif-card-title" style="color:#334155;">{{ $note->title }}</div>
                        <div class="notif-card-body" style="color:#64748b;">{{ $note->body }}</div>
                        <div class="notif-card-meta">
                            @if($note->device)
                            <span>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                {{ $note->device->device_name }}
                            </span>
                            @endif
                            <span>{{ $note->created_at?->format('d M Y, H:i') }}</span>
                            <span style="color:#15803d;font-weight:600;">✓ Read</span>
                        </div>
                    </div>
                    <div class="notif-read-label">Read</div>
                </div>
            @endforeach
        @endif

        {{-- Pagination (only rendered when LengthAwarePaginator is used) --}}
        @if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator && $notifications->hasPages())
            <div style="margin-top:20px; display:flex; justify-content:center;">
                {{ $notifications->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
