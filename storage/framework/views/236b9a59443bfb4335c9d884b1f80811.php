<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IoT Baby Monitor</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-light">
    <div class="d-flex">
        
        <?php if(auth()->guard()->check()): ?>
            <div class="sidebar d-flex flex-column p-3">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand mb-4 text-decoration-none">
                    IoT Baby Monitor
                </a>
                <ul class="nav nav-pills flex-column mb-auto">
                    
                    <?php if(auth()->user()->role && auth()->user()->role->name === 'admin'): ?>
                        <li>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.families')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.families') ? 'active' : ''); ?>">Families</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.devices')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.devices') ? 'active' : ''); ?>">Devices</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.deviceReports')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.deviceReports') ? 'active' : ''); ?>">Device Reports</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.familyReports')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.familyReports') ? 'active' : ''); ?>">Family Reports</a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('admin.megaReports')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.megaReports') ? 'active' : ''); ?>">Mega / Incident Report</a>
                        </li>

                        <li class="mt-3 px-1">
                            <div class="text-white-50 small mb-2">Quick actions</div>
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('admin.devices')); ?>" class="btn btn-light btn-sm text-dark shadow-sm">Register device</a>
                                <a href="<?php echo e(route('admin.families')); ?>" class="btn btn-light btn-sm text-dark shadow-sm">Create family</a>
                                <a href="<?php echo e(route('admin.reports')); ?>" class="btn btn-light btn-sm text-dark shadow-sm">Assign devices</a>
                                <a href="<?php echo e(route('admin.megaReports')); ?>" class="btn btn-light btn-sm text-dark shadow-sm">Mega report</a>

                            </div>
                        </li>

                    <?php elseif(auth()->user()->role && auth()->user()->role->name === 'family_parent'): ?>
                        <li><a href="<?php echo e(route('family.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('family.dashboard') ? 'active' : ''); ?>">Dashboard</a></li>
                        <li><a href="<?php echo e(route('family.caregivers')); ?>" class="nav-link">Caregivers</a></li>


                        <li><a href="<?php echo e(route('family.reports')); ?>" class="nav-link <?php echo e(request()->routeIs('family.reports') ? 'active' : ''); ?>">Reports</a></li>
                    <?php elseif(auth()->user()->role && auth()->user()->role->name === 'caregiver'): ?>
                        <li><a href="<?php echo e(route('caregiver.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('caregiver.dashboard') ? 'active' : ''); ?>">Dashboard</a></li>
                        <li><a href="<?php echo e(route('caregiver.reports')); ?>" class="nav-link <?php echo e(request()->routeIs('caregiver.reports') ? 'active' : ''); ?>">Reports</a></li>
                        <li><a href="<?php echo e(route('caregiver.notifications')); ?>" class="nav-link <?php echo e(request()->routeIs('caregiver.notifications') ? 'active' : ''); ?>">Notifications</a></li>


                    <?php endif; ?>




                    <li><a href="<?php echo e(route('profile.settings')); ?>" class="nav-link <?php echo e(request()->routeIs('profile.settings') ? 'active' : ''); ?>">Profile</a></li>
                    <li>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="nav-link w-100 text-start bg-transparent border-0 text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        <?php endif; ?>

        
        <div class="flex-grow-1 p-4">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    
</body>
</html>
<?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/layouts/app.blade.php ENDPATH**/ ?>