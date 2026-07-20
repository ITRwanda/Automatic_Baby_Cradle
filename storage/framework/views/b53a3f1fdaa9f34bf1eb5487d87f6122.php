

<?php $__env->startSection('content'); ?>
<?php
    $members = $members ?? collect();
    $devices = $devices ?? collect();
?>

<style>
.cg-wrap  { max-width:1100px; margin:0 auto; }
.cg-panel { background:#fff; border-radius:16px; box-shadow:0 2px 14px rgba(0,0,0,.07); overflow:hidden; margin-bottom:22px; }
.cg-hdr   { padding:14px 22px; font-size:.8rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.08em; display:flex; align-items:center; justify-content:space-between; }
.cg-tbl { width:100%; border-collapse:collapse; }
.cg-tbl thead tr { background:#f8fafc; border-bottom:2px solid #e2e8f0; }
.cg-tbl thead th { padding:10px 16px; font-size:.72rem; font-weight:700; text-transform:uppercase;
                   letter-spacing:.07em; color:#64748b; white-space:nowrap; }
.cg-tbl tbody tr { border-bottom:1px solid #f1f5f9; }
.cg-tbl tbody tr:hover { background:#f8fafc; }
.cg-tbl tbody td { padding:12px 16px; font-size:.875rem; vertical-align:top; }
.cg-av { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#006633,#14b8a6);
         display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800;
         font-size:.88rem; flex-shrink:0; }
.btn-sm-act { display:inline-flex; align-items:center; gap:4px; padding:5px 11px; border-radius:7px;
              font-size:.78rem; font-weight:700; cursor:pointer; border:1.5px solid;
              background:#fff; transition:opacity .15s; text-decoration:none; white-space:nowrap; }
.btn-sm-act:hover { opacity:.82; }
.btn-edit   { color:#1d4ed8; border-color:#bfdbfe; }
.btn-edit:hover   { background:#eff6ff; }
.btn-del    { color:#be123c; border-color:#fecdd3; }
.btn-del:hover    { background:#fff1f2; }
.btn-assign { color:#15803d; border-color:#bbf7d0; }
.btn-assign:hover { background:#f0fdf4; }
.btn-unassign { color:#b45309; border-color:#fde68a; }
.btn-unassign:hover { background:#fffbeb; }
.ff-label { font-size:.73rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em;
            color:#475569; margin-bottom:5px; display:block; }
.ff-input { border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 12px;
            font-size:.86rem; width:100%; transition:border-color .15s; }
.ff-input:focus { border-color:#006633; outline:none; box-shadow:0 0 0 3px rgba(0,102,51,.1); }
.ff-sel { border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 12px;
          font-size:.86rem; width:100%; background:#fff; }
.btn-submit { background:linear-gradient(135deg,#006633,#009944); color:#fff; border:none;
              border-radius:8px; padding:9px 22px; font-size:.88rem; font-weight:700; cursor:pointer; }
.btn-cancel-modal { background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0; border-radius:8px;
                    padding:9px 16px; font-size:.88rem; font-weight:600; }
/* device tag pills */
.dev-tag { display:inline-flex; align-items:center; gap:5px; background:#f0fdf4;
           color:#15803d; border:1px solid #bbf7d0; border-radius:20px;
           padding:3px 9px; font-size:.74rem; font-weight:600; margin:2px; }
.dev-tag form { display:inline; margin:0; padding:0; }
.dev-tag button { background:none; border:none; color:#be123c; cursor:pointer;
                  font-size:.8rem; padding:0 0 0 3px; line-height:1; font-weight:700; }
.dev-tag button:hover { color:#9f1239; }
</style>

<div class="cg-wrap">

    <div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:24px;">
        <div>
            <h1 style="font-size:1.4rem;font-weight:800;color:#0f172a;margin:0;">
                <span style="color:#006633;">&#9679;</span> Caregivers
            </h1>
            <p style="font-size:.875rem;color:#64748b;margin:4px 0 0;">
                Manage caregivers — one device can be assigned to multiple caregivers
            </p>
        </div>
        <a href="<?php echo e(route('family.dashboard')); ?>"
           style="background:#f8fafc;color:#334155;border:1.5px solid #e2e8f0;border-radius:8px;
                  padding:8px 16px;font-size:.84rem;font-weight:700;text-decoration:none;">
            ← Dashboard
        </a>
    </div>

    
    <div class="cg-panel">
        <div class="cg-hdr" style="background:linear-gradient(135deg,#006633,#009944);color:#fff;">
            <span>Registered Caregivers</span>
            <span style="background:rgba(255,255,255,.18);color:#fff;border-radius:20px;padding:2px 10px;font-size:.73rem;font-weight:700;">
                <?php echo e($members->count()); ?> caregiver<?php echo e($members->count()!==1?'s':''); ?>

            </span>
        </div>

        <?php if($members->isEmpty()): ?>
            <div style="text-align:center;padding:40px;color:#94a3b8;">No caregivers yet. Add one below.</div>
        <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="cg-tbl">
                <thead>
                    <tr>
                        <th>Caregiver</th>
                        <th>Assigned devices</th>
                        <th style="width:260px;">Assign new device</th>
                        <th style="width:150px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // devices already assigned to this caregiver via pivot
                    $memberDeviceIds = $member->assignedDevices->pluck('id');
                    // devices not yet assigned to this caregiver (can still assign even if assigned to others)
                    $availableDevices = $devices->whereNotIn('id', $memberDeviceIds);
                ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="cg-av"><?php echo e(strtoupper(substr($member->name??'?',0,1))); ?></div>
                            <div>
                                <div style="font-weight:700;color:#0f172a;"><?php echo e($member->name); ?></div>
                                <div style="font-size:.74rem;color:#64748b;"><?php echo e($member->email); ?></div>
                                <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:1px 8px;font-size:.72rem;font-weight:700;">
                                    <?php echo e(is_string($member->role)?$member->role:($member->role?->name??'caregiver')); ?>

                                </span>
                            </div>
                        </div>
                    </td>

                    
                    <td>
                        <?php if($member->assignedDevices->isEmpty()): ?>
                            <span style="color:#94a3b8;font-size:.82rem;">None assigned</span>
                        <?php else: ?>
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                <?php $__currentLoopData = $member->assignedDevices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="dev-tag">
                                    📟 <?php echo e($adev->device_name); ?>

                                    <form method="POST" action="<?php echo e(route('family.unassignDeviceFromCaregiver')); ?>"
                                          onsubmit="return confirm('Unassign <?php echo e(addslashes($adev->device_name)); ?> from <?php echo e(addslashes($member->name)); ?>?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="device_id" value="<?php echo e($adev->id); ?>">
                                        <input type="hidden" name="user_id"   value="<?php echo e($member->id); ?>">
                                        <button type="submit" title="Remove">✕</button>
                                    </form>
                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <?php if($availableDevices->isEmpty()): ?>
                            <span style="color:#94a3b8;font-size:.8rem;">All family devices assigned</span>
                        <?php else: ?>
                        <form method="POST" action="<?php echo e(route('family.assignDeviceToCaregiver')); ?>"
                              style="display:flex;gap:6px;align-items:center;">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="user_id" value="<?php echo e($member->id); ?>">
                            <select name="device_id" class="ff-sel" style="flex:1;" required>
                                <option value="" disabled selected>Select device…</option>
                                <?php $__currentLoopData = $availableDevices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dev->id); ?>">
                                        <?php echo e($dev->device_name); ?>

                                        <?php if($dev->caregivers->isNotEmpty()): ?>
                                            (also: <?php echo e($dev->caregivers->pluck('name')->join(', ')); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="submit" class="btn-sm-act btn-assign">Assign</button>
                        </form>
                        <?php endif; ?>
                    </td>

                    <td style="text-align:right;">
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <button class="btn-sm-act btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCg<?php echo e($member->id); ?>">
                                ✏️ Edit
                            </button>
                            <form method="POST"
                                  action="<?php echo e(route('family.deleteCaregiver', $member->id)); ?>"
                                  onsubmit="return confirm('Delete <?php echo e(addslashes($member->name)); ?>? This cannot be undone.')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-sm-act btn-del">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if($devices->isNotEmpty()): ?>
    <div class="cg-panel">
        <div class="cg-hdr" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff;">
            <span>📟 Device Overview</span>
            <span style="background:rgba(255,255,255,.18);color:#fff;border-radius:20px;padding:2px 10px;font-size:.73rem;font-weight:700;">
                <?php echo e($devices->count()); ?> device<?php echo e($devices->count()!==1?'s':''); ?>

            </span>
        </div>
        <div style="padding:16px 20px;display:flex;flex-wrap:wrap;gap:12px;">
            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 16px;min-width:220px;flex:1;">
                <div style="font-weight:700;color:#0f172a;margin-bottom:4px;"><?php echo e($dev->device_name); ?></div>
                <div style="font-size:.71rem;color:#94a3b8;font-family:monospace;margin-bottom:8px;"><?php echo e($dev->device_token); ?></div>
                <?php if($dev->caregivers->isEmpty()): ?>
                    <span style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;border-radius:20px;padding:2px 9px;font-size:.74rem;font-weight:700;">
                        No caregivers assigned
                    </span>
                <?php else: ?>
                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                        <?php $__currentLoopData = $dev->caregivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:20px;padding:2px 9px;font-size:.74rem;font-weight:600;">
                            👤 <?php echo e($cg->name); ?>

                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="cg-panel">
        <div class="cg-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
            <span>➕ Add New Caregiver</span>
        </div>
        <div style="padding:22px;">
            <form method="POST" action="<?php echo e(route('family.addCaregiver')); ?>">
                <?php echo csrf_field(); ?>
                <?php if($errors->any()): ?>
                    <div style="background:#fff1f2;border:1.5px solid #fecdd3;border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#be123c;font-size:.85rem;">
                        <strong>Please fix:</strong>
                        <ul style="margin:6px 0 0;padding-left:18px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="ff-label">Full name *</label>
                        <input class="ff-input" type="text" name="name" required
                               value="<?php echo e(old('name')); ?>" placeholder="Jane Doe">
                    </div>
                    <div>
                        <label class="ff-label">Email *</label>
                        <input class="ff-input" type="email" name="email" required
                               value="<?php echo e(old('email')); ?>" placeholder="jane@example.com">
                    </div>
                    <div>
                        <label class="ff-label">Password <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                        <input class="ff-input" type="password" name="password" minlength="6"
                               placeholder="Default: password123">
                    </div>
                </div>
                <button type="submit" class="btn-submit">Add Caregiver</button>
            </form>
        </div>
    </div>

</div>


<?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editCg<?php echo e($member->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
            <form method="POST" action="<?php echo e(route('family.updateCaregiver', $member->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;padding:15px 20px;">
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:.95rem;">
                        ✏️ Edit — <?php echo e($member->name); ?>

                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label class="ff-label">Full name *</label>
                        <input class="ff-input" type="text" name="name" required value="<?php echo e($member->name); ?>">
                    </div>
                    <div>
                        <label class="ff-label">Email *</label>
                        <input class="ff-input" type="email" name="email" required value="<?php echo e($member->email); ?>">
                    </div>
                    <div>
                        <label class="ff-label">New password <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                        <input class="ff-input" type="password" name="password" minlength="6"
                               placeholder="Leave blank to keep current">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:12px 20px;gap:8px;">
                    <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/family/caregivers.blade.php ENDPATH**/ ?>