# 4. ការគ្រប់គ្រងបន្ទប់ — Room Management

ផ្នែកនេះគ្រប់គ្រងបន្ទប់ (Room) ដែលជា​ inventory សំខាន់នៃផ្ទះសំណាក់។

> 📌 **លំដាប់ Setup**៖ ត្រូវ Setup តាមលំដាប់ ៖ **Room Types → Rooms → Facilities → Facility ↔ Room links**។

---

## 4.1 Room Types — ប្រភេទបន្ទប់

**Sidebar**: `Rooms Management → Room Types` ▸ URL: `/admin/room_types`

Room Type ជាប្រភេទបន្ទប់ដែលមានតម្លៃ និងចំនួនភ្ញៀវដូចគ្នា (e.g. Standard, Deluxe, Suite)។

### 4.1.1 Field

| Field | ការពិពណ៌នា | ឧទាហរណ៍ |
|---|---|---|
| **Branch** | សាខាដែល Room Type នេះមាន | BR-MAIN |
| **Code** | កូដ​ខ្លី (unique ក្នុង branch) | `STD`, `DLX`, `STE` |
| **Name** | ឈ្មោះពេញ​ | Standard Single |
| **Description** | ការ​ពិពណ៌នា​ | Single bed with AC |
| **Base Price** | តម្លៃ​មូលដ្ឋាន ​​ ​ ​​​ ​​​​ ​ ​ /​​​ ​ យប់ | 25.00 |
| **Max Occupancy** | ចំនួនភ្ញៀវអតិបរមា | 2 |
| **Status** | active / inactive | |

### 4.1.2 Best Practice

- ✅ Setup Room Type ឱ្យ​​​​ ​​​មាន​​​​​ Code តែ ​​​មួយ​ឱ្យសាខានីមួយៗ
- ✅ Base Price ត្រូវ​​ការផ្លាស់​ប្ដូរនៅ Branch ផ្សេង — បង្កើត Room Type វាយដាច់ឱ្យ​​​​​​​​​ Branch មួយៗ
- ✅ Description ត្រូវ​បំពេញ ដើម្បី​ Receptionist ​​មើល​ឃើញ​​​ amenities ខ្លះៗ​ ​​នៅ Booking form

---

## 4.2 Rooms — បន្ទប់ផ្ទាល់

**Sidebar**: `Rooms Management → Rooms` ▸ URL: `/admin/rooms`

Room គឺ​​​ instance ផ្ទាល់របស់ Room Type — e.g. Room 101, 102, 201, 202។

### 4.2.1 Field

| Field | ការពិពណ៌នា | ឧទាហរណ៍ |
|---|---|---|
| **Branch** | សាខា | BR-MAIN |
| **Room Type** | ប្រភេទ ​(Tom Select) | Standard Single |
| **Room Number** | លេខបន្ទប់ (unique ក្នុង branch) | 101 |
| **Floor** | ​ជាន់​ | 1, 2, ground |
| **Status** | `available`, `occupied`, `cleaning`, `maintenance`, `out_of_order` | available |
| **Note** | កំណត់ចំណាំ |  |

### 4.2.2 Status — Lifecycle ស្ថានភាពបន្ទប់

```
                ┌──────────────┐
   Booking ───>│   available   │<─────────────┐
                └──────┬───────┘              │
                       │ Stay created         │
                       ▼                      │
                ┌──────────────┐              │
                │   occupied   │              │
                └──────┬───────┘              │
                       │ Stay checked-out     │
                       │ (StayObserver)       │
                       ▼                      │
                ┌──────────────┐              │
                │   cleaning   │              │ Housekeeping
                └──────┬───────┘              │ task complete
                       │ Maintenance found    │
                       ▼                      │
                ┌──────────────┐              │
                │ maintenance  │──────────────┘
                └──────┬───────┘
                       │ Damage too severe
                       ▼
                ┌──────────────┐
                │ out_of_order │
                └──────────────┘
```

