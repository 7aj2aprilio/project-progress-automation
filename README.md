# Tracking Proyek Konstruksi

Aplikasi web untuk input, hitung, dan melacak data keuangan proyek konstruksi. Menggantikan spreadsheet Excel, data per proyek tersimpan terpusat di database.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade + Alpine.js
- **Styling**: Tailwind CSS (via Laravel Breeze)
- **Database**: MySQL / MariaDB
- **Auth**: Laravel Breeze
- **RBAC**: Role-based access control (admin, analis, viewer)

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+ / MariaDB 10.6+

## Installation

```bash
# Clone repository
git clone <repo-url>
cd project-progress-automation

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=mysql
# DB_DATABASE=project_progress_automation
# DB_USERNAME=root
# DB_PASSWORD=

# Create database
mysql -u root -e "CREATE DATABASE project_progress_automation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations and seed
php artisan migrate --seed
```

## Running

```bash
# Start Laravel server
php artisan serve

# Start Vite dev server (separate terminal)
npm run dev
```

Open `http://localhost:8000` in your browser.

## Default Users

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Admin |
| analis@example.com | password | Analis |
| viewer@example.com | password | Viewer |

## Roles & Permissions

| Feature | Admin | Analis | Viewer |
|---------|:-----:|:------:|:------:|
| Kelola user & role | ✅ | ❌ | ❌ |
| Buat/edit/hapus proyek | ✅ | ✅ | ❌ |
| Input/edit modul | ✅ | ✅ | ❌ |
| Lihat / track data | ✅ | ✅ | ✅ |

## Data Modules (8 per project)

1. **Informasi Project** — Data umum proyek (statis)
2. **Asumsi-Asumsi** — Suku bunga, PPN, PPh, periode pinjaman (statis, partial auto)
3. **Cost Structure** — Biaya mitra pelaksana (statis)
4. **Beban Lainnya** — Fee, CAR, pengawasan, BOP (statis)
5. **Jaminan-Jaminan** — Revenue × % per jenis (dinamis)
6. **Revenue Stream** — Jasa pelaksanaan + management fee (statis)
7. **Perpajakan** — PPh, PPN keluaran/masukan (dinamis)
8. **Pinjaman** — Besar pinjaman, provisi, bunga (dinamis)

## Auto-Calculation Formulas

| Item | Formula |
|------|---------|
| Suku Bunga/Bulan | Suku Bunga Pertahun ÷ 12 |
| Besar Pinjaman | Cost Structure × 2% |
| Biaya Provisi | Besar Pinjaman × 1% |
| Bunga Pinjaman/Bulan | (Pertahun ÷ 12 ÷ 100) × 1 × Besar Pinjaman |
| Jaminan (tiap jenis) | Revenue × % input |
| PPh Pasal 23 | Revenue × PPh rate |
| PPN Keluaran | Cost × PPN rate |
| PPN Masukan | Revenue × PPN rate |

Calculated fields can be manually overridden. Override values are preserved during recalculation (`is_manual` flag).

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── ProjectController.php
│   │   └── UserController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── Project.php
│   ├── ProjectInformation.php
│   ├── Assumption.php
│   ├── CostStructure.php
│   ├── OtherCharge.php
│   ├── Guarantee.php
│   ├── RevenueStream.php
│   ├── Tax.php
│   └── Loan.php
└── Services/
    └── ProjectCalculator.php
```

## License

MIT
