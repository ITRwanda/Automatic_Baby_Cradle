

<?php $__env->startSection('content'); ?>
<?php
    $activities = $activities ?? collect();
    $total      = $activities->count();
    $cryCount   = $activities->where('event_type', 'cry_detected')->count();
    $dhtCount   = $activities->where('event_type', 'dht')->count();
    $devicesHit = $activities->pluck('device_id')->unique()->count();
    $dateMin    = $total ? $activities->min('created_at')?->format('d M Y') : null;
    $dateMax    = $total ? $activities->max('created_at')?->format('d M Y') : null;

    $topDevices = $activities
        ->groupBy(fn($a) => $a->device->device_name ?? 'Unknown')
        ->map(fn($g) => $g->count())
        ->sortDesc()
        ->take(5);

    $latestCry  = $activities->where('event_type','cry_detected')->first();
    $openAlerts = $activities->filter(fn($a) =>
        $a->event_type === 'cry_detected' ||
        (fn($p) => !empty(json_decode($p,true)['temp_alert']) || !empty(json_decode($p,true)['humid_alert']))($a->payload ?? '')
    )->count();

    $qs     = is_array(request()->query()) ? request()->query() : [];
    $csvUrl = route('caregiver.reports.exportCsv', $qs);
    $pdfUrl = route('caregiver.reports.exportPdf', $qs);
?>

