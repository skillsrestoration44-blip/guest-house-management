# 2. ការចាប់ផ្តើមប្រើប្រាស់ — Getting Started

## 2.1 ការចូលប្រព័ន្ធ — Login

### ជំហានទី 1៖ បើក URL ដែល Administrator ផ្ដល់ឱ្យ

```
https://app.your-domain.com/admin/login
```

> ប្រសិនបើ Customer ប្រើ Local development ត្រូវប្រើ `http://127.0.0.1:8000/admin/login`។

### ជំហានទី 2៖ បំពេញ Email និង Password

| ចំណុច | តម្លៃគំរូ |
|---|---|
| Email | `admin@example.com` |
| Password | `password` |

> ⚠️ **Default password ត្រូវប្ដូរភ្លាមៗ** ក្រោយ Login លើកដំបូង — សូមមើលជំពូកទី 3.4 (Users)។

### ជំហានទី 3៖ ចុចប៊ូតុង **Login**

ប្រព័ន្ធនឹងបង្ហាញសារ **"Welcome back!"** (PHPFlasher) នៅជ្រុងខាងលើស្តាំ ហើយ redirect ទៅ Dashboard។

> 💡 **Tip**៖ ប្រសិនបើ Login ខុស ប្រព័ន្ធនឹងបង្ហាញសារ **"Invalid credentials"**។ បន្ទាប់ពីព្យាយាមលើស 5 ដង Login attempt ត្រូវបានកត់ត្រាក្នុង **Login Histories** (មើលជំពូកទី 17)។

---

## 2.2 ស្វែងយល់ Dashboard — Understanding the Dashboard

ក្រោយ Login ជោគជ័យ Customer នឹងឃើញ **Dashboard** ដែលមាន៖

### 2.2.1 KPI Cards (6 ប្រអប់)

| Card | អត្ថន័យ | ប្រភពទិន្នន័យ |
|---|---|---|
| **Rooms** | ចំនួនបន្ទប់សរុបក្នុង Branch | `rooms` (filter by branch) |
| **Guests** | ចំនួនភ្ញៀវសរុប | `guests` |
| **Bookings** | ចំនួន Booking សរុប | `bookings` |
| **Checked‑In** | ចំនួនភ្ញៀវកំពុងស្នាក់ | `stays` where `status=checked_in` |
| **Invoices Total** | ចំនួនប្រាក់ Invoice សរុប | `SUM(grand_total)` |
| **Payments Total** | ចំនួនប្រាក់ ទទួលបានសរុប | `SUM(amount)` where `status=completed` |

> 📌 **កំណត់ចំណាំ**៖ KPI Cards ទាំងនេះ **គោរពតាម Branch ដែលអ្នកកំពុងបើក**។ ប្ដូរ Branch → KPI ផ្លាស់ប្ដូរភ្លាមៗ។

### 2.2.2 រចនាសម្ព័ន្ធអេក្រង់

```
┌──────────────────────────────────────────────────────────────┐
│                     ⬛ Top Header                            │
│  ☰ │ 🔍 Search │      🏢 Branch Switcher │ 🌐 KH/EN │ 👤 User │
├──────────┬───────────────────────────────────────────────────┤
│          │                                                   │
│  Side    │              Main Content Area                    │
│  Menu    │      (Dashboard / DataTable / Form)              │
│  (15     │                                                   │
│  Section)│                                                   │
│          │                                                   │
└──────────┴───────────────────────────────────────────────────┘
```

| ផ្នែក | ការប្រើប្រាស់ |
|---|---|
| **Top Header** | មាន Branch Switcher, Language Switcher, User Menu (Profile, Logout) |
| **Side Menu (Sidebar)** | មានជម្រើស 15 Section និង 41+ Module — ចុចតុលុំសន្លឹកដើម្បីពង្រីក |
| **Main Content** | បង្ហាញ Dashboard, DataTable list, Create/Edit Form, ឬ Show page |

---

## 2.3 ការប្ដូរសាខា — Branch Switching

