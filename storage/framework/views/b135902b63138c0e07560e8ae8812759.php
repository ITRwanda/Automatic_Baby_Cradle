

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 text-primary">Family Members</h2>
        <a href="<?php echo e(route('family.dashboard')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Members</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="fw-semibold"><?php echo e($member->name); ?></td>
                                <td class="text-muted"><?php echo e($member->email ?? ''); ?></td>
                                <td>
                                    
                                    <span class="badge bg-info">
                                        <?php echo e(is_string($member->role) ? $member->role : ($member->role->name ?? 'member')); ?>

                                    </span>
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="<?php echo e(route('family.assignDeviceToMember')); ?>" class="d-inline-block" style="min-width: 280px;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="user_id" value="<?php echo e($member->id); ?>">
                                        <div class="row g-2 justify-content-end align-items-center">
                                            <div class="col-auto">
                                                <select name="device_id" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select device</option>
                                                    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($device->id); ?>"><?php echo e($device->device_name); ?><?php echo e($device->user_id ? ' (Assigned)' : ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button class="btn btn-sm btn-primary" type="submit">Assign</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="mt-2 d-inline-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-outline-secondary" aria-disabled="true" title="Edit member">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-danger" aria-disabled="true" title="Delete member">Delete</a>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-muted p-4 text-center">No family members found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<div class="card shadow-sm mt-4">
        <div class="card-header bg-white fw-bold">Add Member</div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('family.addMember')); ?>">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo e(old('name')); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="<?php echo e(old('email')); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span class="text-muted">(optional)</span></label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>

                <button class="btn btn-primary" type="submit">Add</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/family/members.blade.php ENDPATH**/ ?>