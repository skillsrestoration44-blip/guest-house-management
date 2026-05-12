# 16. គុណភាព / ISO 9001 — Quality / ISO 9001

ផ្នែកនេះគឺជាស្នូលនៃ ISO 9001 Compliance — រួមមាន Guest Feedback, Complaints, Corrective Actions (CAPA), Risks, Supplier Scorecards, និង Document Versions។

---

## 16.1 Guest Feedback

**Sidebar**: `Quality (ISO 9001) → Guest Feedback` ▸ URL: `/admin/guest_feedbacks`

ISO 9001 §8.2.1 — Customer Satisfaction Measurement។

### Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Feedback No.** | Auto (`FB-000001`) | 🤖 |
| **Guest** | Guest | ✅ |
| **Stay** | Stay (optional) | |
| **Rating Overall** | 1-5 stars | ✅ |
| **Rating Room** | 1-5 | |
| **Rating Service** | 1-5 | |
| **Rating Cleanliness** | 1-5 | |
| **Comments** | មតិ | |
| **Action Required** | Boolean (បើ rating < 3) | |

### Workflow

```
1. Stay = checked_out → ផ្ញើ Feedback Form (email/QR)
2. Guest បំពេញ → FB-000xxx
3. Manager ត្រួតពិនិត្យ​ ប្រចាំសប្ដាហ៍
4. Rating < 3 → Create Complaint
5. Pattern មានបញ្ហា → Create Corrective Action
```

---

## 16.2 Complaints

**Sidebar**: `Quality (ISO 9001) → Complaints` ▸ URL: `/admin/complaints`

### Field

| Field | ការពិពណ៌នា |
|---|---|
| **Complaint No.** | Auto (`CPL-000001`) |
| **Guest** | Guest |
| **Subject** | ប្រធានបទ |
| **Description** | លម្អិត |
| **Category** | service, cleanliness, noise, billing, staff, other |
| **Severity** | low, medium, high, critical |
| **Status** | open, investigating, resolved, closed |
| **Resolution** | ការដោះស្រាយ |
| **Assigned To** | Staff/Manager |

### Workflow

```
1. Guest ត្អូញត្អែរ → + Create
2. Status = open → Assigned To Manager
3. investigate → Status = investigating
4. Resolution → Status = resolved
5. Verify ជាមួយ Guest → Status = closed
6. Severity = critical → Auto-create CAPA
```

---

## 16.3 Corrective Actions (CAPA)

**Sidebar**: `Quality (ISO 9001) → Corrective Actions` ▸ URL: `/admin/corrective_actions`

ISO 9001 §10.2 — Nonconformity and Corrective Action។

### Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **CAPA No.** | Auto (`CAPA-000001`) | 🤖 |
| **Source Type** | complaint, audit_finding, risk, internal | ✅ |
| **Title** | ចំណងជើង | ✅ |
| **Problem Description** | បញ្ហា | ✅ |
| **Root Cause** | មូលហេតុមូលដ្ឋាន | |
| **Action Plan** | ផែនការសកម្មភាព | ✅ |
| **Responsible Person** | អ្នកទទួលខុសត្រូវ | ✅ |
| **Target Date** | ថ្ងៃកំណត់ | ✅ |
| **Completion Date** | ថ្ងៃបញ្ចប់ | |
| **Effectiveness Review** | ការត្រួតពិនិត្យលទ្ធផល | |
| **Status** | draft, open, in_progress, completed, verified, closed | ✅ |

### Workflow (PDCA Cycle)

```
1. Source (Complaint/Audit/Risk) → + Create CAPA → status=draft
2. Manager review → status=open
3. Root Cause Analysis (5-Why technique)
4. Plan: Action Plan + Target Date
5. Do: Responsible Person ប្រតិបត្តិ
6. Check: Completion Date + Effectiveness Review
7. Act: បើ effective → status=closed; បើមិន → ស្នើ CAPA ថ្មី
```

---

## 16.4 Risks

**Sidebar**: `Quality (ISO 9001) → Risks` ▸ URL: `/admin/risks`

ISO 9001 §6.1 — Actions to address risks and opportunities (Risk-Based Thinking)។

### Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Risk No.** | Auto (`RSK-000001`) | 🤖 |
| **Title** | ចំណងជើង | ✅ |
| **Description** | ការពិពណ៌នា | |
| **Category** | operational, financial, reputational, safety, compliance | ✅ |
| **Likelihood** | 1-5 | ✅ |
| **Impact** | 1-5 | ✅ |
| **Risk Score** | likelihood × impact (auto) | 🤖 |
| **Risk Level** | Low(1-4), Medium(5-12), High(13-20), Critical(21-25) | 🤖 |
| **Owner** | Staff | ✅ |
| **Mitigation Plan** | ផែនការកាត់បន្ថយ | |
| **Status** | identified, assessing, mitigating, monitoring, closed | ✅ |
| **Review Date** | ថ្ងៃត្រួតពិនិត្យបន្ទាប់ | |

### Workflow

```
1. + Create Risk → Likelihood × Impact = Risk Score (auto)
2. Status = identified → Assessing
3. បើ Score ≥ 13 → Mitigation Plan ចាំបាច់
4. Mitigation Plan → Status = mitigating
5. បន្ទាប់ implement → Status = monitoring
6. Review ប្រចាំខែ → បើ Score ស្ថេរនិង Low → Status = closed
```

> 🤖 **Auto-Logic**: `Risk.risk_score` ត្រូវបានគណនាដោយ Model `saving` event (`likelihood × impact`)។

---

## 16.5 Supplier Scorecards

**Sidebar**: `Quality (ISO 9001) → Supplier Scorecards` ▸ URL: `/admin/supplier_scorecards`

ISO 9001 §8.4 — Control of externally provided processes, products and services។

### Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Scorecard No.** | Auto (`SSC-000001`) | 🤖 |
| **Supplier** | Supplier | ✅ |
| **Period** | ខែ/ឆ្នាំ | ✅ |
| **Quality Rating** | 1-5 | ✅ |
| **Delivery Rating** | 1-5 | ✅ |
| **Price Rating** | 1-5 | ✅ |
| **Service Rating** | 1-5 | ✅ |
| **Overall Rating** | Average (auto) | 🤖 |
| **Comments** | មតិ | |
| **Reviewed By** | Manager | |

### Workflow

```
1. Manager វាយតម្លៃ Supplier នីមួយៗ ប្រចាំខែ
2. បំពេញ Quality / Delivery / Price / Service rating
3. Overall = average (auto)
4. បើ Overall < 3 → ស្នើ Supplier change ឬ Corrective Action
5. Track trend over time → ខ្លះ​​ supplier ត្រូវ remove
```

---

## 16.6 Document Versions

**Sidebar**: `Quality (ISO 9001) → Document Versions` ▸ URL: `/admin/document_versions`

ISO 9001 §7.5 — Documented Information (Control of Documents)។

### Field

| Field | ការពិពណ៌នា |
|---|---|
| **Document Code** | Code (e.g. "SOP-001", "POL-002") |
| **Title** | ចំណងជើង |
| **Version** | លេខ version (e.g. "1.0", "2.1") |
| **Document Type** | SOP, Policy, Manual, Form, Record |
| **Effective Date** | ថ្ងៃចូលធ្វើ |
| **Review Date** | ថ្ងៃត្រួតពិនិត្យបន្ទាប់ |
| **Approved By** | Manager |
| **File Path** | Upload PDF |
| **Status** | draft, active, superseded, obsolete |
| **Change Notes** | ការផ្លាស់ប្ដូរ |

### Workflow

```
1. Create document → status=draft, version=1.0
2. Manager review → Approved By → status=active
3. Effective Date បានកំណត់ → ប្រើជាផ្លូវការ
4. ត្រូវ update? → Create version 1.1 ថ្មី → status=active
5. Previous version 1.0 → status=superseded (auto)
6. មិនប្រើទៀត → status=obsolete
```

> 📌 **Best Practice**: Set Review Date ≤ 1 ឆ្នាំសម្រាប់ documents សំខាន់ៗ។ ISO 9001 តម្រូវការ​ត្រួតពិនិត្យជាប្រចាំ។

---

## 16.7 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| ផ្ញើ Feedback Form ដល់ Guest គ្រប់ Stay | កុំ ignore Rating ≤ 3 |
| Run CAPA ចេញពី Complaints critical | កុំ close Complaint ដោយគ្មាន​​ ​​​ verify |
| Update Risk Register ប្រចាំខែ | កុំ skip Review Date |
| Document Versions ត្រូវ Approved By Manager | កុំ delete old version |
| Supplier Scorecard ប្រចាំខែ | កុំ keep low-performing supplier |
