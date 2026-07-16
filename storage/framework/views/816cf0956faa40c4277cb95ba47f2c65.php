
<?php $devices = $devices ?? collect(); ?>

<style>
.dev-reg-wrap { max-width:1100px; margin:0 auto; }
.dev-panel { background:#fff;border-radius:16px;box-shadow:0 2px 14px rgba(0,0,0,.07);overflow:hidden; }
.dev-panel-hdr { padding:13px 20px;font-size:.78rem;font-weight:700;text-transform:uppercase;
                 letter-spacing:.08em;display:flex;align-items:center;justify-content:space-between;gap:8px; }
.dev-panel-body { padding:22px; }

.ff-lbl { font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#475569;margin-bottom:5px;display:block; }
.ff-inp { border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:.86rem;width:100%;transition:border-color .15s; }
.ff-inp:focus { border-color:#006633;outline:none;box-shadow:0 0 0 3px rgba(0,102,51,.1); }
.btn-reg { background:linear-gradient(135deg,#006633,#009944);color:#fff;border:none;border-radius:8px;
           padding:9px 24px;font-size:.88rem;font-weight:700;cursor:pointer;width:100%;margin-top:4px; }

.dev-tbl { width:100%;border-collapse:collapse; }
.dev-tbl thead tr { background:#f8fafc;border-bottom:2px solid #e2e8f0; }
.dev-tbl thead th { padding:9px 14px;font-size:.72rem;font-weight:700;text-transform:uppercase;
                    letter-spacing:.07em;color:#64748b;white-space:nowrap; }
.dev-tbl tbody tr { border-bottom:1px solid #f1f5f9; }
.dev-tbl tbody tr:hover { background:#f8fafc; }
.dev-tbl tbody td { padding:10px 14px;font-size:.875rem;vertical-align:middle; }

.btn-sm-act { display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;
              font-size:.77rem;font-weight:700;cursor:pointer;border:1.5px solid;background:#fff;
              transition:opacity .15s;text-decoration:none; }
.btn-sm-act:hover { opacity:.82; }
.btn-rename { color:#1d4ed8;border-color:#bfdbfe; }
.btn-rename:hover { background:#eff6ff; }
.btn-del { color:#be123c;border-color:#fecdd3; }
.btn-del:hover { background:#fff1f2; }

.mf-lbl { font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#475569;margin-bottom:5px;display:block; }
.mf-inp { border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:.86rem;width:100%;transition:border-color .15s; }
.mf-inp:focus { border-color:#1d4ed8;outline:none;box-shadow:0 0 0 3px rgba(29,78,216,.1); }
</style>

<div class="dev-reg-wrap">

    
    <div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:24px;">
        <div>
            <h1 style="font-size:1.45rem;font-weight:800;color:#0f172a;margin:0;">
                <span style="color:#006633;">&#9679;</span> Device Registration
            </h1>
            <p style="font-size:.875rem;color:#64748b;margin:4px 0 0;">
                Register new cradle devices. Token is generated automatically.
            </p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:340px 1fr;gap:20px;align-items:start;">

        
        <div class="dev-panel">
            <div class="dev-panel-hdr" style="background:linear-gradient(135deg,#006633,#009944);color:#fff;">
                <span>➕ Register New Device</span>
            </div>
            <div class="dev-panel-body">
                <form method="POST" action="<?php echo e(route('admin.registerDevice')); ?>">
                    <?php echo csrf_field(); ?>
                    <div style="margin-bottom:14px;">
                        <label class="ff-lbl">Device name *</label>
                        <input class="ff-inp" type="text" name="device_name" required
                               maxlength="255" placeholder="e.g. Cradle Sensor A">
                    </div>
                    <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:9px 13px;margin-bottom:14px;font-size:.82rem;color:#0369a1;">
                        A unique UUID token will be generated automatically and shown after registration.
                    </div>
                    <button type="submit" class="btn-reg">Register Device</button>
                </form>
            </div>
        </div>

        
        <div class="dev-panel">
            <div class="dev-panel-hdr" style="background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;">
                <span>Registered Devices</span>
                <span style="background:rgba(255,255,255,.15);color:#fff;border-radius:20px;padding:2px 10px;font-size:.73rem;font-weight:700;">
                    <?php echo e($devices->count()); ?> total
                </span>
            </div>
            <div style="padding:0;overflow-x:auto;">
                <?php if($devices->isEmpty()): ?>
                    <div style="text-align:center;padding:36px;color:#94a3b8;">
                        No devices registered yet.
                    </div>
                <?php else: ?>
                <table class="dev-tbl">
                    <thead>
                        <tr>
                            <th>Device name</th>
                            <th>Token</th>
                            <th style="width:110px;">Family</th>
                            <th style="width:170px;text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="font-weight:700;color:#0f172a;"><?php echo e($device->device_name); ?></td>
                        <td style="font-family:monospace;font-size:.78rem;color:#64748b;">
                            <?php echo e($device->device_token); ?>

                        </td>
                        <td>
                            <?php if($device->family_id): ?>
                                <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:2px 9px;font-size:.74rem;font-weight:700;">
                                    Assigned
                                </span>
                            <?php else: ?>
                                <span style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;border-radius:20px;padding:2px 9px;font-size:.74rem;font-weight:700;">
                                    Unassigned
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex;gap:6px;">
                                <button class="btn-sm-act btn-rename"
                                        data-bs-toggle="modal"
                                        data-bs-target="#renDev<?php echo e($device->id); ?>">
                                    ✏️ Rename
                                </button>
                                <form method="POST"
                                      action="<?php echo e(route('admin.deleteDevice', $device->id)); ?>"
                                      onsubmit="return confirm('Delete device «<?php echo e(addslashes($device->device_name)); ?>»? All its activity records will also be removed.')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-sm-act btn-del">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>


<?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="renDev<?php echo e($device->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
            <form method="POST" action="<?php echo e(route('admin.updateDevice', $device->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;padding:14px 20px;">
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:.95rem;">✏️ Rename Device</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <label class="mf-lbl">Device name *</label>
                        <input type="text" name="device_name" class="mf-inp"
                               required maxlength="255" value="<?php echo e($device->device_name); ?>">
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:.8rem;color:#64748b;">
                        Token (read-only): <code><?php echo e($device->device_token); ?></code>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:12px 20px;gap:8px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\xampp\htdocs\IoTBabyCradle\resources\views/admin/devices_registration.blade.php ENDPATH**/ ?>