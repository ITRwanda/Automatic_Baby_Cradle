

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <h2 class="mb-4 fw-bold text-dark">Admin Dashboard</h2>
    <!-- Quick Stats -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center bg-gradient-primary text-white rounded">
                <h6 class="text-uppercase fw-semibold">Total Families</h6>
                <h2 class="fw-bold"><?php echo e($families_total); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center bg-gradient-success text-white rounded">
                <h6 class="text-uppercase fw-semibold">Total Devices</h6>
                <h2 class="fw-bold"><?php echo e($devices_total); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center bg-gradient-info text-white rounded">
                <h6 class="text-uppercase fw-semibold">Total Users</h6>
                <h2 class="fw-bold"><?php echo e($users_total); ?></h2>
            </div>
        </div>
    </div>
        <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center bg-gradient-warning text-dark rounded">
                <h6 class="text-uppercase fw-semibold">Reports</h6>
                <h2 class="fw-bold"><?php echo e($reports_total); ?></h2>
            </div>
        </div>
    </div>

</div>





    <!-- Charts -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-semibold">Families Overview</div>
                <div class="card-body">
                    <canvas id="familiesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white fw-semibold">Devices Overview</div>
                <div class="card-body">
                    <canvas id="devicesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-info text-white fw-semibold">Device Assignments</div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Device</th>
                        <th>Token</th>
                        <th>Assigned Family</th>
                        <th>Members</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($device->device_name); ?></td>
                            <td><span class="badge bg-secondary"><?php echo e($device->device_token); ?></span></td>
                            <td><?php echo e($device->family ? $device->family->family_name : 'Unassigned'); ?></td>
                            <td>
                                <?php if($device->family): ?>
                                    <span class="badge bg-primary"><?php echo e($device->family->members->count()); ?> members</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // Render charts only if Chart.js + canvases exist
    (function () {
        const familiesCanvas = document.getElementById('familiesChart');
        const devicesCanvas = document.getElementById('devicesChart');

        if (!window.Chart) {
            console.warn('Chart.js not found.');
            return;
        }
        if (!familiesCanvas || !devicesCanvas) {
            console.warn('Chart canvases not found.');
            return;
        }

        // Families Chart
        new Chart(familiesCanvas, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($families->pluck('family_name'), 15, 512) ?>,
                datasets: [{
                    label: 'Members per Family',
                    data: <?php echo json_encode($families->map(fn($f) => $f->members->count()), 15, 512) ?>,
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Devices Chart
        new Chart(devicesCanvas, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($devices->pluck('device_name'), 15, 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($devices->map(fn($d) => $d->family_id ? 1 : 0), 15, 512) ?>,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    })();
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>