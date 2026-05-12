# 12. ការគ្រប់គ្រងស្តុក — Inventory Management

ផ្នែកនេះគ្រប់គ្រងស្តុក (toiletries, linens, food/beverage, cleaning supplies) រួម​មាន​ Suppliers, Stock Categories, Stock Items, និង Stock Movements។

---

## 12.1 Suppliers — អ្នកផ្គត់ផ្គង់

**Sidebar**: `Inventory → Suppliers` ▸ URL: `/admin/suppliers`

### 12.1.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Supplier Code** | លេខកូដ (e.g. "SUP-001") | ✅ |
| **Name** | ឈ្មោះក្រុមហ៊ុន | ✅ |
| **Contact Person** | អ្នកទំនាក់ទំនង | |
| **Phone** | លេខទូរស័ព្ទ | |
| **Email** | អ៊ីមែល | |
| **Address** | អាសយដ្ឋាន | |
| **Tax ID** | លេខពន្ធ | |
| **Payment Terms** | លក្ខខណ្ឌទូទាត់ (e.g. "Net 30") | |
| **Is Active** | បើដំណើរការ | |

> 💡 **Tip**: បន្ទាប់ពីបង្កើត Supplier — ប្រើ Supplier Scorecard module (16.5) ដើម្បីវាយ​តម្លៃ Performance ប្រចាំខែ/ត្រីមាស (ISO 9001 §7.4.1)។

---

## 12.2 Stock Categories — ​​ប្រភេទស្តុក

**Sidebar**: `Inventory → Stock Categories` ▸ URL: `/admin/stock_categories`

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | សាខា |
| **Category Code** | លេខកូដ |
| **Name** | ឈ្មោះ |
| **Description** | ការពិពណ៌នា |
| **Parent Category** | សម្រាប់ hierarchical (optional) |

### 12.2.1 ឧទាហរណ៍ Categories

| Code | Name | Description |
|---|---|---|
| TOIL | Toiletries | Soap, shampoo, towels |
| LIN | Linens | Bedsheets, pillowcases |
| FB | Food & Beverage | Mini-bar items |
| CLEAN | Cleaning Supplies | Detergents, mops |
| OFFICE | Office Supplies | Paper, pens |

---

## 12.3 Stock Items — ឯកសារស្តុក

**Sidebar**: `Inventory → Stock Items` ▸ URL: `/admin/stock_items`

### 12.3.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Item Code** | លេខកូដ (e.g. "ITM-0001") | ✅ |
| **Name** | ឈ្មោះ | ✅ |
| **Stock Category** | ប្រភេទ | ✅ |
| **Default Supplier** | Supplier ដែលប្រើ​ច្រើន | |
| **Unit** | ឯកតា (e.g. "piece", "kg", "bottle") | ✅ |
| **Unit Cost** | តម្លៃទិញ | ✅ |
| **Selling Price** | តម្លៃលក់ (បើ direct sale) | |
| **Current Stock** | ស្តុកសរុបបច្ចុប្បន្ន (auto) | 🤖 |
| **Reorder Level** | កម្រិតត្រូវ​​បញ្ជា (alert threshold) | |
| **Is Active** | បើដំណើរការ | |

### 12.3.2 ឧទាហរណ៍

| Item Code | Name | Category | Unit | Unit Cost | Reorder Level |
|---|---|---|---|---|---|
| ITM-001 | Bath Towel (large) | LIN | piece | 4.50 | 50 |
| ITM-002 | Shampoo 30ml | TOIL | bottle | 0.30 | 200 |
| ITM-003 | Bed Sheet (queen) | LIN | piece | 12.00 | 30 |
| ITM-004 | Toilet Paper | TOIL | roll | 0.75 | 100 |

> ⚠️ **Caution**: នៅពេល Current Stock ≤ Reorder Level → Manager ត្រូវបង្កើត Purchase Order ភ្លាមៗដើម្បីបញ្ជាបន្ថែម។

---