> 🤖 **Auto‑Logic**៖ ​​Customer **មិនត្រូវ​​ប្ដូរ​​ status ដោយ​​ដៃ** ​នៅ​ ​​​ moments ​​ដូច​ខាង​ក្រោម​​​​​​ — ​​ប្រព័ន្ធ​​ ប្ដូរ​​​ ឱ្យ​​​:
> - Stay បង្កើត → Room status = `occupied`
> - Stay checked_out → Room status = `cleaning` + Housekeeping Task auto-created
> - Stay cancelled → Room status = `available`

### 4.2.3 ​Best Practice

- ✅ ​​​ ​បំពេញ Floor ​ដើម្បី filter ងាយ
- ✅ ប្រើ Status `out_of_order` ​​​​ឱ្យបន្ទប់​​ដែល​​ខូច​​​ ​ធ្ងន់ធ្ងរ — Booking form នឹង​​​​​​​មិនអនុញ្ញាត​ឱ្យ Reception book បន្ទប់នោះ
- ❌ ​កុំ​​​​​​​លុប​បន្ទប់​​ដែលមាន Booking history — ប្ដូរ​​ status = `out_of_order` វិញ

---

## 4.3 Facilities — សម្ភារៈ​​​​​/ពិសេស​ៗ​

**Sidebar**: `Rooms Management → Facilities` ▸ URL: `/admin/facilities`

Facility គឺ​​​ "amenity" ដូចជា Wi‑Fi, AC, TV, Mini‑bar, Hair Dryer ។ល​​​​​​​​​​​​​​​. ដែលអាចភ្ជាប់​​​​​​​​​​​​​​​ទៅ Room ។ ប្រព័ន្ធគ្រប់​គ្រង Many‑to‑Many តាម​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ pivot table `facility_room`​​​​​​​​​​​​​​​​​​​។

### 4.3.1 Field Facility

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | សាខា (ឬ​​​​​​​ NULL ឱ្យ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ ​ branches all) |
| **Code** | កូដ (e.g. `WIFI`, `AC`) |
| **Name** | ឈ្មោះ |
| **Icon** | Bootstrap icon class (e.g. `bi-wifi`) |
| **Description** | |
| **Status** | active / inactive |

### 4.3.2 ការ​ភ្ជាប់ Facility ទៅ Room

នៅ Room Edit Form មាន Field **Facilities** (Tom Select multi):

```
1. Sidebar → Rooms → Rooms → 🖉 Edit លើ Room
2. នៅ Field "Facilities" ចុច dropdown → ជ្រើសច្រើន (Wi-Fi, AC, TV, ...)
3. Save
4. ប្រព័ន្ធកត់ត្រាក្នុង pivot `facility_room` (មាន quantity + condition)
```

> 💡 **Tip**៖ Facility ​មាន​ Status — បើ​​​ Customer ដាក់ inactive ​នោះ​ អ្នកប្រើ​មិន​អាច add ​ទៅ​ Room ​ថ្មី​​ទៀត​ទេ​​ ​ ​ ​​​ ​​ ​ ​ ​​ (ប៉ុន្តែ Facility-Room ​​​​ links ​​ដែល​​មាន​​ ​​​នៅ​​​មិន​​​ប៉ះ​ពាល់​​​)។

---

## 4.4 Workflow ស្តង់ដារ Setup Rooms

```
Step 1: បង្កើត Room Types  (4.1)  → Standard, Deluxe, Suite
   ↓
Step 2: បង្កើត Rooms  (4.2)         → 101, 102, 201, 202, ...
   ↓
Step 3: បង្កើត Facilities  (4.3)    → Wi-Fi, AC, TV, Mini-bar
   ↓
Step 4: ភ្ជាប់ Facility ↔ Room  (4.3.2)
   ↓
Step 5: បន្ត → Guest Management (ជំពូកទី 5)
```

> 📌 **Tip ​សម្រាប់​​​ Customer**៖ ​Setup ​ ​Rooms ​​​​​លក្ខណៈ​​​ Bulk ​​​​នោះ​អាច​​ធ្វើ​បាន​​ដោយ​ administrator ​​ៀ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ ​ database ​​​​​​​seed​ ​​​​ឱ្យ​លឿន — ​​​សូម​ទាក់​ទង support team។
