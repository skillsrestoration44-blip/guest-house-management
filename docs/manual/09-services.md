# 9. សេវាកម្ម & ការគិតថ្លៃសេវាកម្ម — Services & Service Charges

ផ្នែកនេះគ្រប់គ្រងសេវាកម្មបន្ថែម​ដែល Guest ប្រើ (food, laundry, mini-bar, ល.) និងការគិតថ្លៃវាក្នុង Stay។

---

## 9.1 Services — សេវាកម្ម (Master Data)

**Sidebar**: `Services → Services` ▸ URL: `/admin/services`

ត្រូវ​បង្កើត​ឡើង​ម្ដង​ជា master data — ប្រើជា catalog សម្រាប់ Service Charges។

### 9.1.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Service Code** | លេខកូដ (e.g. "LAUNDRY", "BREAKFAST", "AIRPORT") | ✅ |
| **Name** | ឈ្មោះ | ✅ |
| **Category** | `food`, `laundry`, `transport`, `spa`, `mini_bar`, `other` | ✅ |
| **Unit Price** | តម្លៃក្នុងមួយ unit | ✅ |
| **Unit** | ឯកតា (e.g. "per_use", "per_hour", "per_kg") | ✅ |
| **Description** | ការពិពណ៌នា | |
| **Is Active** | បើដំណើរការ (default: true) | |

### 9.1.2 ឧទាហរណ៍ Services ទូទៅ

| Service Code | Name | Category | Unit Price | Unit |
|---|---|---|---|---|
| BREAKFAST | Breakfast Set | food | 8.00 | per_use |
| LAUNDRY | Laundry Service | laundry | 12.00 | per_kg |
| AIRPORT | Airport Pickup | transport | 25.00 | per_use |
| MASSAGE | Body Massage | spa | 30.00 | per_hour |
| MINIBAR_BEER | Mini-Bar Beer | mini_bar | 3.50 | per_use |

---

## 9.2 Service Charges — ការគិតថ្លៃសេវាកម្ម

**Sidebar**: `Services → Service Charges` ▸ URL: `/admin/service_charges`

Service Charge ជាការប្រើជាក់ស្ដែងរបស់ Guest ដែលនឹងបញ្ចូលក្នុង Invoice។

### 9.2.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Stay** | Stay ភ្ជាប់ (Tom Select) | ✅ |
| **Service** | Service master (Tom Select) | ✅ |
| **Quantity** | ចំនួន | ✅ |
| **Unit Price** | តម្លៃ/unit (auto-fill from Service) | ✅ |
| **Total Amount** | សរុប (auto = quantity × unit_price) | 🤖 |
| **Service Date** | ថ្ងៃប្រើ (default: now) | ✅ |
| **Note** | កំណត់ចំណាំ | |
| **Status** | `pending`, `delivered`, `cancelled` | ✅ |

### 9.2.2 Workflow

```
1. Guest ស្នើសុំសេវាកម្ម (e.g. Laundry 3 kg)
2. Reception → Sidebar → Service Charges → + Create
3. បំពេញ Form:
   ▸ Stay: ជ្រើស Stay (active checked_in)
   ▸ Service: Laundry Service
     → Unit Price = 12.00 auto-fill
   ▸ Quantity: 3 (kg)
     → Total Amount = 36.00 auto-compute
   ▸ Service Date: today
   ▸ Status: pending (រហូតដល់ delivered)
   ▸ Save

4. នៅពេលសេវាកម្មបញ្ចប់ → Edit Service Charge → Status = delivered

5. ពេលចេញ Invoice (ជំពូក 8.2) Service Charges ទាំងអស់នៃ Stay អាចបន្ថែមជា Invoice Items
```

> 💡 **Tip**: Service Charge ស្ថិតនៅ Stay level ដូច្នេះវាបន្ត accumulate ពេញកំឡុង Stay។ ពេលចេញ Invoice Reception ត្រូវបន្ថែម Service Charges ទាំងអស់ជា Invoice Items។

---

## 9.3 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| បន្ថែម Service Charge ភ្លាមៗបន្ទាប់ពី Guest ប្រើ | កុំទុក​ Service Charges លុះត្រាដល់ Check-out |
| ប្ដូរ Status = delivered បន្ទាប់សេវាកម្មបញ្ចប់ | កុំកែ Unit Price ដោយដៃ បើគ្មានហេតុផល |
| ត្រួតពិនិត្យ Service Charges នៅ Stay Show Page មុនចេញ Invoice | កុំ delete Service Charge — cancel វិញ (status=cancelled) |
| ប្រើ Note សម្រាប់​សេវាកម្ម​​ពិសេស (e.g. "VIP — no charge") | កុំបន្ថែម Service Charges ដែលគ្មាន Stay |

---

## 9.4 ការមើលប្រវត្តិសេវាកម្ម

នៅ **Stay Show Page** Customer អាចមើល List Service Charges ទាំងអស់នៃ Stay នោះ ដោយរួមបញ្ចូល:
- Service ឈ្មោះ
- Quantity, Unit Price, Total
- Service Date
- Status

→ បន្ទាប់មកអាច Add → Invoice Items ដោយផ្ទាល់ ឬ copy values។