## 12.4 Stock Movements — ការផ្លាស់ប្ដូរស្តុក

**Sidebar**: `Inventory → Stock Movements` ▸ URL: `/admin/stock_movements`

កត់ត្រាការចូល/ចេញ​​​ស្តុក — នៅ​ពេល​ Save ​​Current Stock ត្រូវ​​ បាន​​​​ recalculate ដោយ​​ ស្វ័យ​ប្រវត្តិ។

### 12.4.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Movement No.** | Auto (`MV-000001`) | 🤖 |
| **Stock Item** | Item | ✅ |
| **Movement Type** | `purchase`, `usage`, `return`, `adjustment`, `damage`, `transfer` | ✅ |
| **Quantity** | ចំនួន (positive for in, negative for out) | ✅ |
| **Unit Cost** | តម្លៃ/unit (សម្រាប់ purchase) | |
| **Total Cost** | សរុប (auto = qty × unit_cost) | 🤖 |
| **Supplier** | Supplier (សម្រាប់ purchase) | |
| **Reference** | លេខយោង (PO No., Invoice No., etc.) | |
| **Movement Date** | ថ្ងៃ | ✅ |
| **Recorded By** | Staff | 🤖 |
| **Note** | កំណត់ចំណាំ | |

### 12.4.2 Workflow ទិញស្តុក (Purchase)

```
1. Manager បញ្ជា Supplier (in-person/email)
2. ស្តុកមកដល់ → ផ្ទៀងផ្ទាត់ quantity, quality
3. Sidebar → Stock Movements → + Create
4. បំពេញ:
   ▸ Stock Item: Bath Towel (large)
   ▸ Movement Type: purchase
   ▸ Quantity: 100 (positive)
   ▸ Unit Cost: 4.50
     → Total Cost = 450.00 auto
   ▸ Supplier: ABC Linen Supply
   ▸ Reference: "PO-2026-0042"
   ▸ Save

5. Stock Item.current_stock ← + 100 (auto-updated)
6. បន្ថែម Expense (ជំពូក 13.2) ដើម្បីបន្ត Accounting
```

### 12.4.3 Workflow ប្រើ​​​ស្តុក (Usage)

```
1. Housekeeping យក Bath Towels 5 ពី warehouse
2. Sidebar → Stock Movements → + Create
3. បំពេញ:
   ▸ Stock Item: Bath Towel (large)
   ▸ Movement Type: usage
   ▸ Quantity: -5 (negative)
   ▸ Reference: "Room 201, 202, 203"
   ▸ Save

4. Stock Item.current_stock ← - 5
```

---

## 12.5 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| Record Movement រាល់ចូល/ចេញស្តុក​ | កុំកែ Current Stock ដោយដៃ |
| ប្រើ Movement Type 'adjustment' ​សម្រាប់ stock count differences | កុំ delete Movement — បង្កើត 'adjustment' វិញ |
| ត្រួតពិនិត្យ Reorder Level ប្រចាំសប្ដាហ៍ | កុំ Save Movement ដោយគ្មាន Reference |
| Audit Stock ប្រចាំខែ (physical count vs system) | កុំទុក Stock = 0 ដោយ​មិន reorder |

---

## 12.6 Stock Balance Report

នៅ Stock Items index page (DataTable) — Customer អាច:
- Sort តាម Current Stock (ascending) → ឃើញ items ដែលជិតអស់
- Filter តាម Category
- Export CSV (បើ​​​មាន) សម្រាប់​​ accounting

### 12.6.1 ISO 9001 Compliance
- **§7.4.1 Purchasing Process**: រាល់ purchase Movement ត្រូវ​​មាន Supplier និង Reference
- **§7.5.1 Production / Service**: Usage Movements track​​ ​ ​​​ resource consumption
- **§8.5.2 Corrective Action**: Adjustment Movements ​ត្រូវ​​​​ មាន​ Note ច្បាស់​លាស់​ ​នៅពេលមាន discrepancy
