# 📋 Dokumentasi Belajar Laravel

**Sistem Tiket Helpdesk — OTP Login + Filament Dashboard**

|                 |                                   |
| --------------- | --------------------------------- |
| 📅 Tanggal      | 18 Maret 2026                     |
| 🛠️ Tech Stack   | Laravel 12 + Filament v4 + SQLite |
| 🌐 Environment  | Herd (Windows) + VSCode           |
| 📧 Mail Testing | Mailtrap (Email Sandbox)          |

---

## 1. Setup Project Laravel

Project dibuat menggunakan Herd, kemudian dipastikan berada di folder `C:\Users\[nama]\Herd\` agar tidak ada masalah permission di Windows.

**Perintah penting:**

```bash
composer create-project laravel/laravel ticket
php artisan migrate
```

**Konsep yang dipelajari:**

- **Migration** = blueprint struktur tabel database, dijalankan dengan `php artisan migrate`
- **File `.env`** = konfigurasi environment (database, email, app key, dll)
- **SQLite** dipakai untuk development karena tidak perlu install database terpisah
- Folder `Herd` adalah lokasi terbaik untuk project di Windows agar tidak ada masalah permission

---

## 2. Install & Setup Filament v4

Filament adalah admin panel builder untuk Laravel. Diinstall sebagai dashboard untuk admin/agent helpdesk.

```bash
composer require filament/filament:"^4.0" -W
php artisan filament:install --panels
php artisan make:filament-user
```

**Konsep yang dipelajari:**

- Filament v4 sudah stable sejak Agustus 2025
- Panel ID `admin` membuat dashboard tersedia di `/admin`
- Login bawaan Filament dimatikan dengan `->login(false)` di `AdminPanelProvider`

---

## 3. Konfigurasi Email (Mailtrap)

Mailtrap dipakai sebagai email sandbox — email tidak benar-benar terkirim, tapi bisa dilihat di dashboard Mailtrap untuk testing.

**Konfigurasi `.env`:**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxxxxxxx
MAIL_PASSWORD=xxxxxxxxxx
MAIL_FROM_ADDRESS="noreply@ticket.test"
MAIL_FROM_NAME="Ticket System"
```

**Test kirim email via Tinker:**

```bash
php artisan tinker
```

```php
Mail::raw('Test!', fn($msg) => $msg->to('test@example.com')->subject('Test'));
```

---

## 4. Sistem OTP Login

OTP Login dibuat dari scratch tanpa library khusus.

**Flow:**

```
Input email → Dapat kode 6 digit via email → Input kode → Masuk dashboard
```

### 4.1 Tabel OTP

```bash
php artisan make:model Otp -m
```

| Kolom        | Fungsi                               |
| ------------ | ------------------------------------ |
| `email`      | Menyimpan email siapa yang minta OTP |
| `code`       | Kode 6 digit OTP                     |
| `expires_at` | Kapan OTP expired (10 menit)         |

> Tidak memakai `unique()` di kolom email agar user bisa minta OTP berkali-kali. OTP lama dihapus setiap kali ada request baru.

### 4.2 File yang Dibuat

| File                                          | Fungsi                         |
| --------------------------------------------- | ------------------------------ |
| `app/Models/Otp.php`                          | Model representasi tabel otps  |
| `app/Http/Controllers/Auth/OtpController.php` | Logic kirim & verifikasi OTP   |
| `resources/views/auth/otp-email.blade.php`    | Halaman form input email       |
| `resources/views/auth/otp-verify.blade.php`   | Halaman form input kode OTP    |
| `routes/web.php`                              | Pendaftaran URL/route aplikasi |

### 4.3 Route yang Dibuat

```
GET  /login   → showEmailForm()  → tampil form email   (name: login)
POST /login   → sendOtp()        → kirim OTP ke email
GET  /verify  → showVerifyForm() → tampil form kode OTP
POST /verify  → verifyOtp()      → verifikasi kode & login
```

---

## 5. Error yang Ditemui & Solusinya

### ❌ Error 1 — SQLite Read-only

```
attempt to write a readonly database
```

- **Sebab:** Project dibuat di folder Desktop yang tidak punya akses write untuk proses Herd
- **Fix:** Pindahkan project ke `C:\Users\[nama]\Herd\` atau jalankan:
    ```bash
    icacls "path\database\database.sqlite" /grant Everyone:F
    ```

---

### ❌ Error 2 — MAIL_SCHEME tidak support

```
The "tls" scheme is not supported
```

- **Sebab:** `MAIL_SCHEME=tls` tidak dikenal di Laravel 12, atau konfigurasi `.env` ketukar saat paste
- **Fix:** Hapus baris `MAIL_SCHEME` dari `.env`, lalu:
    ```bash
    php artisan config:clear
    ```

---

### ❌ Error 3 — View Not Found

```
View [auth.otp_email] not found
```

- **Sebab:** Controller memanggil view dengan underscore (`otp_email`) tapi nama file memakai dash (`otp-email`)
- **Fix:** Samakan penamaan di Controller:
    ```php
    return view('auth.otp-email');
    ```

---

### ❌ Error 4 — Class Not Found (huruf kecil)

```
Class "App\Http\Controllers\Auth\otp" not found
```

- **Sebab:** Model `Otp` dipanggil dengan huruf kecil (`otp::`) dan belum ada import di atas Controller
- **Fix:** Tambahkan import dan ubah pemanggilan:
    ```php
    use App\Models\Otp;
    // ...
    Otp::where(...) // bukan otp::where(...)
    ```

---

### ❌ Error 5 — Password NOT NULL

```
NOT NULL constraint failed: users.password
```

- **Sebab:** `firstOrCreate()` tidak mengisi kolom `password` yang wajib ada di tabel users
- **Fix:** Tambahkan password random di array kedua `firstOrCreate()`:
    ```php
    $user = User::firstOrCreate(
        ['email' => $request->input('email')],
        [
            'name'     => explode('@', $request->input('email'))[0],
            'password' => bcrypt(str()->random(32)),
        ]
    );
    ```

---

### ❌ Error 6 — Route Login Not Defined

```
Route [login] not defined
```

- **Sebab:** Filament mencari route bernama `login` saat user belum login, tapi route kita bernama `otp.email.form`
- **Fix:** Ubah nama route di `web.php`:
    ```php
    Route::get('/login', [OtpController::class, 'showEmailForm'])->name('login');
    ```

---

## 6. Ringkasan Hasil Hari Ini

- ✅ Project Laravel 12 berhasil dibuat dan berjalan di Herd
- ✅ Filament v4 berhasil diinstall sebagai admin panel
- ✅ Konfigurasi email dengan Mailtrap berhasil
- ✅ Tabel `otps` berhasil dibuat dengan migration
- ✅ OTP login dari scratch berhasil diimplementasi
- ✅ Flow lengkap: input email → OTP via email → verifikasi → masuk dashboard Filament
- ✅ Login bawaan Filament berhasil dinonaktifkan dan diganti dengan OTP

## Next Session

- [ ] Fase 4 — Bikin fitur tiket (Model Ticket, Migration, Resource Filament)
- [ ] Form customer untuk submit tiket baru
- [ ] Role & permission: bedakan customer vs admin/agent
