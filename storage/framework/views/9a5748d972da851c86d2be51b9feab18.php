

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 text-primary">Edit Caregiver</h2>
        <a href="<?php echo e(route('family.caregivers')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('family.updateCaregiver', ['user_id' => $member->id])); ?>">

                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo e(old('name', $member->name)); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="<?php echo e(old('email', $member->email)); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span class="text-muted">(optional)</span></label>
                    <input type="password" name="password" class="form-control" minlength="6" placeholder="Leave blank to keep current password">
                </div>

                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/family/edit_caregiver.blade.php ENDPATH**/ ?>