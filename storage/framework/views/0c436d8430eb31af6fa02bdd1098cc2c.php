

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Device Reports (Admin)</h2>
        <span class="text-muted small">Filter by family, dates, and search. Manage devices from here.</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white fw-semibold">Report Filters & Export</div>
        <div class="card-body">
            <?php
                $qs = request()->query();
                $qs = is_array($qs) ? $qs : [];
                $csvUrl = route('admin.deviceReports.exportCsv', $qs);
                $pdfUrl = route('admin.deviceReports.exportPdf', $qs);
            ?>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="text-muted small">Export the current filtered result</div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?php echo e($csvUrl); ?>" class="btn btn-success btn-sm fw-semibold">Export CSV</a>
                    <a href="<?php echo e($pdfUrl); ?>" class="btn btn-outline-danger btn-sm fw-semibold" target="_blank">Export PDF</a>
                </div>
            </div>

            <form class="row g-3" method="GET" action="<?php echo e(route('admin.deviceReports')); ?>">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Family</label>
                    <select name="family_id" class="form-select">
                        <option value="">All (assigned + unassigned)</option>
                        <?php $__currentLoopData = ($families ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($family->id); ?>" <?php echo e(request('family_id') == $family->id ? 'selected' : ''); ?>>
                                <?php echo e($family->family_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="q" class="form-control" placeholder="Device name or token" value="<?php echo e(request('q')); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">From</label>
                    <input type="date" name="from" class="form-control" value="<?php echo e(request('from')); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">To</label>
                    <input type="date" name="to" class="form-control" value="<?php echo e(request('to')); ?>">
                </div>

                <div class="col-md-12 d-flex gap-2 justify-content-end mt-2">
                    <button class="btn btn-primary" type="submit">Apply</button>
                    <a href="<?php echo e(route('admin.deviceReports')); ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white fw-semibold">Devices</div>
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
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($device->device_name); ?></td>
                                    <td class="text-muted"><?php echo e($device->device_token); ?></td>
                                    <td><?php echo e($device->family ? $device->family->family_name : 'Unassigned'); ?></td>
                                    <td class="text-muted"><?php echo e($device->created_at?->format('Y-m-d')); ?></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                                            
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#drEditDevice-<?php echo e($device->id); ?>"
                                            >
                                                Modify
                                            </button>

                                            
                                            <form
                                                method="POST"
                                                action="<?php echo e(route('admin.deleteDevice', $device->id)); ?>"
                                                onsubmit="return confirm('Delete device "<?php echo e(addslashes($device->device_name)); ?>"?')"
                                            >
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>

                                        
                                        <div class="modal fade" id="drEditDevice-<?php echo e($device->id); ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="<?php echo e(route('admin.updateDevice', $device->id)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modify Device</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Device name</label>
                                                                <input
                                                                    type="text"
                                                                    name="device_name"
                                                                    class="form-control"
                                                                    required
                                                                    maxlength="255"
                                                                    value="<?php echo e($device->device_name); ?>"
                                                                >
                                                            </div>
                                                            <div class="alert alert-info mb-0">
                                                                Token is fixed: <b><?php echo e($device->device_token); ?></b>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary fw-bold">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/device_reports.blade.php ENDPATH**/ ?>