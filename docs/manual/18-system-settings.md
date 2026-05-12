# 18. ការកំណត់ប្រព័ន្ធ — System Settings

ជំពូកនេះណែនាំអំពី Guest House Settings, Code Settings, System Settings និង Backups។

---

## 18.1 Guest House Settings

**Sidebar**: `Settings → Guest House Settings` ▸ URL: `/admin/guest_house_settings`

ការកំណត់ទូទៅសម្រាប់ Branch នីមួយៗ។

### Field

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | Branch (one record per branch) |
| **Name** | ឈ្មោះ Guest House |
| **Address** | អាសយដ្ឋាន |
| **Phone** | លេខទូរស័ព្ទ |
| **Email** | អ៊ីមែល |
| **Website** | URL |
| **Tax ID** | លេខពន្ធ |
| **Currency** | USD / KHR / Other |
| **Logo** | រូបភាព Logo |
| **Check-in Time** | ម៉ោងចូលស្តង់ដារ (e.g. 14:00) |
| **Check-out Time** | ម៉ោងចេញស្តង់ដារ (e.g. 12:00) |

> 📌 ការកំណត់ទាំងនេះបង្ហាញនៅលើ Invoice, Receipt និង Email Templates។

---

## 18.2 Code Settings

**Sidebar**: `Settings → Code Settings` ▸ URL: `/admin/code_settings`

គ្រប់គ្រង Auto-Code Prefix និង Padding សម្រាប់រាល់ module។

### Field

| Field | ការពិពណ៌នា | ឧទាហរណ៍ |
|---|---|---|
| **Module** | Module name | Booking, Invoice, Risk, CAPA |
| **Prefix** | អក្សរនាំមុខ | BK-, INV-, RSK-, CAPA- |
| **Padding** | ចំនួនលេខ (zero-padded) | 6 → 000001 |
| **Separator** | ភ្ជាប់ Prefix & Number | `-` ឬ `/` |
| **Reset Period** | យ៉ាងណានឹង reset | yearly / monthly / never |

### Auto-Logic

```
CodeGeneratorService::generate('Booking') returns "BK-000037":
1. Read CodeSetting: prefix='BK-', padding=6, reset='never'
2. Count existing Bookings → 36
3. Return "BK-" + str_pad(37, 6, '0', STR_PAD_LEFT) = "BK-000037"
```

> ⚠️ **Caution**: កុំប្តូរ Prefix ពេលមាន records ហើយ — នឹងធ្វើឱ្យកូដ legacy ច្រឡំ។ ប្រសិនបើត្រូវប្តូរ → set reset_period=yearly ហើយប្ដូរនៅដើមឆ្នាំ។

---

## 18.3 System Settings

**Sidebar**: `Settings → System Settings` ▸ URL: `/admin/system_settings`

Key-value store សម្រាប់ការកំណត់សកល។

### គំរូ Settings

| Key | Value Example | ការប្រើ |
|---|---|---|
| `default_locale` | `km` ឬ `en` | Default language for new users |
| `timezone` | `Asia/Phnom_Penh` | Timestamp display |
| `tax_rate` | `10` (percent) | Auto-calculate VAT on invoices |
| `service_charge_rate` | `5` (percent) | Auto-apply on stays |
| `currency_symbol` | `$` ឬ `៛` | Display on invoices |
| `password_min_length` | `8` | Validation on user registration |
| `session_timeout` | `120` (minutes) | Auto-logout |
| `backup_retention_days` | `30` | Auto-delete old backups |

---

## 18.4 Backups

**Sidebar**: `Settings → Backups` ▸ URL: `/admin/backups`

ការ Backup Database & Files សម្រាប់ Disaster Recovery (ISO 9001 §7.5.3)។

### Field

| Field | ការពិពណ៌នា |
|---|---|
| **Backup No.** | Auto (`BAK-000001`) |
| **Type** | database, files, full |
| **File Path** | ទីតាំង backup file |
| **File Size** | ទំហំ (MB) |
| **Status** | running, completed, failed |
| **Started At** | ពេលចាប់ផ្តើម |
| **Completed At** | ពេលបញ្ចប់ |
| **Created By** | User / system (cron) |

### Workflow

```
1. Schedule daily backup (cron: 02:00 AM)
2. + Create Backup → status=running
3. mysqldump / SQLite copy → status=completed
4. File size logged
5. បើ retention_days hit → Auto-delete old backup
6. Manual restore: Admin downloads backup → restore via terminal
```

### Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| Schedule daily database backup | កុំ skip backup នៅពេល deploy |
| Test restore ប្រចាំខែ | កុំ rely on backup ដែលមិនធ្លាប់ test |
| Store backups off-site (cloud) | កុំ keep backups តែនៅ server ដូចគ្នា |
| Encrypt sensitive backups | កុំ commit backups ទៅ git |
| Monitor backup status alerts | កុំ ignore failed backups |

> 📌 **ISO 9001 §7.5.3 Records Control**: Backups ត្រូវការពារពី loss, damage និងការ access ដែលគ្មានសិទ្ធិ។

---

## 18.5 Permissions & Roles (Recap)

មើល Chapter 3 (Core System) ដើម្បីគ្រប់គ្រង៖
- **Roles**: Admin, Manager, Receptionist, Housekeeping, Accountant
- **Permissions**: Granular per-module (view, create, edit, delete)
- **RBAC enforcement**: `CheckPermission` middleware ការពាររាល់ admin route

> 🔒 **Best Practice**: Apply principle of least privilege — User ទទួលតែ permissions ដែលត្រូវការសម្រាប់ការងារប៉ុណ្ណោះ។
