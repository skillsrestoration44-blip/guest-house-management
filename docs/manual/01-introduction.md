# 1. ការណែនាំទូទៅ — Introduction

## 1.1 តើ Guest House Management System (GHMS) ជាអ្វី?

**Guest House Management System** គឺជាប្រព័ន្ធគ្រប់គ្រងផ្ទះសំណាក់ពេញលេញ ដែលរួមបញ្ចូល៖

- ការគ្រប់គ្រង **ច្រើនសាខា** (Multi‑Branch) ក្នុងប្រព័ន្ធតែមួយ
- ការគ្រប់គ្រង **ភ្ញៀវ — Booking — Stay — Invoice — Payment** គ្រប់ដំណាក់កាល
- មុខងារ ISO 9001 (Guest Feedback, Complaint, CAPA, Risk Register, Supplier Scorecard, Document Versions)
- ភាសា **ខ្មែរ និងអង់គ្លេស** ប្ដូរបាន **ដោយមិន refresh page**
- ការកត់ត្រា **Audit Trail** ស្វ័យប្រវត្តិ (រាល់ create / update / delete ត្រូវបានកត់ត្រា)

ប្រព័ន្ធត្រូវបានបង្កើតលើ **Laravel 12** ជាមួយ **Bootstrap 5**, **Yajra DataTables** (server‑side), **SweetAlert2**, **PHPFlasher**, **flatpickr**, **Tom Select**, និង **jQuery AJAX**។

---

## 1.2 ស្តង់ដារ Software (Software Standards)

ការប្រើប្រាស់ប្រព័ន្ធនេះត្រូវគោរពតាមស្តង់ដារដូចតទៅ ដើម្បីរក្សាគុណភាពទិន្នន័យ៖

### 1.2.1 ស្តង់ដារទិន្នន័យ — Data Standards

1. **កុំកែលេខកូដ Auto‑generated**៖ Booking No. (`BK-`), Invoice No. (`INV-`), Payment No. (`PAY-`) ។ល។ — ត្រូវបន្សល់ចេញឱ្យប្រព័ន្ធបង្កើតដោយខ្លួនឯង។
2. **គ្រប់ Action Save/Edit/Delete គឺត្រូវបាន Audit**៖ កុំរំពឹងថាការផ្លាស់ប្ដូរអាចលាក់បានឡើយ — រាល់ការផ្លាស់ប្ដូរត្រូវបានកត់ត្រាដោយ user, IP, time, និងតម្លៃចាស់/ថ្មី។
3. **Branch Scope ត្រូវប្រើជាប់**៖ មុនបង្កើត Booking ថ្មី ត្រូវ **ប្ដូរទៅ Branch ត្រឹមត្រូវ** (មើល Header → Branch Switcher) ដើម្បីកុំឱ្យទិន្នន័យចូលខុស Branch។

### 1.2.2 ស្តង់ដារ ISO 9001 — Quality Management

- **§5.2 Customer Focus**៖ កត់ត្រា **Guest Feedback** ប្រចាំ Stay → ប្រើជាមូលដ្ឋាន KPI។
- **§8.2.1 Customer Communication**៖ ប្រសិនបើ Guest មានបញ្ហា ត្រូវកត់ត្រាជា **Complaint** (មិនមែនជា Note ផ្ទាល់ខ្លួន)។
- **§8.5.2 Corrective Action**៖ រាល់ Complaint ដែលធ្ងន់ធ្ងរ ត្រូវបន្តដោយ **CAPA** (Corrective and Preventive Action) ដែលមាន due date និងការ verify។
- **§6.1 Risk‑based Thinking**៖ Update **Risk Register** ប្រចាំខែ — រាល់ហានិភ័យត្រូវមាន Owner, Mitigation Plan, Review Date។
- **§8.4 Supplier Control**៖ វាយតម្លៃ Supplier ប្រចាំខែតាម **Supplier Scorecard** (Quality, Delivery, Price, Service)។
- **§4.2.3 Document Control**៖ ឯកសារសំខាន់ៗ (Policy, Procedure) ត្រូវបញ្ចូលក្នុង **Document Versions** ជាមួយ version + reviewer + effective date។

### 1.2.3 ស្តង់ដារដំណើរការ — Process Standards

