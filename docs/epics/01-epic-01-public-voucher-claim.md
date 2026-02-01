# 01 – EPIC-01: Public Voucher Claim (FULLSTACK)

**Status**: Planned
**Priority**: P0 (Core MVP)
**Platform**: Web (Mobile First)
**Tech Stack**: Laravel 10+, Blade + Tailwind
**Depends On**: EPIC-02 (voucher awal sudah ada & assigned ke PIC; merchant data aktif tersedia)

---

## 1. Overview

EPIC ini mendefinisikan alur **user publik (tanpa login)** untuk:

1. Scan QR Voucher Awal
2. Validasi voucher & PIC
3. Isi form (nama, email)
4. Submit claim (atomic)
5. Redirect ke halaman daftar Voucher Merchant (QR per merchant)

**Boundary penting**:

* EPIC ini **tidak** membahas generate/assign/print voucher awal (EPIC-02)
* EPIC ini **tidak** membahas redeem oleh merchant (EPIC-04)

---

## 2. User Journey

1. User scan QR fisik → membuka `GET /claim/{code}`
2. Sistem validasi voucher awal:

   * ada di DB
   * status = ASSIGNED
   * assigned_pic_id tidak null
3. User melihat form klaim + info PIC (read-only)
4. User isi nama & email → submit `POST /claim`
5. Sistem menjalankan transaksi DB:

   * lock voucher awal
   * re-validasi status
   * buat record claim + public_token
   * set voucher awal menjadi CLAIMED
   * generate N voucher merchant (aktif) untuk claim tersebut
6. User redirect `GET /v/{public_token}`
7. User melihat daftar voucher merchant + QR + status

---

## 3. Halaman (Frontend) yang Dibangun

### 3.1 Public Claim Page

* Route: `GET /claim/{code}`
* View: `resources/views/public/claim.blade.php`
* Komponen:

  * Header campaign
  * Info PIC (read-only)
  * Form: nama, email
  * CTA: "Dapatkan Voucher"
  * Error banner (voucher invalid/used)

### 3.2 Public Voucher List Page

* Route: `GET /v/{public_token}`
* View: `resources/views/public/vouchers.blade.php`
* Komponen:

  * List merchant cards (dinamis)
  * Tombol "Tampilkan QR" per merchant
  * Modal/drawer QR full screen
  * Badge status ACTIVE/REDEEMED
  * Short code fallback

---

## 4. Data Model yang Dipakai (Kontrak)

> Implementasi migration detail ada di EPIC-02, tapi EPIC-01 bergantung pada field berikut.

### 4.1 `initial_vouchers`

* `code` (unique)
* `status` enum: UNASSIGNED, ASSIGNED, CLAIMED, VOID
* `assigned_pic_id` (not null ketika ASSIGNED)

### 4.2 `claims`

* `initial_voucher_id` (unique 1–1)
* `name`
* `email`
* `public_token` (unique)

### 4.3 `merchant_vouchers`

* `initial_voucher_id`
* `merchant_id`
* `code` (unique)
* `status` enum: ACTIVE, REDEEMED, VOID

### 4.4 `merchants` + `merchant_offers`

* merchant aktif yang akan ditampilkan ke user
* offer digunakan untuk display (nama produk, potongan)

---

## 5. Routing & Controller (Laravel Web)

### Routes (routes/web.php)

* `GET  /claim/{code}` → `Public\ClaimController@show`
* `POST /claim`        → `Public\ClaimController@store`
* `GET  /v/{token}`    → `Public\VoucherController@show`

> Semua route ini **web** (return Blade), bukan API.

---

## 6. Feature Breakdown & Todo Task (DEV-READY)

---

## FT-01: Render Claim Page + Validasi Voucher Awal

### Tujuan

Menampilkan halaman form klaim hanya jika voucher valid.

### Acceptance Criteria

* Jika `code` tidak ditemukan → tampilkan halaman error (404 atau custom)
* Jika status != ASSIGNED → tampilkan error "Voucher tidak aktif / sudah dipakai"
* Jika voucher ASSIGNED tapi tidak punya PIC → error "Voucher bermasalah"
* Jika valid → tampilkan form dengan PIC read-only

### Todo Tasks

**Backend**

* [ ] Buat controller `app/Http/Controllers/Public/ClaimController.php`
* [ ] Method `show(string $code)`:

  * ambil `InitialVoucher` by `code`
  * eager load PIC (mis: `pic` relation)
  * validasi status & assigned_pic_id
  * return view `public.claim`
* [ ] Buat view-model data minimal:

  * `campaign_title` (static config)
  * `pic_name`
  * `initial_code`

**Frontend (Blade + Tailwind)**

* [ ] Buat layout base public: `resources/views/layouts/public.blade.php`

  * max-width 430px, center
  * padding aman untuk mobile
* [ ] Buat `resources/views/public/claim.blade.php`

  * Input `name`, `email`
  * Info PIC read-only
  * CTA tinggi min 44px
  * Copy singkat: syarat & privacy

**UX & Copy**

* [ ] Pesan error human readable
* [ ] CTA jelas dan konsisten