ប្រព័ន្ធគ្រប់គ្រងច្រើនសាខា (BR-MAIN, BR-BB, BR-SR ឧទាហរណ៍)។ Customer ត្រូវប្ដូរទៅ **Branch ត្រឹមត្រូវ** មុនប្រតិបត្តិការ។

### វិធី៖

1. ចុច **🏢 Branch Icon** នៅ Top Header
2. ឃើញ Dropdown មាន branches ទាំងអស់ + ជម្រើស **All branches** (សម្រាប់ Super Admin)
3. ចុច Branch ដែលអ្នកចង់ប្រើ
4. **Page នឹង reload ស្វ័យប្រវត្តិ** ហើយ DataTable, Dashboard, ការ create record បន្ទាប់នឹងគោរពតាម Branch នេះ

> 🔒 **Permission**៖ មានតែ Super Admin និង Manager ដែលអាចជ្រើស **All branches**។ Receptionist និង Housekeeper ត្រូវ **lock** នឹង Branch ផ្ទាល់ខ្លួន។

> ⚠️ **កំហុសញឹកញាប់**៖ បង្កើត Booking ខណៈដែល Branch Switcher នៅ "All branches" អាចធ្វើឱ្យ Booking ត្រូវបានដាក់ក្នុង Branch លំនាំដើម (default branch) ដែលអាចខុសពីបំណង។

---

## 2.4 ការប្ដូរភាសា — Language Switching

ប្រព័ន្ធគាំទ្រ ខ្មែរ (KH) និង អង់គ្លេស (EN) ដោយ **មិនចាំបាច់ refresh page**។

### វិធី៖

1. ចុច **🌐 Translate Icon** នៅ Top Header
2. ជ្រើស **🇺🇸 English** ឬ **🇰🇭 ខ្មែរ Khmer**
3. ស្លាក (label) ទាំងអស់ ប្ដូរភ្លាមៗ — DataTable, Sidebar, Form Labels, Header
4. ការប្ដូរនេះ **រក្សាទុកក្នុង Cookie** ដូច្នេះ Login ក្រោយលើកដំបូង Customer នឹងឃើញភាសាដែលបានជ្រើសចុងក្រោយ

> 💡 **Tip**៖ ការប្ដូរភាសា **មិនប៉ះពាល់ដល់ទិន្នន័យ** ឡើយ — វាប្ដូរតែស្លាក UI ទេ។ ឈ្មោះ Guest, Note, Description នៅតែបង្ហាញតាមដែលអ្នកវាយ។

> 📌 **កំណត់ចំណាំសម្រាប់ Trainer**៖ ត្រូវបង្ហាញ Customer ឱ្យឃើញថា ការប្ដូរភាសាមិនធ្វើឱ្យបាត់ filter ឬ search ដែលគេវាយចូលក្នុង DataTable ឡើយ — DataTable ត្រឹមតែ reload ដោយ `ajax.reload(null, false)`។

---

## 2.5 ការគ្រប់គ្រង DataTable — DataTable Operations

រាល់ Module Index Page មាន **Yajra DataTable Server‑Side** ដូចគ្នា។ Customer អាច៖

### 2.5.1 Search

នៅ​ផ្នែកខាងលើស្តាំនៃ DataTable មាន​ប្រអប់ **Search**។ វាយអ្វីទៅក្នុងវា — DataTable filter ភ្លាមៗ​ (debounced 300ms) ទៅកាន់ server។

### 2.5.2 Pagination (Bootstrap 5)

នៅ​ផ្នែកខាងក្រោម DataTable ឃើញ Bootstrap 5 paginator (មានលេខ Page)។ ប្រព័ន្ធប្រើ **Server‑Side Paginator** ដើម្បីលឿនទោះបីមាន 10,000+ rows។

### 2.5.3 Sort

ចុច​លើ Header ឈ្មោះ Column → DataTable ទើ្វាkdj sort តាម Column នោះ (ASC/DESC).

### 2.5.4 Per Page

