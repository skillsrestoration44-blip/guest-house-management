# 17. សុវត្ថិភាព & ការ Audit — Security & Audit

ជំពូកនេះពិពណ៌នាអំពី Login Histories និង Audit Logs ដែលជា backbone នៃ Records Management ISO 9001 §4.2.4 & §7.5.3។

---

## 17.1 Login Histories

**Sidebar**: `Security → Login Histories` ▸ URL: `/admin/login_histories`

### Field

| Field | ការពិពណ៌នា |
|---|---|
| **User** | User account |
| **IP Address** | IP ដែលចូលប្រើ |
| **User Agent** | Browser / Device |
| **Login At** | ថ្ងៃម៉ោងចូល |
| **Logout At** | ថ្ងៃម៉ោងចេញ |
| **Status** | success, failed |
| **Failure Reason** | មូលហេតុ (បើ failed) |

### Workflow & Auto-Logic

```
1. User ព្យាយាមចូល → System កត់ Login History (auto)
2. បើជោគជ័យ → status=success, Login At = now
3. បើបរាជ័យ → status=failed, Failure Reason = "invalid password" / "account locked"
4. User logout → Logout At = now
5. Session expired → Logout At = expiry time (auto)
```

> 🔒 **Security Tip**: ស្វែងរក Login Histories ដែលមាន `status=failed` ច្រើនពេក → ប្រហែលជា brute-force attack។ Lock account ភ្លាមៗ។

---

## 17.2 Audit Logs

**Sidebar**: `Security → Audit Logs` ▸ URL: `/admin/audit_logs`

ISO 9001 §4.2.4 Control of Records — រាល់ការផ្លាស់ប្ដូរទិន្នន័យត្រូវកត់ត្រា។

### Field

| Field | ការពិពណ៌នា |
|---|---|
| **User** | អ្នកធ្វើ |
| **Module** | Model name (Booking, Stay, Invoice...) |
| **Action** | created, updated, deleted |
| **Auditable Type** | Class name |
| **Auditable ID** | Record ID |
| **Old Values** | JSON before |
| **New Values** | JSON after |
| **IP Address** | IP |
| **User Agent** | Browser |
| **Created At** | Timestamp |

### Auto-Generation (AuditObserver)

```
រាល់ Model ដែលមាន `Auditable` trait → AuditObserver fires:
- created: New Values = full attributes; Old Values = null
- updated: Old Values + New Values (តែ field ដែលផ្លាស់ប្ដូរ)
- deleted: Old Values = last state; New Values = null
- User = Auth::user(); IP = Request::ip(); UA = Request::userAgent()
```

> 🤖 **Auto-Logic**: គ្រប់ CRUD លើ Models 41+ បាន observe ដោយស្វ័យប្រវត្តិ។ មិនមាន code ចាំបាច់នៅក្នុង Controllers។

### Filter & Search

| Filter | ប្រើសម្រាប់ |
|---|---|
| **Module** | មើលតែ Booking ឬ Invoice ឯករាជ្យ |
| **Action** | មើលតែ deleted (សុវត្ថិភាព) |
| **User** | មើលថា Manager X ធ្វើអ្វីខ្លះថ្ងៃនេះ |
| **Date Range** | មើលប្រវត្តិ​ ៣០ ថ្ងៃ |
| **Auditable ID** | Track record ជាក់លាក់មួយ (e.g. Booking 123) |

### Forensic Investigation

```
1. Guest ត្អូញ​ ថា Booking របស់ខ្លួនត្រូវកែ
2. Filter Audit Logs: Module=Booking, Auditable ID=123
3. មើល Action=updated entries → ដឹង User មួយណាកែ, ពេលណា, កែអ្វី
4. Compare Old Values vs New Values
5. បើខុស → ប្រើ Old Values ដើម្បី restore
```

---

## 17.3 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| ត្រួតពិនិត្យ Failed Logins ប្រចាំថ្ងៃ | កុំ ignore brute-force pattern |
| Filter Audit Logs តាម Action=deleted រាល់សប្ដាហ៍ | កុំ disable AuditObserver |
| ប្រើ Audit Logs ដើម្បី train staff ថ្មី | កុំ edit Audit Logs ដោយដៃ |
| រក្សា Audit Logs យ៉ាងតិច ៣ ឆ្នាំ (ISO 9001) | កុំ delete old logs ដោយគ្មាន archive |
| Set strong password policy + 2FA | កុំ share user account |

> 📌 **ISO 9001 §4.2.4 Note**: Records ត្រូវ legible, identifiable, retrievable, និងការពារពី loss/damage។ Audit Logs ត្រូវ​ ​​ retain យ៉ាងតិច ៣ ឆ្នាំ ឬតាមតម្រូវការ​ ​ច្បាប់​ ​​ មូលដ្ឋាន។
