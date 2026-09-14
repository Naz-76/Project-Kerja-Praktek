# 🎨 Panduan Desain & Style Guide Sistem Charter Slot Diskominfo Garut

Dokumen ini merupakan panduan resmi (*Design Guidelines & Style Guide*) untuk implementasi antarmuka pengguna (UI/UX) pada sistem pendaftaran Magang / KP / PKL Diskominfo Kabupaten Garut, yang diadaptasi langsung dari berkas **Design Figma** (`D:\Kerja Praktek Bossku\Asset Figma\Design Figma`).

---

## 🔤 1. Tipografi (Typography)

* **Font Family Utama:** `Poppins`, sans-serif (Google Fonts)
* **Font Fallback:** `Inter`, `sans-serif`
* **Import URL:**
  ```html
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;600&display=swap" rel="stylesheet">
  ```

### Hirarki Teks & Heading

| Tingkatan | Ukuran / Kelas Tailwind | Weight | Keterangan Penggunaan |
| :--- | :--- | :--- | :--- |
| **H1 Header 1** | `text-3xl md:text-5xl` | `font-extrabold` (800) | Judul utama Hero Banner (*"Bangun Karier Digitalmu..."*), Judul Halaman Publik (*"Profil Instansi"*), Banner Dashboard (*"Selamat Datang..."*). |
| **H2 Header 2** | `text-2xl md:text-3xl` | `font-bold` (700) | Judul Section (*"TATA CARA PENDAFTARAN"*, *"Persyaratan Berkas"*). |
| **H3 Header 3** | `text-lg md:text-xl` | `font-bold` (700) | Judul Sub-bagian (*"Bagan Struktur Organisasi"*, *"Visi & Misi"*). |
| **H4 Header 4** | `text-base md:text-lg` | `font-semibold` (600) | Judul Kartu Divisi/Bidang, Judul Langkah Tracking Pendaftaran. |
| **H5 Header 5** | `text-sm md:text-base` | `font-semibold` (600) | Judul Field/Section Form Pendaftaran, Label Input. |
| **Body / Regular** | `text-xs sm:text-sm` | `font-normal` (400) s/d `font-medium` (500) | Teks konten, penjelasan bidang, deskripsi persyaratan, placeholder form. |
| **Caption / Meta** | `text-[11px] sm:text-xs` | `font-medium` (500) | Tanggal kuota, status kuota terpakai, instruksi kecil. |

---

## 🎨 2. Palet Warna Resmi (Color Palette)

```
┌─────────────┐   ┌─────────────┐   ┌─────────────┐   ┌─────────────┐
│   #FFFFFF   │   │   #014495   │   │   #0B6FBB   │   │   #2F90E1   │
│ Pure White  │   │ Deep Navy   │   │ Brand Blue  │   │ Light Blue  │
└─────────────┘   └─────────────┘   └─────────────┘   └─────────────┘
```

| Nama Warna | Hex Code | Penggunaan di Antarmuka |
| :--- | :--- | :--- |
| **White** | `#FFFFFF` | Background halaman, kartu putih, teks kontras di atas tombol gelap. |
| **Primary Navy** | `#014495` | Tombol CTA utama (*Daftar*, *Tinjau Pengajuan*, *Simpan*), Header Step Card (1-5), Border Input Aktif. |
| **Secondary Brand** | `#0B6FBB` | Tombol navigasi (*Masuk*, *Dashboard*), Badge aktif, Tab terpilih, Sub-header banner. |
| **Accent Light Blue**| `#2F90E1` | Latar Hero Banner, Badge nomor bulat (1, 2, 3), Progress bar kuota aktif, Layer dekoratif login. |
| **Neutral Slate** | `#F8FAFC` - `#0F172A` | Background secondary, teks heading gelap (`#0a192f`), border card (`#E2E8F0`). |
| **Danger / Logout** | `#8B0000` / `#990000` | Tombol *"Keluar"* (Logout) dan tombol *"Urungkan"* (Batal edit profil). |
| **Success / Status** | `#00E676` / `#059669` | Ikon folder berkas, indikator status aktif. |

---

## 🧩 3. Komponen & Karakteristik Desain (UI Components)

### A. Input Box & Form Control
- **Border Style:** Border tegas berwarna biru khas (`border-[#014495]` atau `border-[#0B6FBB]`) dengan sudut membulat `rounded-xl` atau `rounded-2xl`.
- **Focus Ring:** `focus:ring-2 focus:ring-[#2F90E1] focus:border-[#014495] outline-none`.
- **Background:** Putih bersih (`bg-white`) dengan teks isi `#0f172a`.

### B. Tombol Aksi (Buttons)
- **Primary CTA (`#014495`):** `bg-[#014495] hover:bg-[#002f6c] text-white font-bold py-3 px-8 rounded-xl shadow-md transition-all active:scale-[0.98]`.
- **Secondary / Info (`#0B6FBB`):** `bg-[#0B6FBB] hover:bg-[#095996] text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm`.
- **Danger Button (`#8B0000`):** `bg-[#8B0000] hover:bg-[#6b0000] text-white font-bold py-2.5 px-6 rounded-xl shadow-md`.

