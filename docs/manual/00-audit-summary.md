# 0. លទ្ធផល Audit សង្ខេប — Audit Summary

> ឯកសារនេះសង្ខេបលទ្ធផលនៃការ Audit ដែលធ្វើឡើងមុនពេលរៀបចំ User Manual
> ដើម្បីបញ្ជាក់ថា លក្ខណៈពិសេសទាំងឡាយដែលបានពណ៌នាក្នុងសៀវភៅណែនាំ
> គឺសាកសមនឹងកូដនៃ System ប្រាកដ។

---

## A. វិសាលភាព Audit (Audit Scope)

ការ Audit នេះបានគ្របដណ្ដប់៖

| ផ្នែក | ចំនួន/វិសាលភាព |
|---|---|
| តារាងក្នុង Database (Migration) | **58 តារាង** (ដូចមាននៅក្នុង `2026_05_06_000000_create_guest_house_management_system_tables.php` និង migration ពហុ‑សាខា) |
| Eloquent Models | **56 models** (រួមទាំង pivot/sub‑detail) |
| Admin Controllers | **46 controllers** ដែលផ្ដល់ CRUD ពេញ + 5 controllers ISO 9001 |
| Routes | `routes/web.php` — `Route::resource()` រាល់ Module + `/locale/switch`, `/branch/switch`, `/admin/login` |
| Observers (Auto‑logic) | `BookingObserver`, `StayObserver`, `PaymentObserver`, `InvoiceItemObserver`, `AuditObserver` |
| Service Layer | `CodeGeneratorService` (auto‑code generation) |
| Middleware | `AdminAuthenticate`, `SetCurrentBranch`, `SetLocale`, `CheckPermission` |
| Language Files | `lang/en/messages.php` និង `lang/km/messages.php` — KH/EN ពេញ |
| Sidebar / Layout | `resources/views/admin/layouts/admin_partials/left_sidebar.blade.php` (15 Section, 41+ Module) |

---

## B. Workflow Automation ដែលបានផ្ទៀងផ្ទាត់ (Verified Auto‑Logic)

កម្មវិធីនេះមាន **business logic ស្វ័យប្រវត្តិ** ដើម្បីការពារ Customer ពីការ‌បំពេញខុសដោយដៃ៖

### B.1 ការបង្កើតលេខកូដស្វ័យប្រវត្តិ — Auto‑code generation

រាល់ Booking, Stay, Invoice, Payment, Receipt, Risk, CAPA, Feedback, Complaint, Housekeeping Task ត្រូវបានបង្កើតលេខកូដ (e.g. `BK-000123`) ដោយ `CodeGeneratorService` តាមរយៈ **`code_settings` table**។

| Module | Prefix | ឧទាហរណ៍ |
|---|---|---|
| Guest | `G-` | `G-00001` |
| Booking | `BK-` | `BK-000001` |
| Stay | `ST-` | `ST-000001` |
| Invoice | `INV-` | `INV-000001` |
| Payment | `PAY-` | `PAY-000001` |
| Receipt | `RCP-` | `RCP-000001` |
| Refund | `REF-` | `REF-000001` |
| Expense | `EXP-` | `EXP-000001` |
| Maintenance Request | `REQ-` | `REQ-000001` |
| Housekeeping Task | `HK-` | `HK-000001` |
| Guest Feedback | `FB-` | `FB-000001` |
| Complaint | `CMP-` | `CMP-000001` |
| Corrective Action | `CAPA-` | `CAPA-000001` |
| Risk | `RSK-` | `RSK-000001` |

> **កំណត់ចំណាំ**៖ Customer **មិនត្រូវកែ** លេខកូដដោយដៃទេ — ធ្វើការតាម Standard នេះ បានន័យថាកុំ override ប្រអប់ `booking_no` ក្នុង Booking Form ឱ្យសោះ។

### B.2 ការតាមដានស្ថានភាព Booking — Booking Status History

រាល់ពេល Booking ផ្លាស់ស្ថានភាព (e.g. `pending → confirmed → checked_in → checked_out`) **`BookingObserver` បង្កើត row មួយដោយស្វ័យប្រវត្តិ** ក្នុង `booking_status_histories` (បង្ហាញនៅ Booking Show page)។ → ផ្ដល់ **Audit Trail** តាមស្តង់ដារ ISO 9001 §4.2.4។

### B.3 Stay Check‑out → Housekeeping Task

នៅពេល Stay status ប្រែទៅ `checked_out`, `StayObserver` ៖
1. កំណត់ Room status → `cleaning`
2. បង្កើត `HousekeepingTask` (ប្រសិនបើគ្មាន task pending) ដោយស្វ័យប្រវត្តិ → លេខ `HK-000xxx`

### B.4 Payment → Receipt → Invoice Recalculate

នៅពេលបន្ថែម Payment (`status=completed` និងភ្ជាប់ Invoice)៖
1. `PaymentObserver` បង្កើត **Receipt** ដោយស្វ័យប្រវត្តិ
2. ហៅ `Invoice::recalculate()` — គណនាឡើងវិញ៖ `room_total`, `service_total`, `damage_total`, `paid_amount`, `balance_due`, និងកំណត់ `status` ទៅជា `paid` / `partial` / `unpaid` ដោយផ្អែកលើ payments + refunds។

