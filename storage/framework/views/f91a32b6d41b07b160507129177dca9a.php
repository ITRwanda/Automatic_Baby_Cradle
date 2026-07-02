

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Family Reports (Incidents)</h2>
        <span class="text-muted small">Mega / general incident report restricted to your family devices.</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white fw-semibold">Filters & Export</div>
        <div class="card-body">
            <?php
                $qs = request()->query();
                $qs = is_array($qs) ? $qs : [];
                $csvUrl = route('family.reports.exportCsv', $qs);
                $pdfUrl = route('family.reports.exportPdf', $qs);
            ?>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="text-muted small">Export the current filtered result</div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?php echo e($csvUrl); ?>" class="btn btn-success btn-sm fw-semibold">Export CSV</a>
                    <a href="<?php echo e($pdfUrl); ?>" class="btn btn-outline-danger btn-sm fw-semibold" target="_blank">Export PDF</a>
                </div>
            </div>

            <form method="GET" action="<?php echo e(route('family.reports')); ?>" class="row g-3">

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Device search</label>
                    <input type="text" name="q" class="form-control" placeholder="Device name or token" value="<?php echo e(request('q')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">From</label>
                    <input type="date" name="from" class="form-control" value="<?php echo e(request('from')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">To</label>
                    <input type="date" name="to" class="form-control" value="<?php echo e(request('to')); ?>">
                </div>

                <div class="col-md-12 d-flex gap-2 justify-content-end">
                    <button class="btn btn-primary" type="submit">Apply</button>
                    <a href="<?php echo e(route('family.reports')); ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-semibold">Incidents</div>
        <div class="card-body">
            <?php if(($activities ?? collect())->count() === 0): ?>
                <div class="alert alert-warning mb-0">No incident activities found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>Time</th>
                            <th>Device</th>
                            <th>Family</th>
                            <th>Event</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-muted"><?php echo e($activity->created_at?->format('Y-m-d H:i')); ?></td>
                                <td class="fw-semibold"><?php echo e($activity->device->device_name ?? '—'); ?></td>
                                <td><?php echo e(optional($activity->device->family)->family_name ?? 'Unassigned'); ?></td>
                                <td>
                                    <?php if(!empty($activity->event_type)): ?>
                                        <span class="badge bg-primary"><?php echo e($activity->event_type); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Incident recorded</span>
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
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/family/reports.blade.php ENDPATH**/ ?>