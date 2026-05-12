# 14. ការជូនដំណឹង — Notifications

ផ្នែកនេះគ្រប់គ្រង Notification Templates (template messages) និង Notifications (បន្ទាប់ pending/sent records) ដើម្បីផ្ញើទៅ Guests, Staff, Suppliers។

---

## 14.1 Notification Templates

**Sidebar**: `Notifications → Notification Templates` ▸ URL: `/admin/notification_templates`

Templates គឺជា pre-defined messages ដែលអាច​​ reuse សម្រាប់ scenarios ដូចជា Booking Confirmation, Check-in Reminder, Payment Receipt។

### 14.1.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Template Code** | លេខកូដ (e.g. "BOOKING_CONFIRM") | ✅ |
| **Name** | ឈ្មោះ | ✅ |
| **Channel** | `email`, `sms`, `push`, `in_app` | ✅ |
| **Subject** | ចំណងជើង (សម្រាប់ email) | |
| **Body** | មាតិកា (support placeholders e.g. `{guest_name}`, `{booking_no}`) | ✅ |
| **Is Active** | បើដំណើរការ | |

### 14.1.2 ឧទាហរណ៍ Templates

| Code | Name | Channel | Body |
|---|---|---|---|
| BOOKING_CONFIRM | Booking Confirmation | email | "Dear {guest_name}, your booking {booking_no} is confirmed for {check_in_date}." |
| CHECKIN_REMIND | Check-in Reminder | sms | "Reminder: Check-in tomorrow at {check_in_date} — {guest_house_name}" |
| PAYMENT_RECEIPT | Payment Receipt | email | "Payment of {amount} received. Receipt: {receipt_no}" |

---

## 14.2 Notifications

**Sidebar**: `Notifications → Notifications` ▸ URL: `/admin/notifications`

Notifications ​​ជា instances ដែលត្រូវ​​ផ្ញើ (pending) ឬត្រូវ​​បានផ្ញើ (sent)។

### 14.2.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា | 🤖 |
| **Notification Template** | Template | ✅ |
| **Recipient Type** | `guest`, `staff`, `supplier` | ✅ |
| **Recipient ID** | ID អ្នកទទួល | ✅ |
| **Channel** | (auto from template) | 🤖 |
| **Subject** | (auto from template) | 🤖 |
| **Body** | (auto-merged with placeholders) | 🤖 |
| **Status** | `pending`, `sent`, `failed`, `cancelled` | ✅ |
| **Scheduled At** | datetime ផ្ញើ | |
| **Sent At** | datetime ផ្ញើពិតប្រាកដ | 🤖 |
| **Error Message** | (បើ failed) | |

### 14.2.2 Workflow

```
1. Reception បង្កើត Booking → ប្រព័ន្ធ (ដោយ Manager) បង្កើត Notification:
   ▸ Template: BOOKING_CONFIRM
   ▸ Recipient: Guest
   ▸ Status: pending

2. ប្រព័ន្ធផ្ញើ → Status = sent
3. បើ​ failed → Error Message បង្ហាញហេតុផល
```

> 💡 **Tip**: Customer អាច manual create Notification ​​​ដោយ​​ ​Template — useful សម្រាប់ Bulk announcements (e.g. promotion)។

---

## 14.3 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| ប្រើ Template ច្បាស់លាស់ — មិនមែន free text | កុំ delete sent Notifications |
| Test Template មុនប្រើ production | កុំ ​​​send Notifications ​ ​ដោយគ្មាន ​​ recipient |
| ផ្ទៀងផ្ទាត់ Placeholder names ត្រឹមត្រូវ | កុំ disable Template ដែលមាន pending Notifications |
