# SmartLogbook - Smart Intern Management System

<p align="center">
  <img src="https://raw.githubusercontent.com/FaizinAjah/smartlogbook/main/public/images/logo.png" alt="SmartLogbook Logo" width="120" onerror="this.style.display='none'"/>
</p>

<p align="center">
  <strong>Sistem Manajemen & Log Book Aktivitas Peserta Magang Berbasis Web</strong>
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"/></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind"/></a>
  <a href="https://vitejs.dev"><img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"/></a>
  <a href="https://bun.sh"><img src="https://img.shields.io/badge/Bun-1.x-f472b6?style=for-the-badge&logo=bun&logoColor=white" alt="Bun"/></a>
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/></a>
</p>

---

## 📌 Tentang Proyek

**SmartLogbook (Smart Intern)** adalah sistem informasi berbasis web yang dirancang untuk mempermudah monitoring, pencatatan aktivitas, dan evaluasi kegiatan peserta magang (internship) secara terstruktur.

Aplikasi ini menghubungkan tiga pihak utama: **Super Admin**, **Pembimbing Lapangan**, dan **Peserta Magang**, guna memastikan kegiatan magang terdokumentasi dengan rapi, transparan, dan dapat dipertanggungjawabkan.

---

## ✨ Fitur Utama

### 1. 🛡️ Multi-Role & Hak Akses
* **Super Admin (`Role: 1`)**:
  * Manajemen pengguna aplikasi (buat, edit, reset password, hapus).
  * Manajemen Institusi pendidikan / instansi asal peserta.
  * Manajemen data Pembimbing Lapangan terhubung dengan institusi.
  * Manajemen data dan penugasan Peserta Magang.
  * Konfigurasi Hari Libur nasional & cuti bersama.
  * Monitoring dan persetujuan seluruh log book aktivitas peserta.
* **Peserta Magang / Anggota (`Role: 2`)**:
  * Dashboard statistik log book pribadi.
  * Pengisian log book kegiatan harian (deskripsi, jam kerja, foto dokumentasi).
  * Riwayat log book dengan filter bulan/tahun.
  * Ekspor rekapan log book ke format **PDF** dan **Excel**.
* **Pembimbing Lapangan (`Role: 3`)**:
  * Dashboard monitoring perkembangan peserta bimbingan.
  * Akses read-only daftar log book dan dokumentasi peserta magang di bawah bimbingannya.
  * Verifikasi dan tinjauan keaktifan peserta.

### 2. 📝 Pengelolaan Log Book
* Form input kegiatan harian responsif dengan *drag-and-drop* berkas/foto.
* Kompresi gambar otomatis di sisi klien untuk optimalisasi penyimpanan.
* Tampilan pratinjau bukti kegiatan/transfer menggunakan *ViewerJS*.
* Validasi tanggal otomatis terhadap hari libur kalender.

### 3. 🎨 Tampilan Modern & Aksesibilitas
* Dibangun dengan **Tailwind CSS v4** & **Preline UI**.
* Dukungan mode gelap (*Dark Mode*) dan terang (*Light Mode*).
* Sidebar navigasi dinamis berbasis role dengan proteksi modul aktif.

---

## 🛠️ Tech Stack

* **Backend Framework**: [Laravel](https://laravel.com/)
* **Language**: PHP 8.2+
* **Frontend Tooling**: [Vite](https://vitejs.dev/) & [Bun](https://bun.sh/)
* **CSS Framework**: [Tailwind CSS v4](https://tailwindcss.com/)
* **Database**: MySQL / MariaDB
* **Database Migration & Seeding**: Drizzle ORM Migrator (`db-migrator-with-drizzle`) & Laravel Migrations
* **Libraries Tambahan**:
  * Preline UI (Komponen antarmuka)
  * Flatpickr (Pemilih tanggal)
  * Toastify-JS (Notifikasi interaktif)
  * Croppie & ViewerJS (Manipulasi & pratinjau gambar)

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
Pastikan perangkat Anda telah terinstal:
* **PHP >= 8.2** (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`, `gd`)
* **Composer**
* **Bun** atau **Node.js** (npm)
* **MySQL Server**

### 2. Kloning Repositori
```bash
git clone https://github.com/FaizinAjah/smartlogbook.git
cd smartlogbook
```

### 3. Instal Dependensi
```bash
# Instal dependensi PHP
composer install

# Instal dependensi Node/Vite frontend
bun install
# atau menggunakan npm:
# npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan kredensial database Anda:
```env
APP_NAME=SmartLogbook
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_starter_kit
DB_USERNAME=root
DB_PASSWORD=root
```
Generate App Key:
```bash
php artisan key:generate
```

### 5. Migrasi & Seeding Database
Jalankan migrasi database serta seeding menu sidebar dan akun default:
```bash
# Migrasi tabel Laravel
php artisan migrate

# Seeding menu sidebar & hak akses via migrator
cd db-migrator-with-drizzle
bun run src/seeds/index.ts
cd ..
```

### 6. Menjalankan Server Pengembangan
Jalankan backend Laravel dan Vite frontend:
```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Development Server
bun run dev -- --host localhost
# atau:
# npm run dev -- --host localhost

# Terminal 3 (Opsional) - Queue Worker untuk background jobs
php artisan queue:work --sleep=3 --tries=3
```

Aplikasi dapat diakses melalui browser di: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Kredensial Default Pengujian

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `admin@admin.com` | `password` |
| **Peserta Magang** | `adit@gmail.com` *(atau akun peserta terdaftar)* | `password` |
| **Pembimbing** | `ahmad.hidayat@smktelkom-mlg.sch.id` | `password` |

---

## 📂 Struktur Direktori Proyek

```plaintext
smartlogbook/
├── app/
│   ├── Constants/           # Konstanta peran, hak akses, status
│   ├── Http/Controllers/    # Controller modul Admin, LogBook, Auth
│   ├── Models/              # Model Eloquent (User, Institution, Intern, dll.)
│   └── Usecase/             # Layer logika bisnis & layanan data
├── config/                  # Konfigurasi sistem Laravel
├── database/                # Migrasi & pabrik data Laravel
├── db-migrator-with-drizzle/# Skrip migrasi & seeder TypeScript
├── dokumentasi_logbook/     # Dokumentasi alur & tangkapan layar sistem
├── public/                  # Aset publik terkompilasi
├── resources/
│   ├── css/                 # Berkas styling Tailwind CSS
│   ├── js/                  # Skrip JavaScript & komponen interaktif
│   └── views/               # Template Blade (Admin, LogBook, Layouts)
├── routes/                  # Definisi rute web & otentikasi
└── storage/                 # Berkas unggahan logbook & log sistem
```

---

## 📄 Lisensi

Proyek ini dikembangkan di bawah lisensi [MIT License](LICENSE).

---

<p align="center">
  Dibuat dengan ❤️ oleh <a href="https://github.com/FaizinAjah">FaizinAjah</a>
</p>
