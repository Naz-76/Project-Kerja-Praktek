# PORMA — Portal Magang, Kerja Praktik & PKL Diskominfo Kabupaten Garut
### *Sistem Manajemen Charter Slot Kuota, Verifikasi Berkas & Penempatan Peserta Digital*

Aplikasi web terpadu berbasis **Laravel 11** untuk mendigitalisasi proses pendaftaran, pemantauan kuota (*charter slot*) real-time, verifikasi berkas administrasi, dan penugasan pembimbing lapangan bagi peserta **Praktik Kerja Lapangan (PKL)**, **Kerja Praktik (KP)**, dan **Magang Mandiri** pada **Dinas Komunikasi dan Informatika Kabupaten Garut**.

---

## 🌟 Fitur Utama Sistem

### 1. 🌐 Portal Publik & Informasi Terbuka (`/` & `/informasi`)
* **Monitoring Kuota Real-Time (*Charter Slot*):**
  * Visualisasi *progress bar* kuota terpakai vs kuota total per bidang operasional Diskominfo.
  * Estimasi rentang tanggal aktif pendaftar termuda hingga tertua.
* **Katalog Bidang & Tugas Unit Kerja:**
  * Deskripsi peran dan fokus kompetensi di 4 bidang: Aplikasi Informatika (Aptika), Informasi & Komunikasi Publik (IKP), Statistik & Persandian, serta Infrastruktur Telematika.
* **Alur & Tata Cara Pendaftaran:**
  * Panduan 5 tahapan pendaftaran dari pemilihan program, data tim, asal institusi, upload berkas, hingga tinjauan.
* **Profil Instansi & Struktur Organisasi (`/informasi`):**
  * Bagan interaktif Struktur Organisasi Diskominfo Garut, Visi & Misi, serta ringkasan unit kerja aktif.
* **Footer Informasi Resmi:**
  * Kontak resmi, alamat kantor Diskominfo Garut, dan tautan saluran komunikasi.

---

### 2. 🔐 Autentikasi & Manajemen Akun
* **Single Sign-On (SSO) Google OAuth 2.0:**
  * Login cepat bagi calon peserta menggunakan akun Google resmi.
  * Dilengkapi mekanisme **Demo Fallback** otomatis jika kredensial Google API belum dipasang di lingkungan lokal.
* **Login Khusus Admin Kepegawaian:**
  * Formulir login terenkripsi khusus staf admin/verifikator Diskominfo dengan proteksi `AdminMiddleware`.
* **Pengelolaan Profil Pengguna (`/pendaftar/profil` & `/admin/profil`):**
  * Pembaruan nama lengkap, nomor WhatsApp aktif, pergantian kata sandi aman, serta fitur unggah dan hapus foto profil avatar kustom.
  * Tombol keluar sistem (*Logout*) terintegrasi di dalam halaman profil untuk kemudahan navigasi.

---

### 3. 📝 Modul Formulir Pendaftaran Pendaftar (`/pendaftar/pengajuan/baru`)
* **Form Single-Page Responsif:** Pengisian data tanpa reload halaman dengan validasi instan klien dan server.
* **Logika Kondisional Status Pendaftar:**
  * **Siswa (SMK/SMA):** Otomatis membatasi program ke **PKL** dan memunculkan bagian input **Data Guru Pembimbing** (Nama Lengkap, No. WhatsApp/Telepon, dan Email).
  * **Mahasiswa (Perguruan Tinggi):** Menampilkan opsi program **Kerja Praktik (KP)** dan **Magang Mandiri**.
* **Dukungan Pendaftaran Individu & Kelompok:**
  * Tambah/hapus anggota tim dinamis menggunakan JavaScript dengan penanda otomatis untuk Ketua Tim (*Leader*).
* **Pemilihan Preferensi Bidang:** Pendaftar dapat memilih minat bidang awal dengan catatan bahwa penempatan definitif ditentukan oleh admin.
* **Upload Berkas Surat Pengantar Resmi:**
  * Validasi berkas PDF maksimal 5MB yang tersimpan aman di disk penyimpanan publik terenkripsi.
* **Tinjauan Pengajuan (*Review Step*):** Modal ringkasan data sebelum data final dikirimkan ke database.
* **Pencegahan Double-Submit:** Pengguna dikunci secara otomatis agar tidak dapat mengirim pengajuan baru jika masih memiliki pengajuan berstatus *Menunggu Verifikasi (Pending)* atau *Diterima (Approved)*.

