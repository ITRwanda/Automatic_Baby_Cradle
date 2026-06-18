

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Caregiver Notifications</h2>
            <p class="text-muted mb-0">Safety alerts and caregiver updates will appear here.</p>
        </div>
        <a href="<?php echo e(route('caregiver.dashboard')); ?>" class="btn btn-outline-primary fw-semibold shadow-sm">Dashboard</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #198754 0%, #34d399 100%); color:white; font-weight:700;">
                    Latest Alerts
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-3 mb-3 rounded-3" style="background: linear-gradient(90deg, rgba(16,185,129,.10) 0%, rgba(34,211,238,.08) 100%); border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div>
                                    <div class="badge bg-success">Alert</div>
                                    <div class="mt-2 fw-semibold"><?php echo e($note->message ?? $note); ?></div>
                                </div>
                                <div class="text-muted small"><?php echo e($note->created_at?->format('Y-m-d H:i')); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="alert alert-light border" style="background:#f0fff7; border-color: rgba(16,185,129,.20); border-radius:16px; margin-bottom:0;">
                            No notifications yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #0ea5e9 0%, #22d3ee 100%); color:white; font-weight:700;">
                    Status
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="text-muted">Device coverage</div>
                        <div class="fw-bold">Assigned</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="text-muted">Alert policy</div>
                        <div class="fw-bold">Active</div>
                    </div>
                    <div class="p-3 rounded-3" style="background: rgba(99,102,241,.08); border:1px solid rgba(0,0,0,.06);">
                        <div class="small text-muted">Next step</div>
                        <div class="fw-semibold">Connect real notification events (DB/WebSocket) later.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/member/notifications.blade.php ENDPATH**/ ?>