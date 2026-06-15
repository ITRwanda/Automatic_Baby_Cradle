

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 fw-bold">Family Reports</h2>
        <span class="badge bg-info">Assigned Devices</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark fw-semibold">Devices assigned to this family</div>
        <div class="card-body">
            <?php if(($devices ?? collect())->count() === 0): ?>
                <div class="alert alert-warning mb-0">No devices assigned to this family.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Device</th>
                                <th>Token</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($device->device_name); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo e($device->device_token); ?></span></td>
                                    <td>
                                        <?php if($device->user_id): ?>
                                            <?php ($assignedMember = \App\Models\User::find($device->user_id)); ?>
                                            <span class="badge bg-success">Assigned: <?php echo e($assignedMember?->name ?? 'Unknown'); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
            <td class="text-end">
                                        <form method="POST" action="<?php echo e(route('family.unassignDeviceFromMember')); ?>" onsubmit="return confirm('Unassign this device from the member?');">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="device_id" value="<?php echo e($device->id); ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Unassign</button>
                                        </form>
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