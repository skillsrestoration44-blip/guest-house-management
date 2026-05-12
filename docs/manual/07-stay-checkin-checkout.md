# 7. ការ Check-in / Check-out — Stay Management

Stay គឺជាការស្នាក់នៅផ្ទាល់របស់ Guest ក្នុងបន្ទប់ — បង្កើតបន្ទាប់ពី Booking ត្រូវបាន confirm។ Stay ទទួលខុសត្រូវលើ Room status, Housekeeping, និង Invoice (ដោយប្រយោល)។

---

## 7.1 Stays — ការស្នាក់នៅ

**Sidebar**: `Stay Management → Stays` ▸ URL: `/admin/stays`

### 7.1.1 Field សំខាន់

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Stay No.** | Auto-generated (`ST-000001`) | 🤖 |
| **Booking** | Booking ភ្ជាប់ (Tom Select) | ✅ |
| **Guest** | Guest (auto-filled from Booking) | 🤖 |
| **Room** | Room (auto-filled from Booking) | 🤖 |
| **Check-in Date** | ថ្ងៃចូលជាក់ស្ដែង | ✅ |
| **Actual Check-in At** | datetime ជាក់ស្ដែង (auto = now) | 🤖 |
| **Check-out Date** | ថ្ងៃចេញគ្រោងទុក | ✅ |
| **Actual Check-out At** | datetime ជាក់ស្ដែង (បំពេញពេល check-out) | |
| **Total Nights** | ចំនួនយប់ (auto-computed) | 🤖 |
| **Status** | `checked_in`, `checked_out`, `transferred`, `cancelled` | ✅ |
| **Check-in By** | Staff (auto = current user) | 🤖 |
| **Note** | កំណត់ចំណាំ | |

### 7.1.2 Workflow Check-in (ភ្ញៀវមកដល់)

```
ជំហានទី 1: Booking របស់ Guest មាន status = confirmed (មិនមែន pending)
   → ប្រសិនបើ pending: ប្រមូលប្រាក់កក់ + ប្ដូរ confirmed មុន

ជំហានទី 2: Sidebar → Stay Management → Stays → + Create Stay

ជំហានទី 3: បំពេញ Form:
   - Booking: ជ្រើស Booking ដែលត្រូវនឹងភ្ញៀវ
     → Guest, Room, Check-in/out Date auto-fill
   - Status: checked_in (default)
   - Note: (optional)

ជំហានទី 4: Save
   → StayObserver auto:
     • Generate Stay No. (ST-000xxx)
     • Set Actual Check-in At = now()
     • Set Check-in By = current user
     • Update Room.status = 'occupied'
     • Update Booking.status = 'checked_in'
     • Create BookingStatusHistory entry

ជំហានទី 5: ប្រគល់ Key Card / Reception card ដល់ Guest
```

### 7.1.3 Workflow Check-out (ភ្ញៀវចេញ)

```
ជំហានទី 1: Sidebar → Stays → ស្វែងរក Stay → 🖉 Edit

ជំហានទី 2: ប្ដូរ Status = checked_out
   → បំពេញ Actual Check-out At = now (auto via flatpickr)

ជំហានទី 3: Save
   → StayObserver auto:
     • Update Room.status = 'cleaning'
     • Update Booking.status = 'checked_out'
     • Create HousekeepingTask (HK-000xxx) for the Room
     • Create BookingStatusHistory entry

ជំហានទី 4: បន្ត → ចេញ Invoice (ជំពូកទី 8.1)
```

> 🤖 **Auto-Logic លម្អិត**៖ នៅពេល Stay status = `checked_out` `StayObserver`៖
> 1. ត្រួតពិនិត្យ Housekeeping Task ដែលមាន status = 'pending' សម្រាប់ Room នេះ
> 2. ប្រសិនបើគ្មាន → បង្កើត `HousekeepingTask` ថ្មីដោយ task_type='checkout_clean', priority='high', status='pending'
> 3. Customer **មិនត្រូវបង្កើត Housekeeping Task ដោយដៃ** — Auto System រួចស្រេច

### 7.1.4 ស្ថានភាព Stay — Lifecycle

```
                ┌──────────────┐
   Booking ───>│  checked_in  │
   confirmed   └──────┬───────┘
                       │ Reception ប្ដូរ
                       │ status (Edit)
                       ▼
                ┌──────────────┐
                │ checked_out  │ ← END (auto Housekeeping)
                └──────────────┘

Alternative paths:
   checked_in ──> transferred (use Room Transfer module — 7.2)
   checked_in ──> cancelled (rare — guest leaves immediately)
```

