# Local Development Setup

Panduan setup project **Coconut Sugar Management** di lingkungan lokal menggunakan Docker (tanpa perlu install PHP atau Composer di komputer kamu).

## Prerequisites

Hanya satu yang perlu diinstall di komputer kamu:

- **[Docker Desktop](https://www.docker.com/products/docker-desktop/)** (Windows/Mac/Linux)

Pastikan Docker Desktop sudah **running** (ikon di taskbar berwarna hijau) sebelum melanjutkan.

---

## First-time Setup

Jalankan langkah-langkah berikut **sekali saja** saat pertama kali clone project.

### 1. Clone repository

```bash
git clone <repository-url>
cd coconut_sugar_management_web
```

### 2. Copy file environment

```bash
cp .env.example .env
```

Lalu edit `.env` sesuai kebutuhan. Pastikan variabel berikut sudah ada:

```env
APP_NAME="Coconut Sugar Management"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coconut_sugar
DB_USERNAME=sail
DB_PASSWORD=password

WWWGROUP=1000
WWWUSER=1000
```

### 3. Start Docker containers

```powershell
docker compose -f compose.yaml up -d
```

> Saat pertama kali, Docker akan **build image** PHP 8.5 — proses ini butuh 5-15 menit tergantung koneksi internet. Selanjutnya akan langsung cepat.

### 4. Install dependencies PHP

```powershell
docker compose -f compose.yaml exec laravel.test composer install
```

### 5. Generate application key

```powershell
docker compose -f compose.yaml exec laravel.test php artisan key:generate
```

### 6. Jalankan migrasi database

```powershell
docker compose -f compose.yaml exec laravel.test php artisan migrate
```

### 7. Install Node dependencies

```powershell
docker compose -f compose.yaml exec laravel.test npm install
```

### 8. Build frontend assets

```powershell
# Untuk production-like build (sekali jalan):
docker compose -f compose.yaml exec laravel.test npm run build
```

### 9. Buka di browser

```
http://127.0.0.1
```

---

## Daily Development Workflow

Setiap kali mulai kerja, jalankan:

**Terminal 1 — Start containers (jika belum jalan):**
```powershell
docker compose -f compose.yaml up -d
```

**Terminal 2 — Frontend dev server dengan HMR:**
```powershell
docker compose -f compose.yaml exec laravel.test npm run dev
```

> Biarkan Terminal 2 tetap terbuka selama development. HMR (Hot Module Replacement) akan otomatis reload browser saat kamu edit file Vue.

**Selesai kerja — Stop containers:**
```powershell
docker compose -f compose.yaml down
```

---

## Perintah yang Sering Dipakai

Semua perintah dijalankan melalui Docker (tidak perlu PHP/Composer lokal):

```powershell
# Artisan
docker compose -f compose.yaml exec laravel.test php artisan migrate
docker compose -f compose.yaml exec laravel.test php artisan migrate:fresh --seed
docker compose -f compose.yaml exec laravel.test php artisan make:model NamaModel -mfs
docker compose -f compose.yaml exec laravel.test php artisan make:controller NamaController

# Composer
docker compose -f compose.yaml exec laravel.test composer require nama/package
docker compose -f compose.yaml exec laravel.test composer install

# NPM
docker compose -f compose.yaml exec laravel.test npm install nama-package
docker compose -f compose.yaml exec laravel.test npm run build

# Database
docker compose -f compose.yaml exec laravel.test php artisan migrate:fresh --seed

# Testing
docker compose -f compose.yaml exec laravel.test php artisan test

# Logs
docker compose -f compose.yaml logs laravel.test --tail=50 -f
```

> **Tip:** Buat alias di PowerShell untuk memperpendek perintah:
> ```powershell
> # Tambahkan ke $PROFILE
> function sail { docker compose -f compose.yaml exec laravel.test $args }
> # Setelah itu bisa pakai: sail php artisan migrate
> ```

---

## Struktur Layanan Docker

| Service | URL / Port | Keterangan |
|---|---|---|
| Laravel App | http://127.0.0.1 | Aplikasi utama (port 80) |
| Vite Dev Server | http://127.0.0.1:5173 | Frontend HMR |
| MySQL | localhost:3306 | Database (user: `sail`, pass: `password`) |
| Redis | localhost:6379 | Cache & queue |

Untuk koneksi ke MySQL dari database client (TablePlus, DBeaver, dll):
- **Host:** `127.0.0.1`
- **Port:** `3306`
- **Database:** `coconut_sugar`
- **Username:** `sail`
- **Password:** `password`

---

## Troubleshooting

### Docker engine tidak running
Buka **Docker Desktop** dan tunggu sampai status "Engine running" (ikon hijau di taskbar).

### Port 80 sudah dipakai
Edit `compose.yaml`, ubah port mapping:
```yaml
ports:
  - '8080:80'  # Akses via http://127.0.0.1:8080
```

### Error `WWWGROUP invalid group ID`
Pastikan `.env` sudah berisi:
```env
WWWGROUP=1000
WWWUSER=1000
```
Lalu rebuild: `docker compose -f compose.yaml build --no-cache`

### Vite manifest not found
Jalankan build assets:
```powershell
docker compose -f compose.yaml exec laravel.test npm run build
```

### Permission error di storage/
```powershell
docker compose -f compose.yaml exec laravel.test chmod -R 775 storage bootstrap/cache
```