| ដំណាក់កាល | Module ត្រូវប្រើ | លំដាប់ |
|---|---|---|
| Reception ទទួលភ្ញៀវ | Guest → Booking | បង្កើត Guest មុន ហើយបន្ទាប់មក Booking |
| Check‑in | Booking → Stay | កុំ skip Booking — Stay ត្រូវ link ជាមួយ Booking |
| ការស្នាក់នៅ | Service Charge | រាល់ service បន្ថែម (food, laundry) ត្រូវបញ្ចូលជា Service Charge |
| Check‑out | Stay → Invoice | Invoice ត្រូវចេញ មុនពេលអនុញ្ញាតភ្ញៀវចេញ |
| ការទូទាត់ប្រាក់ | Payment → Receipt | Receipt ចេញដោយ **Auto** — កុំបង្កើតដោយដៃ |
| Housekeeping | Housekeeping Task | ត្រូវបញ្ចប់ task មុន assign Booking ថ្មីទៅ Room នោះ |

---

## 1.3 តួនាទីអ្នកប្រើប្រាស់ — User Roles

ប្រព័ន្ធគាំទ្រ **Role‑Based Access Control (RBAC)**។ Role គំរូដែលបានកំណត់៖

| Role | តួនាទីសំខាន់ | Module អាចប្រើ |
|---|---|---|
| **super_admin** | គ្រប់គ្រងគ្រប់ Branch | គ្រប់ Module |
| **admin** | គ្រប់គ្រង Branch មួយ | គ្រប់ Module ក្នុង Branch |
| **receptionist** | Front Desk | Guest, Booking, Stay, Payment, Service Charge |
| **housekeeping** | សម្អាតបន្ទប់ | Housekeeping Tasks (read + update status) |
| **accountant** | គណនេយ្យ | Invoice, Payment, Receipt, Expense, Salary |
| **manager** | គ្រប់គ្រង Operation | Reports, Dashboard, ISO 9001 Modules |

> Customer អាច **កែតួនាទី** តាមតម្រូវការ — សូមមើលជំពូកទី 2។

---

## 1.4 រចនាសម្ព័ន្ធជំពូក — Document Layout

ឯកសារនេះតម្រៀបតាម **លំដាប់ប្រើប្រាស់ប្រាក់ប្រាស់ពិត — User Journey**៖

1. **0. Audit Summary** — លទ្ធផល Audit (សម្រាប់អ្នកគ្រប់គ្រង)
2. **1. Introduction** — ឯកសារនេះ
3. **2. Getting Started** — Login, ចំណេះដឹងរូបរាងអេក្រង់, ការប្ដូរ Branch/Language
4. **3. Core System** — Branches, Staff, Users, Roles, Permissions
5. **4. Room Management** — Room Types, Rooms, Facilities
6. **5. Guest Management** — Guests, Guest Documents
7. **6. Booking Workflow** — បង្កើត Booking, ផ្លាស់ស្ថានភាព
8. **7. Check‑in / Check‑out** — Stays, Room Transfers
9. **8. Invoicing & Payments** — Invoice, Payment, Receipt, Refund
10. **9. Services** — Services, Service Charges
11. **10. Housekeeping** — Tasks, Checklist Items
12. **11. Maintenance** — Maintenance Requests
13. **12. Inventory** — Suppliers, Categories, Items, Movements
14. **13. Accounting** — Expense Categories, Expenses, Salaries
15. **14. Notifications** — Notifications, Templates
16. **15. Website** — Website Pages, Online Booking Requests
17. **16. Quality (ISO 9001)** — Feedback, Complaint, CAPA, Risk, Supplier Scorecard
18. **17. Security & Audit** — Login History, Audit Logs
19. **18. Settings** — Guest House, Code, System, Backups
20. **A. Appendix** — FAQ, Troubleshooting, Glossary

---

## 1.5 និមិត្តសញ្ញាក្នុងឯកសារ — Symbols Used

| និមិត្តសញ្ញា | អត្ថន័យ |
|---|---|
| ✅ | សកម្មភាពត្រឹមត្រូវ |
| ⚠️ | សូមប្រុងប្រយ័ត្ន |
| ❌ | កុំធ្វើ |
| 💡 | Tip ជំនាញ |
| 🔒 | តម្រូវឱ្យមាន Permission ពិសេស |
| 🤖 | ដំណើរការដោយស្វ័យប្រវត្តិ — កុំធ្វើដោយដៃ |
| 📌 | គន្លឹះសំខាន់ |

