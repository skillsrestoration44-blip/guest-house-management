# 5. ការគ្រប់គ្រងភ្ញៀវ — Guest Management

ភ្ញៀវ (Guest) គឺជាបុគ្គល​ដែលកក់​ឬ​ស្នាក់នៅ — ​Module នេះ​ ​ត្រូវ​​​ setup​​​​​​​​​ មុនពេល​​បង្កើត Booking ដំបូង​។

---

## 5.1 Guests — ភ្ញៀវ

**Sidebar**: `Guest Management → Guests` ▸ URL: `/admin/guests`

### 5.1.1 Field សំខាន់

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | ✅ |
| **Guest Code** | Auto‑generated (`G-00001`) — កុំកែ | 🤖 |
| **Full Name** | ឈ្មោះពេញ​ | ✅ |
| **Gender** | male / female / other | |
| **Date of Birth** | ថ្ងៃខែឆ្នាំកំណើត​ | |
| **Nationality** | សញ្ជាតិ​ (e.g. Cambodian, Thai) | |
| **Phone** | លេខទូរសព្ទ​ | ✅ |
| **Email** | អ៊ីមែល | |
| **Address** | អាសយដ្ឋាន​បច្ចុប្បន្ន | |
| **ID Type** | `passport`, `national_id`, `driver_license` | |
| **ID Number** | លេខ document | |
| **Note** | កំណត់ចំណាំ​​ | |
| **Status** | active / blacklisted | ✅ |

> 🤖 **Auto‑Generated**៖ `Guest Code` (e.g. `G-00001`) ​ត្រូវ​បាន​បង្កើត​ដោយ `CodeGeneratorService` — Customer **គ្មាន​ត្រូវ​បំពេញ​​​ ​​ឡើយ​​​​​​​​**។ ប្រសិនបើ​​អ្នក​លុប​​ field នេះ​​ ​​​ប្រព័ន្ធ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ បង្កើតស្វ័យប្រវត្តិ។

### 5.1.2 ការបង្កើត Guest ថ្មី (ចាប់​ផ្ដើម​​ ​​​​​​ Reception ​​)​

```
1. Sidebar → Guest Management → Guests
2. ចុច + Create Guest
3. បំពេញ Form:
   - Full Name: Mr. Kim Chanthy
   - Phone: 012345678
   - Nationality: Cambodian
   - ID Type: national_id, ID Number: 12345678
4. Save
5. ប្រព័ន្ធនឹង​បង្កើត​ Guest Code (G-00001) ដោយ​ស្វ័យប្រវត្តិ
6. Guest ត្រឡប់​មក​​ Index Page → ប្រើ​​បន្ទាប់​​​​​​សម្រាប់​​​​​​​ Booking
```

### 5.1.3 ស្ថានភាព Blacklist

ប្រសិនបើ Guest មាន​​ ​បញ្ហា​​​ (ឧ. ​បំផ្លាញ​​​​​​​បន្ទប់​​​​​, មិន​​​បាន​​​បង់​​​ប្រាក់) Customer ​អាច​ ​ប្ដូរ​ Status​​​ →​ `blacklisted`​​​:

```
1. Edit Guest → Status = blacklisted
2. បំពេញ Note ​ ​​ ​​ហេតុ​ផល​​​​​ (e.g. "Damaged room 201 on 2026-01-15")
3. Save
```

> 🔒 ​ ​​​ ​​​ ​ ​​​​ ​ ​​ ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ ​ ​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ Booking form នឹង​​​​បង្ហាញ​​​ ​​​​warning​​​​ ​បើ​​​ Customer ព្យាយាម book guest blacklisted។

---

## 5.2 Guest Documents — ឯកសារភ្ញៀវ

**Sidebar**: `Guest Management → Guest Documents` ▸ URL: `/admin/guest_documents`

ប្រើ​​​​​សម្រាប់​​​​​ ​​​upload រូប passport / ID card សម្រាប់​​​​​​ ​ ​​បំពេញ​​ ស្តង់ដារ​ KYC (Know Your Customer)។

### 5.2.1 Field

| Field | ការពិពណ៌នា |
|---|---|
| **Guest** | ​ភ្ជាប់ទៅ​​​​​ Guest |
| **Document Type** | passport / national_id / visa / driver_license |
| **Document Number** | លេខឯកសារ |
| **Issue Date** | ថ្ងៃ​ចេញ​ |
| **Expiry Date** | ថ្ងៃ​ផុតកំណត់​ |
| **File Path** | upload (PDF ឬ image) |
| **Note** | |

### 5.2.2 Workflow

```
1. នៅ​ Reception → ​ស្នើ​​​​​សុំ​​ Guest ឱ្យ​​​​​​​​​​​​​បង្ហាញ​​​​ Passport
2. Sidebar → Guest Documents → + Create
3. ​ភ្ជាប់ Guest, ជ្រើស Document Type, បំពេញលេខ និងថ្ងៃផុតកំណត់
4. Upload File (Photo ឬ Scan)
5. Save
```

> 💡 **Tip**៖ ​​​​​ ​​Document ​Expiry Date ​​​​​ប្រចាំ​​ខែ​​​ — Manager ​​​​​​អាច filter ​​​​​​​​ Guest Documents ​​​ដោយ ​​Expiry Date < ៣០ថ្ងៃ​​​​​​​​​​​​​​​​​​​​​​ ​​​​​​​​​​​​​​​​​​​​​​​​​​ ​សម្រាប់​​​​​​​ ​​អនុញ្ញាត​ផ្ដល់​​​​​​​ permits ឬ​​​​ visa ​បន្តៗ ។

---

## 5.3 Best Practice — អនុសញ្ញាសម្រាប់​​​​​ Reception

| ✅ Do | ❌ Don't |
|---|---|
| ​​បង្កើត​​​ Guest មុន​​​ Booking​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ | កុំ​បំពេញ​​ ​​Guest ​ ​ឈ្មោះ​​​​​ ​នៅក្នុង​​ Booking ​Note ​​​ — ​​​ត្រូវ​​​​​​​​ link ទៅ Guest record |
| Verify Phone Number​​​​​​ | កុំចែករំលែក Phone​​​ ​​​​មួយ​ ​ឱ្យ Guests ច្រើន |
| Update Address ​បើ​​​​​​​​​Guest ​ប្ដូរ​ | កុំ delete Guest ដែលមាន Booking history |
| Upload Document សម្រាប់​​​​​​​​ Foreigner | កុំ​​​​​​ keep ID copy នៅ​​​ desktop ​ — upload ​ឱ្យ​​​​​​​​ ឯកសារ​​​​​​​​​​​​​ ​​​នៅ​​​ ​​​ ​​​​​secure​​ |

---

## 5.4 ការ​ Search Guest

នៅ Index Page​ (`/admin/guests`) ​​Customer អាច search តាម​​​ ៖
- Guest Code (e.g. `G-00012`)
- Full Name (partial match)
- Phone Number
- ID Number

DataTable ​debounce 300ms — វាយ ​3 ​​តួ​​​​​ ​​យ៉ាង​​​​​​​​​​តិច​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​ ​​​​​​​​​​​​មុន​​ filter ​ ​​​​​​​​​ឱ្យ​លឿន​​​​​​​​​​​​​​។