---

### 4. 📊 Dashboard & Pelacakan Status Pengajuan (`/pendaftar/dashboard`)
* **Live Stepper Tracking:** Status 3 tahap dinamis: *Surat Terkirim* → *Sedang Diverifikasi* → *Keputusan Akhir*.
* **Informasi Pembimbing Lapangan:** Jika pengajuan disetujui, peserta dapat melihat nama lengkap dan jabatan Pembimbing Lapangan resmi yang ditugaskan.
* **Transparansi Catatan Admin:**
  * Pesan arahan persetujuan (contoh: petunjuk izin ke Kesbangpol atau penyerahan berkas fisik).
  * Alasan penolakan tertulis secara rinci jika berkas ditolak.
* **Unduh Surat Balasan Digital:** Tombol unduh dokumen surat balasan resmi (PDF) yang diterbitkan oleh Diskominfo.

---

### 5. 🛡️ Panel Admin Kepegawaian (`/admin/*`)
* **Sidebar Navigasi Kiri (Mobile-Friendly):** Menu navigasi di sisi kiri layar dengan dukungan *drawer* hamburger menu di layar ponsel.
* **Dashboard Analitik Terpadu:**
  * Ringkasan metrik total pendaftaran, antrean verifikasi, peserta diterima, dan pengajuan tertolak.
  * Metrik demografi peserta (Siswa vs Mahasiswa) serta beban bimbingan staf (*Supervisor Workloads*).
  * *Quick-View Modal Drilldown* untuk melihat rincian pendaftar tanpa meninggalkan dashboard.
* **Verifikasi Berkas & Penempatan Bidang:**
  * Pratinjau dokumen PDF surat pengantar langsung di browser.
  * Penetapan bidang definitif dengan alokasi kuota otomatis.
  * Form penugasan Pembimbing Lapangan dan catatan arahan penerimaan.
  * Input alasan penolakan yang komprehensif.
* **Pessimistic Quota Locking (`QuotaService`):**
  * Pengurangan kuota proporsional berdasarkan jumlah anggota tim menggunakan transaksi `lockForUpdate()`.
  * Restorasi/rollback kuota otomatis jika status dibatalkan atau ditolak.
  * Standardisasi periode kuota kuartalan aktif (`YYYY-Q#`) yang konsisten.
* **Pembersihan Berkas Usang (*Storage Cleanup*):**
  * Penghapusan otomatis file PDF surat balasan lama saat admin mengunggah versi pembaharuan.
* **Manajemen Bidang & Kuota (`/admin/bidang-kuota`):**
  * Kelola nama bidang, uraian tugas, dan kapasitas total slot.
  * Proteksi *Cascade Delete*: Menolak penghapusan bidang jika masih terdapat pendaftar aktif.
* **Laporan & Rekapitulasi Terpadu (`/admin/laporan`):**
  * Rekapitulasi data berbasis 4 tab (Pendaftar, Program, Bidang, Status) dengan filter dan cetak.

---

## 🛠️ Prasyarat Perangkat & Lingkungan (*Prerequisites*)

1. **PHP:** Versi `>= 8.2` (ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `curl`, `fileinfo`, `gd`).
2. **Composer:** Versi `>= 2.x`.
3. **Node.js & NPM:** Versi `>= 18.x` atau `>= 20.x`.
4. **Database:** **MySQL** atau **MariaDB** (via Laragon, XAMPP, atau MySQL Server).

---

## 🚀 Panduan Instalasi & Menjalankan Project

### 1. Masuk ke Direktori Project
```bash
cd "Project-Kerja-Praktek"
```

### 2. Install Dependensi PHP & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi File Environment (`.env`)
Salin konfigurasi dari `.env.example` jika belum memiliki file `.env`:
```bash
# Windows (PowerShell):
copy .env.example .env

# Linux / Git Bash:
cp .env.example .env
```

Pastikan konfigurasi database pada `.env` telah disesuaikan:
```env
APP_NAME="Charter Slot Diskominfo Garut"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=charter_slot_diskominfo
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key, Migrasi & Seed Database
```bash
# Generate Enkripsi Aplikasi
php artisan key:generate

# Migrasi Tabel dan Seeder Data Awal (Dummy Lengkap)
php artisan migrate:fresh --seed
```

### 5. Buat Symbolic Link Storage (Wajib untuk Berkas PDF & Avatar)
```bash
php artisan storage:link
```

### 6. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser: **`http://localhost:8000`**

