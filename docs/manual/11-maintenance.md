# 11. ការថែទាំ — Maintenance

ផ្នែកនេះគ្រប់គ្រងសំណើជួសជុល/ថែទាំ (AC, plumbing, electrical, etc.) រួមជាមួយរូបថត ​និងតម្លៃ ដែលជា​​លក្ខណៈ​ Audit Trail សម្រាប់​​ ISO 9001 §7.5.1 Maintenance Records។

---

## 11.1 Maintenance Requests — សំណើជួសជុល

**Sidebar**: `Operations → Maintenance Requests` ▸ URL: `/admin/maintenance_requests`

### 11.1.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Request No.** | Auto (`MNT-000001`) | 🤖 |
| **Room** | Room (optional — អាចជា common area) | |
| **Issue Type** | `plumbing`, `electrical`, `hvac`, `furniture`, `appliance`, `structural`, `other` | ✅ |
| **Priority** | `low`, `medium`, `high`, `urgent` | ✅ |
| **Description** | ការពិពណ៌នាបញ្ហា | ✅ |
| **Status** | `reported`, `assigned`, `in_progress`, `completed`, `cancelled` | ✅ |
| **Reported By** | Staff (auto = current user) | 🤖 |
| **Assigned To** | Maintenance staff | |
| **Reported At** | datetime (default: now) | 🤖 |
| **Started At** | datetime | |
| **Completed At** | datetime | |
| **Resolution** | ការដោះស្រាយ | |
| **Note** | កំណត់ចំណាំ | |

### 11.1.2 Workflow

```
1. Reception/Housekeeping ឃើញបញ្ហា (e.g. AC មិនត្រជាក់)
   → Sidebar → Maintenance Requests → + Create

2. បំពេញ Form:
   ▸ Room: 201
   ▸ Issue Type: hvac
   ▸ Priority: high
   ▸ Description: "AC unit not cooling — room temperature 28°C"
   ▸ Status: reported
   ▸ Save → Auto MNT-000xxx

3. ប្រសិនបើ Room នោះមាន Guest ស្នាក់ → Reception ផ្លាស់ Guest ​ដោយ Room Transfer (7.2)

4. Maintenance Manager → Edit Request → Assigned To = Technician X → Status = assigned

5. Technician X → Edit → Status = in_progress
   → Started At = now

6. Technician X បន្ថែម Photos (11.2) និង Costs (11.3) ក្នុង Request Show Page

7. ការងារបញ្ចប់ → Edit Request → Status = completed
   → Completed At = now
   → Resolution: "Refrigerant refilled and compressor cleaned"

8. ប្រសិនបើ Room.status = 'maintenance' → ប្ដូរទៅ 'available' (តាមរយៈ Housekeeping cleaning task)
```

> ⚠️ **Caution**: បើ Issue Type ជា 'structural' ឬ 'electrical' (សុវត្ថិភាព) → Priority ត្រូវ​ ​= 'urgent' និងជូនដំណឹង​​ Manager ភ្លាមៗ។

---

## 11.2 Maintenance Photos — រូបថត

**Sidebar**: `Operations → Maintenance Photos` ▸ URL: `/admin/maintenance_photos`

ឯកសារ​រូបថត​មុន/ក្រោយ​​ការ​ជួសជុល ​ ​​​ — ផ្តល់​ Audit Trail និងភស្ដុតាង​​សម្រាប់​​ Insurance ឬ Supplier claims។

| Field | ការពិពណ៌នា |
|---|---|
| **Maintenance Request** | Request ភ្ជាប់ |
| **Photo Path** | Upload file (jpg, png) |
| **Photo Type** | `before`, `during`, `after` |
| **Caption** | ចំណងជើង / ការពិពណ៌នា |
| **Uploaded By** | Staff |
| **Uploaded At** | datetime |

### 11.2.1 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| Upload រូបថត `before` និង `after` រាល់ Request | កុំ skip photos សម្រាប់ Issue Type 'structural' |
| Caption ច្បាស់លាស់ (e.g. "Broken pipe in bathroom — water leak") | កុំ upload រូបថត​ដែលគ្មាន​ Caption |

---

## 11.3 Maintenance Costs — ការចំណាយ

**Sidebar**: `Operations → Maintenance Costs` ▸ URL: `/admin/maintenance_costs`

| Field | ការពិពណ៌នា |
|---|---|
| **Maintenance Request** | Request ភ្ជាប់ |
| **Cost Type** | `labor`, `parts`, `external_service`, `other` |
| **Amount** | ចំនួនទឹកប្រាក់ |
| **Description** | ការពិពណ៌នា (e.g. "Refrigerant gas 1kg") |
| **Supplier** | Supplier (បើ external) |
| **Receipt Photo** | Upload (optional) |
| **Recorded By** | Staff |
| **Recorded At** | datetime |

### 11.3.1 Workflow

```
1. Technician បន្ថែម Costs បន្ទាប់ពីការងារបញ្ចប់:
   ▸ Cost Type: parts
   ▸ Amount: 25.00
   ▸ Description: "Refrigerant R134a 1kg"
   ▸ Supplier: ABC Supply Co.

2. បន្ថែម​ Costs ​បន្ថែម​ ​សម្រាប់​ Labor:
   ▸ Cost Type: labor
   ▸ Amount: 15.00
   ▸ Description: "2 hours labor"

3. Maintenance Manager review → ផ្ទៀងផ្ទាត់ Total Cost
4. ក្នុង Accounting → បន្ថែម Expense ​ដែលភ្ជាប់​​​ទៅ​ Maintenance Request (ជំពូក 13.2)
```

---

## 11.4 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| Photo `before/after` រាល់ Request | កុំ delete Request — cancel វិញ |
| Cost breakdown ច្បាស់លាស់ (labor + parts) | កុំ skip Photos សម្រាប់​​ structural issues |
| Resolution field ​ឱ្យ ​​លម្អិត​ (សម្រាប់ recurring issues) | កុំទុក​ Request status = reported ​លើស 24h ​​​ ​បើ priority = urgent |
| ត្រួតពិនិត្យ Audit Logs មុនបិទ​​​ Request | កុំ​​​ Assign ​​ ​ Request ឱ្យ Staff​ ​ ​​ដែល​​​មិនមាន​​​ role ​សមរម្យ |