នៅ​ផ្នែកខាងលើឆ្វេង មាន **Show 10/25/50/100 entries** dropdown — ជ្រើសចំនួន row ក្នុង page នីមួយៗ។

### 2.5.5 Action Buttons

នៅ Column **Action** ចុងក្រោយ មាន ៖

| ប៊ូតុង | សកម្មភាព |
|---|---|
| 🖉 **Edit** | បើក Form កែប្រែ |
| 🗑 **Delete** | លុប — ប្រព័ន្ធនឹងបង្ហាញ **SweetAlert2 confirm dialog** ▸ **"Are you sure?"** ▸ ចុច **Yes, delete it!** ▸ AJAX DELETE ▸ DataTable reload |

> ❌ **កុំទេ**៖ កុំ delete record ដោយ​ប្រើ database tools ដោយផ្ទាល់ — នោះនឹងបាត់ Audit Log និងធ្វើឱ្យ FK ខូច។

---

## 2.6 Form Conventions — អនុសញ្ញា Form

រាល់ Form Create / Edit ប្រើស្តង់ដារដូចគ្នា៖

### 2.6.1 Field Types

| Field Type | Widget | ឧទាហរណ៍ |
|---|---|---|
| Text | input | `Booking No`, `Guest Name` |
| Number | input | `Adults`, `Room Price` |
| Date | **flatpickr** picker | `Check-in Date` |
| Datetime | **flatpickr** | `Actual Check-in At` |
| Time | **flatpickr** | `Check-in Time` |
| Select | **Tom Select** (មានសញ្ញាស្វែងរក + multi-select) | `Guest`, `Room`, `Status` |
| Textarea | textarea | `Note`, `Description` |

### 2.6.2 Required Fields

ស្លាកមាន **\*** ពណ៌ក្រហម — ត្រូវបំពេញ មុនពេល Save។

### 2.6.3 Validation

ប្រសិនបើ Customer បំពេញខុស ​ឬ​ខ្វះ Field ដែលតម្រូវ៖
1. Form **មិន submit** ទេ
2. បង្ហាញ **error message** ក្រហម​ក្រោម Field នោះ
3. បង្ហាញ **PHPFlasher error notice** នៅ​ខាងលើស្តាំ

### 2.6.4 Save Button

ចុច **Save** → ប្រព័ន្ធ POST → Validate → Save → Redirect ទៅ Index Page → បង្ហាញសារ **"Created successfully"** (PHPFlasher)។

> 💡 **Tip**៖ ប្ដូរ Field ដែលជា **Date** ដោយ​ប្រើ flatpickr picker — កុំ​វាយ format ខុស (e.g. dd/MM/YYYY)។ Picker អនុញ្ញាតឱ្យ Customer ​ចាក់​ tay ឬ​​ចុច​ ​លេី​ calendar។

---

## 2.7 ការ Logout

នៅ Top Header ស្តាំ ចុច **👤 User Icon** → **Logout** → ប្រព័ន្ធបិទ session ហើយ redirect ទៅ Login page។

> 🔒 **Best Practice**៖ ត្រូវ Logout រាល់ពេលឈប់ប្រើ — ជាពិសេស​នៅ Computer ​សាធារណៈ​។

---

## 2.8 Quick Reference — តារាងធ្វើ​ចំណេះ​ដឹងឯកសារ​​សំខាន់

| សកម្មភាព | កន្លែងក្នុង UI |
|---|---|
| ប្ដូរ Branch | Header → 🏢 |
| ប្ដូរភាសា | Header → 🌐 |
| Profile / Logout | Header → 👤 |
| ស្វែងរក | DataTable → Search box |
| បង្កើតថ្មី | Index Page → ប៊ូតុង **+ Create** |
| កែប្រែ | DataTable → Action → 🖉 Edit |
| លុប | DataTable → Action → 🗑 Delete (តម្រូវ Confirm) |
| មើល Detail | DataTable → Action → 👁 Show (ប្រសិនបើមាន) |
