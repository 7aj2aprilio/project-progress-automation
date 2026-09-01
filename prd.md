# PRD — Tracking Proyek Konstruksi (Pengganti Excel)

## 1. Ringkasan

Aplikasi web untuk input, hitung, dan melacak data keuangan proyek konstruksi. Tujuan utama menggantikan spreadsheet Excel lama, sehingga data per proyek tersimpan terpusat di database, dapat dilacak, dan dihitung otomatis oleh aplikasi.

Aplikasi terdiri dari **8 modul** data per proyek. Sebagian modul diisi manual (statis), sebagian dihitung otomatis dari input lain (dinamis).

## 2. Tech Stack

| Lapisan | Teknologi |
|---------|-----------|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Blade + Alpine.js |
| Styling | Bootstrap (atau Tailwind) |
| Database | MySQL / MariaDB |
| Auth | Laravel Breeze `make:auth` |
| Role/Akses | RBAC (role-based access control) via middleware + guard |

## 3. Persona & Role

| Role | Keterangan |
|------|------------|
| **Admin** | Kelola user/role, akses semua fitur (CRUD proyek & modul) |
| **Analis** | Input dan edit proyek & semua modul |
| **Viewer** | Hanya melihat / melacak data (read-only) |

### Matriks Akses

| Fitur | Admin | Analis | Viewer |
|-------|:-----:|:------:|:------:|
| Kelola user & role | ✅ | ❌ | ❌ |
| Buat/edit/hapus proyek | ✅ | ✅ | ❌ |
| Input/edit seluruh modul | ✅ | ✅ | ❌ |
| Lihat / track tabel | ✅ | ✅ | ✅ |

## 4. Struktur Data — 8 Modul

Setiap modul terhubung ke **satu proyek**. Modul dibagi dua tipe:

- **Statis** = diinput manual, nilainya tetap tapi bisa diedit sewaktu-waktu.
- **Dinamis** = dihitung aplikasi dari input modul lain.

### 4.1 INFORMASI PROJECT — *Statis*
| Field | Tipe |
|-------|------|
| Nama Pelanggan | string |
| Kategori Project | string |
| Nama Project | string |
| Lokasi Project | string |
| Estimasi Mulai Pekerjaan | MM/YY |
| Durasi Project (bulan) | int |
| Durasi Retensi (bulan) | int |
| Pengawasan Konstruksi | enum: Sendiri / Menggunakan MK |
| Tipe Bangunan | string |
| TOP Pembayaran kepada Mitra | string |
| TOP Pembayaran Pelanggan | string |

### 4.2 ASUMSI-ASUMSI — *Statis*
| Field | Tipe |
|-------|------|
| Suku Bunga Pertahun | decimal (%) |
| Suku Bunga per Bulan | decimal (%) — auto-hit from tahun, override manual |
| Pemodalan (Loan) | decimal (%) |
| Provisi | decimal (%) |
| PPN | decimal (%) |
| PPh Pasal 23 | decimal (%) |
| Periode Pinjaman (bulan) | int |

### 4.3 COST STRUCTURE / CASH OUT — *Statis*
| Field | Tipe |
|-------|------|
| Biaya Mitra Pelaksana (Exclude PPN) | decimal |

### 4.4 BEBAN LAINNYA — *Statis*
| Field | Tipe |
|-------|------|
| Fee Fasilitas Jaminan | decimal |
| Admin Fasilitas Jaminan | decimal |
| Construction Assurance Risk (CAR) | decimal |
| Iuran Jasa Konstruksi | decimal |
| Biaya Pengawasan | decimal |
| BOP Project | decimal |

### 4.5 JAMINAN-JAMINAN — *Dinamis (dari Revenue × %)*
| Field | Tipe |
|-------|------|
| Jaminan Uang Muka | % input manual dari Revenue |
| Jaminan Penawaran | % input manual dari Revenue |
| Jaminan Pelaksanaan | % input manual dari Revenue |
| Jaminan Pemeliharaan | % input manual dari Revenue |

### 4.6 REVENUE STREAM / CASH IN — *Statis*
| Field | Tipe |
|-------|------|
| Jasa Pelaksanaan Konstruksi | decimal |
| Management Fee GSD | decimal |

### 4.7 PERPAJAKAN — *Dinamis*
| Field | Tipe |
|-------|------|
| PPh | decimal — rate dari asumsi |
| PPN | decimal — rate dari asumsi |

### 4.8 PINJAMAN — *Dinamis*
| Field | Tipe |
|-------|------|
| Besar Pinjaman | decimal — dari Cost Structure × 2% |
| Biaya Provisi | decimal — Besar Pinjaman × 1% |
| Bunga Pinjaman | decimal — per bulan |

## 5. Formula / Aturan Perhitungan

