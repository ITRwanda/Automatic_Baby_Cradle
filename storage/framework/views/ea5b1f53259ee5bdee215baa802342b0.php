

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Admin Reports</h2>
        <div class="text-muted small">Filter devices by family and export</div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white fw-semibold">Report Filters</div>
        <div class="card-body">

            <form class="row g-3" method="GET" action="<?php echo e(route('admin.reports')); ?>">

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Family</label>
                    <select name="family_id" class="form-select">
                        <option value="">All families</option>
                        <?php $__currentLoopData = ($families ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($family->id); ?>" <?php echo e(request('family_id') == $family->id ? 'selected' : ''); ?>>
                                <?php echo e($family->family_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search device</label>
                    <input type="text" name="q" class="form-control" placeholder="Device name or token" value="<?php echo e(request('q')); ?>">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100" type="submit">Apply</button>
                </div>
            </form>

            <div class="mt-3 d-flex flex-wrap gap-2">

            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white fw-semibold">Device Report</div>
        <div class="card-body">
            <?php if(($devices ?? collect())->count() === 0): ?>
                <div class="alert alert-warning mb-0">No devices found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Token</th>
                                <th>Family</th>
                                <th>Assigned?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($device->device_name); ?></td>
                                    <td class="text-muted"><?php echo e($device->device_token); ?></td>
                                    <td><?php echo e($device->family ? $device->family->family_name : 'Unassigned'); ?></td>
                                    <td>
                                        <?php if($device->family): ?>
                                            <span class="badge bg-success">Yes</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No</span>
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


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/reports_filtered.blade.php ENDPATH**/ ?>