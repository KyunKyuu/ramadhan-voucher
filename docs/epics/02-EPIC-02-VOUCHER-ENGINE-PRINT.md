# 02 – EPIC-02: Voucher Engine & Print (FULLSTACK)

**Status**: Planned
**Priority**: P0 (Core MVP)
**Platform**: Web (Admin panel)
**Tech Stack**: Laravel 10+, Blade + Tailwind, DomPDF

---

## 1. Overview

EPIC ini membangun **mesin voucher inti** yang dikelola oleh SuperAdmin:

* Generate Voucher Awal (batch)
* Assign Voucher Awal ke PIC
* **Print Voucher Awal menjadi PDF (A4: 3 voucher per halaman, bentuk voucher horizontal/panjang)**
* Standarisasi generator code & QR
* Guardrails integritas data (unique constraint, status lifecycle)

**Boundary penting**:

* EPIC ini tidak membahas flow klaim publik (EPIC-01)
* EPIC ini tidak membahas scan & redeem oleh merchant (EPIC-04)

---

## 2. Business Value

* Memastikan distribusi voucher terkontrol & terukur
* Memungkinkan voucher fisik dicetak rapi untuk operasional lapangan
* Mencegah voucher dipakai sebelum di-assign
* Menjadi dasar data untuk analytics

---

## 3. Data Model (MVP) – Migration & Constraint

### 3.1 `pics`

> Catatan: boleh disederhanakan menjadi `users` role PIC, namun untuk admin CRUD PIC yang clean disarankan table `pics`.

Minimal:

* `id`
* `name`
* `code` (unique, optional)
* timestamps

### 3.2 `voucher_batches`

* `id`
* `name` (contoh: "Ramadhan-Week1")
* `generated_count`
* `created_by_admin_id`
* timestamps

### 3.3 `initial_vouchers`

* `id`
* `batch_id` (nullable)
* `code` (unique, indexed)
* `status` enum: `UNASSIGNED | ASSIGNED | CLAIMED | VOID`
* `assigned_pic_id` (nullable)
* `claimed_at` (nullable)
* timestamps

**Constraints**:

* unique index: `code`

### 3.4 `merchants`

* `id`, `name`, `slug`, `is_active`, timestamps

### 3.5 `merchant_offers`

* `id`, `merchant_id`, `title`, `discount_type`, `discount_value`, `description`, `is_active`, timestamps

### 3.6 `merchant_vouchers`

* `id`
* `initial_voucher_id`
* `merchant_id`
* `code` (unique)
* `status` enum: `ACTIVE | REDEEMED | VOID`
* `redeemed_at` (nullable)
* `redeemed_by_merchant_user_id` (nullable)
* timestamps

**Constraints**:

* unique index: `code`

---

## 4. Status Lifecycle & Rules

### 4.1 Voucher Awal (`initial_vouchers.status`)

* `UNASSIGNED`: baru digenerate, belum boleh dipakai user
* `ASSIGNED`: sudah di-assign ke PIC, boleh dipakai claim publik
* `CLAIMED`: sudah dipakai 1x → tidak boleh claim ulang
* `VOID`: dibatalkan

**Rules**

* Generate → selalu UNASSIGNED
* Assign → hanya UNASSIGNED → ASSIGNED
* Claim (EPIC-01) → hanya ASSIGNED → CLAIMED

---

## 5. Halaman Admin (Frontend) yang Dibangun

> EPIC-03 akan membahas dashboard & navigasi luas. EPIC-02 fokus page yang dibutuhkan untuk operasi voucher engine.

### 5.1 Generate Voucher Page

* Route: `GET /admin/vouchers/generate`
* View: `resources/views/admin/vouchers/generate.blade.php`
* Form: jumlah voucher, nama batch (optional)

### 5.2 Assign Voucher Page