<style>
    /* ── Page ──────────────────────────────────────────────── */
    .cg-page { max-width: 1380px; margin: 0 auto; padding: 0 4px; }

    /* ── Header ─────────────────────────────────────────────── */
    .cg-header { display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; justify-content:space-between; margin-bottom:28px; }
    .cg-title  { font-size:1.55rem; font-weight:800; color:#0f172a; margin:0; line-height:1.2; }
    .cg-sub    { font-size:.875rem; color:#64748b; margin:4px 0 0; }

    /* ── KPI cards ───────────────────────────────────────────── */
    .cg-kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(165px,1fr)); gap:16px; margin-bottom:28px; }
    .cg-kpi {
        border-radius:14px; padding:18px 20px;
        box-shadow:0 2px 12px rgba(0,0,0,.06);
        display:flex; flex-direction:column; gap:4px;
    }
    .cg-kpi-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; opacity:.65; }
    .cg-kpi-value { font-size:2rem; font-weight:800; line-height:1; }
    .cg-kpi-hint  { font-size:.75rem; opacity:.6; margin-top:2px; }

    .kpi-teal  { background:linear-gradient(135deg,#f0fdfa,#ccfbf1); color:#0f766e; }
    .kpi-rose  { background:linear-gradient(135deg,#fff1f2,#ffe4e6); color:#be123c; }
    .kpi-sky   { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); color:#0369a1; }
    .kpi-lime  { background:linear-gradient(135deg,#f7fee7,#ecfccb); color:#4d7c0f; }
    .kpi-slate { background:linear-gradient(135deg,#f8fafc,#f1f5f9); color:#334155; }

    /* ── Alert banner ─────────────────────────────────────────── */
    .alert-banner {
        background:linear-gradient(135deg,#fff1f2,#ffe4e6);
        border:1.5px solid #fecdd3; border-radius:12px;
        padding:14px 18px; margin-bottom:22px;
        display:flex; align-items:center; gap:12px;
    }
    .alert-banner-icon { font-size:1.4rem; flex-shrink:0; }
    .alert-banner-text { font-size:.875rem; color:#9f1239; font-weight:600; }
    .alert-banner-sub  { font-size:.78rem; color:#be123c; margin-top:2px; }

    /* ── Panels ──────────────────────────────────────────────── */
    .cg-panel { background:#fff; border-radius:16px; box-shadow:0 2px 14px rgba(0,0,0,.07); overflow:hidden; }
    .cg-panel-hdr {
        padding:13px 20px; font-size:.78rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.08em;
        display:flex; align-items:center; gap:8px;
    }
    .cg-panel-body { padding:20px; }

    /* ── Layout grid ─────────────────────────────────────────── */
    .cg-grid { display:grid; grid-template-columns:300px 1fr; gap:20px; align-items:start; margin-bottom:20px; }
    @media(max-width:860px){ .cg-grid{ grid-template-columns:1fr; } }

    /* ── Filter form ─────────────────────────────────────────── */
    .ff-label { font-size:.73rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#475569; margin-bottom:5px; display:block; }
    .ff-input {
        border:1.5px solid #e2e8f0; border-radius:8px;
        padding:7px 11px; font-size:.86rem; width:100%;
        transition:border-color .15s;
    }
    .ff-input:focus { border-color:#0f766e; outline:none; box-shadow:0 0 0 3px rgba(15,118,110,.1); }
    .btn-ff-apply {
        background:linear-gradient(135deg,#0f766e,#14b8a6);
        color:#fff; border:none; border-radius:8px;
        padding:8px 20px; font-size:.86rem; font-weight:700;
        cursor:pointer; transition:opacity .15s;
    }
    .btn-ff-apply:hover { opacity:.88; }
    .btn-ff-reset {
        background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0;
        border-radius:8px; padding:8px 14px; font-size:.86rem; font-weight:600;
        text-decoration:none; display:inline-block; transition:background .15s;
    }
    .btn-ff-reset:hover { background:#e2e8f0; }

    /* ── Export buttons ──────────────────────────────────────── */
    .cg-exp-row { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:0; }
    .cg-btn-exp {
        display:inline-flex; align-items:center; gap:6px;
        padding:7px 14px; border-radius:8px;
        font-size:.8rem; font-weight:700; text-decoration:none; border:none; cursor:pointer;
    }
    .cg-btn-csv { background:#16a34a; color:#fff; }
    .cg-btn-csv:hover { background:#15803d; color:#fff; }
    .cg-btn-pdf { background:#dc2626; color:#fff; }
    .cg-btn-pdf:hover { background:#b91c1c; color:#fff; }

    /* ── Breakdown bars ──────────────────────────────────────── */
    .bkd-row  { display:flex; flex-direction:column; gap:10px; }
    .bkd-lbl  { display:flex; justify-content:space-between; font-size:.82rem; font-weight:600; margin-bottom:3px; }
    .bkd-track{ height:7px; border-radius:99px; background:#f1f5f9; overflow:hidden; }
    .bkd-fill { height:100%; border-radius:99px; }

    /* ── Table ───────────────────────────────────────────────── */
    .cg-table { width:100%; border-collapse:collapse; }
    .cg-table thead tr { background:#f8fafc; border-bottom:2px solid #e8eaf0; }
    .cg-table thead th {
        padding:10px 14px; font-size:.72rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.07em;
        color:#64748b; white-space:nowrap;
    }
    .cg-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .1s; }
    .cg-table tbody tr:hover { background:#f8fafc; }
    .cg-table tbody td { padding:11px 14px; font-size:.875rem; vertical-align:middle; }

    /* ── Event badges ────────────────────────────────────────── */
    .ev-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:.74rem; font-weight:700; white-space:nowrap; }
    .ev-cry   { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; }
    .ev-dht   { background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd; }
    .ev-other { background:#f8fafc; color:#475569; border:1px solid #e2e8f0; }

    /* ── Sensor pills ────────────────────────────────────────── */
    .sp-row  { display:flex; flex-wrap:wrap; gap:6px; margin-top:4px; }
    .sp-pill { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:20px; font-size:.74rem; font-weight:600; }
    .sp-ok   { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .sp-warn { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
    .sp-crit { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; }

    /* ── Empty state ─────────────────────────────────────────── */
    .cg-empty { text-align:center; padding:48px 24px; color:#94a3b8; }
    .cg-empty p { margin:8px 0 0; font-size:.925rem; }

    .cg-divider { border:none; border-top:1px solid #f1f5f9; margin:14px 0; }
</style>

<div class="cg-page">

    
    <div class="cg-header">
        <div>
            <h1 class="cg-title">
                <span style="color:#0f766e;">&#9679;</span>
                Caregiver Incident Report
            </h1>
            <p class="cg-sub">Sensor events from devices assigned to you — <?php echo e(auth()->user()->name); ?></p>
        </div>
        <div class="cg-exp-row">
            <a href="<?php echo e($csvUrl); ?>" class="cg-btn-exp cg-btn-csv">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
            <a href="<?php echo e($pdfUrl); ?>" class="cg-btn-exp cg-btn-pdf" target="_blank">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Export PDF
            </a>
            <a href="<?php echo e(route('caregiver.dashboard')); ?>" class="btn-ff-reset" style="display:inline-flex;align-items:center;">
                ← Dashboard
            </a>
        </div>
    </div>

    
    <?php if($openAlerts > 0): ?>
    <div class="alert-banner">
        <div class="alert-banner-icon">🚨</div>
        <div>
            <div class="alert-banner-text"><?php echo e($openAlerts); ?> alert<?php echo e($openAlerts > 1 ? 's' : ''); ?> require attention</div>
            <div class="alert-banner-sub">
                <?php if($latestCry): ?>
                    Last cry detected: <?php echo e($latestCry->created_at?->diffForHumans()); ?>

                    (<?php echo e($latestCry->device->device_name ?? '—'); ?>)
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="cg-kpi-grid">
        <div class="cg-kpi kpi-teal">
            <div class="cg-kpi-label">Total Events</div>
            <div class="cg-kpi-value"><?php echo e($total); ?></div>
            <div class="cg-kpi-hint">Current filter</div>
        </div>
        <div class="cg-kpi kpi-rose">
            <div class="cg-kpi-label">Cry Alerts</div>
            <div class="cg-kpi-value"><?php echo e($cryCount); ?></div>
            <div class="cg-kpi-hint">Sound incidents</div>
        </div>
        <div class="cg-kpi kpi-sky">
            <div class="cg-kpi-label">DHT Events</div>
            <div class="cg-kpi-value"><?php echo e($dhtCount); ?></div>
            <div class="cg-kpi-hint">Temp / humidity</div>
        </div>
        <div class="cg-kpi kpi-lime">
            <div class="cg-kpi-label">Devices</div>
            <div class="cg-kpi-value"><?php echo e($devicesHit); ?></div>
            <div class="cg-kpi-hint">In this period</div>
        </div>
        <div class="cg-kpi kpi-slate">
            <div class="cg-kpi-label">Period</div>
            <div class="cg-kpi-value" style="font-size:1rem; line-height:1.4;">
                <?php if($dateMin): ?>
                    <?php echo e($dateMin); ?><br>
                    <span style="font-size:.8rem; opacity:.6;">to <?php echo e($dateMax); ?></span>
                <?php else: ?>
                    —
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="cg-grid">

        
        <div style="display:flex; flex-direction:column; gap:20px;">

            
            <div class="cg-panel">
                <div class="cg-panel-hdr" style="background:linear-gradient(135deg,#0f766e,#14b8a6);color:#fff;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Filters
                </div>
                <div class="cg-panel-body">
                    <form method="GET" action="<?php echo e(route('caregiver.reports')); ?>" style="display:flex;flex-direction:column;gap:14px;">
                        <div>
                            <label class="ff-label">Device search</label>
                            <input class="ff-input" type="text" name="q" placeholder="Name or token…" value="<?php echo e(request('q')); ?>">
                        </div>
                        <div>
                            <label class="ff-label">From date</label>
                            <input class="ff-input" type="date" name="from" value="<?php echo e(request('from')); ?>">
                        </div>
                        <div>
                            <label class="ff-label">To date</label>
                            <input class="ff-input" type="date" name="to" value="<?php echo e(request('to')); ?>">
                        </div>
                        <div style="display:flex;gap:8px;margin-top:4px;">
                            <button type="submit" class="btn-ff-apply">Apply</button>
                            <a href="<?php echo e(route('caregiver.reports')); ?>" class="btn-ff-reset">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            
            <?php if($total > 0): ?>
            <div class="cg-panel">
                <div class="cg-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Device Breakdown
                </div>
                <div class="cg-panel-body">
                    <div class="bkd-row">
                        <?php $__currentLoopData = $topDevices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $cnt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $pct = $total > 0 ? round(($cnt/$total)*100) : 0; ?>
                        <div>
                            <div class="bkd-lbl"><span><?php echo e($name); ?></span><span style="color:#64748b;"><?php echo e($cnt); ?></span></div>
                            <div class="bkd-track">
                                <div class="bkd-fill" style="width:<?php echo e($pct); ?>%; background:linear-gradient(90deg,#0f766e,#2dd4bf);"></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <hr class="cg-divider" style="margin-top:18px;">

                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:10px;">Event Types</div>
                    <?php $__currentLoopData = $activities->groupBy(fn($a)=>$a->event_type??'unknown')->map(fn($g)=>$g->count())->sortDesc()->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $el => $ec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $ep = $total > 0 ? round(($ec/$total)*100) : 0;
                        $ecolor = $el==='cry_detected'
                            ? 'linear-gradient(90deg,#be123c,#f43f5e)'
                            : ($el==='dht'
                                ? 'linear-gradient(90deg,#0369a1,#38bdf8)'
                                : 'linear-gradient(90deg,#475569,#94a3b8)');
                    ?>
                    <div style="margin-bottom:8px;">
                        <div class="bkd-lbl"><span><?php echo e($el); ?></span><span style="color:#64748b;"><?php echo e($ec); ?></span></div>
                        <div class="bkd-track"><div class="bkd-fill" style="width:<?php echo e($ep); ?>%;background:<?php echo e($ecolor); ?>;"></div></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

        
        <div class="cg-panel" style="min-width:0;">
            <div class="cg-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;justify-content:space-between;">
                <span style="display:flex;align-items:center;gap:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Live Incident Feed
                </span>
                <span style="font-size:.75rem;opacity:.6;font-weight:400;text-transform:none;letter-spacing:0;">
                    <?php echo e($total); ?> record<?php echo e($total !== 1 ? 's' : ''); ?>

                </span>
            </div>
            <div class="cg-panel-body" style="padding:0;">
                <?php if($total === 0): ?>
                    <div class="cg-empty">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22c5.52 0 10-4.48 10-10S17.52 2 12 2 2 6.48 2 12s4.48 10 10 10z"/><path d="M9 9h.01M15 9h.01M8 13s1 2 4 2 4-2 4-2"/></svg>
                        <p>No incidents found — all clear!</p>
                    </div>
                <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="cg-table">
                        <thead>
                            <tr>
                                <th style="width:150px;">Time</th>
                                <th style="width:180px;">Device</th>
                                <th style="width:130px;">Event</th>
                                <th>Sensor Readings</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $evt     = $activity->event_type ?? '';
                                $payload = $activity->payload;
                                $decoded = null;
                                if (is_string($payload)) {
                                    $t = trim($payload);
                                    if (str_starts_with($t, '{')) $decoded = json_decode($t, true);
                                }
                                $temp      = $decoded['temperature'] ?? null;
                                $hum       = $decoded['humidity']    ?? null;
                                $sound     = $decoded['sound_level'] ?? null;
                                $tAlert    = !empty($decoded['temp_alert']);
                                $hAlert    = !empty($decoded['humid_alert']);
                                $isAlert   = $evt === 'cry_detected' || $tAlert || $hAlert;
                            ?>
                            <tr style="<?php echo e($isAlert ? 'background:#fff9f9;' : ''); ?>">
                                <td>
                                    <div style="font-weight:600;color:#0f172a;">
                                        <?php echo e($activity->created_at?->format('d M Y')); ?>

                                    </div>
                                    <div style="font-size:.75rem;color:#94a3b8;">
                                        <?php echo e($activity->created_at?->format('H:i:s')); ?>

                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:600;color:#0f172a;">
                                        <?php echo e($activity->device->device_name ?? '—'); ?>

                                    </div>
                                    <div style="font-size:.72rem;color:#94a3b8;font-family:monospace;">
                                        <?php echo e(Str::limit($activity->device->device_token ?? '', 18)); ?>

                                    </div>
                                </td>
                                <td>
                                    <?php if($evt === 'cry_detected'): ?>
                                        <span class="ev-badge ev-cry">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                                            Cry Detected
                                        </span>
                                    <?php elseif($evt === 'dht'): ?>
                                        <span class="ev-badge ev-dht">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                            DHT Sensor
                                        </span>
                                    <?php else: ?>
                                        <span class="ev-badge ev-other"><?php echo e($evt ?: 'Event'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($decoded): ?>
                                        <div class="sp-row">
                                            <?php if($temp !== null): ?>
                                                <span class="sp-pill <?php echo e($tAlert ? 'sp-crit' : 'sp-ok'); ?>">
                                                    🌡 <?php echo e($temp); ?>°C <?php if($tAlert): ?> ⚠ <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($hum !== null): ?>
                                                <span class="sp-pill <?php echo e($hAlert ? 'sp-warn' : 'sp-ok'); ?>">
                                                    💧 <?php echo e($hum); ?>% <?php if($hAlert): ?> ⚠ <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($sound !== null): ?>
                                                <span class="sp-pill sp-crit">🔊 <?php echo e($sound); ?></span>
                                            <?php endif; ?>
                                            <?php if(!$tAlert && !$hAlert && $sound === null && ($temp !== null || $hum !== null)): ?>
                                                <span class="sp-pill sp-ok">✓ Normal</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif($payload): ?>
                                        <span style="font-size:.8rem;color:#64748b;"><?php echo e(Str::limit($payload, 80)); ?></span>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/member/reports.blade.php ENDPATH**/ ?>