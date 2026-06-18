# TODO - IoTBabyCradle (Role restructuring: member -> caregiver)

## Step 1: Update roles
- [ ] Update `database/seeders/RoleSeeder.php` to seed `admin`, `family_parent`, `caregiver` only.

## Step 2: Replace middleware
- [ ] Remove/replace `app/Http/Middleware/FamilyMemberMiddleware.php` with `CaregiverMiddleware`.
- [ ] Update `app/Http/Kernel.php` route middleware mapping.

## Step 3: Update routes
- [ ] Remove `/member` route group from `routes/web.php`.
- [ ] Add `/caregiver` route group using new middleware and new controller.

## Step 4: Controllers + views
- [ ] Remove `app/Http/Controllers/MemberController.php`.
- [ ] Add `app/Http/Controllers/CaregiverController.php` (dashboard/reports/notifications) using `caregiver.*` views.
- [ ] Rename `resources/views/member/*` -> `resources/views/caregiver/*`.

## Step 5: Family parent caregiver management
- [ ] Update `app/Http/Controllers/FamilyController.php` to manage `caregivers` instead of `members`.
- [ ] Update routes, method names, and role lookups from `family_member` to `caregiver`.
- [ ] Rename `resources/views/family/members.blade.php` -> `resources/views/family/caregivers.blade.php`.

## Step 6: Layout navigation
- [ ] Update `resources/views/layouts/app.blade.php` to show caregiver navigation instead of member.

## Step 7: Clean references
- [ ] Update any remaining references to `family_member`, `member.*` routes, and `/member` URLs across the repo.

## Step 8: Verification
- [x] Run `php artisan route:list` to confirm routes.
- [ ] Run a smoke test: login as each role and ensure correct access.


