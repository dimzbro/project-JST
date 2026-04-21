# JST - Job Sharing Task

Platform berbagi tugas pekerjaan berbasis web menggunakan Laravel 12.

## Persyaratan

- **PHP** >= 8.2
- **Composer**
- **MySQL** (XAMPP / Laragon / WAMP)

---

## Cara Menjalankan Project (Langkah Mudah)

### 1. Clone Project
```bash
git clone https://github.com/dimzbro/project-JST.git
cd project-JST
```

### 2. Install Composer
```bash
composer install
```

### 3. Copy File Environment
```bash
cp .env.example .env
```
> **Windows CMD:** `copy .env.example .env`

### 4. Generate App Key
```bash
php artisan key:generate
```

### 5. Buat Database
Buat database baru di **phpMyAdmin** atau MySQL CLI dengan nama:
```
JST
```
> Pastikan XAMPP/Laragon sudah aktif dan MySQL berjalan.

Edit file `.env` jika perlu menyesuaikan username/password database:
```
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi
```bash
php artisan migrate
```

### 7. Jalankan Server
```bash
php artisan serve
```

Buka browser dan akses: **http://localhost:8000**

---

## Shortcut (Semua Langkah Otomatis)

Jika sudah menyiapkan database `JST`, bisa langsung jalankan:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## Peran Pengguna

| Role   | Keterangan                     |
|--------|-------------------------------|
| Admin  | Kelola pengguna & verifikasi job |
| Client | Posting pekerjaan               |
| Worker | Mengambil & menyelesaikan tugas |

---

## Tidak Perlu NPM / Node.js

Project ini sudah menggunakan asset yang telah di-build. **Tidak perlu menjalankan `npm install` atau `npm run dev`.**
