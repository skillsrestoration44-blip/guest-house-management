# 8. ការចេញ Invoice និងការទូទាត់ — Invoicing & Payments

ផ្នែកនេះគ្របដណ្តប់ workflow ហិរញ្ញវត្ថុ៖ Invoice → Items → Payment → Receipt → Refund (បើចាំបាច់)។

---

## 8.1 Payment Methods — វិធីសាស្ត្រទូទាត់

**Sidebar**: `Invoicing & Payments → Payment Methods` ▸ URL: `/admin/payment_methods`

ត្រូវ​បង្កើតមុនពេលធ្វើ Payment ដំបូង (master data)។

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | សាខា |
| **Name** | ឈ្មោះ (e.g. "Cash", "ABA Bank", "Wing", "Credit Card") |
| **Code** | លេខកូដខ្លី (e.g. "CASH", "ABA", "WING") |
| **Type** | `cash`, `bank_transfer`, `mobile_money`, `card`, `other` |
| **Account No.** | លេខគណនី (សម្រាប់ bank/mobile) |
| **Is Active** | បើដំណើរការ |

> 💡 **Tip**៖ បង្កើតឡើងម្តងជាទៅពេលដំឡើង — បន្ទាប់មក Receptionists អាចជ្រើសក្នុង Payment form។

---

## 8.2 Invoices — វិក្កយបត្រ

**Sidebar**: `Invoicing & Payments → Invoices` ▸ URL: `/admin/invoices`

### 8.2.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Invoice No.** | Auto-generated (`INV-000001`) | 🤖 |
| **Stay** | Stay ភ្ជាប់ (Tom Select) | ✅ |
| **Guest** | Guest (auto-fill from Stay) | 🤖 |
| **Issued Date** | ថ្ងៃចេញ (flatpickr, default = now) | ✅ |
| **Due Date** | ថ្ងៃផុតកំណត់ | |
| **Subtotal** | សរុបរង (auto-computed from items) | 🤖 |
| **Tax Amount** | ពន្ធ | |
| **Discount Amount** | បញ្ចុះតម្លៃ | |
| **Total Amount** | សរុប (auto = subtotal + tax - discount) | 🤖 |
| **Paid Amount** | ចំនួនបង់ (auto-computed from payments) | 🤖 |
| **Balance** | នៅសល់ (auto = total - paid) | 🤖 |
| **Status** | `unpaid`, `partial`, `paid`, `cancelled`, `refunded` | 🤖 |
| **Note** | កំណត់ចំណាំ | |

> 🤖 **Auto-Logic**: `Invoice::recalculate()` ត្រូវបានហៅរាល់ពេល Invoice Item, Payment, ឬ Refund មានការផ្លាស់ប្ដូរ — វាគណនា subtotal, total, paid, balance, និង status ដោយស្វ័យប្រវត្តិ។ Customer **មិនត្រូវកែ Status ដោយដៃ**។

### 8.2.2 Workflow ចេញ Invoice

```
ជំហានទី 1: Stay ត្រូវមាន status = checked_out (រឺ checked_in សម្រាប់ Pre-bill)

ជំហានទី 2: Sidebar → Invoices → + Create Invoice
   ▸ Stay: ជ្រើស Stay
     → Guest, Issued Date auto-fill
   ▸ Due Date: (optional)
   ▸ Tax Amount: 0 (ឬតាមអត្រាដែលប្រើ)
   ▸ Discount Amount: 0 (ឬតាមការព្រមព្រៀង)
   ▸ Note: (optional)
   ▸ Save → Auto-generate INV-000xxx

ជំហានទី 3: Add Invoice Items (ក្នុង Invoice Show Page)
   ▸ Room Charge: 35 × 2 nights = 70
   ▸ Breakfast: 8 × 2 = 16
   ▸ Laundry: 12
   → Invoice::recalculate() គណនា subtotal = 98

ជំហានទី 4: Total Amount auto = 98 + tax - discount

ជំហានទី 5: បង្ហាញ Invoice ដល់ Guest (Print ឬ Email)

ជំហានទី 6: ប្រមូលប្រាក់ → បន្តទៅ Payment (8.3)
```

### 8.2.3 Invoice Items

នៅ Invoice Show Page Customer អាច៖
- Add Items: ប្រភេទ (room, service, food, other), description, quantity, unit_price → auto-compute amount
- Edit Items
- Delete Items
- រាល់ការ Save នឹង trigger `InvoiceItemObserver` → `Invoice::recalculate()`

---

## 8.3 Payments — ការទូទាត់

**Sidebar**: `Invoicing & Payments → Payments` ▸ URL: `/admin/payments`

### 8.3.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Payment No.** | Auto (`PAY-000001`) | 🤖 |
| **Invoice** | Invoice ភ្ជាប់ (Tom Select) | ✅ |
| **Payment Method** | វិធីសាស្ត្រ (Tom Select) | ✅ |
| **Amount** | ចំនួនបង់ | ✅ |
| **Paid Date** | ថ្ងៃបង់ (default = now) | ✅ |
| **Reference No.** | លេខយោង (bank ref, mobile txn id) | |
| **Status** | `pending`, `completed`, `failed`, `refunded` | ✅ |
| **Received By** | Staff (auto = current user) | 🤖 |
| **Note** | កំណត់ចំណាំ | |

