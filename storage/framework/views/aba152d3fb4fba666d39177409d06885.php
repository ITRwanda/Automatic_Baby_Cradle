

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold text-dark">Families Management</h2>
        <span class="badge bg-primary fs-6">Admin</span>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white fw-semibold">
                    + Register New Family
                </div>

                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Admin registers a family and assigns a family parent.
                    </p>

                    <form method="POST" action="<?php echo e(route('admin.createFamily')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Family name</label>
                            <input
                                type="text"
                                name="family_name"
                                class="form-control"
                                required
                                maxlength="255"
                                placeholder="e.g. Johnson Family"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Family parent</label>
                            <select name="parent_id" class="form-select" required>
                                <option value="">Select parent</option>
                                <?php $__currentLoopData = ($parents ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($parent->id); ?>">
                                        <?php echo e($parent->name); ?> (<?php echo e($parent->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            Create Family
                        </button>
                    </form>

                    <hr class="my-4"/>

                    <div class="alert alert-info mb-0">
                        Tip: After creating a family, click <b>Assign devices</b> on the right.
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white fw-semibold">
                    Existing Families
                </div>

                <div class="card-body">
                    <?php if(($families ?? collect())->count() === 0): ?>
                        <div class="alert alert-warning mb-0">No families found.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 220px;">Family</th>
                                        <th style="width: 110px;">Members</th>
                                        <th style="width: 220px;">Devices</th>
                                        <th class="text-end" style="width: 320px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $families; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $assignedDevices = $family->devices ?? collect();
                                        $assignedCount = $assignedDevices->count();

                                        $unassignedCount = 0;
                                        try {
                                            $unassignedCount = ($allDevices ?? collect())->whereNull('family_id')->count();
                                        } catch (\Throwable $e) {
                                            $unassignedCount = 0;
                                        }

                                        $parentName = optional($family->parent)->name ?? '—';
                                    ?>

                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($family->family_name); ?></div>
                                            <div class="text-muted small">Parent: <?php echo e($parentName); ?></div>
                                        </td>

                                        <td>
                                            <span class="badge bg-primary"><?php echo e(($family->members ?? collect())->count()); ?></span>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge bg-success">Assigned: <?php echo e($assignedCount); ?></span>
                                                <span class="badge bg-secondary">Unassigned: <?php echo e($unassignedCount); ?></span>
                                            </div>
                                        </td>

                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                
                                                <button
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editFamilyModal-<?php echo e($family->id); ?>"
                                                >
                                                    Modify
                                                </button>

                                                
                                                <button
                                                    class="btn btn-sm btn-outline-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#assignDevicesModal-<?php echo e($family->id); ?>"
                                                >
                                                    Assign devices
                                                </button>

                                                
                                                <form
                                                    method="POST"
                                                    action="<?php echo e(route('admin.deleteFamily', $family->id)); ?>"
                                                    onsubmit="return confirm('Delete family "<?php echo e(addslashes($family->family_name)); ?>"? This will also delete its devices.')"
                                                >
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    
                                    <div class="modal fade" id="editFamilyModal-<?php echo e($family->id); ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form method="POST" action="<?php echo e(route('admin.updateFamily', $family->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modify Family</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Family name</label>
                                                                <input
                                                                    type="text"
                                                                    name="family_name"
                                                                    class="form-control"
                                                                    required
                                                                    maxlength="255"
                                                                    value="<?php echo e($family->family_name); ?>"
                                                                >
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Family parent</label>
                                                                <select name="parent_id" class="form-select" required>
                                                                    <?php $__currentLoopData = ($parents ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option
                                                                            value="<?php echo e($parent->id); ?>"
                                                                            <?php echo e($family->parent_id == $parent->id ? 'selected' : ''); ?>

                                                                        >
                                                                            <?php echo e($parent->name); ?> (<?php echo e($parent->email); ?>)
                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="modal fade" id="assignDevicesModal-<?php echo e($family->id); ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div>
                                                        <h5 class="modal-title">Assign devices to: <?php echo e($family->family_name); ?></h5>
                                                        <div class="text-muted small">Only unassigned devices are shown.</div>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <?php
                                                        $unassignedDevices = ($allDevices ?? collect())->whereNull('family_id');
                                                        $assignedDevicesNow = $family->devices ?? collect();
                                                    ?>

                                                    
                                                    <?php if($unassignedDevices->count() === 0): ?>
                                                        <div class="alert alert-warning mb-0">No unassigned devices available.</div>
                                                    <?php else: ?>
                                                        <div class="mb-4">
                                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                                <h6 class="mb-0 fw-bold">Unassigned devices</h6>
                                                                <span class="text-muted small"><?php echo e($unassignedDevices->count()); ?> available</span>
                                                            </div>

                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-hover align-middle">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Device</th>
                                                                            <th>Token</th>
                                                                            <th class="text-end">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php $__currentLoopData = $unassignedDevices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr>
                                                                            <td class="fw-semibold"><?php echo e($device->device_name); ?></td>
                                                                            <td><span class="badge bg-secondary"><?php echo e($device->device_token); ?></span></td>
                                                                            <td class="text-end">
                                                                                <form method="POST" action="<?php echo e(route('admin.assignDevice')); ?>">
                                                                                    <?php echo csrf_field(); ?>
                                                                                    <input type="hidden" name="device_id" value="<?php echo e($device->id); ?>">
                                                                                    <input type="hidden" name="family_id" value="<?php echo e($family->id); ?>">
                                                                                    <button type="submit" class="btn btn-sm btn-success fw-bold">Assign</button>
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <hr class="my-4"/>

                                                    
                                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                                        <h6 class="mb-0 fw-bold">Assigned devices (manage)</h6>
                                                        <span class="text-muted small"><?php echo e($assignedDevicesNow->count()); ?> assigned</span>
                                                    </div>

                                                    <?php if($assignedDevicesNow->count() === 0): ?>
                                                        <div class="alert alert-secondary mb-0">No devices assigned yet.</div>
                                                    <?php else: ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover align-middle">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Device</th>
                                                                        <th>Token</th>
                                                                        <th class="text-end">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php $__currentLoopData = $assignedDevicesNow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td class="fw-semibold"><?php echo e($device->device_name); ?></td>
                                                                        <td><span class="badge bg-secondary"><?php echo e($device->device_token); ?></span></td>
                                                                        <td class="text-end">
                                                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                                                
                                                                                <button
                                                                                    class="btn btn-sm btn-outline-primary"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#editDeviceModal-<?php echo e($device->id); ?>"
                                                                                >
                                                                                    Modify
                                                                                </button>

                                                                                
                                                                                <form
                                                                                    method="POST"
                                                                                    action="<?php echo e(route('admin.unassignDevice')); ?>"
                                                                                    onsubmit="return confirm('Unassign device "<?php echo e(addslashes($device->device_name)); ?>" from this family?')"
                                                                                >
                                                                                    <?php echo csrf_field(); ?>
                                                                                    <input type="hidden" name="device_id" value="<?php echo e($device->id); ?>">
                                                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Unassign</button>
                                                                                </form>

                                                                                
                                                                                <form
                                                                                    method="POST"
                                                                                    action="<?php echo e(route('admin.deleteDevice', $device->id)); ?>"
                                                                                    onsubmit="return confirm('Delete device "<?php echo e(addslashes($device->device_name)); ?>"? This cannot be undone.')"
                                                                                >
                                                                                    <?php echo csrf_field(); ?>
                                                                                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    
                                                                    <div class="modal fade" id="editDeviceModal-<?php echo e($device->id); ?>" tabindex="-1" aria-hidden="true">
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
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/families.blade.php ENDPATH**/ ?>