* Route: `GET /admin/vouchers/assign`
* View: `resources/views/admin/vouchers/assign.blade.php`
* Form: pilih PIC, pilih batch (optional), jumlah assign
* Info: stok UNASSIGNED tersisa

### 5.3 Print Voucher Page

* Route: `GET /admin/vouchers/print`
* View (preview/filter): `resources/views/admin/vouchers/print.blade.php`
* Action:

  * filter by PIC/batch/status
  * generate PDF

---

## 6. Routing & Controller (Laravel Web)

### Routes (routes/web.php)

* `GET  /admin/vouchers/generate` → `Admin\InitialVoucherController@create`
* `POST /admin/vouchers/generate` → `Admin\InitialVoucherController@store`
* `GET  /admin/vouchers/assign` → `Admin\InitialVoucherAssignController@create`
* `POST /admin/vouchers/assign` → `Admin\InitialVoucherAssignController@store`
* `GET  /admin/vouchers/print` → `Admin\InitialVoucherPrintController@index`
* `GET  /admin/vouchers/print/pdf` → `Admin\InitialVoucherPrintController@pdf`

Middleware:

* `auth`
* `role:SUPERADMIN`

---

## 7. Feature Breakdown & Todo Task (DEV-READY)

---

## FT-01: Code Generator Standard (Initial & Merchant Voucher)

### Tujuan

Membuat generator code yang aman, unik, dan konsisten.

### Acceptance Criteria

* Panjang code default 14 char (range 12–16 ok)
* Character set base32/base62 (mudah dibaca, minim ambigu)
* Unik (unique constraint DB sebagai last guard)

### Todo Tasks

**Backend**

* [ ] Buat helper/service: `app/Support/CodeGenerator.php`

  * method: `make(int $length=14): string`
  * gunakan `random_bytes()` lalu encode base32/base62
* [ ] Buat unit test minimal (opsional): generate 10k code, cek collision (best-effort)

---

## FT-02: Generate Voucher Awal (Batch)

### Tujuan

Admin dapat generate N voucher awal dalam sebuah batch.

### Acceptance Criteria

* Bisa input jumlah voucher (N)
* (Optional) input nama batch
* Hasil generate status UNASSIGNED
* Kode unik

### Todo Tasks

**DB**

* [ ] Migration `voucher_batches`
* [ ] Migration `initial_vouchers` (include batch_id)

**Backend**

* [ ] Service: `app/Services/InitialVoucherGeneratorService.php`

  * method: `generate(int $count, ?int $batchId, int $adminId): VoucherBatch`
  * bulk insert initial_vouchers
* [ ] Controller:

  * `Admin/InitialVoucherController@create` (render form)
  * `Admin/InitialVoucherController@store` (handle POST)

**Frontend (Blade)**

* [ ] `admin/vouchers/generate.blade.php`

  * input jumlah
  * input batch name (optional)
  * submit
  * success message (count + batch)

**QA**

* [ ] generate 1
* [ ] generate 50
* [ ] generate 0 (should fail)

---

## FT-03: Assign Voucher ke PIC (Batch / Quantity)

### Tujuan

Mengaktifkan voucher dengan cara assign voucher UNASSIGNED ke PIC.

### Acceptance Criteria

* Pilih PIC
* Pilih batch (optional)
* Input jumlah
* Sistem hanya mengambil voucher UNASSIGNED
* Update mass:

  * set assigned_pic_id
  * set status ASSIGNED

### Todo Tasks

**DB**

* [ ] Migration `pics` (atau pakai users role PIC)

**Backend**

* [ ] Service: `app/Services/InitialVoucherAssignService.php`

  * method: `assign(int $picId, int $qty, ?int $batchId=null): int`
  * select voucher UNASSIGNED with limit qty
  * update status ASSIGNED
  * return jumlah sukses
* [ ] Controller:

  * `Admin/InitialVoucherAssignController@create` (render)
  * `Admin/InitialVoucherAssignController@store` (process)

**Frontend (Blade)**

