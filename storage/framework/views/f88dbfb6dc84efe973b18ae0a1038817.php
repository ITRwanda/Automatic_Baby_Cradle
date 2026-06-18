

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="mb-1 fw-bold" style="color:#0f172a;">Admin Console</h2>
            <p class="text-muted mb-0">Brilliant overview of families, devices, and incidents.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.reports')); ?>" class="btn btn-outline-dark fw-semibold shadow-sm">Assign devices</a>
            <a href="<?php echo e(route('admin.megaReports')); ?>" class="btn btn-dark fw-semibold shadow-sm">Mega report</a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #0b5ed7 0%, #4aa3ff 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Total Families</div>
                    <div class="display-6 fw-bold"><?php echo e($families_total); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #34d399 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Total Devices</div>
                    <div class="display-6 fw-bold"><?php echo e($devices_total); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #22d3ee 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Total Users</div>
                    <div class="display-6 fw-bold"><?php echo e($users_total); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #d97706 0%, #fbbf24 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Reports</div>
                    <div class="display-6 fw-bold"><?php echo e($reports_total); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color:white; font-weight:700;">
                    Families Overview
                </div>
                <div class="card-body" style="height:320px;">
                    <canvas id="familiesChart" height="320"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #16a34a 0%, #22d3ee 100%); color:white; font-weight:700;">
                    Devices Overview
                </div>
                <div class="card-body" style="height:320px;">
                    <canvas id="devicesChart" height="320"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #0ea5e9 0%, #111827 100%); color:white; font-weight:700;">
            Device Assignments
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
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
                                <td><span class="badge" style="background: rgba(148,163,184,.25); color:#0f172a; border: 1px solid rgba(148,163,184,.35);"><?php echo e($device->device_token); ?></span></td>
                                <td><?php echo e($device->family ? $device->family->family_name : 'Unassigned'); ?></td>
                                <td>
                                    <?php if($device->family): ?>
                                        <span class="badge bg-primary" style="border-radius:999px;"><?php echo e($device->family->members->count()); ?> members</span>
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