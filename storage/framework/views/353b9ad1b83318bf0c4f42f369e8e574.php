

<?php $__env->startSection('content'); ?>
<?php
    $families = $families ?? collect();
    $parents  = $parents  ?? collect();
    $total    = $families->count();
?>

<style>
.afr-page { max-width:1200px; margin:0 auto; }
.afr-hdr  { display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:26px; }
.afr-title{ font-size:1.45rem;font-weight:800;color:#0f172a;margin:0; }
.afr-sub  { font-size:.875rem;color:#64748b;margin:4px 0 0; }

/* KPI */
.afr-kpi { display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:24px; }
.afr-kpi-card { border-radius:14px;padding:16px 18px;box-shadow:0 2px 10px rgba(0,0,0,.06);display:flex;flex-direction:column;gap:4px; }
.afr-kpi-lbl { font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;opacity:.6; }
.afr-kpi-val { font-size:1.85rem;font-weight:800;line-height:1; }
.ak-blue  { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8; }
.ak-teal  { background:linear-gradient(135deg,#f0fdfa,#ccfbf1); color:#0f766e; }
.ak-amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#b45309; }

/* panel */
.afr-panel { background:#fff;border-radius:16px;box-shadow:0 2px 14px rgba(0,0,0,.07);overflow:hidden;margin-bottom:20px; }
.afr-panel-hdr { padding:13px 20px;font-size:.78rem;font-weight:700;text-transform:uppercase;
                 letter-spacing:.08em;display:flex;align-items:center;justify-content:space-between;gap:8px; }
.afr-panel-body { padding:18px 20px; }

/* filter form */
.ff-lbl { font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#475569;margin-bottom:5px;display:block; }
.ff-inp { border:1.5px solid #e2e8f0;border-radius:8px;padding:7px 11px;font-size:.86rem;width:100%;transition:border-color .15s; }
.ff-inp:focus { border-color:#dc2626;outline:none;box-shadow:0 0 0 3px rgba(220,38,38,.1); }

/* table */
.afr-tbl { width:100%;border-collapse:collapse; }
.afr-tbl thead tr { background:#f8fafc;border-bottom:2px solid #e2e8f0; }
.afr-tbl thead th { padding:10px 14px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;white-space:nowrap; }
.afr-tbl tbody tr { border-bottom:1px solid #f1f5f9;transition:background .1s; }
.afr-tbl tbody tr:hover { background:#f8fafc; }
.afr-tbl tbody td { padding:12px 14px;font-size:.875rem;vertical-align:middle; }

/* action buttons */
.btn-act { display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:7px;
           font-size:.78rem;font-weight:700;cursor:pointer;border:1.5px solid;background:#fff;
           transition:opacity .15s;text-decoration:none; }
.btn-act:hover { opacity:.82; }
.btn-modify { color:#1d4ed8;border-color:#bfdbfe; }
.btn-modify:hover { background:#eff6ff; }
.btn-danger { color:#be123c;border-color:#fecdd3; }
.btn-danger:hover { background:#fff1f2; }

/* modal fields */
.mf-lbl { font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#475569;margin-bottom:5px;display:block; }
.mf-inp { border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:.86rem;width:100%;transition:border-color .15s; }
.mf-inp:focus { border-color:#1d4ed8;outline:none;box-shadow:0 0 0 3px rgba(29,78,216,.1); }

.afr-empty { text-align:center;padding:40px;color:#94a3b8;font-size:.9rem; }
</style>

<div class="afr-page">

    
    <div class="afr-hdr">
        <div>
            <h1 class="afr-title">
                <span style="color:#dc2626;">&#9679;</span> Family Reports
            </h1>
            <p class="afr-sub">View, search, modify and delete registered families</p>
        </div>
    </div>

    
    <div class="afr-kpi">
        <div class="afr-kpi-card ak-blue">
            <div class="afr-kpi-lbl">Total Families</div>
            <div class="afr-kpi-val"><?php echo e($total); ?></div>
        </div>
        <div class="afr-kpi-card ak-teal">
            <div class="afr-kpi-lbl">Total Devices</div>
            <div class="afr-kpi-val"><?php echo e($families->sum(fn($f)=>$f->devices->count())); ?></div>
        </div>
        <div class="afr-kpi-card ak-amber">
            <div class="afr-kpi-lbl">Total Members</div>
            <div class="afr-kpi-val"><?php echo e($families->sum(fn($f)=>$f->members->count())); ?></div>
        </div>
    </div>

    
    <div class="afr-panel">
        <div class="afr-panel-hdr" style="background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;">
            <span>🔍 Filters</span>
        </div>
        <div class="afr-panel-body">
            <form method="GET" action="<?php echo e(route('admin.familyReports')); ?>"
                  style="display:grid;grid-template-columns:1fr 160px 160px auto;gap:14px;align-items:flex-end;">
                <div>
                    <label class="ff-lbl">Search family / parent</label>
                    <input class="ff-inp" type="text" name="q"
                           placeholder="Family name, parent name or email…" value="<?php echo e(request('q')); ?>">
                </div>
                <div>
                    <label class="ff-lbl">From</label>
                    <input class="ff-inp" type="date" name="from" value="<?php echo e(request('from')); ?>">
                </div>
                <div>
                    <label class="ff-lbl">To</label>
                    <input class="ff-inp" type="date" name="to" value="<?php echo e(request('to')); ?>">
                </div>
                <div style="display:flex;gap:8px;">
                    <button type="submit"
                            style="background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;border:none;border-radius:8px;padding:8px 20px;font-size:.86rem;font-weight:700;cursor:pointer;">
                        Apply
                    </button>
                    <a href="<?php echo e(route('admin.familyReports')); ?>"
                       style="background:#f1f5f9;color:#475569;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 14px;font-size:.86rem;font-weight:600;text-decoration:none;">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    
    <div class="afr-panel">
        <div class="afr-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
            <span>Families (<?php echo e($total); ?>)</span>
        </div>
        <div style="padding:0;">
            <?php if($families->isEmpty()): ?>
                <div class="afr-empty">No families match your filters.</div>
            <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="afr-tbl">
                    <thead>
                        <tr>
                            <th>Family</th>
                            <th>Parent</th>
                            <th style="width:90px;">Members</th>
                            <th style="width:90px;">Devices</th>
                            <th style="width:110px;">Created</th>
                            <th style="width:160px;text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $families; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pName  = optional($family->parent)->name  ?? '—';
                        $pEmail = optional($family->parent)->email ?? '';
                        $memCnt = $family->members->count();
                        $devCnt = $family->devices->count();
                    ?>
                    <tr>
                        <td>
                            <div style="font-weight:700;color:#0f172a;"><?php echo e($family->family_name); ?></div>
                            <div style="font-size:.72rem;color:#94a3b8;">ID #<?php echo e($family->id); ?></div>
                        </td>
                        <td>
                            <div style="font-weight:600;color:#0f172a;"><?php echo e($pName); ?></div>
                            <div style="font-size:.74rem;color:#64748b;"><?php echo e($pEmail); ?></div>
                        </td>
                        <td>
                            <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:20px;padding:2px 10px;font-size:.75rem;font-weight:700;">
                                <?php echo e($memCnt); ?>

                            </span>
                        </td>
                        <td>
                            <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:2px 10px;font-size:.75rem;font-weight:700;">
                                <?php echo e($devCnt); ?>

                            </span>
                        </td>
                        <td style="font-size:.8rem;color:#64748b;">
                            <?php echo e($family->created_at?->format('d M Y')); ?>

                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex;gap:6px;">
                                <button class="btn-act btn-modify"
                                        data-bs-toggle="modal"
                                        data-bs-target="#frMod<?php echo e($family->id); ?>">
                                    ✏️ Modify
                                </button>
                                <form method="POST"
                                      action="<?php echo e(route('admin.deleteFamily', $family->id)); ?>"
                                      onsubmit="return confirm('Delete «<?php echo e(addslashes($family->family_name)); ?>»? This also removes its devices and members.')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-act btn-danger">🗑 Delete</button>
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
<div class="modal fade" id="frMod<?php echo e($family->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <form method="POST" action="<?php echo e(route('admin.updateFamily', $family->id)); ?>">
                <?php echo csrf_field(); ?>

                <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;padding:16px 22px;">
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:1rem;">
                        ✏️ Modify Family — <?php echo e($family->family_name); ?>

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
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:12px;">
                            Parent Account Details
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
                        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:9px 13px;margin-top:10px;font-size:.81rem;color:#0369a1;">
                            Only fill password fields to change the parent's login credentials.
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
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/family_reports.blade.php ENDPATH**/ ?>