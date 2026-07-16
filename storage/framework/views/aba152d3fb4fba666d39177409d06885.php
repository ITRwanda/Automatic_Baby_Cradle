

<?php $__env->startSection('content'); ?>
<?php
    $families   = $families   ?? collect();
    $parents    = $parents    ?? collect();
    $allDevices = $allDevices ?? collect();
?>

<style>
.fmgr-page { max-width:1380px; margin:0 auto; }
.fmgr-hdr  { display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:26px; }
.fmgr-title{ font-size:1.45rem;font-weight:800;color:#0f172a;margin:0; }
.fmgr-sub  { font-size:.875rem;color:#64748b;margin:4px 0 0; }

/* two-column layout */
.fmgr-grid { display:grid;grid-template-columns:360px 1fr;gap:22px;align-items:start; }
@media(max-width:900px){ .fmgr-grid{ grid-template-columns:1fr; } }

/* panels */
.fmgr-panel { background:#fff;border-radius:16px;box-shadow:0 2px 14px rgba(0,0,0,.07);overflow:hidden; }
.fmgr-panel-hdr { padding:14px 20px;font-size:.78rem;font-weight:700;text-transform:uppercase;
                  letter-spacing:.08em;display:flex;align-items:center;justify-content:space-between;gap:8px; }
.fmgr-panel-body { padding:22px; }

/* form fields */
.ff-lbl { font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#475569;margin-bottom:5px;display:block; }
.ff-inp { border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:.86rem;width:100%;transition:border-color .15s;margin-bottom:0; }
.ff-inp:focus { border-color:#006633;outline:none;box-shadow:0 0 0 3px rgba(0,102,51,.1); }
.btn-create { background:linear-gradient(135deg,#006633,#009944);color:#fff;border:none;border-radius:8px;
              padding:10px 24px;font-size:.9rem;font-weight:700;cursor:pointer;width:100%;margin-top:4px; }

/* families table */
.fmgr-tbl { width:100%;border-collapse:collapse; }
.fmgr-tbl thead tr { background:#f8fafc;border-bottom:2px solid #e2e8f0; }
.fmgr-tbl thead th { padding:9px 14px;font-size:.72rem;font-weight:700;text-transform:uppercase;
                     letter-spacing:.07em;color:#64748b;white-space:nowrap; }
.fmgr-tbl tbody tr { border-bottom:1px solid #f1f5f9;transition:background .1s; }
.fmgr-tbl tbody tr:hover { background:#f8fafc; }
.fmgr-tbl tbody td { padding:11px 14px;font-size:.875rem;vertical-align:middle; }

/* action buttons */
.btn-act { display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;
           font-size:.78rem;font-weight:700;cursor:pointer;border:1.5px solid;background:#fff;
           transition:opacity .15s; }
.btn-act:hover { opacity:.82; }
.btn-modify { color:#1d4ed8;border-color:#bfdbfe; }
.btn-modify:hover { background:#eff6ff; }
.btn-assign { color:#15803d;border-color:#bbf7d0; }
.btn-assign:hover { background:#f0fdf4; }
.btn-danger { color:#be123c;border-color:#fecdd3; }
.btn-danger:hover { background:#fff1f2; }

/* search input */
.fmgr-search { width:100%;border:1.5px solid #e2e8f0;border-radius:8px;
               padding:7px 12px;font-size:.85rem;outline:none;margin-bottom:12px; }
.fmgr-search:focus { border-color:#006633;box-shadow:0 0 0 3px rgba(0,102,51,.1); }

/* modal fields */
.mf-lbl { font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#475569;margin-bottom:5px;display:block; }
.mf-inp { border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:.86rem;width:100%;transition:border-color .15s; }
.mf-inp:focus { border-color:#1d4ed8;outline:none;box-shadow:0 0 0 3px rgba(29,78,216,.1); }

/* device rows inside modal */
.dev-row { display:flex;align-items:center;justify-content:space-between;padding:9px 13px;
           border-radius:10px;margin-bottom:6px;border:1.5px solid #f1f5f9;background:#f8fafc;gap:10px; }
.dev-row.assigned { border-color:#bbf7d0;background:#f0fdf4; }
.dev-name  { font-size:.88rem;font-weight:700;color:#0f172a; }
.dev-token { font-size:.71rem;color:#94a3b8;font-family:monospace; }
</style>

<div class="fmgr-page">

    
    <div class="fmgr-hdr">
        <div>
            <h1 class="fmgr-title">
                <span style="color:#006633;">&#9679;</span> Family Management
            </h1>
            <p class="fmgr-sub">Create families, assign devices, and manage parent accounts</p>
        </div>
        <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:20px;padding:4px 14px;font-size:.82rem;font-weight:700;">
            Admin
        </span>
    </div>

    <div class="fmgr-grid">

        
        <div class="fmgr-panel">
            <div class="fmgr-panel-hdr" style="background:linear-gradient(135deg,#006633,#009944);color:#fff;">
                <span>➕ Create New Family</span>
            </div>
            <div class="fmgr-panel-body">

                <?php if($errors->any()): ?>
                    <div style="background:#fff1f2;border:1.5px solid #fecdd3;border-radius:10px;padding:12px 15px;margin-bottom:16px;color:#be123c;font-size:.84rem;">
                        <strong>Please fix:</strong>
                        <ul style="margin:6px 0 0;padding-left:18px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($err); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('admin.createFamily')); ?>"
                      style="display:flex;flex-direction:column;gap:13px;">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="ff-lbl">Family name *</label>
                        <input class="ff-inp" type="text" name="family_name" required
                               maxlength="255" placeholder="e.g. Johnson Family"
                               value="<?php echo e(old('family_name')); ?>">
                    </div>
                    <div style="border-top:1px solid #f1f5f9;padding-top:13px;">
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:11px;">
                            Family Parent Account
                        </div>
                        <div style="display:flex;flex-direction:column;gap:11px;">
                            <div>
                                <label class="ff-lbl">Full name *</label>
                                <input class="ff-inp" type="text" name="parent_name" required
                                       maxlength="255" placeholder="e.g. John Parent"
                                       value="<?php echo e(old('parent_name')); ?>">
                            </div>
                            <div>
                                <label class="ff-lbl">Email *</label>
                                <input class="ff-inp" type="email" name="parent_email" required
                                       placeholder="parent@example.com"
                                       value="<?php echo e(old('parent_email')); ?>">
                            </div>
                            <div>
                                <label class="ff-lbl">Password *</label>
                                <input class="ff-inp" type="password" name="parent_password"
                                       required minlength="6" placeholder="Min 6 characters">
                            </div>
                            <div>
                                <label class="ff-lbl">Confirm password *</label>
                                <input class="ff-inp" type="password" name="parent_password_confirmation"
                                       required minlength="6" placeholder="Repeat password">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-create">Create Family</button>
                </form>

                <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:9px 13px;margin-top:16px;font-size:.81rem;color:#0369a1;">
                    💡 After creating a family, click <strong>Devices</strong> on the right to assign cradle devices.
                </div>
            </div>
        </div>

        
        <div class="fmgr-panel">
            <div class="fmgr-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
                <span>Registered Families</span>
                <span style="background:rgba(255,255,255,.15);color:#fff;border-radius:20px;padding:2px 10px;font-size:.73rem;font-weight:700;">
                    <?php echo e($families->count()); ?> &nbsp;·&nbsp; <?php echo e($allDevices->whereNull('family_id')->count()); ?> unassigned device<?php echo e($allDevices->whereNull('family_id')->count()!==1?'s':''); ?>

                </span>
            </div>

            <div style="padding:12px 14px 0;">
                <input class="fmgr-search" id="fmgrSearch" type="text"
                       placeholder="Search by family name, parent name or email…">
            </div>

            <?php if($families->isEmpty()): ?>
                <div style="text-align:center;padding:36px;color:#94a3b8;">No families yet. Create one on the left.</div>
            <?php else: ?>
            <div style="overflow-x:auto;padding-bottom:4px;">
                <table class="fmgr-tbl">
                    <thead>
                        <tr>
                            <th>Family</th>
                            <th>Parent</th>
                            <th style="width:80px;">Members</th>
                            <th style="width:80px;">Devices</th>
                            <th style="width:210px;text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="fmgrTbody">
                    <?php $__currentLoopData = $families; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pName  = optional($family->parent)->name  ?? '—';
                        $pEmail = optional($family->parent)->email ?? '';
                        $devCnt = ($family->devices ?? collect())->count();
                        $memCnt = ($family->members ?? collect())->count();
                        $srch   = strtolower($family->family_name.' '.$pName.' '.$pEmail);
                    ?>
                    <tr data-search="<?php echo e($srch); ?>">
                        <td>
                            <div style="font-weight:700;color:#0f172a;"><?php echo e($family->family_name); ?></div>
                            <div style="font-size:.71rem;color:#94a3b8;">ID #<?php echo e($family->id); ?></div>
                        </td>
                        <td>
                            <div style="font-weight:600;color:#0f172a;"><?php echo e($pName); ?></div>
                            <div style="font-size:.73rem;color:#64748b;"><?php echo e($pEmail); ?></div>
                        </td>
                        <td>
                            <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:20px;padding:2px 9px;font-size:.74rem;font-weight:700;">
                                <?php echo e($memCnt); ?>

                            </span>
                        </td>
                        <td>
                            <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:2px 9px;font-size:.74rem;font-weight:700;">
                                <?php echo e($devCnt); ?>

                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex;gap:5px;flex-wrap:wrap;justify-content:flex-end;">
                                <button class="btn-act btn-modify"
                                        data-bs-toggle="modal"
                                        data-bs-target="#fmMod<?php echo e($family->id); ?>">
                                    ✏️ Modify
                                </button>
                                <button class="btn-act btn-assign"
                                        data-bs-toggle="modal"
                                        data-bs-target="#fmDev<?php echo e($family->id); ?>">
                                    📟 Devices
                                </button>
                                <form method="POST"
                                      action="<?php echo e(route('admin.deleteFamily', $family->id)); ?>"
                                      onsubmit="return confirm('Delete «<?php echo e(addslashes($family->family_name)); ?>»?\nThis also removes its devices and members.')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-act btn-danger">🗑</button>
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

    </div>
</div>


<?php $__currentLoopData = $families; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $unassigned = $allDevices->whereNull('family_id');
    $assigned   = $family->devices ?? collect();
?>


<div class="modal fade" id="fmMod<?php echo e($family->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <form method="POST" action="<?php echo e(route('admin.updateFamily', $family->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;padding:16px 22px;">
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:1rem;">
                        ✏️ Modify — <?php echo e($family->family_name); ?>

                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:22px;display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label class="mf-lbl">Family name *</label>
                        <input type="text" name="family_name" class="mf-inp"
                               required maxlength="255" value="<?php echo e($family->family_name); ?>">
                    </div>
                    <div style="border-top:1px solid #f1f5f9;padding-top:14px;">
                        <div style="font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:11px;">
                            Parent Account
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div>
                                <label class="mf-lbl">Parent name</label>
                                <input type="text" name="parent_name" class="mf-inp"
                                       value="<?php echo e(optional($family->parent)->name); ?>" placeholder="Full name">
                            </div>
                            <div>
                                <label class="mf-lbl">Parent email</label>
                                <input type="email" name="parent_email" class="mf-inp"
                                       value="<?php echo e(optional($family->parent)->email); ?>" placeholder="email@example.com">
                            </div>
                            <div>
                                <label class="mf-lbl">New password <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                                <input type="password" name="parent_password" class="mf-inp"
                                       placeholder="Leave blank to keep current">
                            </div>
                            <div>
                                <label class="mf-lbl">Confirm password</label>
                                <input type="password" name="parent_password_confirmation" class="mf-inp"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:13px 22px;gap:8px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="fmDev<?php echo e($family->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <div class="modal-header" style="background:linear-gradient(135deg,#006633,#009944);border:none;padding:16px 22px;">
                <div>
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:1rem;">
                        📟 Manage Devices — <?php echo e($family->family_name); ?>

                    </h5>
                    <div style="font-size:.77rem;color:rgba(255,255,255,.75);margin-top:2px;">
                        Assign available devices or manage ones already linked to this family.
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:22px;">

                
                <div style="font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:9px;">
                    Available (unassigned) — <?php echo e($unassigned->count()); ?>

                </div>
                <?php if($unassigned->isEmpty()): ?>
                    <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px;text-align:center;color:#94a3b8;font-size:.84rem;margin-bottom:18px;">
                        No unassigned devices. Register one first on the Devices page.
                    </div>
                <?php else: ?>
                    <div style="margin-bottom:20px;">
                        <?php $__currentLoopData = $unassigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="dev-row">
                            <div>
                                <div class="dev-name"><?php echo e($dev->device_name); ?></div>
                                <div class="dev-token"><?php echo e($dev->device_token); ?></div>
                            </div>
                            <form method="POST" action="<?php echo e(route('admin.assignDevice')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="device_id" value="<?php echo e($dev->id); ?>">
                                <input type="hidden" name="family_id"  value="<?php echo e($family->id); ?>">
                                <button type="submit"
                                        style="background:linear-gradient(135deg,#006633,#009944);color:#fff;border:none;border-radius:7px;padding:6px 14px;font-size:.8rem;font-weight:700;cursor:pointer;">
                                    Assign →
                                </button>
                            </form>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                
                <div style="border-top:1px solid #f1f5f9;padding-top:18px;">
                    <div style="font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:9px;">
                        Assigned to this family — <?php echo e($assigned->count()); ?>

                    </div>
                    <?php if($assigned->isEmpty()): ?>
                        <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:12px;text-align:center;color:#15803d;font-size:.84rem;">
                            No devices assigned yet.
                        </div>
                    <?php else: ?>
                        <?php $__currentLoopData = $assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="dev-row assigned">
                            <div style="flex:1;min-width:0;">
                                <div class="dev-name"><?php echo e($dev->device_name); ?></div>
                                <div class="dev-token"><?php echo e($dev->device_token); ?></div>
                                <?php if($dev->user): ?>
                                    <div style="font-size:.73rem;color:#0f766e;margin-top:2px;">
                                        👤 Caregiver: <?php echo e($dev->user->name); ?> (<?php echo e($dev->user->email); ?>)
                                    </div>
                                <?php else: ?>
                                    <div style="font-size:.73rem;color:#f59e0b;margin-top:2px;">
                                        ⚠ No caregiver assigned
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;flex-shrink:0;">
                                
                                <button class="btn-act btn-modify"
                                        data-bs-toggle="modal"
                                        data-bs-target="#fmRenDev<?php echo e($dev->id); ?>">
                                    ✏️ Rename
                                </button>
                                
                                <form method="POST" action="<?php echo e(route('admin.unassignDevice')); ?>"
                                      onsubmit="return confirm('Unassign «<?php echo e(addslashes($dev->device_name)); ?>» from this family?')">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="device_id" value="<?php echo e($dev->id); ?>">
                                    <button type="submit" class="btn-act"
                                            style="color:#b45309;border-color:#fde68a;">
                                        ⛓ Unassign
                                    </button>
                                </form>
                                
                                <form method="POST" action="<?php echo e(route('admin.deleteDevice', $dev->id)); ?>"
                                      onsubmit="return confirm('Permanently delete «<?php echo e(addslashes($dev->device_name)); ?>»?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-act btn-danger">🗑 Delete</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:12px 22px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php $__currentLoopData = $assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="fmRenDev<?php echo e($dev->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
            <form method="POST" action="<?php echo e(route('admin.updateDevice', $dev->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;padding:13px 20px;">
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:.95rem;">✏️ Rename Device</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <label class="mf-lbl">Device name *</label>
                        <input type="text" name="device_name" class="mf-inp"
                               required maxlength="255" value="<?php echo e($dev->device_name); ?>">
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:.79rem;color:#64748b;">
                        Token (read-only): <code><?php echo e($dev->device_token); ?></code>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:11px 20px;gap:8px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<script>
(function(){
    var inp=document.getElementById('fmgrSearch');
    if(!inp)return;
    var rows=Array.from(document.querySelectorAll('#fmgrTbody tr[data-search]'));
    inp.addEventListener('input',function(){
        var q=inp.value.trim().toLowerCase();
        rows.forEach(function(r){
            r.style.display=(!q||r.dataset.search.includes(q))?'':'none';
        });
    });
})();
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/families.blade.php ENDPATH**/ ?>