---

## 🔐 Kredensial Akun Pengujian (*Demo Accounts*)

| Peran (Role) | Email | Password | Hak Akses & Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin Kepegawaian** | `admin@garutkab.go.id` | `password123` | Akses penuh Panel Admin (`/admin/dashboard`), Verifikasi, Kelola Kuota, & Laporan. |
| **Pendaftar (Mahasiswa Demo)** | `pendaftar@gmail.com` | `password123` | Akun pendaftar terdaftar dengan pengajuan aktif. |
| **Pendaftar (Google SSO Demo)** | `google_user@gmail.com` | *(Login Google)* | Klik tombol *"Masuk dengan Google"* pada halaman login untuk login instan. |

---

## ⚙️ Panduan Konfigurasi Integrasi Produksi

### A. Konfigurasi Google OAuth 2.0 (Masuk dengan Google)
Untuk menghubungkan autentikasi Google akun asli di lingkungan produksi:
1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat proyek baru atau pilih proyek yang ada.
3. Masuk ke menu **APIs & Services** → **OAuth consent screen**:
   * Pilih *User Type*: **External**.
   * Isi nama aplikasi (*Charter Slot Diskominfo Garut*) dan email kontak pengembang.
4. Masuk ke menu **Credentials** → **Create Credentials** → **OAuth client ID**:
   * *Application type*: **Web application**.
   * *Authorized redirect URIs*: Tambahkan `http://localhost:8000/auth/google/callback` (atau domain produksi Anda).
5. Salin *Client ID* dan *Client Secret* ke dalam file `.env`:
   ```env
   GOOGLE_CLIENT_ID="xxxx-xxxx.apps.googleusercontent.com"
   GOOGLE_CLIENT_SECRET="GOCSPX-xxxx"
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   ```

### B. Konfigurasi SMTP Email (Gmail App Password)
Untuk mengaktifkan fitur pengiriman email notifikasi dan kode OTP ke alamat Gmail pendaftar:
1. Buka akun Google pengirim (misal: email kedinasan) dan pastikan **Verifikasi 2 Langkah** telah aktif.
2. Masuk ke [Google Security - App Passwords](https://myaccount.google.com/apppasswords).
3. Buat nama sandi aplikasi baru (contoh: `Laravel Mailer Diskominfo`).
4. Salin kode 16-karakter yang dihasilkan ke `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=diskominfo@garutkab.go.id
   MAIL_PASSWORD="abcd efgh ijkl mnop"
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=diskominfo@garutkab.go.id
   MAIL_FROM_NAME="Portal Magang Diskominfo Garut"
   ```

---

## 📁 Struktur Direktori Penting

```
sistem-pendaftaran-diskominfo-V2.2/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                # Controller Admin: Verifikasi, Kuota, Laporan, Profil
│   │   │   ├── Applicant/            # Controller Pendaftar: Form Pendaftaran, Dashboard, Profil
│   │   │   ├── Auth/                 # AuthController (Google OAuth & Admin Login)
│   │   │   └── PublicController.php  # Beranda Publik & Informasi Instansi
│   │   └── Middleware/
│   │       └── AdminMiddleware.php   # Proteksi Hak Akses Administrator
│   ├── Models/                       # Eloquent: Registration, Department, SlotQuota, User, dsb.
│   └── Services/
│       ├── QuotaService.php          # Logika Pessimistic Locking & Alokasi Kuota
│       └── OtpService.php            # Layanan Pembuatan & Verifikasi Kode OTP
├── database/
│   ├── migrations/                   # Skema Tabel Basis Data Terstruktur
│   └── seeders/DatabaseSeeder.php    # Seeder Akun, Bidang & Variasi Pendaftar
├── resources/views/
│   ├── admin/                        # Antarmuka Panel Admin (Dashboard, Verifikasi, Laporan)
│   ├── applicant/                    # Antarmuka Pendaftar (Dashboard, Form Pendaftaran, Profil)
│   ├── auth/                         # Halaman Login
│   ├── layouts/                      # Layout Master: app.blade.php & admin.blade.php
│   └── public/                       # Halaman Beranda Publik & Profil Instansi (/informasi)
└── routes/web.php                    # Definisi Rute Terpadu
```

---

&copy; 2026 **Dinas Komunikasi dan Informatika Kabupaten Garut** — Hak Cipta Dilindungi.