### C. Kartu Konten (Cards & Containers)
- **Struktur:** Background putih `bg-white`, border lembut `border border-slate-200`, sudut rounded `rounded-2xl` atau `rounded-3xl`, shadow melayang halus `shadow-xl` / `shadow-2xl`.
- **Layer Dekoratif (Login Page):** Tiga lapis persegi bersudut rounded di sudut kiri atas dan kanan bawah dengan sudut rotasi dinamis (`-rotate-3` & `rotate-3`) menggunakan gradasi warna `#014495`, `#0B6FBB`, dan `#2F90E1`.

---

## 🛡️ 4. Integritas Logika Bisnis (Business Logic Invariants)

Selama penerapan desain dan perubahan UI/UX, **aturan bisnis berikut TIDAK BOLEH berubah atau terganggu**:

1. 🔒 **Pessimistic Quota Locking:**
   * Alokasi kuota tetap diproses melalui `QuotaService` dengan mekanisme transaksi `lockForUpdate()` untuk mencegah *race condition*.
   * Pengurangan kuota proporsional berdasarkan jumlah peserta tim (`participant_count`).

2. 👥 **Logika Kondisional Siswa vs Mahasiswa:**
   * Pilihan status **Siswa** otomatis membatasi program ke **PKL** dan memunculkan input **Data Guru Pembimbing** (Nama, WA/Telepon, Email).
   * Pilihan status **Mahasiswa** menampilkan program **Kerja Praktik (KP)** dan **Magang Mandiri**.

3. 👥 **Dukungan Kelompok & Multi-Peserta:**
   * Dynamic add/remove anggota tim tetap menyimpan `is_leader` untuk pemohon utama dan mencatat seluruh NIS/NIM anggota secara utuh.

4. 📄 **Upload & Validasi Berkas:**
   * Validasi ketat file PDF maksimal 5MB (untuk surat pengantar) dan 10MB (untuk proposal).
   * File disimpan aman di storage Laravel dengan symbolic link `storage/`.

5. 🔐 **Role & Autentikasi:**
   * Admin memiliki rute terproteksi middleware `['auth', 'admin']` dengan sidebar khusus.
   * Pendaftar masuk melalui akun Google (OAuth) / demo mode dan dialihkan ke `/pendaftar/dashboard`.

6. 🔁 **Restorasi Kuota Otomatis:**
   * Jika admin membatalkan persetujuan atau menolak pengajuan yang sebelumnya sudah disetujui, kuota bidang terkait dikembalikan otomatis ke database.

---

## 📋 5. Daftar Halaman & Status Penerapan Desain

| Halaman | Rute Web | File Blade Terkait | Referensi Figma | Status |
| :--- | :--- | :--- | :--- | :--- |
| **Beranda Publik** | `/` | `resources/views/public/index.blade.php` | `Beranda Publik.png` | ✅ Selesai |
| **Tentang Kami / Profil** | `/informasi` | `resources/views/public/info.blade.php` | `Tentang Kami.png` | ✅ Selesai |
| **Login Pengguna & Admin**| `/login` | `resources/views/auth/login.blade.php` | `Log in Form.png` | ✅ Selesai |
| **Dashboard Pendaftar** | `/pendaftar/dashboard` | `resources/views/applicant/dashboard.blade.php` | `Beranda Pengguna.png` & `2.png` | ✅ Selesai |
| **Form Pendaftaran** | `/pendaftar/pengajuan/baru` | `resources/views/applicant/registration/create.blade.php` | `form-pengajuan-pendaftaran.png` | ✅ Selesai |
| **Profil & Edit Profil** | `/pendaftar/profil` | `resources/views/applicant/profile.blade.php` | `Profile Pengguna.png` & `Card Edit Profile.png` | ✅ Selesai |
| **Layout Utama & Drawer** | Global | `resources/views/layouts/app.blade.php` | `Style.png` & Header/Footer | ✅ Selesai |
| **Admin Dashboard** | `/admin/dashboard` | `resources/views/admin/dashboard.blade.php` | Style Token & Palette | ✅ Selesai |
| **Verifikasi & Penempatan** | `/admin/verifikasi` | `resources/views/admin/verification/index.blade.php` & `show.blade.php` | Style Token & Palette | ✅ Selesai |
| **Manajemen Bidang & Kuota**| `/admin/departments`| `resources/views/admin/departments/index.blade.php` | Style Token & Palette | ✅ Selesai |
| **Surat Balasan Manual** | `/admin/reply-letters` | `resources/views/admin/reply-letters/index.blade.php` | Style Token & Palette | ✅ Selesai |
| **Laporan & Rekapitulasi** | `/admin/reports` | `resources/views/admin/reports/index.blade.php` | Style Token & Palette | ✅ Selesai |
| **Profil Admin** | `/admin/profil` | `resources/views/admin/profile.blade.php` | Style Token & Palette | ✅ Selesai |

---
&copy; 2026 **Dinas Komunikasi dan Informatika Kabupaten Garut**
