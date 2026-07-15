

<?php $__env->startSection('content'); ?>
<?php
    $devices       = $devices       ?? collect();
    $notifications = $notifications ?? collect();
    $unreadCount   = $unreadCount   ?? 0;

    $recentAlerts  = $notifications->whereIn('event_type', ['cry_detected','cradle'])
                                   ->take(3);
?>

<style>
    .cgd-page { max-width: 1200px; margin: 0 auto; }

    /* ── Header ─────────────────────────────────────────────── */
    .cgd-header { display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:28px; }
    .cgd-title  { font-size:1.45rem; font-weight:800; color:#0f172a; margin:0; }
    .cgd-sub    { font-size:.875rem; color:#64748b; margin:4px 0 0; }

    /* ── KPI strip ───────────────────────────────────────────── */
    .cgd-kpi { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:28px; }
    .cgd-kpi-card {
        border-radius:14px; padding:16px 18px;
        box-shadow:0 2px 10px rgba(0,0,0,.06);
        display:flex; flex-direction:column; gap:4px;
    }
    .cgd-kpi-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; opacity:.6; }
    .cgd-kpi-value { font-size:1.85rem; font-weight:800; line-height:1; }
    .cgd-kpi-hint  { font-size:.73rem; opacity:.55; }

    .k-teal  { background:linear-gradient(135deg,#f0fdfa,#ccfbf1); color:#0f766e; }
    .k-rose  { background:linear-gradient(135deg,#fff1f2,#ffe4e6); color:#be123c; }
    .k-sky   { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); color:#0369a1; }

    /* ── Main grid ───────────────────────────────────────────── */
    .cgd-grid { display:grid; grid-template-columns:1fr 380px; gap:20px; }
    @media(max-width:900px){ .cgd-grid{ grid-template-columns:1fr; } }

    /* ── Panel ───────────────────────────────────────────────── */
    .cgd-panel { background:#fff; border-radius:16px; box-shadow:0 2px 14px rgba(0,0,0,.07); overflow:hidden; }
    .cgd-panel-hdr {
        padding:13px 20px; font-size:.78rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.08em;
        display:flex; align-items:center; justify-content:space-between; gap:8px;
    }
    .cgd-panel-body { padding:20px; }

    /* ── Device row ──────────────────────────────────────────── */
    .device-row {
        display:flex; align-items:center; justify-content:space-between;
        padding:14px 16px; border-radius:12px; margin-bottom:10px;
        background:linear-gradient(90deg,rgba(15,118,110,.08),rgba(20,184,166,.05));
        border:1px solid rgba(15,118,110,.12);
        gap:12px;
    }
    .device-name  { font-size:.95rem; font-weight:700; color:#0f172a; }
    .device-token { font-size:.72rem; color:#94a3b8; font-family:monospace; margin-top:2px; }

    /* ── Notification mini card ──────────────────────────────── */
    .notif-mini {
        padding:12px 14px; border-radius:10px; margin-bottom:8px;
        border:1.5px solid #f1f5f9; background:#fff;
        transition:border-color .1s;
    }
    .notif-mini.unread-cry { border-color:#fecdd3; background:#fff9f9; }
    .notif-mini.unread-dht { border-color:#bae6fd; background:#f0f9ff; }
    .notif-mini.unread-other{ border-color:#e0f2fe; background:#f8fafc; }
    .notif-mini-title { font-size:.85rem; font-weight:700; color:#0f172a; }
    .notif-mini-body  { font-size:.78rem; color:#64748b; margin-top:2px; line-height:1.45; }
    .notif-mini-time  { font-size:.72rem; color:#94a3b8; margin-top:4px; }

    /* ── Action buttons ──────────────────────────────────────── */
    .cgd-btn-primary {
        background:linear-gradient(135deg,#0f766e,#14b8a6);
        color:#fff; border:none; border-radius:8px;
        padding:8px 18px; font-size:.84rem; font-weight:700;
        text-decoration:none; display:inline-flex; align-items:center; gap:6px;
        transition:opacity .15s;
    }
    .cgd-btn-primary:hover { opacity:.88; color:#fff; }
    .cgd-btn-outline {
        background:#f8fafc; color:#334155; border:1.5px solid #e2e8f0;
        border-radius:8px; padding:8px 14px; font-size:.84rem; font-weight:700;
        text-decoration:none; display:inline-flex; align-items:center; gap:6px;
        transition:background .15s;
    }
    .cgd-btn-outline:hover { background:#e2e8f0; color:#334155; }

    /* ── Unread badge ────────────────────────────────────────── */
    .u-badge {
        background:#be123c; color:#fff; border-radius:20px;
        padding:1px 8px; font-size:.72rem; font-weight:700; line-height:1.4;
    }

    /* ── Empty state ─────────────────────────────────────────── */
    .cgd-empty { text-align:center; padding:28px 16px; color:#94a3b8; font-size:.85rem; }
</style>

<div class="cgd-page">

    
    <div class="cgd-header">
        <div>
            <h1 class="cgd-title">
                <span style="color:#0f766e;">&#9679;</span>
                Caregiver Dashboard
            </h1>
            <p class="cgd-sub">Welcome back, <?php echo e(auth()->user()->name); ?></p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <a href="<?php echo e(route('caregiver.reports')); ?>" class="cgd-btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Incident Reports
            </a>
            <a href="<?php echo e(route('caregiver.notifications')); ?>" class="cgd-btn-outline">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                Notifications
                <?php if($unreadCount > 0): ?>
                    <span class="u-badge"><?php echo e($unreadCount); ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    
    <div class="cgd-kpi">
        <div class="cgd-kpi-card k-teal">
            <div class="cgd-kpi-label">Assigned Devices</div>
            <div class="cgd-kpi-value"><?php echo e($devices->count()); ?></div>
            <div class="cgd-kpi-hint">Active cradles</div>
        </div>
        <div class="cgd-kpi-card k-rose">
            <div class="cgd-kpi-label">Unread Alerts</div>
            <div class="cgd-kpi-value"><?php echo e($unreadCount); ?></div>
            <div class="cgd-kpi-hint">Requiring attention</div>
        </div>
        <div class="cgd-kpi-card k-sky">
            <div class="cgd-kpi-label">Recent Notifications</div>
            <div class="cgd-kpi-value"><?php echo e($notifications->count()); ?></div>
            <div class="cgd-kpi-hint">Last 10 events</div>
        </div>
    </div>

    
    <div class="cgd-grid">

        
        <div class="cgd-panel">
            <div class="cgd-panel-hdr" style="background:linear-gradient(135deg,#0f766e,#14b8a6);color:#fff;">
                <span style="display:flex;align-items:center;gap:7px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Assigned Devices
                </span>
                <span style="font-size:.75rem;opacity:.7;font-weight:400;text-transform:none;letter-spacing:0;">
                    <?php echo e($devices->count()); ?> device<?php echo e($devices->count() !== 1 ? 's' : ''); ?>

                </span>
            </div>
            <div class="cgd-panel-body">
                <?php $__empty_1 = true; $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="device-row">
                        <div>
                            <div class="device-name"><?php echo e($device->device_name); ?></div>
                            <div class="device-token"><?php echo e($device->device_token); ?></div>
                        </div>
                        <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:3px 10px;font-size:.74rem;font-weight:700;">
                            Active
                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="cgd-empty">No devices assigned to you yet.</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="cgd-panel">
            <div class="cgd-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
                <span style="display:flex;align-items:center;gap:7px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    Recent Alerts
                </span>
                <?php if($unreadCount > 0): ?>
                    <span class="u-badge"><?php echo e($unreadCount); ?> new</span>
                <?php endif; ?>
            </div>
            <div class="cgd-panel-body" style="padding:14px 16px;">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $evt      = strtolower($note->event_type ?? '');
                        $isCry    = $evt === 'cry_detected';
                        $isDht    = $evt === 'dht';
                        $icon     = $isCry ? '🔔' : ($isDht ? '🌡️' : '⚠️');
                        $isUnread = is_null($note->read_at);
                        $cls      = '';
                        if ($isUnread) {
                            $cls = $isCry ? 'unread-cry' : ($isDht ? 'unread-dht' : 'unread-other');
                        }
                    ?>
                    <div class="notif-mini <?php echo e($cls); ?>">
                        <div class="notif-mini-title"><?php echo e($icon); ?> <?php echo e($note->title); ?></div>
                        <div class="notif-mini-body"><?php echo e(Str::limit($note->body, 100)); ?></div>
                        <div class="notif-mini-time">
                            <?php echo e($note->created_at?->diffForHumans()); ?>

                            <?php if($note->device): ?> &nbsp;·&nbsp; <?php echo e($note->device->device_name); ?> <?php endif; ?>
                            <?php if(!$isUnread): ?> &nbsp;·&nbsp; <span style="color:#15803d;">✓ read</span> <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="cgd-empty">No notifications yet.</div>
                <?php endif; ?>

                <?php if($notifications->count() > 0): ?>
                <div style="margin-top:12px;text-align:center;">
                    <a href="<?php echo e(route('caregiver.notifications')); ?>" class="cgd-btn-outline" style="font-size:.78rem;padding:6px 14px;">
                        View all notifications →
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/member/dashboard.blade.php ENDPATH**/ ?>