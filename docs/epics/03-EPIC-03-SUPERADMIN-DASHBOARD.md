# 03 – EPIC-03: SuperAdmin Dashboard (FULLSTACK)

**Status**: Planned
**Priority**: P0 (Core MVP)
**Platform**: Web Admin (Mobile First friendly)
**Tech Stack**: Laravel 10+, Blade + Tailwind

---

## 1. Overview

EPIC ini membangun **panel SuperAdmin** untuk mengelola operasional sistem voucher Ramadhan, mencakup:

* Dashboard ringkas KPI
* CRUD PIC
* CRUD Merchant
* Atur Offer/Diskon merchant
* View data end-to-end (voucher awal, claim, merchant voucher, redeem)
* Navigasi admin & layout dasar

**Boundary penting**:

* Proses generate/assign/print voucher adalah implementasi EPIC-02 (EPIC-03 hanya menyediakan UI & entrypoint)
* Flow claim publik adalah EPIC-01
* Redeem merchant adalah EPIC-04

---

## 2. Tujuan & Business Value

* Admin punya kontrol penuh untuk operasional distribusi voucher
* Monitoring cepat (apa yang sudah di-assign, diklaim, diredeem)
* Memudahkan perubahan offer/diskon tanpa deploy ulang
* Menjadi sumber data untuk evaluasi program Ramadhan

---

## 3. Admin IA (Information Architecture)

### 3.1 Navigasi (Sidebar / Topbar)

Minimum menu:

1. Dashboard
2. PIC
3. Voucher Awal

   * Generate
   * Assign
   * Print
4. Merchant
5. Offer/Diskon
6. Data

   * Claims
   * Redeems

> Mobile-first: gunakan **topbar + off-canvas menu** (hamburger) agar nyaman di HP.

---

## 4. Layout & UI Guidelines (Mobile First)

* Container max-width 1100px (admin), namun tetap responsif
* Di mobile:

  * list pakai card (bukan tabel)
  * filter pakai drawer / collapsible
  * action button jelas (primary CTA)
* Di desktop:

  * tabel boleh digunakan untuk data besar

Komponen standar:

* KPI card
* Data table/card list
* Filter bar
* Empty state
* Flash message (success/error)

---

## 5. Routing & Controller (Laravel Web)

### Middleware

* `auth`
* `role:SUPERADMIN`

### Routes (routes/web.php)

**Dashboard**

* `GET /admin` → `Admin\DashboardController@index`

**PIC**

* `GET  /admin/pics` → `Admin\PicController@index`
* `GET  /admin/pics/create` → `Admin\PicController@create`
* `POST /admin/pics` → `Admin\PicController@store`
* `GET  /admin/pics/{id}/edit` → `Admin\PicController@edit`
* `PUT  /admin/pics/{id}` → `Admin\PicController@update`
* `POST /admin/pics/{id}/toggle` → `Admin\PicController@toggleActive`

**Merchant**

* `GET  /admin/merchants` → `Admin\MerchantController@index`
* `GET  /admin/merchants/create` → `Admin\MerchantController@create`
* `POST /admin/merchants` → `Admin\MerchantController@store`
* `GET  /admin/merchants/{id}/edit` → `Admin\MerchantController@edit`
* `PUT  /admin/merchants/{id}` → `Admin\MerchantController@update`
* `POST /admin/merchants/{id}/toggle` → `Admin\MerchantController@toggleActive`

**Offers**

* `GET  /admin/offers` → `Admin\OfferController@index`
* `GET  /admin/offers/create` → `Admin\OfferController@create`
* `POST /admin/offers` → `Admin\OfferController@store`
* `GET  /admin/offers/{id}/edit` → `Admin\OfferController@edit`
* `PUT  /admin/offers/{id}` → `Admin\OfferController@update`

**Data**

* `GET /admin/data/claims` → `Admin\ClaimDataController@index`
* `GET /admin/data/redeems` → `Admin\RedeemDataController@index`

> Route generate/assign/print voucher mengikuti EPIC-02.

---

## 6. Halaman Admin yang Dibangun (View)

### 6.1 Admin Base Layout

* `resources/views/layouts/admin.blade.php`

  * topbar + off-canvas menu
  * slot konten
  * flash messages

### 6.2 Dashboard

* `resources/views/admin/dashboard.blade.php`

  * KPI cards
  * summary table/card

### 6.3 PIC CRUD

* `admin/pics/index.blade.php`
* `admin/pics/create.blade.php`
* `admin/pics/edit.blade.php`

### 6.4 Merchant CRUD

* `admin/merchants/index.blade.php`
* `admin/merchants/create.blade.php`
* `admin/merchants/edit.blade.php`

### 6.5 Offers CRUD

* `admin/offers/index.blade.php`
* `admin/offers/create.blade.php`
* `admin/offers/edit.blade.php`

### 6.6 Data Views

* `admin/data/claims.blade.php`
* `admin/data/redeems.blade.php`

---

## 7. Feature Breakdown & Todo Task (DEV-READY)

---

## FT-01: Admin Layout + Navigation

### Tujuan

Menyediakan kerangka UI admin yang konsisten dan mobile-friendly.

### Acceptance Criteria

