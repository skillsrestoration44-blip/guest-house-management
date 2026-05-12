# 3. ប្រព័ន្ធស្នូល — Core System

ផ្នែក "Core" គឺជាមូលដ្ឋាននៃប្រព័ន្ធទាំងមូល — បន្ទាប់ពី Admin install ប្រព័ន្ធ​ ត្រូវ​ setup សាខា Staff Users Roles មុនជានិច្ច។

---

## 3.1 Branches — សាខា

**Sidebar**: `Core → Branches` ▸ URL: `/admin/branches`

### 3.1.1 តើ Branch មានសារៈសំខាន់អ្វី?

រាល់ទិន្នន័យ (Booking, Stay, Invoice, Payment, Guest, Room, ល.) ត្រូវបានភ្ជាប់ទៅ **Branch មួយ**។ បើ Customer មាន 3 ផ្ទះសំណាក់ ត្រូវបង្កើត Branch 3 រួចបន្ទាប់មកទើបបន្ត។

### 3.1.2 Field សំខាន់ក្នុង Form

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Code** | លេខកូដខ្លី (e.g. `BR-MAIN`, `BR-BB`) — ត្រូវ unique | ✅ |
| **Name** | ឈ្មោះពេញ​សាខា (e.g. "Main Branch — Phnom Penh") | ✅ |
| **Address** | អាសយដ្ឋានពេញ​លេញ | ✅ |
| **Phone** | លេខទូរសព្ទ | |
| **Email** | អ៊ីមែលសាខា | |
| **Is Default** | គ្រើស​ ✅ មួយប៉ុណ្ណោះ​ — សម្រាប់ Branch លំនាំដើមនៅ Login ដំបូង | |
| **Status** | `active` / `inactive` | ✅ |

### 3.1.3 ដំណើរការបង្កើត Branch ថ្មី

```mermaid
1. Sidebar → Core → Branches
2. ចុច "+ Create Branch"
3. បំពេញ Form (Code, Name, Address, ...)
4. ជ្រើស Status = active
5. Save
6. បន្ទាប់មក ប្ដូរ Branch Switcher ទៅ Branch ថ្មី → ចាប់ផ្ដើមបង្កើត Room, Staff, Guest ។ល។
```

> ⚠️ **Don't**៖ កុំ​លុប Branch ដែល​មាន Booking ឬ Invoice — ប្រព័ន្ធនឹង error ដោយសារ FK Constraint។ ​ត្រូវ​ប្ដូរ Status = `inactive` វិញ។

---

## 3.2 Staff — និយោជិត

**Sidebar**: `Core → Staff` ▸ URL: `/admin/staff`

Staff គឺជាបុគ្គលិកដែលធ្វើការ​ផ្ទាល់ — មានឬគ្មាន User Account ក៏បាន (e.g. Housekeeper អាចគ្មាន Login)។

### 3.2.1 Field សំខាន់

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | សាខា Staff ធ្វើការ |
| **Staff Code** | លេខកូដ (e.g. `STF-0001`) |
| **Full Name** | ឈ្មោះពេញ |
| **Gender** | ប្រុស / ស្រី |
| **Phone** | លេខទូរសព្ទ |
| **Email** | សម្រាប់ផ្សារភ្ជាប់ User Account |
| **Position** | តួនាទី (e.g. Receptionist, Housekeeper, Manager) |
| **Salary** | ប្រាក់ខែ (សម្រាប់ Salary Module) |
| **Hire Date** | ថ្ងៃចូលធ្វើការ |
| **Status** | active / inactive (resigned) |

### 3.2.2 Best Practice

- ✅ បំពេញ **Hire Date** ដើម្បីគណនា tenure និង anniversary
- ✅ ប្រសិនបើ Staff ឈប់ធ្វើការ ត្រូវ ប្ដូរ **Status = inactive** ​ ហើយ User Account ដែលភ្ជាប់ត្រូវប្ដូរ​ស្ថានភាព​ផងដែរ
- ❌ កុំ​លុប Staff ដែលមាន Salary records ឬ Attendance — ​ត្រូវ inactivate វិញ

---

## 3.3 Staff Attendance — ការមកធ្វើការ

**Sidebar**: `Core → Staff Attendance` ▸ URL: `/admin/staff_attendances`

ប្រើដើម្បីកត់ត្រាការមកធ្វើការ​​ប្រចាំថ្ងៃរបស់ Staff។

### 3.3.1 Field

| Field | ការពិពណ៌នា |
|---|---|
| **Staff** | ជ្រើស Staff (Tom Select) |
| **Attendance Date** | ថ្ងៃ (flatpickr) |
| **Check‑in Time** | ម៉ោងមកដល់ |
| **Check‑out Time** | ម៉ោងចេញ |
| **Status** | `present`, `absent`, `late`, `leave`, `holiday` |
| **Note** | កំណត់ចំណាំ |

### 3.3.2 Workflow ប្រចាំថ្ងៃ

```
ព្រឹក     →  Receptionist ឬ Manager បើក Module នេះ
          →  ចុច + Create
          →  ជ្រើស Staff + Status = present + Check-in Time
          →  Save

ល្ងាច    →  ត្រឡប់មក Edit row ដដែល
          →  បំពេញ Check-out Time
          →  Save
```

> 💡 **Tip**៖ Manager អាច filter តាម `Status = absent` ដើម្បីកត់ត្រា​ស្ថានភាព​​​​បុគ្គលិកដែលមិនមកធ្វើការ — បន្ទាប់មកបន្ត Salary deduction ។ល។​ ក្នុងជំពូកទី 13។

