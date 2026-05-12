# 15. គេហទំព័រ & ការ Book Online — Website & Online Booking

ផ្នែកនេះគ្រប់គ្រងគេហទំព័រសាធារណៈ (Pages) និងសំណើ Booking ដែលមកពី Online (Guest fill form on website)។

---

## 15.1 Website Pages

**Sidebar**: `Website → Pages` ▸ URL: `/admin/website_pages`

Pages គឺជា CMS content សម្រាប់​​ Public Website (Home, About, Contact, Rooms, etc.)។

### 15.1.1 Field

| Field | ការពិពណ៌នា | ចាំបាច់ |
|---|---|---|
| **Branch** | សាខា (or NULL = global) | |
| **Slug** | URL slug (e.g. "about-us") | ✅ |
| **Title** | ចំណងជើង | ✅ |
| **Content** | មាតិកា (HTML/Markdown) | ✅ |
| **Meta Description** | SEO description | |
| **Is Published** | បើបង្ហាញ​លើ Public | |
| **Sort Order** | លំដាប់​បង្ហាញ | |

### 15.1.2 ឧទាហរណ៍ Pages

| Slug | Title |
|---|---|
| home | Welcome to Our Guest House |
| about-us | About Us |
| rooms | Our Rooms |
| facilities | Facilities |
| contact | Contact Us |
| policies | Cancellation Policy |

---

## 15.2 Online Booking Requests

**Sidebar**: `Website → Online Booking Requests` ▸ URL: `/admin/online_booking_requests`

Online Booking Requests ​​ ​ ​ ​ ​​ ​​ ​​ ​​​​ជាសំណើ Booking ដែលមកពី Guest គេបំពេញ form នៅ Public Website។

### 15.2.1 Field

| Field | ការពិពណ៌នា |
|---|---|
| **Branch** | សាខា |
| **Request No.** | Auto (`OBR-000001`) |
| **Guest Name** | ឈ្មោះ |
| **Guest Email** | អ៊ីមែល |
| **Guest Phone** | លេខទូរស័ព្ទ |
| **Check-in Date** | ថ្ងៃចូល |
| **Check-out Date** | ថ្ងៃចេញ |
| **Adults / Children** | ចំនួន |
| **Room Type** | ប្រភេទ​បន្ទប់ |
| **Special Request** | សំណើពិសេស |
| **Status** | `new`, `reviewing`, `confirmed`, `rejected`, `cancelled` |
| **Reviewed By** | Reception |
| **Booking ID** | (បើ confirmed → link to Booking) |

### 15.2.2 Workflow

```
1. Guest បំពេញ Form នៅ Public Website
2. ប្រព័ន្ធ​បង្កើត Online Booking Request (OBR-000xxx, status=new)
3. Reception ​​ ​ → Sidebar → Online Booking Requests
   ▸ Filter status=new
   ▸ Open Request

4. Reception ត្រួតពិនិត្យ Room Availability មុនបញ្ជាក់:
   ▸ Status = reviewing → ផ្ទៀងផ្ទាត់
   ▸ ​ ​ ​ ​ ​ ​​ Status = confirmed → បង្កើត Booking ផ្លូវការ (Chapter 6)
   ▸ Booking ID ភ្ជាប់​​ត្រឡប់​ ​ ​ ​​ Online Booking Request

5. ប្រសិនបើ Room មិន​​ ​​​មាន → Status = rejected → ផ្ញើ Notification ដល់ Guest
```

> 🤖 **Auto-Logic**: នៅពេល Online Booking Request status = 'confirmed' Customer ត្រូវ​​បង្កើត Booking ​ ​ ​ ​ ​​បន្ត និងបញ្ចូល Booking ID ត្រឡប់​​​ ដើម្បីបង្កើតទំនាក់ទំនង។

---

## 15.3 Best Practice

| ✅ Do | ❌ Don't |
|---|---|
| ត្រួតពិនិត្យ Online Booking Requests យ៉ាងហោចណាស់ 2x/ថ្ងៃ | កុំ ignore Requests លើស 24h |
| ឆ្លើយតប Guest តាម Email/SMS | កុំ confirm Request ដោយគ្មាន​ ​ ​​ ​​​ verify​​ ​ ​​ Room |
| Track conversion rate (new → confirmed) | កុំ delete Request — cancel វិញ |
| Update Page content ប្រចាំខែ | កុំ Publish Page ដែលគ្មាន Content |