---

## 7.2 Room Transfers — ការផ្លាស់បន្ទប់

**Sidebar**: `Stay Management → Room Transfers` ▸ URL: `/admin/room_transfers`

ប្រើនៅពេល Guest ស្នាក់ហើយត្រូវផ្លាស់ទៅ Room ផ្សេង (e.g. Room 201 មានបញ្ហា A/C → ត្រូវផ្លាស់ទៅ 202)។

### 7.2.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Stay** | Stay record (Tom Select) | ✅ |
| **From Room** | Room ដើម (auto-filled) | 🤖 |
| **To Room** | Room ថ្មី (Tom Select) — ត្រូវ available | ✅ |
| **Transfer Date** | ថ្ងៃផ្លាស់ | ✅ |
| **Transfer Time** | ម៉ោងផ្លាស់ | |
| **Reason** | ហេតុផល (e.g. "AC broken in Room 201") | ✅ |
| **Transferred By** | Staff (auto = current user) | 🤖 |
| **Note** | កំណត់ចំណាំ | |

### 7.2.2 Workflow

```
1. Reception ឬ Manager ផ្ទៀងផ្ទាត់ Room ថ្មីមាន available

2. Sidebar → Room Transfers → + Create

3. បំពេញ Room Transfer Form:
   ▸ Stay: ជ្រើស Stay ដើម (To Room នឹងបង្ហាញ Room ចាស់)
   ▸ To Room: ជ្រើស Room ថ្មី
   ▸ Reason: បំពេញច្បាស់លាស់
   ▸ Save

4. ប្រព័ន្ធ:
   ▸ Update Stay.room_id = To Room
   ▸ Update From Room.status = 'cleaning' (Housekeeping needed)
   ▸ Update To Room.status = 'occupied'
   ▸ Create HousekeepingTask for From Room
   ▸ Audit Log កត់ត្រា Transfer

5. ប្រគល់ Key Card ថ្មី → ដាក់ Key Card ចាស់
```

> 💡 **Tip**៖ ប្រសិនបើ Room ដើមមានបញ្ហា Reception ត្រូវផ្លាស់ Customer ដោយមិនគិតលុយបន្ថែម — បញ្ចូល Reason ច្បាស់លាស់ដើម្បី Manager អាច track។

---

## 7.3 ការ Tracking Stay លម្អិត

នៅ **Stay Show Page** (`/admin/stays/{id}`) Customer អាចមើល៖

- ព័ត៌មាន Stay សំខាន់ៗ
- Booking ដែលភ្ជាប់ + Booking Status History
- Service Charges ដែល Guest ប្រើក្នុងអំឡុង Stay (link ទៅ Service Charges)
- Invoice ដែលត្រូវបានចេញ
- Housekeeping Tasks ដែលត្រូវបានបង្កើតសម្រាប់ Room នៃ Stay នេះ
- Room Transfer ប្រវត្តិ (បើមាន)

---

## 7.4 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| បំពេញ Actual Check-in/out At ឱ្យត្រឹមត្រូវ | កុំ skip Stay record (កុំ check-in ដោយផ្ទាល់ក្នុង Booking) |
| ប្ដូរ Status ជា checked_out ភ្លាមៗបន្ទាប់ពីភ្ញៀវចេញ | កុំទុក Stay = checked_in យូរបន្ទាប់ពីភ្ញៀវចេញ |
| ប្រើ Room Transfer module ជំនួសការ edit Stay.room_id ផ្ទាល់ | កុំ delete Stay record |
| បំពេញ Note ច្បាស់ៗសម្រាប់ requests ពិសេស | កុំ override Stay No. ដោយដៃ |

---

## 7.5 ការដោះស្រាយបញ្ហាទូទៅ

| បញ្ហា | ដំណោះស្រាយ |
|---|---|
| Room.status នៅតែជា 'occupied' បន្ទាប់ពី check-out | ត្រួតពិនិត្យថា Stay.status = 'checked_out' (ត្រូវ save Edit form) |
| គ្មាន Housekeeping Task បន្ទាប់ check-out | StayObserver មិនបាន fire — ត្រួតពិនិត្យ EventServiceProvider |
| Booking នៅតែ status = 'confirmed' បន្ទាប់ check-in | Stay observer មិនបានធ្វើបច្ចុប្បន្នភាព Booking — ផ្ទៀងផ្ទាត់ Booking_id ត្រឹមត្រូវ |
