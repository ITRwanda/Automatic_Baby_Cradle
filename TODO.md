# TODO

- [x] Inspect existing admin reports (routes/controller/views).
- [x] Add `admin.megaReports` route.
- [x] Add `AdminController::megaReports()` to query device activities with device+family eager loading and apply filters.
- [x] Add missing relationships in `DeviceActivity` model.
- [ ] Ensure DB schema supports `device_activities.device_id`, `event_type`, `payload`.
- [ ] Fix/avoid migration duplication: one migration already created `device_activities` table; the added migration should only add columns.
- [ ] Run migrations cleanly (or manually verify columns exist).
- [ ] Validate `/admin/mega/reports` loads and displays incident rows with family + device info.