---

## 3.4 Users — អ្នកប្រើប្រាស់

**Sidebar**: `Core → Users` ▸ URL: `/admin/users`

User គឺជាគណនី Login។ User ត្រូវភ្ជាប់ទៅ Staff (តាមរយៈ `staff_id`) និងអាចមាន Role ច្រើន។

### 3.4.1 Field

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | Branch ដែល User មានសិទ្ធិ​ប្រើ​ (Super Admin = អាច​លុប​ field នេះ) |
| **Staff** | ជ្រើស Staff record (optional) |
| **Name** | ឈ្មោះបង្ហាញ |
| **Username** | ឈ្មោះ user (unique) |
| **Email** | អ៊ីមែល (unique) |
| **Phone** | |
| **Password** | ⚠️ ប្ដូរបាន តែ​​​ចំពោះ User មួយដែលបង្កើត​ឬ ​ ​ User ខ្លួនឯង |
| **Roles** | Tom Select (multi) — ជ្រើស Role ដែល User មាន |
| **Status** | active / inactive |

### 3.4.2 Best Practice

- ✅ បង្កើត User មួយ​ឱ្យ​​​ Staff មួយ — មិនចែករំលែក​ User មួយ ក្នុងបុគ្គលិកច្រើន
- ✅ ប្ដូរ Password ភ្លាមៗបន្ទាប់ពី Login លើកដំបូង
- ✅ Assign Role ត្រឹមតែ​អ្វីដែលត្រូវការ (Principle of Least Privilege)
- ❌ កុំ assign Super Admin role ឱ្យ Receptionist
- 🔒 រាល់ការប្រែប្រួល User ត្រូវបានកត់​ត្រា​ Audit Log

### 3.4.3 ការ Reset Password

```
1. Login ជា Admin
2. Sidebar → Core → Users → ស្វែងរក User
3. ចុច 🖉 Edit
4. បំពេញ Password ថ្មី (បន្សល់ច្បាប់​ Validation: យ៉ាងតិច 8 តួអក្សរ)
5. Save
6. ប្រាប់ User ឱ្យ Login ដោយ Password ថ្មី
```

---

## 3.5 Roles — តួនាទី

**Sidebar**: `Core → Roles` ▸ URL: `/admin/roles`

Role គឺជា​ "package" នៃ Permissions — ផ្ដល់ឱ្យ User តាមរយៈ Many-to-Many `role_user`។

### 3.5.1 Role គំរូដែលបាន seed

| Role Name | Description |
|---|---|
| `super_admin` | គ្រប់សិទ្ធិ — គ្រប់ Branch |
| `admin` | គ្រប់សិទ្ធិក្នុង Branch |
| `receptionist` | Booking, Guest, Stay, Service Charge |
| `housekeeping` | Housekeeping Tasks |
| `accountant` | Invoice, Payment, Receipt, Refund, Expense, Salary |
| `manager` | Read‑all + Reports |

### 3.5.2 បង្កើត Role ថ្មី

```
1. Sidebar → Core → Roles → + Create
2. បំពេញ Name (e.g. `auditor`), Description
3. Save
4. បន្ទាប់មក ភ្ជាប់ Permissions តាមជំពូក 3.6
```

---

## 3.6 Permissions — សិទ្ធិ

**Sidebar**: `Core → Permissions` ▸ URL: `/admin/permissions`

Permission គឺជា​សកម្មភាពមួយៗ (e.g. `booking.create`, `invoice.delete`)។

### 3.6.1 Naming Convention

| Pattern | ឧទាហរណ៍ |
|---|---|
| `<module>.view` | មើល list នៃ Module |
| `<module>.create` | បង្កើត record ថ្មី |
| `<module>.update` | កែប្រែ record |
| `<module>.delete` | លុប record |
| `<module>.show` | មើល detail | 

### 3.6.2 ភ្ជាប់ Permission ទៅ Role

```
1. Sidebar → Core → Roles
2. ចុច 🖉 Edit លើ Role
3. នៅ field "Permissions" (Tom Select) ជ្រើស Permission ដែលត្រូវការ
4. Save
5. ប្រព័ន្ធនឹងព្រួត​ឯង​​ ៖
   - User ដែលមាន Role នេះ នឹង​​អាចចូល Module នោះ
   - User ដែលគ្មាន permission នឹងឃើញ HTTP 403
```

### 3.6.3 Middleware `CheckPermission`

ប្រព័ន្ធប្រើ middleware `CheckPermission` ដែលភ្ជាប់ទៅ Route — ​​ បង្ហាញ​ Error 403 បើ User គ្មាន permission។ Customer **​​មិនបាច់​ configure** middleware ​ដោយផ្ទាល់​ — Admin developer ​ដាក់ឱ្យ​​ហើយ។

---

## 3.7 Workflow ស្តង់ដារ ការ Setup System ដំបូង

```
Step 1: បង្កើត Branches  (3.1)
   ↓
Step 2: បង្កើត Roles + Permissions  (3.5, 3.6)
   ↓
Step 3: បង្កើត Staff  (3.2)
   ↓
Step 4: បង្កើត Users + assign Roles  (3.4)
   ↓
Step 5: ប្ដូរ Default Admin Password  (3.4.3)
   ↓
Step 6: បន្ត Setup → Rooms (ជំពូកទី 4)
```