### B.5 Invoice Items → Auto Total

`InvoiceItemObserver` គណនា `total = quantity × unit_price` រាល់ពេល saving + ហៅ `Invoice::recalculate()` បន្ទាប់ពី save — Customer **គ្មានទេ** ត្រូវបញ្ចូល `total` ខ្លួនឯង។

### B.6 Risk Auto‑Score

`Risk` model មាន `static::saving()` callback៖ `risk_score = likelihood × impact` ដោយស្វ័យប្រវត្តិ។

### B.7 Audit Log

`AuditObserver` ភ្ជាប់ទៅ models ចំនួន **41+** (រួមមាន 6 ISO 9001 models) — រាល់ create / update / delete ត្រូវបានកត់ត្រាក្នុង `audit_logs` ដោយរួមបញ្ចូល `user_id`, `action`, `module`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`។ → ផ្ដល់ **Records Management** តាម ISO 9001 §4.2.4។

---

## C. ស្តង់ដារ ISO 9001 ដែលបាន Implement (ISO 9001 Compliance Map)

| ISO 9001 Requirement | Module ក្នុងប្រព័ន្ធ | ការអនុវត្ត |
|---|---|---|
| §5.2 Customer Focus | Guest Feedback | ✅ ជាមួយរង្វាយតម្លៃ 1‑5 + តាមដាន `published` |
| §8.2.1 Customer Communication | Complaints | ✅ មាន category + severity + status workflow |
| §8.5.2 Corrective Action | Corrective Actions (CAPA) | ✅ ភ្ជាប់ទៅ Complaint/Feedback + due date |
| §6.1 Risk‑based Thinking | Risks (Risk Register) | ✅ Likelihood × Impact + auto score + owner |
| §8.4 Supplier Control | Supplier Scorecards | ✅ ប្រចាំខែ + 4 KPI (quality, delivery, price, service) |
| §4.2.3 Document Control | Document Versions | ✅ Version + reviewer + effective date |
| §4.2.4 Records Management | Audit Logs (1,150+ entries seeded) | ✅ |
| §6.2 Human Resources | Staff + Roles + Permissions | ✅ RBAC តាម role ផ្ទាល់ |
| §7.5.5 Preservation | Backups + System Settings | ✅ |
| §8.2.4 Monitoring of Process | Login Histories | ✅ |
| §9.1 Performance Indicators | Dashboard Counts | ✅ (ដំណាក់កាលដំបូង) |

> **ISO 9001 Readiness ≈ 80%** (ដូចបង្ហាញនៅ `AUDIT_REPORT.md`)។

---

## D. Multi‑Branch & Multi‑Language ស្តង់ដារ

- **Multi‑Branch**៖ `BelongsToBranch` trait + Header **Branch Switcher** + `SetCurrentBranch` middleware → គ្រប់ DataTable និង Action ត្រូវបាន **scoped ដោយ `branch_id`** ដូច្នេះ Branch មួយ មិនអាចមើលទិន្នន័យ Branch ផ្សេងបានទេ (លើកលែង Super‑admin)។
- **Multi‑Language**៖ KH/EN ដោយ **POST `/locale/switch`** → ត្រឡប់ JSON translations → jQuery ប្ដូរ `[data-i18n]` និង `[data-i18n-placeholder]` **ដោយមិនចាំបាច់ refresh page**។ → DataTables reload ដោយ `ajax.reload(null, false)` រក្សាទំព័របច្ចុប្បន្ន។

---

## E. ការផ្ទៀងផ្ទាត់ (Verification Evidence)

| ការផ្ទៀងផ្ទាត់ | លទ្ធផល |
|---|---|
| `php artisan migrate:fresh --seed` | ជោគជ័យ ~9 វិនាទី |
| ចំនួន Audit Logs បន្ទាប់ពី seed | **1,154+ entries** |
| ស្ថានភាព Invoice បន្ទាប់ពី seed | 15 paid + 6 partial (auto via `PaymentObserver`) |
| ការ test browser ពេញលេញ | រូបថតអេក្រង់ + report ក្នុង `test-report.md`, `test-report-seeders.md` |
| ការប្រកាសមុខងារ KH/EN ក្នុង DataTable headers | ពេញលេញ (PR #3) |
| ការ render DataTables (window.__ fix) | ពេញលេញ (PR #4) |
| AuditObserver សម្រាប់ ISO 9001 models | ពេញលេញ (PR #5) |
| Database Seeders សម្រាប់ 58 តារាង | ពេញលេញ (PR #6) |

---

## F. ការសន្មត់សម្រាប់ Manual

ឯកសារ User Manual ខាងក្រោមមាន **ការសន្មត់**ដូចតទៅ៖
1. Customer ប្រើ browser ទំនើប (Chrome, Firefox, Edge) ជាមួយ JavaScript បើក។
2. Customer ត្រូវមាន User Account មួយ មាន role យ៉ាងតិច `staff`។
3. Customer បានចូល branch ត្រឹមត្រូវ (ឬជា Super‑admin) មុនធ្វើការ។
4. Server URL ត្រូវបានផ្ដល់ដោយ administrator (ឧ. `https://app.your-domain.com/admin`)។
5. KH/EN — Customer អាចប្ដូរបានគ្រប់ពេល មិនប៉ះពាល់ដល់ data។
