# 05 – EPIC-05: Analytics & Reporting (FULLSTACK)

**Status**: Planned
**Priority**: P1 (Post-MVP / Enhancement)
**Platform**: Admin + Merchant
**Tech Stack**: Laravel 10+, Blade + Tailwind

---

## 1. Overview

EPIC ini menambahkan kemampuan **analytics** dan **reporting** untuk:

* SuperAdmin (global analytics)
* Merchant (analytics per merchant)
* Export data (CSV)

Analytics digunakan untuk evaluasi program Ramadhan: distribusi voucher, conversion (claim), dan redemption per merchant/PIC.

---

## 2. Metrics yang Disediakan

### 2.1 Global (SuperAdmin)

* Total voucher generated
* Total voucher assigned
* Total voucher claimed
* Total merchant vouchers redeemed
* Claim rate: claimed / assigned
* Redeem rate per merchant: redeemed / (merchant vouchers generated)
* Top PIC by claims
* Top PIC by redeems
* Trend harian: claims per hari, redeems per hari

### 2.2 Merchant (Per Merchant)

* Total redeemed
* Redeemed today
* Unique users (by email)
* Top PIC yang membawa user (by count)
* Trend harian redeem

---

## 3. Halaman yang Dibangun

### 3.1 Admin Analytics Dashboard

* Route: `GET /admin/analytics`
* View: `resources/views/admin/analytics/index.blade.php`
* Sections:

  * KPI cards
  * chart placeholder (opsional)
  * tables: top PIC, redeem per merchant

### 3.2 Merchant Analytics

* Route: `GET /merchant/analytics`
* View: `resources/views/merchant/analytics/index.blade.php`

### 3.3 Export Center (Admin)

* Route: `GET /admin/exports`
* View: `resources/views/admin/exports/index.blade.php`
* Export endpoints:

  * Claims CSV
  * Redeems CSV
  * Voucher Stock CSV

---

## 4. Routing & Controller

### Admin

* `GET /admin/analytics` → `Admin\AnalyticsController@index`
* `GET /admin/exports` → `Admin\ExportController@index`
* `GET /admin/exports/claims.csv` → `Admin\ExportController@claims`
* `GET /admin/exports/redeems.csv` → `Admin\ExportController@redeems`
* `GET /admin/exports/vouchers.csv` → `Admin\ExportController@vouchers`

### Merchant

* `GET /merchant/analytics` → `Merchant\AnalyticsController@index`

Middleware:

* admin: `auth` + `role:SUPERADMIN`
* merchant: `auth` + `role:MERCHANT`

---

## 5. Feature Breakdown & Todo Task (DEV-READY)

---

## FT-01: Admin Analytics KPI

### Acceptance Criteria

* KPI tampil sesuai filter tanggal (default 7 hari terakhir)
* Tidak ada data leak (admin boleh lihat semua)

### Todo Tasks

**Backend**

* [ ] `Admin/AnalyticsController@index`

  * input filter: `date_from`, `date_to`
  * query counts:

    * `initial_vouchers` by status
    * `claims` count
    * `merchant_vouchers` redeemed count
  * compute rate:

    * claim_rate = claims / assigned
    * redeem_rate_per_merchant

**Frontend**

* [ ] `admin/analytics/index.blade.php`

  * filter tanggal (collapsible)
  * KPI cards

**QA**

* [ ] default range 7 hari
* [ ] custom range

---

## FT-02: Top PIC & Merchant Breakdown

### Acceptance Criteria

* Tabel top PIC by claim
* Tabel top PIC by redeem
* Breakdown redeem per merchant

### Todo Tasks

**Backend**

* [ ] Query group by PIC untuk claims
* [ ] Query join untuk redeems (merchant_vouchers → initial_vouchers → pic)
* [ ] Query redeem per merchant

**Frontend**

* [ ] Tabel desktop + card mobile

---

## FT-03: Merchant Analytics

### Acceptance Criteria

* Merchant hanya melihat data merchant-nya

### Todo Tasks

**Backend**

* [ ] `Merchant/AnalyticsController@index`

  * filter date
  * KPI redeemed total & today
  * top PIC for this merchant

**Frontend**

* [ ] `merchant/analytics/index.blade.php`

---

## FT-04: Export CSV (Admin)

### Acceptance Criteria

* Download CSV tanpa timeout (paginasi/streaming kalau besar)
* Kolom jelas dan konsisten

### CSV Definitions

**Claims CSV columns**

* claimed_at
* initial_code
* pic_name
* user_name
* user_email

**Redeems CSV columns**

* redeemed_at
* merchant_name
* merchant_voucher_code
* initial_code
* pic_name
* user_email

**Vouchers Stock CSV columns**

* created_at
* batch_name
* initial_code
* status
* pic_name

### Todo Tasks

**Backend**

* [ ] `Admin/ExportController@index` (menu export)
* [ ] `claims()` streaming CSV
* [ ] `redeems()` streaming CSV
* [ ] `vouchers()` streaming CSV

**Frontend**

* [ ] `admin/exports/index.blade.php`

  * tombol download
  * deskripsi kolom

**QA**

* [ ] file bisa dibuka di Excel
* [ ] encoding UTF-8

---

## 6. Definition of Done (EPIC-05)

* [ ] Admin analytics page berfungsi
* [ ] Merchant analytics page berfungsi
* [ ] Export CSV 3 jenis berjalan
* [ ] Filter tanggal berfungsi
* [ ] Tidak ada data leak

---

## 7. Out of Scope

* Chart interaktif advanced (boleh menyusul)
* Scheduled reporting / email

---