**QA**

* [ ] code random → error
* [ ] voucher UNASSIGNED → error
* [ ] voucher CLAIMED → error
* [ ] voucher ASSIGNED valid → tampil form

---

## FT-02: Submit Claim (Atomic) + Redirect

### Tujuan

Menyimpan data klaim dan mengunci voucher awal agar tidak bisa dipakai ulang.

### Acceptance Criteria

* Validasi input:

  * name required (min 2)
  * email required (email format)
* Operasi claim atomic:

  * kalau double submit, hanya 1 yang sukses
* Setelah sukses redirect ke `/v/{public_token}`

### Todo Tasks

**Backend**

* [ ] Method `store(Request $request)` di `ClaimController`
* [ ] Validation rules (FormRequest disarankan):

  * `code` required
  * `name` required|string|min:2|max:120
  * `email` required|email|max:120
* [ ] DB Transaction:

  1. lock row `initial_vouchers` by code (`forUpdate()`)
  2. re-check status ASSIGNED
  3. create `claims` dengan `public_token`
  4. update `initial_vouchers.status = CLAIMED`, set `claimed_at`
  5. call service generator voucher merchant (FT-03)
* [ ] Handle error:

  * jika status berubah saat proses → tampil error

**Frontend**

* [ ] Tambahkan hidden input `code` pada form
* [ ] Disable tombol submit + loading state
* [ ] Tampilkan error inline dari validation

**Security**

* [ ] Rate limit POST /claim (mis. 10/min/IP)
* [ ] Pastikan CSRF aktif

**QA**

* [ ] submit valid → redirect berhasil
* [ ] submit invalid email → error
* [ ] double submit (2 tab) → 1 sukses, 1 gagal

---

## FT-03: Generate Voucher Merchant Setelah Claim

### Tujuan

Membuat 1 voucher merchant untuk setiap merchant aktif, setelah claim sukses.

### Acceptance Criteria

* Jumlah voucher = jumlah merchant aktif
* 1 initial voucher menghasilkan N merchant vouchers
* Setiap voucher memiliki code unik
* Status default ACTIVE

### Todo Tasks

**Backend**

* [ ] Buat service `app/Services/MerchantVoucherGenerator.php`

  * input: `InitialVoucher $initialVoucher`
  * ambil merchant aktif: `Merchant::where('is_active', true)->get()`
  * generate `code` unik per merchant voucher
  * bulk insert `merchant_vouchers`
* [ ] Pastikan unique index di DB pada `merchant_vouchers.code`

**QA**

* [ ] merchant aktif 4 → voucher 4
* [ ] merchant aktif 1 → voucher 1
* [ ] tidak ada duplicate code

---

## FT-04: Render Voucher List Page (Public Token)

### Tujuan

Menampilkan voucher merchant milik user berdasarkan `public_token`.

### Acceptance Criteria

* Token valid → tampil list
* Token invalid → error "Voucher tidak ditemukan"
* List menampilkan:

  * merchant name/logo
  * discount/offer text
  * QR voucher merchant
  * status ACTIVE/REDEEMED

### Todo Tasks

**Backend**

* [ ] Buat controller `app/Http/Controllers/Public/VoucherController.php`
* [ ] Method `show(string $token)`:

  * ambil `Claim::where('public_token',$token)->first()`
  * load `merchantVouchers.merchant.offer`
  * return view `public.vouchers`

**Frontend**

* [ ] Buat `resources/views/public/vouchers.blade.php`

  * merchant card list
  * tombol buka modal QR
  * short code fallback
  * status badge

**QR Rendering (UI)**

* [ ] Render QR voucher merchant di modal

  * payload: voucher merchant code (atau URL redeem yang aman, lihat EPIC-04)

**QA**

* [ ] token invalid
* [ ] voucher status redeemed tampil berbeda
* [ ] modal QR bisa dibuka/tutup nyaman di HP

---

## FT-05: Error Handling & UX Polishing

### Tujuan

Menjamin user paham jika terjadi error dan tidak bingung.

### Todo Tasks

* [ ] Buat `resources/views/public/error.blade.php`
* [ ] Mapping error ke pesan:

  * voucher not found
  * voucher not active
  * voucher already used
  * token invalid
* [ ] Tambahkan link/CTA back (opsional)

---

## 7. UI Guidelines (Mobile First)

* max-width container: 430px
* font base 14–16px
* tombol minimal 44px tinggi
* spacing antar card minimal 12–16px
* modal QR full-screen untuk kemudahan scan

---

## 8. Definition of Done (EPIC-01)

* [ ] User bisa claim voucher awal 1x
* [ ] Data claim tersimpan dan konsisten
* [ ] Voucher merchant tergenerate sesuai merchant aktif
* [ ] User bisa melihat list voucher merchant via public_token
* [ ] Error states rapi & tidak bocor data sensitif
* [ ] UI mobile-first usable

---

## 9. Out of Scope

* Login user
* Redeem oleh merchant (EPIC-04)
* Print voucher (EPIC-02)
* Analytics (EPIC-05)

---