* Topbar tampil di semua halaman admin
* Off-canvas menu untuk mobile
* Active state menu jelas
* Flash message tampil setelah aksi CRUD

### Todo Tasks

**Frontend**

* [ ] Buat `layouts/admin.blade.php`
* [ ] Buat komponen partial:

  * `admin/partials/topbar.blade.php`
  * `admin/partials/sidebar.blade.php`
  * `admin/partials/flash.blade.php`

**Backend**

* [ ] Middleware role superadmin
* [ ] Group route `/admin` dengan middleware

**QA**

* [ ] Menu bisa dibuka/tutup di mobile
* [ ] Semua link route benar

---

## FT-02: Dashboard KPI (Summary)

### Tujuan

Memberikan gambaran cepat performa distribusi voucher.

### KPI Minimum

* Total voucher generated
* Total voucher assigned
* Total voucher claimed
* Total merchant vouchers redeemed

### Acceptance Criteria

* KPI tampil sebagai card, responsif
* Data konsisten dengan DB

### Todo Tasks

**Backend**

* [ ] `Admin/DashboardController@index`

  * query count:

    * `initial_vouchers` by status
    * `claims` count
    * `merchant_vouchers` redeemed count
* [ ] View model data ke blade

**Frontend**

* [ ] `admin/dashboard.blade.php`

  * KPI card grid (1 kolom mobile, 2–4 kolom desktop)
  * section “Recent Claims” (top 10)

**QA**

* [ ] KPI sesuai data
* [ ] Empty state jika belum ada data

---

## FT-03: CRUD PIC (Admin)

### Tujuan

Admin bisa mengelola PIC yang akan menerima distribusi voucher.

### Data PIC

* name
* code (optional)
* is_active

### Acceptance Criteria

* Admin bisa create/edit/disable
* PIC disabled tidak bisa dipakai untuk assign voucher

### Todo Tasks

**DB**

* [ ] Migration `pics` (name, code unique nullable, is_active)

**Backend**

* [ ] `Admin/PicController` (index/create/store/edit/update/toggle)
* [ ] Validasi:

  * name required
  * code unique jika diisi

**Frontend**

* [ ] Index list:

  * search by name
  * action edit/disable
  * mobile card view
* [ ] Form create/edit:

  * input name
  * input code
  * toggle active

**QA**

* [ ] Create PIC
* [ ] Update PIC
* [ ] Disable PIC
* [ ] Assign voucher harus menolak PIC disabled (EPIC-02)

---

## FT-04: CRUD Merchant (Admin)

### Tujuan

Admin dapat menambah & mengaktifkan merchant yang berpartisipasi.

### Data Merchant

* name
* slug
* is_active
* logo (optional)

### Acceptance Criteria

* Merchant aktif muncul di public voucher list
* Merchant nonaktif tidak dibuatkan merchant voucher saat claim

### Todo Tasks

**DB**

* [ ] Migration `merchants` (name, slug unique, is_active, logo_url nullable)

**Backend**

* [ ] `Admin/MerchantController` CRUD + toggle
* [ ] Validasi slug unique

**Frontend**

* [ ] Index list + search
* [ ] Form create/edit

**QA**

* [ ] Merchant aktif muncul di proses generate merchant vouchers (EPIC-01)
* [ ] Merchant nonaktif tidak masuk

---

## FT-05: CRUD Offer/Diskon (Admin)

### Tujuan

Admin menentukan potongan harga yang ditampilkan di voucher merchant.

### Data Offer

* merchant_id
* title
* discount_type (PERCENT/AMOUNT)
* discount_value
* description
* is_active

### Acceptance Criteria

* Offer dapat diubah kapan saja
* Voucher list publik menampilkan offer terbaru

### Todo Tasks

**DB**

* [ ] Migration `merchant_offers`

**Backend**

* [ ] `Admin/OfferController` CRUD
* [ ] Validation rules

**Frontend**

* [ ] Offer list (filter by merchant)
* [ ] Offer create/edit form

**QA**

* [ ] Update offer, public page berubah

---

## FT-06: Data Views (Claims & Redeems)

### Tujuan

Admin dapat melihat data detail untuk evaluasi.

### Claims View

* filter: tanggal, PIC, email
* fields: code voucher awal, PIC, name, email, waktu claim

### Redeems View

* filter: tanggal, merchant, PIC
* fields: merchant voucher code, merchant, PIC, user, waktu redeem

### Todo Tasks

**Backend**

* [ ] `Admin/ClaimDataController@index` (query + pagination)
* [ ] `Admin/RedeemDataController@index` (query + pagination)

**Frontend**

* [ ] Card list mobile, table desktop
* [ ] Filter UI (collapsible)

**QA**

* [ ] Pagination
* [ ] Filter berfungsi

---

## 8. Definition of Done (EPIC-03)

* [ ] Admin layout & navigation siap
* [ ] Dashboard KPI tampil
* [ ] CRUD PIC berjalan
* [ ] CRUD Merchant berjalan
* [ ] CRUD Offer berjalan
* [ ] Data views claims & redeems bisa dipakai
* [ ] Mobile-friendly admin UI

---

## 9. Out of Scope

* Export CSV (EPIC-05)
* Analytics advanced (EPIC-05)
* Merchant panel (EPIC-04)

---
