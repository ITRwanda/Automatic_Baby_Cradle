

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4 text-info fw-bold">Family Parent Dashboard</h2>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body bg-info text-white">
                    <h5>Assigned Devices</h5>
                    <h3><?php echo e($devices->count()); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body bg-secondary text-white">
                    <h5>Family Members</h5>
                    <h3><?php echo e($members->count()); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body bg-warning text-dark">
                    <h5>Alerts</h5>
                    <h3><?php echo e($alerts->count() ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Devices -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">Assigned Devices</div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <p><strong><?php echo e($device->device_name); ?></strong> 
                           <span class="badge bg-secondary">Token: <?php echo e($device->device_token); ?></span></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p>No devices assigned yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Family Members -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">Family Members</div>
                <div class="card-body">
                    <a href="<?php echo e(route('family.members')); ?>" class="btn btn-outline-primary mb-2">Add Members</a>
                    <a href="<?php echo e(route('family.roles')); ?>" class="btn btn-outline-dark mb-2">Assign Roles</a>
                    <hr>
                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <p><?php echo e($member->name); ?> - <span class="badge bg-info"><?php echo e($member->role); ?></span></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p>No members added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports -->
    <div class="card shadow mt-4">
        <div class="card-header bg-warning text-dark">Device Reports</div>
        <div class="card-body">
            <canvas id="reportsChart"></canvas>
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
                data: <?php echo json_encode($devices->map(fn($d)=>$d->alerts->count() ?? 0), 15, 512) ?>,
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