# 04 – EPIC-04: Merchant Panel (Scan & Redeem) – FULLSTACK

**Status**: Planned
**Priority**: P0 (Core MVP)
**Platform**: Web Merchant (Mobile First)
**Tech Stack**: Laravel 10+, Blade + Tailwind, QR Scanner (html5-qrcode / instascan)

---

## 1. Overview

EPIC ini membangun panel **Merchant** untuk:

* Login merchant
* Scan QR voucher merchant (via kamera / input manual)
* Validasi voucher (harus milik merchant tersebut)
* Redeem voucher (one-time, atomic)
* Melihat riwayat penukaran
* Mengatur offer/diskon merchant (sesuai requirement)

**Boundary penting**:

* Generate/assign/print voucher awal: EPIC-02
* Claim publik: EPIC-01
* Admin CRUD merchant/offer: EPIC-03 (merchant setting di EPIC ini hanya jika diizinkan)

---

## 2. User Journey (Merchant)

1. Merchant login
2. Merchant membuka halaman Scan
3. Merchant scan QR voucher merchant yang ditunjukkan user
4. Sistem menampilkan hasil validasi:

   * valid / invalid
   * jika valid: info user & PIC
5. Merchant klik "Redeem" untuk mengunci voucher
6. Sistem update status voucher menjadi REDEEMED
7. Merchant dapat melihat riwayat penukaran & analytics ringkas

---

## 3. Data Model yang Dipakai (Kontrak)

### 3.1 `users`

* role: MERCHANT
* `merchant_id` wajib terisi

### 3.2 `merchant_vouchers`

* `code` (unique)
* `merchant_id`
* `status`: ACTIVE/REDEEMED/VOID
* `redeemed_at`
* `redeemed_by_merchant_user_id`

### 3.3 Relasi data untuk tampilan

Saat validasi/redeem, merchant bisa melihat:

* user (name, email) dari `claims`
* PIC dari `initial_vouchers.assigned_pic_id`

---

## 4. Halaman Merchant (Frontend) yang Dibangun

### 4.1 Merchant Base Layout

* `resources/views/layouts/merchant.blade.php`

  * topbar
  * bottom nav (mobile): Dashboard | Scan | Riwayat | Setting

### 4.2 Merchant Dashboard

* Route: `GET /merchant`
* View: `resources/views/merchant/dashboard.blade.php`
* KPI:

  * redeemed today
  * total redeemed
  * unique users (opsional)

### 4.3 Scan Page

* Route: `GET /merchant/scan`
* View: `resources/views/merchant/scan.blade.php`
* Komponen:

  * camera preview + tombol start/stop
  * manual input fallback
  * result card (valid/invalid)
  * tombol Redeem

### 4.4 Redeem History

* Route: `GET /merchant/redemptions`
* View: `resources/views/merchant/redemptions.blade.php`
* Filter: date range
* List: voucher code, user, PIC, redeemed_at

### 4.5 Merchant Offer Setting (Jika Merchant Boleh Atur)

* Route: `GET /merchant/offer`
* View: `resources/views/merchant/offer.blade.php`
* Form:

  * title
  * discount type/value
  * description
  * active

---

## 5. Routing & Controller (Laravel Web)

### Middleware

* `auth`
* `role:MERCHANT`

### Routes

**Dashboard**

* `GET /merchant` → `Merchant\DashboardController@index`

**Scan**

* `GET /merchant/scan` → `Merchant\ScanController@index`
* `POST /merchant/validate` → `Merchant\RedeemController@validateCode`
* `POST /merchant/redeem` → `Merchant\RedeemController@redeem`

**History**

* `GET /merchant/redemptions` → `Merchant\RedemptionController@index`

**Offer** (optional)

* `GET /merchant/offer` → `Merchant\OfferController@edit`
* `PUT /merchant/offer` → `Merchant\OfferController@update`

---

## 6. Feature Breakdown & Todo Task (DEV-READY)

---

## FT-01: Merchant Layout + Bottom Navigation (Mobile First)

### Tujuan

Merchant UI nyaman di HP (scan biasanya pakai HP).

### Acceptance Criteria

* Bottom nav fixed (tidak menutupi konten)
* Halaman scan mudah diakses
* Flash message tampil

### Todo Tasks

**Frontend**

* [ ] `layouts/merchant.blade.php`
* [ ] partial:

  * `merchant/partials/topbar.blade.php`
  * `merchant/partials/bottomnav.blade.php`
  * `merchant/partials/flash.blade.php`

