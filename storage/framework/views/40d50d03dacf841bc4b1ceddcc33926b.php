

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4 text-primary fw-bold">Profile Settings</h2>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-person-circle"></i> Personal Information
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" value="<?php echo e(auth()->user()->name); ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" value="<?php echo e(auth()->user()->email); ?>" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Password Update -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-lock-fill"></i> Security Settings
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('profile.password.update')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="card shadow mt-4">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle-fill"></i> Account Actions
        </div>
        <div class="card-body">
            <p class="text-muted">If you wish to deactivate your account, you can request it below.</p>
            <form method="POST" action="<?php echo e(route('profile.deactivate')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-danger">Deactivate Account</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/profile/settings.blade.php ENDPATH**/ ?>