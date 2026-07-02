# TODO - Export reports (CSV + PDF) + summary cards

## Step 1: Repo understanding
- [x] Locate existing mega report and family/member report Blade files
- [x] Locate existing report controllers and filter logic
- [x] Confirm PDF library presence in composer.json (none found)

## Step 2: CSV export for Mega report (Admin)
- [ ] Add controller method to generate streamed CSV
- [ ] Add routes for CSV download
- [ ] Add export buttons in `resources/views/admin/mega_report.blade.php`

## Step 3: CSV export for Family + Member reports
- [ ] Add controller methods for family + caregiver/member CSV downloads
- [ ] Add routes for CSV download
- [ ] Add export buttons in family + member report Blade files

## Step 4: “PDF” export implementation
- [ ] Implement A4 portrait **print-view HTML** download endpoint (dependency-free fallback)
- [ ] Add dedicated Blade print view for mega + family/member exports (same filters)



## Step 5: Professional summary cards for Admin Mega report
- [ ] Add count cards: total incidents, devices involved, date range, event breakdown
- [ ] Add export buttons (CSV + PDF) next to filters for Mega report


## Step 6: Verification
- [ ] Verify downloads preserve filters
- [ ] Verify pages render without errors

