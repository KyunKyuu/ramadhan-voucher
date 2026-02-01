# 00 – EPIC OVERVIEW: SISTEM VOUCHER RAMADHAN

**Project**: Sistem Voucher Ramadhan (QR-based)
**Version**: 1.1 (Reset & Rebaseline)
**Status**: Planning – MVP
**Tech Stack**: Laravel 10+, Blade + Tailwind (Mobile First)
**Document Owner**: Product / Engineering

---

## 1. Tujuan Dokumen

Dokumen ini adalah **root document** (titik awal) untuk seluruh pengembangan Sistem Voucher Ramadhan.

Fungsi dokumen ini:

* Menjadi **sumber kebenaran tunggal (single source of truth)**
* Menentukan **boundary antar EPIC**
* Menjadi referensi untuk sprint planning & task breakdown

Semua dokumen EPIC, Feature Spec, dan Task **HARUS mengacu ke dokumen ini**.

---

## 2. Ringkasan Produk

Sistem Voucher Ramadhan adalah aplikasi web **mobile-first** berbasis Laravel yang digunakan untuk:

* Distribusi voucher fisik (QR Voucher Awal)
* Klaim voucher oleh user tanpa login
* Konversi voucher awal menjadi voucher merchant digital
* Penukaran voucher merchant secara offline
* Pengumpulan & analisis data distribusi voucher

Sistem ini **BUKAN** payment system dan **TIDAK** memproses transaksi keuangan.

---

## 3. Prinsip Dasar Sistem (Non-Negotiable)

1. **One Voucher – One Claim**

   * Voucher Awal hanya bisa diklaim 1x

2. **One Merchant Voucher – One Redeem**

   * Voucher Merchant hanya bisa diredeem 1x

3. **Atomic Transaction**

   * Claim & redeem wajib menggunakan DB transaction + locking

4. **Mobile First**

   * Target viewport utama: 360–430px

5. **Fullstack Laravel**

   * Backend + Frontend (Blade)
   * BUKAN API-only

---

## 4. Role & Akses

### 4.1 SuperAdmin

* Generate voucher awal (batch)
* Assign voucher ke PIC
* Print voucher fisik (PDF)
* CRUD Merchant & Diskon
* Melihat seluruh data & analytics

### 4.2 PIC

* Bukan user login (MVP)
* Menjadi relasi kepemilikan voucher
* Digunakan untuk tracking distribusi

### 4.3 Merchant

* Login ke panel merchant
* Scan & redeem voucher merchant
* Melihat data penukaran
* Mengatur diskon merchant

### 4.4 Public User

* Scan QR voucher awal
* Isi form klaim
* Melihat voucher merchant

---

## 5. EPIC LIST & BOUNDARY (FINAL)

| EPIC ID     | Nama EPIC              | Scope Utama                        |
| ----------- | ---------------------- | ---------------------------------- |
| **EPIC-01** | Public Voucher Claim   | Scan QR → Form → Voucher List      |
| **EPIC-02** | Voucher Engine & Print | Generate, Assign, Print, Integrity |
| **EPIC-03** | SuperAdmin Dashboard   | UI Admin & Management              |
| **EPIC-04** | Merchant Panel         | Scan & Redeem Voucher              |
| **EPIC-05** | Analytics & Reporting  | Statistik & Export Data            |

> ⚠️ Aturan penting: **Satu fitur hanya boleh ada di satu EPIC**.

---

## 6. High-Level User Journey

1. SuperAdmin generate voucher awal
2. SuperAdmin assign voucher ke PIC
3. SuperAdmin print voucher fisik (A4, 3 voucher per halaman)
4. Voucher dibagikan ke user
5. User scan QR voucher awal
6. User isi form klaim
7. Sistem generate voucher merchant
8. User datang ke merchant
9. Merchant scan & redeem voucher
10. Data tersimpan untuk analisis

---

## 7. Struktur Dokumen (Disarankan)

```
docs/
├── 00-EPIC-OVERVIEW.md
├── 01-EPIC-01-PUBLIC-VOUCHER-CLAIM.md
├── 02-EPIC-02-VOUCHER-ENGINE-PRINT.md
├── 03-EPIC-03-SUPERADMIN-DASHBOARD.md
├── 04-EPIC-04-MERCHANT-PANEL.md
└── 05-EPIC-05-ANALYTICS.md
```

---

## 8. Definition of Ready (DoR) untuk EPIC

Sebuah EPIC **boleh dikerjakan** jika:

* Boundary jelas
* Tidak overlap EPIC lain
* Fullstack (BE + FE) terdefinisi
* Data model jelas
* Edge case terdaftar

---

## 9. Definition of Done (Global)

* Tidak ada fitur silang antar EPIC
* Semua P0 selesai
* Voucher tidak bisa dipakai ganda
* UI mobile-first lolos QA
* Data konsisten & audit-able

---

## 10. Catatan Penting

Dokumen ini adalah **baseline baru**. Semua EPIC setelah ini akan:

* dibuat sebagai file TERPISAH
* memiliki nomor & scope yang konsisten
* direview sebelum lanjut ke EPIC beri