* [ ] `admin/vouchers/assign.blade.php`

  * dropdown PIC
  * dropdown batch (optional)
  * input qty
  * tampilkan stok UNASSIGNED

**QA**

* [ ] assign qty <= stok
* [ ] assign qty > stok (partial? atau fail) → tentukan: default **fail + message**

---

## FT-04: Print Voucher Awal ke PDF (A4: 3 Voucher per Page)

### Tujuan

Mencetak voucher fisik dengan layout konsisten untuk distribusi.

### Spesifikasi Layout (Final)

* Paper: **A4 Portrait (210 × 297mm)**
* Margin: 10mm
* Content: **3 voucher per halaman** (stack vertikal)
* Bentuk voucher: **horizontal/panjang ke samping**
* Isi voucher minimal:

  * QR: `APP_URL/claim/{initial_code}`
  * Short code teks: `{initial_code}`
  * PIC label: `{pic_name}` (opsional)
  * Campaign title + valid until (opsional)

### Acceptance Criteria

* PDF ter-generate dan bisa di-download
* 3 voucher per halaman konsisten
* QR bisa discan dari hasil print
* Short code terbaca

### Todo Tasks

**Dependencies**

* [ ] Install DomPDF: `barryvdh/laravel-dompdf`
* [ ] Install QR generator: `simple-qrcode`

**Routing**

* [ ] `GET /admin/vouchers/print` (filter page)
* [ ] `GET /admin/vouchers/print/pdf` (generate PDF)

**Controller**

* [ ] `Admin/InitialVoucherPrintController@index`

  * render filter page
* [ ] `Admin/InitialVoucherPrintController@pdf`

  * query vouchers (filter: pic_id, batch_id, status)
  * sort by code
  * chunk vouchers per 3
  * pass data to PDF view
  * return `PDF::loadView(...)->stream()` / `download()`

**Blade (Filter Page)**

* [ ] `resources/views/admin/vouchers/print.blade.php`

  * filter by PIC/batch/status
  * tombol "Generate PDF"

**Blade (PDF View)**

* [ ] `resources/views/admin/print/initial-vouchers.blade.php`

  * CSS minimal (kompatibel DomPDF)
  * `@page { size: A4; margin: 10mm; }`
  * `.voucher { height: 95mm; width: 190mm; }`
  * `page-break-after` setiap 3 voucher
  * QR minimal 35–40mm
  * garis potong (optional)

**QR Rendering**

* [ ] QR sebagai SVG/Base64 agar tajam

  * payload: `config('app.url').'/claim/'.$voucher->code`

**QA**

* [ ] Print 1 voucher → 1 halaman
* [ ] Print 3 voucher → 1 halaman
* [ ] Print 4 voucher → 2 halaman (3 + 1)
* [ ] Scan QR dari hasil print

---

## FT-05: Integrity Guardrails (Constraint + Transaction)

### Tujuan

Mencegah data anomali dan race condition.

### Todo Tasks

**DB Constraints**

* [ ] unique index: `initial_vouchers.code`
* [ ] unique index: `merchant_vouchers.code`
* [ ] unique index: `claims.initial_voucher_id`

**Guard Rules**

* [ ] Assign hanya untuk UNASSIGNED
* [ ] Print default hanya ASSIGNED

**Note**

* Locking untuk claim berada di EPIC-01, namun constraint-nya disiapkan di EPIC-02.

---

## 8. Definition of Done (EPIC-02)

* [ ] Generate voucher awal batch berjalan
* [ ] Assign voucher ke PIC berjalan
* [ ] Print PDF A4 (3 voucher per halaman) berjalan
* [ ] Code generator aman & unik
* [ ] DB constraints terpasang

---

## 9. Out of Scope

* Tampilan dashboard admin lengkap (EPIC-03)
* Claim flow publik (EPIC-01)
* Redeem oleh merchant (EPIC-04)

---