**QA**

* [ ] bottom nav tidak overlap

---

## FT-02: Scan QR Voucher Merchant (Camera + Manual)

### Tujuan

Merchant bisa scan voucher yang ditunjukkan user.

### Acceptance Criteria

* Kamera bisa scan QR
* Jika kamera gagal, merchant bisa input manual code
* Setelah scan/input, sistem menampilkan hasil validasi

### Todo Tasks

**Frontend**

* [ ] `merchant/scan.blade.php`
* [ ] Integrasi library JS scanner:

  * rekomendasi: `html5-qrcode`
* [ ] UI states:

  * idle
  * scanning
  * scanned result
  * error
* [ ] Manual input field + tombol "Check"

**Backend**

* [ ] Endpoint `POST /merchant/validate`
* [ ] `RedeemController@validateCode`

  * cek voucher by code
  * cek voucher.merchant_id == merchant login
  * cek status ACTIVE
  * return JSON minimal untuk UI (valid/invalid + meta)

**Security**

* [ ] Rate limit validate endpoint

**QA**

* [ ] scan code valid
* [ ] scan code milik merchant lain
* [ ] scan code redeemed

---

## FT-03: Redeem Voucher (Atomic)

### Tujuan

Mengunci voucher agar tidak bisa dipakai ulang.

### Acceptance Criteria

* Redeem hanya untuk voucher ACTIVE
* Redeem hanya jika voucher milik merchant itu
* Setelah redeem status jadi REDEEMED, set redeemed_at
* Jika redeem ulang → gagal (tampilkan sudah ditukarkan)

### Todo Tasks

**Backend**

* [ ] Endpoint `POST /merchant/redeem`
* [ ] `RedeemController@redeem`

  * validate input: code required
  * DB transaction:

    * lock row merchant_vouchers by code
    * re-check merchant_id & status
    * update status REDEEMED
    * set redeemed_at
    * set redeemed_by_merchant_user_id
* [ ] Response untuk UI (success + message + meta)

**Frontend**

* [ ] Tombol "Redeem" hanya muncul jika valid
* [ ] Konfirmasi sederhana (optional): modal "Yakin redeem?"
* [ ] Success state jelas

**QA**

* [ ] redeem valid
* [ ] redeem double submit
* [ ] redeem milik merchant lain

---

## FT-04: Merchant Dashboard (KPI Ringkas)

### Tujuan

Merchant bisa lihat performa penukaran.

### KPI Minimum

* Redeemed today
* Total redeemed

### Todo Tasks

**Backend**

* [ ] `Merchant/DashboardController@index`

  * query based on merchant_id

**Frontend**

* [ ] `merchant/dashboard.blade.php`

  * KPI card
  * list redeemed terbaru (top 10)

**QA**

* [ ] KPI sesuai merchant login

---

## FT-05: Redemption History (List + Filter)

### Tujuan

Merchant bisa melihat daftar voucher yang sudah ditukarkan.

### Acceptance Criteria

* List hanya menampilkan data merchant itu
* Filter tanggal berfungsi
* Pagination

### Todo Tasks

**Backend**

* [ ] `Merchant/RedemptionController@index`

  * filter date
  * paginate

**Frontend**

* [ ] `merchant/redemptions.blade.php`

  * mobile card list
  * filter collapsible

**QA**

* [ ] filter
* [ ] pagination

---

## FT-06: Merchant Offer Setting (Optional)

### Catatan

Requirement menyebut merchant bisa menentukan harga potongan. Ini bisa:

* A) diizinkan merchant edit offer sendiri (fitur ini)
* B) hanya admin yang boleh edit offer (EPIC-03)

### Todo Tasks (Jika Opsi A)

**Backend**

* [ ] `Merchant/OfferController@edit/@update`
* [ ] pastikan hanya offer milik merchant tersebut

**Frontend**

* [ ] `merchant/offer.blade.php`

---

## 7. Definition of Done (EPIC-04)

* [ ] Merchant bisa scan QR voucher
* [ ] Validasi voucher benar (milik merchant & status ACTIVE)
* [ ] Redeem atomic & one-time
* [ ] Dashboard KPI tampil
* [ ] Riwayat redeem tampil + filter
* [ ] Mobile-first usability ok

---

## 8. Out of Scope

* Analytics advanced (EPIC-05)
* Export data (EPIC-05)

---