| Item | Rumus | Tipe |
|------|-------|------|
| Suku Bunga per Bulan | Suku Bunga / tahun ÷ 12 | auto (bisa override) |
| Besar Pinjaman | Cost Structure × 2% | hitung tetap, nilai bisa diedit/override |
| Biaya Provisi | Besar Pinjaman × 1% | hitung otomatis |
| Bunga Pinjaman (per bulan) | (Suku Bunga / tahun ÷ 12) × 1 × Besar Pinjaman | hitung otomatis |
| Jaminan (tiap jenis) | Revenue × % (input manual per jenis) | hitung otomatis |
| PPh | Revenue / Cost × rate PPh (asumsi) | hitung otomatis |
| PPN | Revenue / Cost × rate PPN (asumsi) | hitung otomatis |

> Catatan: angka `2%` pada Besar Pinjaman dan `1%` pada Biaya Provisi adalah **konstanta tetap tanpa rumus** (hardcoded). Nilai **Besar Pinjaman** tetap bisa diedit manual sebagai override.

### Detail (dikonfirmasi user)
- **Besar Pinjaman** = Cost Structure × 2% (dikalikan tetap 2%, bukan range).
- **Biaya Provisi** = Besar Pinjaman × 1%.
- **Bunga Pinjaman** = (suku bunga pertahun ÷ 12) × 1 × Besar Pinjaman — tetap `× 1`, tidak dikali periode pinjaman.

## 6. Fungsionalitas (Requirement)

### R1 — Autentikasi & Role
- Login/logout.
- Guard berbasis role: admin, analis, viewer.

### R2 — Manajemen User (Admin)
- Kelola user, tetapkan role.

### R3 — Manajemen Proyek
- Membuat proyek (dashboard).
- List proyek + ringkasan (total cost, total revenue, estimasi laba, status).
- Edit & hapus proyek (admin/analis).

### R4 — Input & Edit Modul
- Form per modul (8 modul), tersimpan ke database.
- Field hasil kalkulasi otomatis terisi saat simpan.
- Field tertentu bisa override manual (flag `is_manual`).

### R5 — Tracking View
- Tampilan ala tabel/spreadsheet per proyek.
- Rincian tiap modul + rekap total (total cost, total revenue, estimasi laba).

### R6 — Kalkulasi Otomatis
- Service `ProjectCalculator` menghitung nilai dinamis saat save.
- Formula konsisten (lihat Bagian 5).

## 7. Rancangan Teknis

### Skema Database (ringkas)
Tabel relasional, `projects` sebagai induk:

- `projects`
- `project_informations` (4.1)
- `assumptions` (4.2)
- `cost_structures` (4.3)
- `other_charges` (4.4)
- `guarantees` (4.5)
- `revenue_streams` (4.6)
- `taxes` (4.7)
- `loans` (4.8)
- `users`, `roles`, `role_user` (RBAC)

Relasi: `projects` 1:1 dengan semua tabel modul.

### Struktur Aplikasi Laravel
- `Service/ProjectCalculator.php` — engine kalkulasi.
- `Models/` untuk tiap entitas.
- `Controllers/` — ProjectController + controller per modul (atau tab dalam satu controller).
- Middleware `role:` untuk proteksi route.

### Halaman / Route
| Route | Deskripsi | Akses |
|-------|-----------|-------|
| `/` dashboard | daftar proyek + ringkasan | semua |
| `/projects{id}` | detail proyek, tab 8 modul | semua (viewer read-only) |
| `/projects/create` | buat proyek | admin, analis |
| `/projects/{id}/edit` | edit modul | admin, analis |
| `/users` | kelola user | admin |

## 8. Aturan Override Nilai Hitung

- Setiap field dinamis punya flag `is_manual`:
  - `false` → nilai dihitung dari promise.
  - `true` → nilai dihitung lalu bisa ditimpa user; recalculation hanya menimpa field yang belum `is_manual = true`.

## 9. Milestone Implementasi

1. Setup Laravel + Breeze + migrasi 8 skema modul + seed role.
2. Model & controller CRUD: proyek + tiap modul.
3. `ProjectCalculator` service + test unit untuk rumus.
4. View Blade: dashboard, form tab modul, tracking grid.
5. RBAC middleware + proteksi route.
6. Polish & testing manual.

## 10. Deliverable

File **`prd.md`** ini + (saat implementasi) aplikasi Laravel fungsional sesuai spesifikasi di atas.

## 11. Scope Non-Goals (saat ini)

- Tidak ada perhitungan depresiasi/amortisasi.
- Tidak ada integrasi akuntansi (jurnal/laporan neraca).
- Proyeksi arus kas bulanan penuh (timeline multi-bulan) bisa jadi fase 2, di luar scope awal.
