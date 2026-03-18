<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 🎫 Ticketing System

Sistem tiket helpdesk berbasis web dibangun dengan Laravel 12 dan Filament v4. Login menggunakan OTP via email tanpa password.

---

## 🛠️ Tech Stack

|                  |                              |
| ---------------- | ---------------------------- |
| **Framework**    | Laravel 12                   |
| **Admin Panel**  | Filament v4                  |
| **Database**     | SQLite (development)         |
| **Auth**         | OTP via Email (passwordless) |
| **Mail Testing** | Mailtrap                     |
| **Environment**  | PHP 8.4, Herd                |

---

## ✨ Fitur

- 🔐 Login tanpa password — cukup email + kode OTP 6 digit
- 📧 OTP dikirim via email, berlaku 10 menit
- 🗂️ Dashboard admin menggunakan Filament v4
- 🎫 Manajemen tiket helpdesk (customer support)

---

## ⚙️ Instalasi

### 1. Clone & Install Dependencies

```bash
git clone <repo-url>
cd ticketing
composer install
```

### 2. Copy & Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi `.env`

Sesuaikan bagian berikut di file `.env`:

```env
# Database
DB_CONNECTION=sqlite

# Mail (gunakan Mailtrap untuk development)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@ticket.test"
MAIL_FROM_NAME="Ticket System"
```

### 4. Jalankan Migration

```bash
php artisan migrate
```

### 5. Buat User Admin

```bash
php artisan make:filament-user
```

### 6. Jalankan Server

```bash
php artisan serve
```

Atau jika menggunakan Herd, akses langsung di `http://ticketing.test`

---

## 🗂️ Struktur Project

```
app/
├── Http/
│   └── Controllers/
│       └── Auth/
│           └── OtpController.php   # Logic OTP login
├── Models/
│   ├── Otp.php                     # Model tabel OTP
│   └── User.php
database/
├── migrations/
│   └── xxxx_create_otps_table.php  # Tabel OTP
resources/
└── views/
    └── auth/
        ├── otp-email.blade.php     # Form input email
        └── otp-verify.blade.php    # Form input kode OTP
routes/
└── web.php                         # Route aplikasi
```

---

## 🔐 Flow OTP Login

```
1. User buka /login
2. Input email → klik "Kirim OTP"
3. Laravel generate kode 6 digit → simpan ke tabel otps
4. Kode dikirim ke email user
5. User input kode OTP
6. Laravel verifikasi kode & expired time
7. Jika valid → login & redirect ke /admin
```

---

## 🌐 URL

| URL       | Keterangan                       |
| --------- | -------------------------------- |
| `/login`  | Halaman input email              |
| `/verify` | Halaman input kode OTP           |
| `/admin`  | Dashboard Filament (admin/agent) |

---

## 📦 Perintah Berguna

```bash
# Jalankan migration
php artisan migrate

# Rollback migration terakhir
php artisan migrate:rollback

# Clear config cache
php artisan config:clear

# Buka Tinker (REPL Laravel)
php artisan tinker

# Buat user admin Filament
php artisan make:filament-user
```
