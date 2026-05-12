# 13. ការគ្រប់គ្រងហិរញ្ញវត្ថុ — Accounting

ផ្នែកនេះគ្រប់គ្រង​ Expenses, Salaries, និង Payroll សម្រាប់ tracking ការចំណាយ​​ប្រចាំថ្ងៃ​/​ប្រចាំខែ។

---

## 13.1 Expense Categories — ប្រភេទការចំណាយ

**Sidebar**: `Accounting → Expense Categories` ▸ URL: `/admin/expense_categories`

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | សាខា |
| **Category Code** | លេខកូដ |
| **Name** | ឈ្មោះ |
| **Description** | ការពិពណ៌នា |
| **Budget Amount** | ថវិកា​កំណត់​​​​ (optional) |
| **Is Active** | បើដំណើរការ |

### 13.1.1 ឧទាហរណ៍ Categories

| Code | Name | Budget Amount |
|---|---|---|
| UTIL | Utilities (Water, Electricity, Internet) | 500.00/month |
| MAINT | Maintenance Supplies | 300.00/month |
| FOOD | F&B Purchases | 800.00/month |
| TRANS | Transportation | 200.00/month |
| OFFICE | Office & Admin | 150.00/month |
| MISC | Miscellaneous | 100.00/month |

---

## 13.2 Expenses — ការចំណាយ

**Sidebar**: `Accounting → Expenses` ▸ URL: `/admin/expenses`

### 13.2.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Expense No.** | Auto (`EXP-000001`) | 🤖 |
| **Expense Category** | ប្រភេទ (Tom Select) | ✅ |
| **Amount** | ចំនួនទឹកប្រាក់ | ✅ |
| **Expense Date** | ថ្ងៃចំណាយ | ✅ |
| **Description** | ការពិពណ៌នា | ✅ |
| **Vendor / Supplier** | អ្នកផ្គត់ផ្គង់ (optional) | |
| **Receipt Photo** | Upload (optional) | |
| **Payment Method** | វិធីសាស្ត្រ (cash, bank, etc.) | |
| **Approved By** | Manager/Admin | |
| **Status** | `pending`, `approved`, `rejected` | ✅ |
| **Note** | កំណត់ចំណាំ | |

### 13.2.2 Workflow

```
1. Staff ​ចំណាយ → រក្សា​ Receipt → Sidebar → Expenses → + Create
2. បំពេញ Form:
   ▸ Expense Category: Utilities
   ▸ Amount: 85.00
   ▸ Expense Date: 2026-05-06
   ▸ Description: "Electricity bill — May 2026"
   ▸ Receipt Photo: upload
   ▸ Status: pending
   ▸ Save → Auto EXP-000xxx

3. Accountant/Manager → Edit → ផ្ទៀងផ្ទាត់ → Status = approved
4. Expense ​ត្រូវបាន​​ record ​ក្នុង​​ Audit Logs → Financial reporting
```

> 📌 **Note**: Expenses ភ្ជាប់ទៅ Branch — ដូច្នេះ Manager អាច Filter Expenses តាម Branch ដើម្បី​​​​ ​​​ ​​​compare ​ការចំណាយ​​​ រវាង​ Branches។

---

## 13.3 Salaries — ប្រាក់ខែ

**Sidebar**: `Accounting → Salaries` ▸ URL: `/admin/salaries`

### 13.3.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Staff** | បុគ្គលិក (Tom Select) | ✅ |
| **Basic Salary** | ប្រាក់ខែគោល | ✅ |
| **Allowances** | ប្រាក់​​ ​​​​​​ ​​​​​​​​​​​​​​​​​​​​​​​​​​ += | |
| **Deductions** | ការកាត់ | |
| **Net Salary** | សរុប (auto = basic + allowances - deductions) | 🤖 |
| **Pay Period** | រយៈពេល (e.g. "2026-05") | ✅ |
| **Payment Date** | ថ្ងៃបង់ | ✅ |
| **Payment Method** | វិធីសាស្ត្រ | |
| **Status** | `pending`, `paid`, `cancelled` | ✅ |
| **Note** | កំណត់ចំណាំ | |

### 13.3.2 Workflow

```
1. Monthly → Accountant បង្កើត Salary records សម្រាប់ Staff ទាំងអស់:
   ▸ Sidebar → Salaries → + Create
   ▸ Staff: John Doe
   ▸ Basic Salary: 300.00
   ▸ Allowances: 20.00 (transport)
   ▸ Deductions: 10.00 (absent days)
   ▸ Pay Period: 2026-05
     → Net Salary = 310.00 auto

2. Manager review → Status = paid
3. Payment Date = 2026-05-28
4. Repeat for each Staff member
```

> 💡 **Tip**: ប្រើ Staff Attendance module (ជំពូក 3.3) ដើម្បីគណនា Deductions មុន​​ បង្កើត Salary — absent days × daily rate = deduction amount។

---

## 13.4 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| Upload Receipt Photo ​រាល់​ Expense | កុំបង្កើត Expense ​ដោយ​គ្មាន​​ ​Description |
| Approve Expenses មុន​​​ ​​end-of-month closing | កុំកែ​ ​Net Salary ដោយដៃ — ​ កែ​​ components វិញ |
| ត្រួតពិនិត្យ Budget Amount vs Actual ប្រចាំខែ | កុំ skip Salary ​ records —​​​​ វា​​​​​​ ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ skip = no audit trail |
| ភ្ជាប់ Maintenance Costs ទៅ Expenses | កុំ delete Expense — cancel វិញ |
