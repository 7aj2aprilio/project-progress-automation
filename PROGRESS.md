# Project Progress

## Current Status

MVP of Tracking Proyek Konstruksi application has been **REFACTORED** to support dynamic data (1:N relationships) and Global Settings. Running at `http://localhost:8000`.

## Completed

- Laravel 12 project setup with Breeze (Blade + Tailwind)
- MySQL database configured (`project_progress_automation`)
- RBAC: 3 roles (admin, analis, viewer) with RoleMiddleware
- **[Refactor]** Database schema updated: converted static modules (`other_charges`, `guarantees`, `taxes`, `loans`, `revenue_streams`) to dynamic 1:N tables (`project_beban`, `project_jaminan`, `project_pajak`, `project_pinjaman`, `project_revenues`).
- **[Refactor]** Added `global_settings` table for centralizing assumptions (Suku Bunga, PPN, PPh, Provisi, Pemodalan, Periode, Threshold Kelayakan).
- **[Refactor]** Added `loan_rate` to `project_informations` to allow project-specific loan multiplier override (default 1.65%).
- ProjectCalculator service updated to handle dynamic loops and read from Global Settings.
- Controllers: Dashboard, Project, User, **SettingController**.
- Blade views: Dashboard with "Kelayakan" indicator, Project Edit (Alpine.js dynamic form rows), Project Show, **Global Settings** menu.
- Seeders updated with sample data matching the new schema.
- Manual override system with `is_manual` flags retained for dynamic rows.

## Recent Changes

- 2026-08-31: Refactored database to support dynamic lists (Cost, Beban, Revenue, Pajak, Pinjaman).
- 2026-08-31: Added Global Settings panel for Admin.
- 2026-08-31: Added 'Layak'/'Tidak Layak' margin threshold classification.

## In Progress

- None currently

## Issues

- Tax formula convention: PPN Keluaran uses Revenue, PPN Masukan uses Cost (as requested).

## Next Steps

- Add unit tests for ProjectCalculator
- Add data export (Excel/PDF)
- Consider adding audit trail / change log
- Phase 2: Monthly cash flow projection (per PRD scope)
- **Phase 3 (Planned): Kurva S / Time Schedule Feature**
  - Buat tabel `work_item_schedules` (atau sejenisnya) untuk menyimpan sebaran bobot per minggu (Rencana/Target).
  - Buat antarmuka (UI) agar User bisa menginput persentase target mingguan per item pekerjaan (seperti sel warna biru di Excel).
  - Buat fungsionalitas perhitungan *Rencana Minggu ini*, *Rencana Kumulatif*, *Realisasi Minggu ini*, *Realisasi Kumulatif*, dan *Deviasi*.
  - Menampilkan grafik Kurva S (garis Rencana vs Realisasi) di Dashboard/PDF Laporan.

## Important Notes

- **Login credentials**: admin@example.com / analis@example.com / viewer@example.com — all use password: `password`
- **Settings**: Admin can access "Pengaturan Global" to adjust default tax rates, loan percentages, and feasibility thresholds.
- **Dynamic Inputs**: Users can dynamically add/remove rows for Beban Lainnya, Rincian Revenue, Jaminan, Pajak, dan Komponen Pinjaman. Total Cost and Total Revenue automatically calculate.
