# AGENTS.md

## Repo state
- Greenfield. Currently contains only `prd.md` (product requirements). No code, no composer/package.json, not a git repo.
- Task is to build the app described in `prd.md`.

## Intended stack (per prd.md)
- Laravel 11 (PHP 8.2+) + Blade + Alpine.js
- MySQL/MariaDB
- Auth: Laravel Breeze; RBAC with 3 roles: `admin` (user mgmt + full CRUD), `analis` (CRUD), `viewer` (read-only)
- 8 data modules, all 1:1 children of `projects`

## Business rules — do not guess (from PRD)
- **Besar Pinjaman** = Cost Structure × 2% (constant `2%`, not a formula-range). Editable/override allowed.
- **Biaya Provisi** = Besar Pinjaman × 1%.
- **Bunga Pinjaman (per bulan)** = (Suku Bunga Pertahun ÷ 12) × 1 × Besar Pinjaman — the `× 1` is intentional (not periperiode × loan period).
- **Suku Bunga per Bulan** = Suku Bunga Pertahun ÷ 12, auto-computed but manually overridable.
- **Jaminan** (tiap jenis) = Revenue × % (percentage input manually).
- **PPh / PPN** = derived from Revenue/Cost × rate in ASUMSI.
- Manual vs calculated: dynamic fields carry an `is_manual` flag so recalculation only overwrites non-overridden values.

##Response