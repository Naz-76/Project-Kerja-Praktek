# PORMA — Portal Magang, Kerja Praktik & PKL Diskominfo Kabupaten Garut
### *Sistem Manajemen Charter Slot Kuota, Verifikasi Berkas, Penempatan Peserta Digital & Integrasi REST API*

Aplikasi web terpadu berbasis **Laravel 11** untuk mendigitalisasi proses pendaftaran, pemantauan kuota (*charter slot*) real-time, verifikasi berkas administrasi, penugasan pembimbing lapangan, penerbitan surat balasan resmi, serta integrasi data pihak ketiga bagi peserta **Praktik Kerja Lapangan (PKL)**, **Kerja Praktik (KP)**, dan **Magang Mandiri** pada **Dinas Komunikasi dan Informatika Kabupaten Garut**.

---

## 🌟 Fitur Utama Sistem

### 1. 🌐 Portal Publik & Informasi Terbuka (`/` & `/informasi`)
* **Monitoring Kuota Real-Time (*Charter Slot*):**
  * Visualisasi *progress bar* kuota terpakai vs kuota total per bidang operasional Diskominfo.
  * Penghitungan sisa kuota dan persentase kapasitas secara otomatis.
* **Katalog Bidang & Peran Kerja:**
  * Deskripsi fokus kompetensi di 4 bidang: Aplikasi Informatika (Aptika), Informasi & Komunikasi Publik (IKP), Statistik & Persandian, serta Infrastruktur Telematika.
* **Alur & Tata Cara Pendaftaran:**
  * Panduan 5 tahapan pendaftaran dari pemilihan program, data tim, asal institusi, upload berkas, hingga tinjauan.
* **Profil Instansi & Struktur Organisasi (`/informasi`):**
  * Bagan interaktif Struktur Organisasi Diskominfo Garut, Visi & Misi, serta ringkasan unit kerja aktif.

---

### 2. 🔐 Autentikasi & Manajemen Akun
* **Single Sign-On (SSO) Google OAuth 2.0:**
  * Login instan bagi calon peserta menggunakan akun Google resmi.
  * Dilengkapi mekanisme **Demo Fallback** otomatis untuk kemudahan pengujian lokal.
* **Login Terproteksi Khusus Admin:**
  * Formulir login terenkripsi khusus staf admin/verifikator Kepegawaian Diskominfo dengan proteksi `AdminMiddleware`.
* **Pengelolaan Profil Pengguna (`/pendaftar/profil` & `/admin/profil`):**
  * Pembaruan nama lengkap, nomor WhatsApp aktif, pergantian kata sandi aman, serta fitur unggah dan hapus foto profil avatar kustom.

---

### 3. 📝 Modul Pendaftaran Peserta (`/pendaftar/pengajuan/baru`)
* **Form Single-Page Responsif & Mobile-Friendly:**
  * Pengisian data bertahap tanpa reload halaman dengan validasi instan.
* **Logika Kondisional Berdasarkan Status Pendaftar:**
  * **Siswa (SMK/SMA):** Otomatis membatasi program ke **PKL** dan memunculkan bagian input **Data Guru Pembimbing** (Nama Lengkap, No. WhatsApp, dan Email).
  * **Mahasiswa (Perguruan Tinggi):** Menampilkan opsi program **Kerja Praktik (KP)** dan **Magang Mandiri**.
* **Dukungan Pendaftaran Individu & Kelompok:**
  * Tambah/hapus anggota tim dinamis menggunakan JavaScript dengan penanda otomatis untuk Ketua Tim (*Leader*).
* **Upload Berkas Surat Pengantar Resmi:**
  * Validasi berkas PDF maksimal 5MB yang tersimpan aman di disk penyimpanan publik terenkripsi.
* **Pencegahan Double-Submit & Concurrency Lock:**
  * Pendaftar dikunci secara otomatis agar tidak dapat mengirim pengajuan baru jika masih memiliki pengajuan aktif (*Pending* atau *Approved*).

---

### 4. 📊 Dashboard & Pelacakan Status Pengajuan (`/pendaftar/dashboard`)
* **Live Stepper Tracking:** Status 3 tahap dinamis: *Surat Terkirim* → *Sedang Diverifikasi* → *Keputusan Akhir*.
* **Siklus Status Pendaftaran Lengkap:**
  * `Menunggu Verifikasi (Pending)`: Berkas dalam antrean peninjauan oleh admin.
  * `Diterima (Approved)`: Berkas lolos verifikasi, bidang definitif ditetapkan, dan pembimbing lapangan ditugaskan.
  * `Ditolak (Rejected)`: Berkas belum memenuhi syarat; pendaftar dapat melihat alasan penolakan dan mengajukan pendaftaran ulang.
  * `Selesai (Completed)`: Masa magang/PKL telah selesai dijalankan; peserta mendapat petunjuk penyerahan lembar nilai/administrasi ke kantor.
* **Informasi Pembimbing Lapangan & Kontak WhatsApp:**
  * Menampilkan nama, jabatan, serta link direct-chat WhatsApp pembimbing lapangan yang ditugaskan.