### 8.3.2 Workflow ទូទាត់

```
ជំហានទី 1: Sidebar → Payments → + Create

ជំហានទី 2: បំពេញ Form:
   ▸ Invoice: ជ្រើស Invoice
   ▸ Payment Method: Cash / ABA / Wing
   ▸ Amount: 50 (បង់ផ្នែក) ឬ 98 (បង់ពេញ)
   ▸ Reference No.: (បើ bank/mobile)
   ▸ Status: completed
   ▸ Save

ជំហានទី 3: PaymentObserver auto:
   • Generate Payment No. (PAY-000xxx)
   • Update Invoice.paid_amount (recalculate)
   • Update Invoice.balance
   • Update Invoice.status:
     - paid_amount = total → 'paid'
     - paid_amount > 0 && paid_amount < total → 'partial'
     - paid_amount = 0 → 'unpaid'
   • If Payment.status = 'completed':
     - Auto-create Receipt (RCP-000xxx)
```

> 🤖 **Auto-Logic លម្អិត — Receipt Creation**: នៅពេល Payment.status = 'completed' `PaymentObserver` បង្កើត `Receipt` ភ្លាមៗ ដោយចម្លង Amount, Payment Method, Payment No., និងបង្កើត Receipt No. (RCP-000xxx)។

---

## 8.4 Receipts — បង្កាន់ដៃ

**Sidebar**: `Invoicing & Payments → Receipts` ▸ URL: `/admin/receipts`

Receipt ត្រូវបានបង្កើតដោយ **ស្វ័យប្រវត្តិ** ពី Payment ដែលមាន status = 'completed' — Customer មិនត្រូវបង្កើតដោយដៃ។

| Field | ការពិពណ៌នា |
|---|---|
| **Receipt No.** | Auto (`RCP-000001`) |
| **Payment** | Payment source |
| **Issued Date** | ថ្ងៃចេញ Receipt |
| **Amount** | ចំនួនទឹកប្រាក់ |
| **Issued By** | Staff |

### 8.4.1 ការប្រើ
- Print Receipt ដើម្បីប្រគល់ដល់ Guest ជាភស្តុតាង
- រក្សា Audit Trail ហិរញ្ញវត្ថុ (ISO 9001)

---

## 8.5 Refunds — ការប្រគល់ប្រាក់ត្រឡប់

**Sidebar**: `Invoicing & Payments → Refunds` ▸ URL: `/admin/refunds`

ប្រើនៅពេលត្រូវប្រគល់ប្រាក់ Guest វិញ (Booking cancel, តម្លៃខុស, ល.)។

### 8.5.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Refund No.** | Auto (`RFD-000001`) | 🤖 |
| **Payment** | Payment ដែលត្រូវ refund | ✅ |
| **Amount** | ចំនួនត្រលប់ (≤ Payment.amount) | ✅ |
| **Refund Date** | ថ្ងៃ refund | ✅ |
| **Reason** | ហេតុផល (e.g. "Booking cancelled", "Overcharged") | ✅ |
| **Status** | `pending`, `approved`, `rejected`, `completed` | ✅ |
| **Approved By** | Manager (auto = current user) | 🤖 |
| **Note** | កំណត់ចំណាំ | |

### 8.5.2 Workflow

```
1. Guest ស្នើ Refund → Reception សួរ Manager
2. Manager ផ្ទៀងផ្ទាត់ → Sidebar → Refunds → + Create
3. បំពេញ: Payment, Amount, Reason
4. Status = pending → Manager approves → status = approved
5. ប្រគល់ប្រាក់តាមវិធីដែលបង់ (cash/bank transfer back)
6. ប្ដូរ Status = completed
7. Invoice::recalculate() — paid_amount ថយ → Invoice.status អាចត្រលប់ទៅ 'partial' ឬ 'refunded'
```

---

## 8.6 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| ប្រមូល Reference No. សម្រាប់ bank/mobile payments | កុំ skip Reference No. នៅ payments អនឡាញ |
| ផ្ទៀងផ្ទាត់ Amount ច្បាស់ៗមុន Save Payment | កុំកែ Invoice.status ដោយដៃ |
| Print Receipt ប្រគល់ Guest រាល់ payment | កុំ delete Payment — បង្កើត Refund វិញ |
| Add Tax / Discount នៅ Invoice level (មិនមែន Item) | កុំ delete Receipt — Receipt auto-generated |
| ប្រើ Refund សម្រាប់ Cancel ឬ Overcharge | កុំប្រើ Refund ដើម្បីកែ Payment ខុស (delete Payment មុន) |

---

## 8.7 Audit Trail

រាល់ Invoice, Payment, Receipt, Refund ត្រូវបានកត់ត្រាក្នុង `audit_logs`៖
- ANY CREATE/UPDATE/DELETE → entry ក្នុង Audit Log
- Mapping: `module=Invoice`, `module=Payment`, etc.
- នេះគឺជា ISO 9001 §4.2.4 Records Management requirement
