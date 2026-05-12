# 6. ការគ្រប់គ្រងការកក់ — Booking Management

Booking គឺជា **ចំណុចចាប់ផ្ដើម** នៃ workflow ប្រាក់ប្រចាំថ្ងៃ — Reception បង្កើត Booking ដំបូង បន្ទាប់មក Stay → Invoice → Payment → Receipt។

---

## 6.1 Bookings — ការកក់

**Sidebar**: `Booking Management → Bookings` ▸ URL: `/admin/bookings`

### 6.1.1 Field សំខាន់

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 (auto from current branch) |
| **Booking No.** | Auto‑generated (`BK-000001`) — កុំកែ | 🤖 |
| **Guest** | ភ្ជាប់ទៅ Guest record (Tom Select) | ✅ |
| **Room** | Room (ជ្រើស Tom Select) | ✅ |
| **Booking Source** | `walk_in`, `phone`, `website`, `facebook`, `agency` | ✅ |
| **Check‑in Date** | ថ្ងៃចូល (flatpickr) | ✅ |
| **Check‑out Date** | ថ្ងៃចេញ (after_or_equal check‑in) | ✅ |
| **Check‑in Time** | (optional) | |
| **Check‑out Time** | (optional) | |
| **Adults** | ចំនួនមនុស្សពេញវ័យ | ✅ |
| **Children** | ចំនួនកុមារ | |
| **Total Guests** | ចំនួនសរុប (adults + children) | ✅ |
| **Room Price** | តម្លៃ /​ យប់ (default: room_type.base_price) | ✅ |
| **Deposit Amount** | ចំនួនកក់ | |
| **Discount Amount** | បញ្ចុះតម្លៃ | |
| **Status** | `pending`, `confirmed`, `checked_in`, `checked_out`, `cancelled`, `no_show` | ✅ |
| **Cancel Reason** | ហេតុផល cancel (បំពេញនៅពេល status=cancelled) | |
| **Note** | កំណត់ចំណាំ | |

### 6.1.2 ស្ថានភាព Booking — Status Lifecycle

```
                    ┌───────────────┐
   Reception ───>│    pending    │
   បង្កើត           └───────┬───────┘
                            │ Confirmation
                            │ (Deposit received)
                            ▼
                    ┌───────────────┐
                    │   confirmed   │
                    └───────┬───────┘
                            │ Stay create →
                            │ (StayObserver)
                            ▼
                    ┌───────────────┐
                    │  checked_in   │
                    └───────┬───────┘
                            │ Stay checked-out
                            ▼
                    ┌───────────────┐
                    │  checked_out  │ — END (Final state)
                    └───────────────┘

Alternative endings:
   pending  ───> cancelled (cancel_reason required)
   pending  ───> no_show (Guest មិនមកក្នុងថ្ងៃកំណត់)
   confirmed ───> cancelled
```

> 🤖 **Auto‑Logic**៖ រាល់ការប្ដូរ status ត្រូវបានកត់ត្រាដោយ `BookingObserver` ក្នុង `booking_status_histories` ដោយរួមបញ្ចូល `from_status`, `to_status`, `changed_by_id`, `note`, `created_at`។ → ផ្ដល់ **Audit Trail** តាមស្តង់ដារ ISO 9001 §4.2.4។

### 6.1.3 ដំណើរការបង្កើត Booking ថ្មី (Step‑by‑Step)

```
ជំហានទី 1: ត្រួតពិនិត្យ Branch Switcher នៅ Header
   → ត្រូវនៅ Branch ត្រឹមត្រូវ
   
ជំហានទី 2: បង្កើត Guest មុន (បើ Guest មិនទាន់មាន)
   → Sidebar → Guests → + Create
   → Save (ទទួលបាន G-00xxx)
   
ជំហានទី 3: Sidebar → Booking Management → Bookings → + Create
   
ជំហានទី 4: បំពេញ Form:
   - Guest: ជ្រើស Guest ដែលទើបបង្កើត (Tom Select autocomplete)
   - Room: ជ្រើស Room ដែល available
   - Booking Source: walk_in (ឧទាហរណ៍)
   - Check‑in Date: 2026-05-10
   - Check‑out Date: 2026-05-12
   - Adults: 2, Children: 0, Total: 2
   - Room Price: 35 (auto‑filled from Room Type)
   - Deposit: 20
   - Status: pending (default)
   
ជំហានទី 5: Save
   → ប្រព័ន្ធ generate Booking No. (BK-000xxx)
   → Redirect ទៅ Index Page
   → PHPFlasher: "Created successfully"
   
ជំហានទី 6: ប្រមូលប្រាក់កក់ → ផ្លាស់ Status = confirmed
   → Edit Booking → Status = confirmed → Save
   → BookingObserver កត់ត្រា status_history
```