* **Unduh Surat Balasan Digital Resmi:**
  * Tautan unduh surat balasan resmi (PDF) yang diterbitkan oleh Diskominfo.
* **Blok Riwayat Pendaftaran:**
  * Rekam jejak seluruh permohonan yang pernah diajukan lengkap dengan tabel responsif berindikator geser (*horizontal scroll cues*).

---

### 5. 🛡️ Panel Admin Kepegawaian (`/admin/*`)
* **Sidebar Navigasi Modern (Desktop & Mobile):** Menu navigasi gelap elegan Diskominfo dengan drawer mobile terintegrasi.
* **Dashboard Analitik & Pemantauan Latar Belakang (*Silent Check*):**
  * Ringkasan metrik total pendaftaran, antrean verifikasi, peserta diterima, ditolak, dan selesai.
  * **Floating Notification Pill:** Notifikasi mengambang saat ada pengajuan baru masuk tanpa reload paksa browser.
  * Metrik demografi peserta dan beban kerja pembimbing lapangan (*Supervisor Workloads*).
* **Verifikasi Berkas & Penempatan Bidang (`/admin/verifikasi`):**
  * Tinjau berkas pendaftaran dan pratinjau dokumen PDF surat pengantar dengan tampilan responsif anti-overflow di layar seluler.
  * Form persetujuan: Alokasi bidang definitif, penugasan pembimbing lapangan, catatan arahan, dan unggah surat balasan.
  * **Fitur Surat Balasan Susulan:** Admin dapat mengunggah atau mengganti berkas Surat Balasan PDF resmi secara susulan kapan saja setelah pendaftar disetujui (misal: saat mahasiswa selesai mengurus surat rekomendasi Bakesbangpol).
  * Penolakan berkas dengan alasan tertulis terperinci.
  * Tombol konfirmasi program selesai (*Completed*) untuk membebaskan kuota kembali.
* **Modal Pop-Up Kustom (*Design Guidelines Compliant*):**
  * Seluruh aksi krusial (Tolak dan Selesai) menggunakan modal dialog kustom glassmorphism bertema gelap Diskominfo, menggantikan dialog bawaan browser (`confirm()`).
* **Manajemen Bidang & Kuota (`/admin/bidang-kuota`):**
  * Kelola nama bidang, uraian tugas, kapasitas total slot, dan penugasan pembimbing lapangan multi-entri.
  * Header bersih dan seragam sesuai standar antarmuka panel admin.
* **Rekapitulasi Laporan Terpadu (`/admin/laporan`):**
  * Filter multi-kategori (Semua, Mahasiswa, Siswa), filter rentang tanggal, filter status, dan fitur ekspor/cetak siap pakai.

---

### 6. 🔌 Integrasi REST API Publik (`/api/v1/*`)
* **Keamanan Otentikasi API Key:**
  * Dilindungi middleware `ValidateApiKey` via Header `X-API-KEY`, `Authorization: Bearer`, atau Query Parameter `api_key`.
  * Panel manajemen API Key di `/admin/api-integrasi`: Generate key acak kriptografis, status aktif/nonaktif (*toggle*), dan log pemakaian terakhir (*last used at*).
* **Daftar Endpoint API:**
  * `GET /api/v1/departments`: Mengambil daftar bidang, ketersediaan kuota triwulan aktif (`total`, `used`, `remaining`, `%`), dan daftar pembimbing.
  * `GET /api/v1/registrations`: Mengambil data pendaftar/peserta dengan parameter filter (`status`, `applicant_status`, `program_type`, `department_id`, `date_from`, `date_to`) beserta paginasi.
  * `GET /api/v1/statistics`: Mengambil statistik agregat pendaftaran dan utilisasi kuota.

---

### 7. ⚙️ Integritas Kuota & Layanan Terjadwal (*Backend Architecture*)
* **Pessimistic Locking (`QuotaService`):**
  * Membungkus transaksi dengan `lockForUpdate()` di tabel `slot_quotas` untuk mencegah *race condition* atau kuota bocor saat diakses bersamaan.
* **Composite Unique Index:**
  * Database mengunci kombinasi `['department_id', 'period']` sehingga tidak ada duplikasi data kuota per periode.
* **Siklus Otomatis Kuota:**
  * Berkurang saat *Approve*, kembali saat *Reject*, dan dibebaskan saat *Complete*.
* **Command Sinkronisasi Otomatis:**
  * Perintah `php artisan porma:sync-quota` terjadwal harian untuk menyapu pendaftar yang masa magangnya sudah lewat agar statusnya otomatis beralih menjadi *Completed* dan kuota terlepas.

---

## 🛠️ Prasyarat Lingkungan (*Prerequisites*)

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
Salin konfigurasi dari `.env.example`:
```bash
# Windows (PowerShell):
copy .env.example .env

# Linux / macOS:
cp .env.example .env
```

Pastikan konfigurasi database pada `.env` telah sesuai:
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

QUEUE_CONNECTION=database
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

### 6. Jalankan Server Aplikasi & Queue Worker
Buka dua jendela terminal terpisah:

**Terminal 1 — Server Web:**
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **`http://localhost:8000`**

**Terminal 2 — Antrean Email & Notifikasi (Queue Worker):**
```bash
php artisan queue:work --tries=3
```

---

## 🔐 Kredensial Akun Pengujian (*Demo Accounts*)

| Peran (Role) | Email | Password | Hak Akses & Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin Kepegawaian** | `admin@garutkab.go.id` | `password123` | Akses penuh Panel Admin (`/admin/dashboard`), Verifikasi, Kelola Kuota, Laporan, & Integrasi API. |
| **Pendaftar (Mahasiswa Demo)** | `pendaftar@gmail.com` | `password123` | Akun pendaftar terdaftar dengan riwayat pengajuan aktif. |
| **Pendaftar (Google SSO Demo)** | `google_user@gmail.com` | *(Login Google)* | Klik tombol *"Masuk dengan Google"* pada halaman login untuk simulasi login instan. |

---

## 🧪 Pengujian Otomatis (*Automated Testing Suite*)

Sistem dilengkapi rangkaian pengujian otomatis unit & feature test:
```bash
php artisan test
```
**Cakupan Pengujian:**
* Integritas kuota (*Quota Service*, alokasi, pelepasan, rollback, over-quota exception).
* Proteksi konkurensi & *atomic locking*.
* Validasi input pendaftar (NISN/NIM minimal 5 digit).
* Otorisasi dan validasi middleware REST API Key.
* Endpoint pengecekan pembaruan data latar belakang (*silent check updates*).

---

## ⚙️ Panduan Konfigurasi Integrasi Tambahan

### A. Konfigurasi Google OAuth 2.0 (Masuk dengan Google)
1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat proyek baru dan masuk ke menu **APIs & Services** → **Credentials**.
3. Buat **OAuth client ID** bertipe *Web application*.
4. Masukkan URL Callback: `http://localhost:8000/auth/google/callback`.
5. Salin *Client ID* dan *Client Secret* ke file `.env`:
   ```env
   GOOGLE_CLIENT_ID="xxxx-xxxx.apps.googleusercontent.com"
   GOOGLE_CLIENT_SECRET="GOCSPX-xxxx"
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   ```

### B. Konfigurasi SMTP Gmail (Notifikasi Email Real)
Untuk mengaktifkan pengiriman email notifikasi status pendaftaran ke email pendaftar:
1. Aktifkan **Verifikasi 2 Langkah** pada akun Google pengirim.
2. Buat sandi aplikasi di [Google App Passwords](https://myaccount.google.com/apppasswords).
3. Salin 16 karakter password aplikasi ke `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=email_dinas@garutkab.go.id
   MAIL_PASSWORD="xxxx xxxx xxxx xxxx"
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=email_dinas@garutkab.go.id
   MAIL_FROM_NAME="Portal Magang Diskominfo Garut"
   ```

---

## 📁 Struktur Direktori Penting

```
Project-Kerja-Praktek/
├── app/
│   ├── Console/Commands/
│   │   └── SyncQuotaCommand.php          # Command: porma:sync-quota (pelepasan kuota selesai)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # Admin: Dashboard, Verifikasi, Kuota, Laporan, API, Profil
│   │   │   ├── Api/                      # REST API: PublicDataApiController
│   │   │   ├── Applicant/                # Pendaftar: Form Pendaftaran, Dashboard, Profil
│   │   │   ├── Auth/                     # AuthController (Google SSO & Admin Login)
│   │   │   └── PublicController.php      # Beranda Publik & Informasi Instansi
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php       # Proteksi Hak Akses Administrator
│   │       └── ValidateApiKey.php        # Proteksi Keamanan REST API Key
│   ├── Models/                           # Eloquent: Registration, Department, SlotQuota, ApiKey, dsb.
│   └── Services/
│       ├── QuotaService.php              # Logika Pessimistic Locking & Alokasi Kuota
│       └── OtpService.php                # Layanan Pembuatan & Verifikasi Kode OTP
├── database/
│   ├── migrations/                       # Skema Tabel Basis Data Terstruktur
│   └── seeders/DatabaseSeeder.php        # Seeder Akun, Bidang & Variasi Pendaftar
├── resources/views/
│   ├── admin/                            # Panel Admin: Dashboard, Verifikasi, Kuota, Laporan, API
│   ├── applicant/                        # Antarmuka Pendaftar: Dashboard, Form Pendaftaran, Profil
│   ├── auth/                             # Halaman Login & Registrasi
│   ├── layouts/                          # Layout: app.blade.php & admin.blade.php (Custom Modal)
│   └── public/                           # Halaman Beranda Publik & Profil Instansi (/informasi)
├── routes/
│   ├── api.php                           # Definisi Rute REST API v1
│   ├── console.php                       # Definisi Task Scheduling Artisan
│   └── web.php                           # Definisi Rute Web Terpadu
└── tests/Feature/                        # Automated Tests: QuotaServiceTest & EvaluationTest
```

---

&copy; 2026 **Dinas Komunikasi dan Informatika Kabupaten Garut** — Hak Cipta Dilindungi.
