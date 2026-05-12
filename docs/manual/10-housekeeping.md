# 10. ការសម្អាតបន្ទប់ — Housekeeping

ផ្នែកនេះគ្រប់គ្រងការសម្អាត​បន្ទប់​ ដោយ​ Tasks ត្រូវ​បាន​បង្កើតស្វ័យប្រវត្តិពី​ Stay check-out (តាមការកំណត់​ក្នុង​ StayObserver) និងអនុញ្ញាតឱ្យ Housekeeping staff ​ត្រួតពិនិត្យ checklist។

---

## 10.1 Housekeeping Tasks — ការងារសម្អាត

**Sidebar**: `Operations → Housekeeping Tasks` ▸ URL: `/admin/housekeeping_tasks`

### 10.1.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Task No.** | Auto (`HK-000001`) | 🤖 |
| **Room** | Room | ✅ |
| **Task Type** | `checkout_clean`, `routine_clean`, `deep_clean`, `linen_change`, `inspection` | ✅ |
| **Priority** | `low`, `medium`, `high`, `urgent` | ✅ |
| **Status** | `pending`, `in_progress`, `completed`, `verified`, `cancelled` | ✅ |
| **Assigned To** | Staff (housekeeping) | |
| **Scheduled Date** | ថ្ងៃកំណត់ | |
| **Started At** | datetime ចាប់ផ្ដើម | |
| **Completed At** | datetime បញ្ចប់ | |
| **Note** | កំណត់ចំណាំ | |

### 10.1.2 Auto-Creation

> 🤖 **Auto-Logic**: នៅពេល Stay status = 'checked_out' `StayObserver` បង្កើត `HousekeepingTask` ភ្លាមៗដោយ:
> - Task Type = 'checkout_clean'
> - Priority = 'high'
> - Status = 'pending'
> - Room = Stay.room

### 10.1.3 Workflow

```
1. Stay status = 'checked_out' → Auto HousekeepingTask (HK-000xxx, pending)

2. Housekeeping Manager → Sidebar → Housekeeping Tasks
   ▸ Edit Task → Assigned To = Staff A → Save

3. Staff A ចូលធ្វើការ → Edit Task → Status = in_progress
   → Started At = now

4. Staff A សម្អាតបន្ទប់ → ត្រួតពិនិត្យ Checklist (10.2)

5. Staff A បញ្ចប់ → Edit Task → Status = completed
   → Completed At = now

6. Housekeeping Manager ឬ Reception ផ្ទៀងផ្ទាត់ → Status = verified
   → Update Room.status = 'available'
```

> ⚠️ **Caution**: ប្រសិនបើ Room.status នៅជា 'cleaning' រយៈពេលយូរ — ត្រួតពិនិត្យថា Task មាន status = 'verified' (មិនមែន 'completed' ប៉ុណ្ណោះ)។

---

## 10.2 Housekeeping Checklist Items

**Sidebar**: `Operations → Housekeeping Checklist Items` ▸ URL: `/admin/housekeeping_checklist_items`

Checklist Items ត្រូវបាន​ភ្ជាប់ទៅ Task — ដើម្បីផ្ដល់​ Staff នូវ​ list ច្បាស់លាស់​នៃ​អ្វី​ដែលត្រូវធ្វើ។

### 10.2.1 Field

| Field | ការពិពណ៌នា |
|---|---|
| **Housekeeping Task** | Task |
| **Item Description** | សកម្មភាព (e.g. "Change bed linen") |
| **Is Checked** | ✓ ឬ Boolean |
| **Checked At** | datetime |
| **Checked By** | Staff |
| **Note** | កំណត់ចំណាំ |

### 10.2.2 ឧទាហរណ៍ Checklist សម្រាប់ `checkout_clean`

| # | Item Description |
|---|---|
| 1 | Change bed linen and pillowcases |
| 2 | Clean and disinfect bathroom |
| 3 | Vacuum carpet / mop floor |
| 4 | Empty trash bins |
| 5 | Restock toiletries and amenities |
| 6 | Wipe windows and mirrors |
| 7 | Check mini-bar inventory |
| 8 | Verify all electronics work (TV, AC, lights) |
| 9 | Final inspection — room ready for next guest |

> 💡 **Tip**: Manager អាច​បង្កើត Checklist Template (បង្កើតជា Task ដែរ ហើយ copy ទៅ Task ថ្មីៗ) ឬ​បន្ថែម​ Items ដោយដៃ​​នៅ​ Task Show Page។

---

## 10.3 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| Assign Task ឱ្យ Staff ច្បាស់លាស់ | កុំ delete Task ដោយផ្ទាល់ — ប្រើ cancelled |
| ត្រួតពិនិត្យ checklist ទាំងអស់មុន Status = completed | កុំ skip verification step (verified) |
| ប្ដូរ Room.status = 'available' តាមរយៈ Task verify | កុំកែ Room.status ដោយដៃ |
| ប្រើ Priority = 'urgent' សម្រាប់ VIP arrivals | កុំ Assign Task ឱ្យ Staff ដែលគ្មាន role 'housekeeping' |
