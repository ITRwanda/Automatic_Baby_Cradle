<?php
    $familiesCollection = $families ?? collect();
    $parentsCollection = $parents ?? collect();

    $globalUnassigned = 0;
    try {
        $globalUnassigned = ($allDevices ?? collect())->whereNull('family_id')->count();
    } catch (\Throwable $e) {
        $globalUnassigned = 0;
    }
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-info text-white fw-semibold d-flex align-items-center justify-content-between gap-2">
        <span>Existing Families</span>
        <span class="badge bg-dark">Unassigned devices: <?php echo e($globalUnassigned); ?></span>
    </div>

    <div class="card-body">
        <?php if($familiesCollection->count() === 0): ?>
            <div class="alert alert-warning mb-0">No families found.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sm">
                    <thead class="table-light">
                        <tr>
                            <th colspan="4" class="pt-0 pb-2">
                                <input
                                    id="familiesSearch"
                                    type="text"
                                    class="form-control form-control-sm"
                                    placeholder="Search families by name or parent..."
                                />
                            </th>
                        </tr>
                        <tr>
                            <th style="min-width: 220px;">Family</th>
                            <th style="width: 110px;">Members</th>
                            <th style="width: 220px;">Devices</th>
                            <th class="text-end" style="width: 320px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $familiesCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $parentName = optional($family->parent)->name ?? '—';
                                $searchParent = optional($family->parent)->name ?? '';
                                $searchParentEmail = optional($family->parent)->email ?? '';
                                $assignedCount = ($family->devices ?? collect())->count();
                            ?>

                            <tr data-family-search="<?php echo e(strtolower($family->family_name.' '.$searchParent.' '.$searchParentEmail)); ?>">
        <td>
                                    <div class="fw-semibold"><?php echo e($family->family_name); ?></div>
                                    <div class="text-muted small">
                                        Parent: <?php echo e($parentName); ?>

                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-primary"><?php echo e(($family->members ?? collect())->count()); ?></span>
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-success">Assigned: <?php echo e($assignedCount); ?></span>
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
                                            onsubmit="return confirm('Delete family <?php echo e(addslashes($family->family_name)); ?>? This will also delete its devices.')"
                                        >
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <script>
                (function () {
                    const input = document.getElementById('familiesSearch');
                    if (!input) return;

                    const rows = Array.from(document.querySelectorAll('tbody tr[data-family-search]'));
                    const handler = function () {
                        const q = (input.value || '').trim().toLowerCase();
                        rows.forEach(row => {
                            const text = (row.getAttribute('data-family-search') || '').toLowerCase();
                            row.style.display = (!q || text.includes(q)) ? '' : 'none';
                        });
                    };

                    input.addEventListener('input', handler);
                    handler();
                })();
            </script>

            
            <?php $__currentLoopData = $familiesCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
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
                                            <div class="form-control-plaintext">
                                                <?php echo e(optional($family->parent)->name ?? '—'); ?>

                                                <span class="text-muted">(<?php echo e(optional($family->parent)->email ?? ''); ?>)</span>
                                            </div>
                                            <input type="hidden" name="parent_id" value="<?php echo e($family->parent_id); ?>">
                                            <div class="text-muted small mt-1">
                                                Family parent cannot be changed.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Parent name</label>
                                                <input
                                                    type="text"
                                                    name="parent_name"
                                                    id="parentNameInput-<?php echo e($family->id); ?>"
                                                    class="form-control"
                                                    value="<?php echo e(optional($family->parent)->name); ?>"
                                                >
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Parent email</label>
                                                <input
                                                    type="email"
                                                    name="parent_email"
                                                    id="parentEmailInput-<?php echo e($family->id); ?>"
                                                    class="form-control"
                                                    value="<?php echo e(optional($family->parent)->email); ?>"
                                                >
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label fw-semibold">Parent password (optional)</label>
                                            <input
                                                type="password"
                                                name="parent_password"
                                                class="form-control mb-2"
                                                placeholder="Enter new password (optional)"
                                            >
                                            <input
                                                type="password"
                                                name="parent_password_confirmation"
                                                class="form-control"
                                                placeholder="Confirm new password (optional)"
                                            >
                                        </div>

                                        <div class="alert alert-info mt-3 mb-0">
                                            Admin can update parent account details (name/email/password optional) and the family name.
                                        </div>
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
                                                                onsubmit="return confirm('Unassign device <?php echo e(addslashes($device->device_name)); ?> from this family?')"
                                                            >
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="device_id" value="<?php echo e($device->id); ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-secondary">Unassign (family)</button>
                                                            </form>

                                                            
                                                            <form
                                                                method="POST"
                                                                action="<?php echo e(route('admin.unassignDeviceFromFamilyParent')); ?>"
                                                                onsubmit="return confirm('Unassign device <?php echo e(addslashes($device->device_name)); ?> from family parent?')"
                                                            >
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="device_id" value="<?php echo e($device->id); ?>">
                                                                <input type="hidden" name="family_id" value="<?php echo e($family->id); ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-warning">Unassign (parent)</button>
                                                            </form>

                                                            
                                                            <form
                                                                method="POST"
                                                                action="<?php echo e(route('admin.deleteDevice', $device->id)); ?>"
                                                                onsubmit="return confirm('Delete device <?php echo e(addslashes($device->device_name)); ?>? This cannot be undone.')"
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
                                                                    <div class="alert alert-info mb-0">Token is fixed: <b><?php echo e($device->device_token); ?></b></div>
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
        <?php endif; ?>
    </div>
</div>

<?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/_families_table_fixed.blade.php ENDPATH**/ ?>