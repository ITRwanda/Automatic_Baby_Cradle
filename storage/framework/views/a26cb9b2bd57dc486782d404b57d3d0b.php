

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 text-info fw-bold">Family Parent Dashboard</h2>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body bg-info text-white">
                    <h5 class="mb-2">Assigned Devices</h5>
                    <h3 class="mb-0"><?php echo e($devices->count()); ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body bg-secondary text-white">
                    <h5 class="mb-2">Family Members</h5>
                    <h3 class="mb-0"><?php echo e($members->count()); ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body bg-warning text-dark">
                    <h5 class="mb-2">Alerts</h5>
                    <h3 class="mb-0"><?php echo e(isset($alerts) ? $alerts->count() : 0); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main grid -->
    <div class="row">
        <!-- Left: Devices + chart -->
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white fw-bold">Assigned Devices</div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-2">
                            <div>
                                <div class="fw-bold"><?php echo e($device->device_name); ?></div>
                                <div class="text-muted">Token: <?php echo e($device->device_token); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">No devices assigned yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold">Device Reports</div>
                <div class="card-body">
                    <canvas id="reportsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Right: Members + actions -->
        <div class="col-lg-5 mt-0 mt-lg-0">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white fw-bold">Family Members</div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap mb-3">
                            <a href="<?php echo e(route('family.members')); ?>" class="btn btn-outline-primary btn-sm">View Members</a>
                        <a href="<?php echo e(route('family.roles')); ?>" class="btn btn-outline-dark btn-sm">Assign Roles</a>
                    </div>

                    <hr>

                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        
                        <div class="border rounded p-3 mb-2">
                            <div class="fw-bold"><?php echo e($member->name); ?></div>
                            <div class="text-muted"><?php echo e($member->email ?? ''); ?></div>

                            <div class="mt-2">
                                <span class="badge bg-info">
                                    <?php echo e(is_string($member->role) ? $member->role : ($member->role->name ?? 'member')); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">No members added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Reports Chart (example: alerts per device)
    new Chart(document.getElementById('reportsChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($devices->pluck('device_name'), 15, 512) ?>,
            datasets: [{
                label: 'Alerts per Device',
                data: <?php echo json_encode($devices->map(fn($d)=>optional($d->alerts)->count() ?? 0), 15, 512) ?>,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255,193,7,0.3)',
                fill: true,
                tension: 0.3
            }]
        }
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/family/dashboard.blade.php ENDPATH**/ ?>