### 6.1.4 ការ Cancel Booking

```
1. Sidebar → Bookings → 🖉 Edit
2. Status = cancelled
3. បំពេញ Cancel Reason (e.g. "Guest changed plans")
4. Save
5. Status History កត់ត្រា: pending → cancelled (or confirmed → cancelled)
6. ⚠️ បើ Booking នេះមាន Deposit រួចហើយ — ត្រូវ:
   - បង្កើត Refund (ជំពូកទី 8)
   - ឬ retain ប្រាក់កក់ជា penalty (ប្ដូរ status of Deposit accordingly)
```

> ❌ **Don't**៖ កុំ delete Booking ដោយផ្ទាល់ — ត្រូវ cancel វិញ ដើម្បីរក្សា Audit Trail និងស្ថានភាពហិរញ្ញវត្ថុ។

### 6.1.5 Validation ពិសេស — Room Availability

ប្រព័ន្ធមាន Custom Rule `RoomAvailable` ដែល **រារាំង Booking ត្រួតគ្នា**:

```
ប្រសិនបើ Customer ព្យាយាមកក់ Room 101 ពី 2026-05-10 → 2026-05-12
ហើយ Room 101 មាន Booking រួចហើយពី 2026-05-11 → 2026-05-13
→ ប្រព័ន្ធ reject ដោយ error: "Room is not available for selected dates"
```

> 💡 **Tip**៖ បើ Customer ចង់ផ្លាស់ Booking ទៅ Room ផ្សេង — ត្រូវប្រើ **Room Transfer** (ជំពូកទី 7.2) ជំនួស edit Booking ផ្ទាល់។

---

## 6.2 Online Booking Requests — សំណើ Booking តាមអនឡាញ

**Sidebar**: `Booking Management → Online Booking Requests` ▸ URL: `/admin/online_booking_requests`

ប្រើដើម្បីទទួល Booking ពី Website front‑end (មុន Reception confirm)។

### 6.2.1 Field

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | សាខា |
| **Guest Name** | ឈ្មោះ (មិនទាន់ជា Guest record) |
| **Phone**, **Email** | ទំនាក់ទំនង |
| **Check‑in/out Date** | ថ្ងៃ |
| **Adults**, **Children** | ចំនួនមនុស្ស |
| **Room Type Preferred** | ប្រភេទដែលចង់ |
| **Status** | `pending`, `approved`, `rejected` |
| **Approved Booking** | ភ្ជាប់ Booking ដែលបង្កើតពេល approve (auto) |

### 6.2.2 Workflow

```
1. Customer បំពេញ Form នៅ Website front‑end
2. Online Booking Request បង្កើតក្នុងតារាង (status=pending)
3. Reception មើល Sidebar → Online Booking Requests
4. ត្រួតពិនិត្យ availability + capacity
5. ប្រសិនបើ OK:
   a. Edit → Status = approved
   b. បង្កើត Guest record (បើ Guest មិនទាន់មាន)
   c. បង្កើត Booking record → ភ្ជាប់ approved_booking_id
6. ប្រសិនបើមិន OK:
   a. Edit → Status = rejected
   b. ទំនាក់ទំនង Customer ដោយតេលេហ្វូន/អ៊ីមែល
```

---

## 6.3 Best Practice សម្រាប់ Reception

| ✅ Do | ❌ Don't |
|---|---|
| ត្រួតពិនិត្យ Room status មុនកក់ | កុំកក់ Room ដែល status = `out_of_order` |
| បំពេញ Note ចំពោះ requests ពិសេស | កុំ skip Total Guests field |
| Confirm Booking ភ្លាមៗបន្ទាប់ពីទទួលប្រាក់កក់ | កុំ keep Booking ជា pending លើស 24h |
| ប្រើ Cancel Reason ច្បាស់លាស់ | កុំ delete Booking — cancel វិញ |
| Update Room Price បើជា promotion | កុំ override Booking No. ដោយដៃ |

---

## 6.4 ការមើលប្រវត្តិ Booking — Status History

នៅ **Booking Show Page** (`/admin/bookings/{id}`) Customer អាចមើល៖
- ព័ត៌មាន Booking ទាំងអស់
- **Status History Panel** — បង្ហាញរាល់ការផ្លាស់ប្ដូរ status ជាបន្ទាត់ពេល (timeline)
- Stay records ដែលបង្កើតពី Booking នេះ
- Invoice & Payments ដែលភ្ជាប់
