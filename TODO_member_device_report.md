# TODO: Per-member device assignment on Family report

## Current limitation
- Your `devices` table currently only has `family_id`.
- Family members assignment UI cannot persist which member owns the device.

## Required DB change
- Add a column to `devices`:
  - Option A: `member_id` (recommended name) referencing `users.id`
  - Option B: `user_id` referencing `users.id`

## After DB change
- Update `FamilyController@assignDeviceToMember` to set the new column.
- Update admin family-parent assign/unassign to set the same column.
- Update `resources/views/family/reports.blade.php` to render a table:
  - Member name
  - Device name/token
  - Unassign action per member

## Files likely affected
- `database/migrations/*` (new migration)
- `app/Http/Controllers/FamilyController.php`
- `app/Http/Controllers/AdminController.php`
- `resources/views/family/reports.blade.php`
- `routes/web.php` (if unassign-per-member needs a new route)

