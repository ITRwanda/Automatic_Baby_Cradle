@php
    $familiesCollection = $families   ?? collect();
    $allDevices         = $allDevices ?? collect();
    $globalUnassigned   = $allDevices->whereNull('family_id')->count();
@endphp

{{-- ════════════════════════════════════════════════════════════
     FAMILIES TABLE  (modals rendered OUTSIDE the <table>)
     ════════════════════════════════════════════════════════════ --}}

<style>
.fam-panel { background:#fff; border-radius:16px; box-shadow:0 2px 14px rgba(0,0,0,.07); overflow:hidden; }
.fam-hdr   { padding:14px 20px; font-size:.8rem; font-weight:700; text-transform:uppercase;
             letter-spacing:.08em; display:flex; align-items:center; justify-content:space-between;
             background:linear-gradient(135deg,#006633,#009944); color:#fff; }
.fam-badge { background:rgba(255,255,255,.18); color:#fff; border-radius:20px;
             padding:2px 10px; font-size:.73rem; font-weight:700; }

/* search input */
.fam-search { width:100%; border:1.5px solid #e2e8f0; border-radius:8px;
              padding:7px 12px; font-size:.85rem; outline:none; }
.fam-search:focus { border-color:#006633; box-shadow:0 0 0 3px rgba(0,102,51,.1); }

/* table */
.fam-tbl { width:100%; border-collapse:collapse; }
.fam-tbl thead th { padding:9px 12px; font-size:.72rem; font-weight:700; text-transform:uppercase;
                    letter-spacing:.07em; color:#64748b; background:#f8fafc;
                    border-bottom:2px solid #e2e8f0; white-space:nowrap; }
.fam-tbl tbody tr { border-bottom:1px solid #f1f5f9; transition:background .1s; }
.fam-tbl tbody tr:hover { background:#f8fafc; }
.fam-tbl tbody td { padding:11px 12px; font-size:.875rem; vertical-align:middle; }

/* action buttons */
.btn-act { display:inline-flex; align-items:center; gap:5px; padding:5px 12px;
           border-radius:7px; font-size:.78rem; font-weight:700; cursor:pointer;
           border:1.5px solid; transition:opacity .15s; text-decoration:none; background:#fff; }
.btn-act:hover { opacity:.82; }
.btn-modify   { color:#1d4ed8; border-color:#bfdbfe; }
.btn-modify:hover { background:#eff6ff; }
.btn-assign   { color:#15803d; border-color:#bbf7d0; }
.btn-assign:hover { background:#f0fdf4; }
.btn-danger   { color:#be123c; border-color:#fecdd3; }
.btn-danger:hover { background:#fff1f2; }

/* modal tweaks */
.modal-title-lg { font-size:1.05rem; font-weight:800; }
.ff-label { font-size:.73rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.05em; color:#475569; margin-bottom:5px; display:block; }
.ff-input { border:1.5px solid #e2e8f0; border-radius:8px; padding:7px 11px;
            font-size:.86rem; width:100%; transition:border-color .15s; }
.ff-input:focus { border-color:#006633; outline:none; box-shadow:0 0 0 3px rgba(0,102,51,.1); }

/* device assign rows inside modal */
.dev-row { display:flex; align-items:center; justify-content:space-between;
           padding:9px 14px; border-radius:10px; margin-bottom:7px;
           border:1.5px solid #f1f5f9; background:#f8fafc; gap:10px; }
.dev-row.assigned { border-color:#bbf7d0; background:#f0fdf4; }
.dev-name  { font-size:.88rem; font-weight:700; color:#0f172a; }
.dev-token { font-size:.72rem; color:#94a3b8; font-family:monospace; }
</style>

<div class="fam-panel">
    <div class="fam-hdr">
        <span>Registered Families</span>
        <span class="fam-badge">{{ $familiesCollection->count() }} families &nbsp;·&nbsp; {{ $globalUnassigned }} unassigned device{{ $globalUnassigned !== 1 ? 's' : '' }}</span>
    </div>

    <div style="padding:14px 16px 0;">
        <input class="fam-search" id="famSearch" type="text"
               placeholder="Search by family name, parent name or email…">
    </div>

    <div style="padding:12px 16px; overflow-x:auto;">
        @if($familiesCollection->isEmpty())
            <div style="text-align:center;padding:32px;color:#94a3b8;">No families yet. Create one on the left.</div>
        @else
        <table class="fam-tbl">
            <thead>
                <tr>
                    <th>Family</th>
                    <th>Parent account</th>
                    <th style="width:90px;">Members</th>
                    <th style="width:110px;">Devices</th>
                    <th style="width:220px;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody id="famTbody">
                @foreach($familiesCollection as $family)
                @php
                    $pName  = optional($family->parent)->name  ?? '—';
                    $pEmail = optional($family->parent)->email ?? '';
                    $devCnt = ($family->devices ?? collect())->count();
                    $memCnt = ($family->members ?? collect())->count();
                    $search = strtolower($family->family_name.' '.$pName.' '.$pEmail);
                @endphp
                <tr data-search="{{ $search }}">
                    <td>
                        <div style="font-weight:700;color:#0f172a;">{{ $family->family_name }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;">ID #{{ $family->id }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:#0f172a;">{{ $pName }}</div>
                        <div style="font-size:.74rem;color:#64748b;">{{ $pEmail }}</div>
                    </td>
                    <td>
                        <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:20px;padding:2px 10px;font-size:.75rem;font-weight:700;">
                            {{ $memCnt }}
                        </span>
                    </td>
                    <td>
                        <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:2px 10px;font-size:.75rem;font-weight:700;">
                            {{ $devCnt }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <div style="display:inline-flex;gap:6px;flex-wrap:wrap;justify-content:flex-end;">
                            {{-- Modify --}}
                            <button class="btn-act btn-modify"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modFam{{ $family->id }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Modify
                            </button>

                            {{-- Assign devices --}}
                            <button class="btn-act btn-assign"
                                    data-bs-toggle="modal"
                                    data-bs-target="#assignDev{{ $family->id }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                Devices
                            </button>

                            {{-- Delete --}}
                            <form method="POST"
                                  action="{{ route('admin.deleteFamily', $family->id) }}"
                                  onsubmit="return confirm('Delete family «{{ addslashes($family->family_name) }}»?\nThis will also remove its members and devices.')">
                                @csrf
                                <button type="submit" class="btn-act btn-danger">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     MODALS — rendered outside the table to prevent DOM issues
     ════════════════════════════════════════════════════════ --}}
@foreach($familiesCollection as $family)
@php
    $unassigned = $allDevices->whereNull('family_id');
    $assigned   = $family->devices ?? collect();
@endphp

{{-- ── MODIFY FAMILY MODAL ────────────────────────────── --}}
<div class="modal fade" id="modFam{{ $family->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <form method="POST" action="{{ route('admin.updateFamily', $family->id) }}">
                @csrf

                <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;padding:16px 22px;">
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:1rem;">
                        ✏️ Modify Family — {{ $family->family_name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:22px;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="ff-label">Family name</label>
                            <input type="text" name="family_name" class="ff-input"
                                   required maxlength="255" value="{{ $family->family_name }}">
                        </div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9;padding-top:16px;margin-top:4px;">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:12px;">
                            Family Parent Account
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ff-label">Parent name</label>
                                <input type="text" name="parent_name" class="ff-input"
                                       value="{{ optional($family->parent)->name }}" placeholder="Full name">
                            </div>
                            <div class="col-md-6">
                                <label class="ff-label">Parent email</label>
                                <input type="email" name="parent_email" class="ff-input"
                                       value="{{ optional($family->parent)->email }}" placeholder="email@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="ff-label">New password <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                                <input type="password" name="parent_password" class="ff-input" placeholder="Leave blank to keep current">
                            </div>
                            <div class="col-md-6">
                                <label class="ff-label">Confirm password</label>
                                <input type="password" name="parent_password_confirmation" class="ff-input" placeholder="Confirm new password">
                            </div>
                        </div>
                        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:10px 14px;margin-top:12px;font-size:.82rem;color:#0369a1;">
                            Only fill in password fields to change the parent's login credentials.
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:14px 22px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── ASSIGN / MANAGE DEVICES MODAL ──────────────────── --}}
<div class="modal fade" id="assignDev{{ $family->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">

            <div class="modal-header" style="background:linear-gradient(135deg,#006633,#009944);border:none;padding:16px 22px;">
                <div>
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:1rem;">
                        📟 Manage Devices — {{ $family->family_name }}
                    </h5>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.75);margin-top:2px;">
                        Assign unregistered devices or manage those already linked to this family.
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:22px;">

                {{-- ── Unassigned devices ────────────────────── --}}
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:10px;">
                    Available (unassigned) devices — {{ $unassigned->count() }}
                </div>

                @if($unassigned->isEmpty())
                    <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:14px;text-align:center;color:#94a3b8;font-size:.85rem;margin-bottom:18px;">
                        No unassigned devices. Register a new device first.
                    </div>
                @else
                    <div style="margin-bottom:20px;">
                        @foreach($unassigned as $dev)
                        <div class="dev-row">
                            <div>
                                <div class="dev-name">{{ $dev->device_name }}</div>
                                <div class="dev-token">{{ $dev->device_token }}</div>
                            </div>
                            <form method="POST" action="{{ route('admin.assignDevice') }}">
                                @csrf
                                <input type="hidden" name="device_id" value="{{ $dev->id }}">
                                <input type="hidden" name="family_id"  value="{{ $family->id }}">
                                <button type="submit"
                                        style="background:linear-gradient(135deg,#006633,#009944);color:#fff;border:none;border-radius:8px;padding:6px 14px;font-size:.8rem;font-weight:700;cursor:pointer;">
                                    Assign →
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- ── Assigned devices ──────────────────────── --}}
                <div style="border-top:1px solid #f1f5f9;padding-top:18px;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:10px;">
                        Assigned to this family — {{ $assigned->count() }}
                    </div>

                    @if($assigned->isEmpty())
                        <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:14px;text-align:center;color:#15803d;font-size:.85rem;">
                            No devices assigned yet. Use the section above to assign one.
                        </div>
                    @else
                        @foreach($assigned as $dev)
                        <div class="dev-row assigned">
                            <div style="flex:1;min-width:0;">
                                <div class="dev-name">{{ $dev->device_name }}</div>
                                <div class="dev-token">{{ $dev->device_token }}</div>
                                @if($dev->user)
                                    <div style="font-size:.74rem;color:#0f766e;margin-top:3px;">
                                        👤 Caregiver: {{ $dev->user->name }} ({{ $dev->user->email }})
                                    </div>
                                @else
                                    <div style="font-size:.74rem;color:#f59e0b;margin-top:3px;">
                                        ⚠ No caregiver assigned
                                    </div>
                                @endif
                            </div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;flex-shrink:0;">
                                {{-- Modify name --}}
                                <button class="btn-act btn-modify"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDev{{ $dev->id }}">
                                    ✏️ Rename
                                </button>

                                {{-- Unassign from family --}}
                                <form method="POST" action="{{ route('admin.unassignDevice') }}"
                                      onsubmit="return confirm('Unassign «{{ addslashes($dev->device_name) }}» from this family?')">
                                    @csrf
                                    <input type="hidden" name="device_id" value="{{ $dev->id }}">
                                    <button type="submit" class="btn-act"
                                            style="color:#b45309;border-color:#fde68a;">
                                        ⛓ Unassign
                                    </button>
                                </form>

                                {{-- Delete device --}}
                                <form method="POST" action="{{ route('admin.deleteDevice', $dev->id) }}"
                                      onsubmit="return confirm('Permanently delete «{{ addslashes($dev->device_name) }}»? This cannot be undone.')">
                                    @csrf
                                    <button type="submit" class="btn-act btn-danger">
                                        🗑 Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:12px 22px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ── RENAME DEVICE MODALS (one per assigned device) ─── --}}
@foreach($assigned as $dev)
<div class="modal fade" id="editDev{{ $dev->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none;">
            <form method="POST" action="{{ route('admin.updateDevice', $dev->id) }}">
                @csrf
                <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;">
                    <h5 class="modal-title" style="color:#fff;font-weight:800;font-size:.95rem;">✏️ Rename Device</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <label class="ff-label">Device name</label>
                    <input type="text" name="device_name" class="ff-input"
                           required maxlength="255" value="{{ $dev->device_name }}">
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:9px 12px;margin-top:12px;font-size:.8rem;color:#64748b;">
                        Token (read-only): <code>{{ $dev->device_token }}</code>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:12px 20px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endforeach

{{-- Search JS --}}
<script>
(function () {
    var inp = document.getElementById('famSearch');
    if (!inp) return;
    var rows = Array.from(document.querySelectorAll('#famTbody tr[data-search]'));
    inp.addEventListener('input', function () {
        var q = inp.value.trim().toLowerCase();
        rows.forEach(function (r) {
            r.style.display = (!q || r.dataset.search.includes(q)) ? '' : 'none';
        });
    });
})();
</script>
