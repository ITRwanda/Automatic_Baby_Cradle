# IoTBabyCradle TODO

## Device assignment from Family Members page
- [x] Update plan confirmed for per-member device ownership + reporting.
- [ ] Create migration to ensure `devices.user_id` exists (and nullable + FK to users).
- [ ] Update `app/Models/Device.php` relationship to `User` (assigned member).
- [ ] Update `resources/views/family/members.blade.php` to persist device assignment per member (device select + submit to `family.assignDeviceToMember`).
- [ ] Add/implement unassign per member flow (route + controller method) for `family.reports`.
- [ ] Update `resources/views/family/reports.blade.php` to show member → device mapping + unassign per member.
- [ ] Update `FamilyController::assignDeviceToMember` to clear `family_id`/`user_id` consistently only as needed (already sets both; keep validation).
- [ ] Update `AdminController` assign/unassign-to-family-parent to also clear `devices.user_id` on unassign (consistency).
- [ ] Manual test: verify dropdown appears and assignment persists across parent/member/admin.

