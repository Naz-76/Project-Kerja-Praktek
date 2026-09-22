# Dokumentasi Rekapitulasi Perubahan Sistem
**Portal Magang & PKL (PORMA) — Diskominfo Kabupaten Garut**
*Tanggal Pembaruan: 18 September 2026*

---

## 📌 Ringkasan Eksekutif
Dokumentasi ini merangkum seluruh pembaruan antarmuka (UI/UX), peningkatan responsivitas perangkat seluler (*mobile-friendly*), implementasi modal pop-up kustom berbasis *Design Guidelines*, eliminasi *hard-reload* otomatis, serta penguatan logika bisnis kuota dan backend yang telah diterapkan pada aplikasi.

---

## 1. 📱 Responsivitas Mobile & Tampilan Verifikasi Berkas

### A. Perbaikan Kartu Dokumen & Tombol "Lihat PDF" (Anti-Overflow)
- **Masalah Sebelumnya:** Pada pengujian di layar ponsel pintar (lebar layar $\le 390\text{px}$), nama berkas yang panjang dan tanpa spasi (contoh: `Surat_Pengantar_Universitas_Padjadjaran_(UNPAD).pdf`) memaksa kontainer teks melebar. Akibatnya, tombol **"Lihat PDF"** terdorong keluar dari garis tepi kartu (*overflow*).
- **Perubahan yang Diterapkan (`resources/views/admin/verification/show.blade.php`):**
  - Mengubah susunan tata letak dari flex baris statis menjadi **responsif adaptif** (`flex flex-col sm:flex-row sm:items-center justify-between gap-3.5`).
  - **Di Layar HP ($< 640\text{px}$):** Kartu berkas bertransisi rapi menjadi vertikal dengan tombol "Lihat PDF" membentang penuh (`w-full`), sangat nyaman ditekan jari (*touch-friendly*), dan dijamin 100% berada di dalam batas kartu.
  - **Di Layar Tablet / Desktop ($\ge 640\text{px}$):** Tetap berjejer horizontal elegan dengan tombol di sisi kanan (`sm:w-auto`).
  - Menambahkan utilitas `min-w-0`, `flex-1`, dan `break-all` agar nama file dokumen panjang terpotong rapi dengan *text wrapping* tanpa merusak batas kontainer.
  - Menambahkan badge ikon PDF merah (`fa-file-pdf`) dan efek *micro-interaction hover*.

### B. Penyempurnaan Kartu Surat Balasan Resmi
- Dibuat senada dengan kartu dokumen di atasnya: mendukung susunan adaptif mobile (`flex-col sm:flex-row`), teks info terstruktur rapi, dan tombol unduh yang responsif.

### C. Optimasi Kontainer & Tipografi Mobile
- Padding kontainer utama disesuaikan menjadi `p-4 sm:p-8 lg:p-10` agar lebih proporsional di layar seluler.
- Nama instansi/kampus yang panjang ditambahkan `break-words` untuk mencegah teks terpotong di tepi layar.

---

## 2. 🎨 Modal Pop-up Kustom (Design Guidelines Compliant)

### A. Penggantian Dialog Bawaan Browser (`confirm()`)
- **Masalah Sebelumnya:** Aksi penolakan dan penyelesaian pengajuan masih menggunakan dialog standar bawaan browser (`127.0.0.1:8000 says: Apakah Anda yakin...`) yang kaku dan tidak selaras dengan identitas visual portal.
- **Perubahan yang Diterapkan (`resources/views/layouts/admin.blade.php`):**
  - Dibuat komponen modal konfirmasi kustom global (`#customConfirmModal`) dengan tema gelap elegan Diskominfo:
    - *Backdrop:* `bg-slate-950/80` dengan efek `backdrop-blur-sm`.
    - *Kartu Modal:* `bg-slate-900 border border-slate-700/80 rounded-3xl shadow-2xl`.
    - *Animasi:* Transisi mikro `scale-95 opacity-0` $\rightarrow$ `scale-100 opacity-100`.
    - *Aksesibilitas:* Menutup otomatis jika tombol **Batal** ditekan atau area luar kartu diklik.

### B. Integrasi pada Form Aksi (`resources/views/admin/verification/show.blade.php`)
1. **Konfirmasi Tolak Pengajuan:**
   - Ikon peringatan rose/merah (`fa-solid fa-triangle-exclamation`).
   - Penegasan teks bahwa status akan menjadi **DITOLAK** dan kuota bidang terkait otomatis dikembalikan.
   - Tombol eksekusi merah Diskominfo (`bg-[#8B0000] hover:bg-[#6b0000]`).
   - Dilengkapi *native form validation check* (`form.reportValidity()`) sehingga bila alasan tolak belum diisi, form langsung memberi tahu pengguna sebelum modal muncul.
2. **Konfirmasi Tandai Selesai Program:**
   - Ikon bendera program selesai (`fa-solid fa-flag-checkered`).
   - Penegasan status **SELESAI** dan pelepasan kuota secara otomatis.
   - Tombol eksekusi bertema biru langit (`bg-sky-600 hover:bg-sky-700`).

---

## 3. ⚡ Stabilitas Layar & Notifikasi Latar Belakang (Tanpa Auto-Reload Paksa)

### A. Eliminasi Auto-Refresh Paksa
- **Masalah Sebelumnya:** Halaman web melakukan reload otomatis setiap selang waktu tertentu (`setInterval 60 detik`) atau saat pengguna berpindah tab (`visibilitychange`), menyebabkan layar berkedip, posisi scroll melompat ke atas, dan risiko kehilangan ketikan data pada form yang sedang diisi.
- **Solusi:** Seluruh skrip reload paksa di `resources/views/layouts/app.blade.php` dan `resources/views/layouts/admin.blade.php` telah dinonaktifkan.

### B. Indikator Notifikasi Modern (*Silent Polling*)
- **Endpoint Silent Check:** Disediakan route `GET /admin/check-updates` pada `app/Http/Controllers/Admin/AdminDashboardController.php` yang memeriksa pengajuan baru di latar belakang tanpa me-refresh browser.
- **Floating Notification Pill:** Jika ada berkas baru masuk dari pemohon, muncul lencana mengambang di pojok kanan atas panel admin:
  ```
  🔔 Ada pengajuan pendaftaran baru masuk!
  [Muat Ulang]  [✕]
  ```
- Admin memiliki kendali penuh untuk menekan **Muat Ulang** saat pekerjaan selesai atau menutup lencana tanpa terganggu.

---

## 4. 📋 Dashboard Pengguna & Alur Pendaftaran

### A. Visualisasi Alur Status & Tahapan
- Mempertahankan alur *stepper* verifikasi pendaftaran asli:
  - `Tahap 1: Verifikasi Berkas`
  - `Tahap 2: Wawancara / Penempatan Bidang`
  - `Tahap 3: Penerbitan Surat Keputusan / Balasan`
- Penempatan tombol **Daftar Ulang** dipisahkan secara rapi (tidak menempel di dalam blok notifikasi penolakan).
- Blok riwayat pendaftaran ditambahkan untuk memudahkan pemohon melihat histori pengajuan sebelumnya.

### B. Indikator Visual Tabel Scrollable
- Pada tabel data panjang (seperti daftar anggota tim dan tabel kuota), ditambahkan petunjuk visual *horizontal scroll cues* agar pengguna di layar seluler langsung mengetahui bahwa tabel dapat digeser ke samping.

---

## 5. 🛡️ Keutuhan Backend, Kuota & Integritas Data

1. **Sinkronisasi Kuota Otomatis:**
   - **Penerimaan (Approved):** Kuota bidang terpakai bertambah, sisa kuota berkurang.
   - **Penolakan (Rejected):** Kuota bidang yang sebelumnya teralokasi langsung dikembalikan secara otomatis.
   - **Penyelesaian (Completed):** Kuota dibebaskan kembali untuk periode selanjutnya.
2. **Atomic Lock & Pencegahan Race Condition:**
   - Menggunakan locking database untuk mencegah bentrok pendaftaran bersamaan saat kuota tersisa 1 slot.
3. **Integrasi REST API & API Key Management:**
   - Endpoint terproteksi middleware API Key untuk integrasi sistem eksternal.

---

## 6. 🧪 Hasil Pengujian & Verifikasi (Automated Tests)

Pengujian otomatis dijalankan melalui PHPUnit / Laravel Test Suite:
```bash
php artisan test
```

**Hasil Pengujian:**
```text
PASS  Tests\Unit\ExampleTest
✓ that true is true

PASS  Tests\Feature\ExampleTest
✓ the application returns a successful response

PASS  Tests\Feature\QuotaServiceTest
✓ approve registration decrements quota
✓ quota exceeded throws exception
✓ reject approved registration restores quota
✓ complete approved registration releases quota
✓ unique composite index prevents duplicate department period
✓ atomic lock prevents concurrent submission

PASS  Tests\Feature\SupervisorEvaluationFeaturesTest
✓ registration rejects nis nim with less than 5 characters
✓ registration accepts nis nim with 5 or more characters
✓ applicant dashboard and detail display completion message
✓ admin can view department quotas with stats
✓ admin reports filters with applicant status and date range
✓ api endpoints reject unauthorized access
✓ api endpoints return data with valid api key
✓ admin can add multiple supervisors simultaneously
✓ admin can check updates without hard reload

Tests:    17 passed (79 assertions)
Duration: 1.07s
```
**Status: 100% Lulus (17 tests, 79 assertions).**

---

## 7. 🚀 Penyempurnaan Hasil Masukan Pengujian Blackbox (21 September 2026)

1. **Upload Surat Balasan Digital Susulan untuk Pendaftar Diterima (*Approved*):**
   - **File:** [`VerificationController.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/app/Http/Controllers/Admin/VerificationController.php) & [`show.blade.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/resources/views/admin/verification/show.blade.php).
   - **Solusi:** Menambahkan route `admin.verification.upload-reply-letter` dan form upload susulan pada kartu berkas surat balasan resmi. Admin kini leluasa mengunggah atau mengganti file Surat Balasan PDF resmi secara susulan setelah proses persetujuan awal selesai (misal: saat mahasiswa selesai mengurus surat rekomendasi Bakesbangpol).
2. **Pembersihan Redundansi Tombol di Dashboard Pendaftar:**
   - **File:** [`dashboard.blade.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/resources/views/applicant/dashboard.blade.php).
   - **Solusi:** Menghapus tombol "Rincian Pengajuan" di Welcome Banner atas, menyisakan 2 tautan rincian lainnya (pada footer kartu ringkasan dan kolom aksi tabel riwayat) agar banner lebih bersih dan terhindar dari tombol ganda.
3. **Penyelarasan Header Halaman Kelola Bidang & Kuota:**
   - **File:** [`index.blade.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/resources/views/admin/departments/index.blade.php).
   - **Solusi:** Menghapus teks statis `• Administrasi Kantor` dan badge `Periode: 2026-Q3` serta kotak pembungkus putih pada header, sehingga header halaman ini kini seragam, bersih, dan konsisten dengan seluruh halaman admin lainnya.
4. **Pencegahan Human Error Saat Penerimaan Pengajuan (Wajib Pilih Bidang & Pembimbing Lapangan):**
   - **File Terkait:**
     - Controller: [`VerificationController.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/app/Http/Controllers/Admin/VerificationController.php)
     - Blade View: [`show.blade.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/resources/views/admin/verification/show.blade.php)
     - Automated Test: [`SupervisorEvaluationFeaturesTest.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/tests/Feature/SupervisorEvaluationFeaturesTest.php)
   - **Latar Belakang & Masalah:**
     Admin berpotensi secara tidak sengaja menyetujui (*approve*) pengajuan peserta tanpa menentukan bidang penempatan final atau tanpa menunjuk pembimbing lapangan, yang menyebabkan kebingungan bagi peserta saat hari pertama magang/PKL. Selain itu, pesan peringatan bawaan browser sebelumnya menampilkan bahasa Inggris default (*"Please fill out this field."*) yang kurang komunikatif.
   - **Solusi & Implementasi:**
     - **Pesan Interaktif Bahasa Indonesia:** Mengganti pesan bawaan browser menggunakan API HTML5 `setCustomValidity()` dan atribut `oninvalid`/`oninput`/`onchange` menjadi: **"Tolong pilih pembimbing terlebih dahulu"** dan **"Tolong pilih bidang penempatan terlebih dahulu"**.
     - **Validasi Backend Ketat:** `VerificationController::approve()` mewajibkan `department_id` (`required|exists:departments,id`) dan `supervisor_name` (`required|string|max:255`), dilengkapi pesan error berbahasa Indonesia yang seragam.
     - **Interaksi Frontend & Visual Cue:** Ditambahkan tanda bintang merah/wajib (`*`) pada label Pembimbing Lapangan, atribut `required` pada field nama pembimbing, dan validasi interaktif sebelum aksi dikirim.
     - **Custom Confirmation Modal (Glassmorphism Emerald):** Saat tombol *"Konfirmasi Diterima & Simpan"* ditekan, dijalankan fungsi `handleApproveClick()` yang memverifikasi kelengkapan form dan memunculkan modal pop-up konfirmasi elegan berisi ringkasan *Bidang Penempatan* dan *Nama Pembimbing* yang dipilih.

5. **Sinkronisasi Dua Arah Nomor WhatsApp Antara Profil Pengguna dan Formulir Pendaftaran:**
   - **File Terkait:**
     - Blade View: [`create.blade.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/resources/views/applicant/registration/create.blade.php)
     - Controller Pendaftaran: [`RegistrationController.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/app/Http/Controllers/Applicant/RegistrationController.php)
     - Controller Profil: [`ApplicantDashboardController.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/app/Http/Controllers/Applicant/ApplicantDashboardController.php)
     - Automated Test: [`SupervisorEvaluationFeaturesTest.php`](file:///d:/Kerja%20Praktek%20DISKOMINFO/Project%20Sistem/Project-Kerja-Praktek/tests/Feature/SupervisorEvaluationFeaturesTest.php)
   - **Latar Belakang & Masalah:**
     Nomor WhatsApp yang telah diisi pada Profil Akun peserta sebelumnya tidak otomatis terisi di form pendaftaran, dan nomor yang diisikan di form pendaftaran tidak otomatis memperbarui profil akun atau data kontak ketua.
   - **Solusi & Implementasi:**
     - **Profil $\rightarrow$ Form Pendaftaran:** Nilai default input `leader_phone` otomatis mengambil nomor telepon akun pengguna (`value="{{ old('leader_phone', auth()->user()->phone) }}"`).
     - **Form Pendaftaran $\rightarrow$ Profil:** Saat pendaftaran dikirimkan (`store`), jika nomor WhatsApp pada form berbeda atau profil belum memiliki nomor, sistem otomatis memperbarui `users.phone`.
     - **Profil $\rightarrow$ Data Pendaftaran:** Saat pengguna memperbarui nomor WhatsApp di halaman Profil, sistem otomatis memperbarui nomor telepon kontak ketua pada data pendaftaran aktif (`registration_participants.phone`).
   - **Hasil Pengujian Otomatis:**
     ```bash
     PASS  Tests\Feature\SupervisorEvaluationFeaturesTest
     ✓ admin approval requires department and supervisor to prevent human error
     ✓ admin approval succeeds when department and supervisor are provided
     ✓ registration form submission syncs phone to user profile
     ✓ user profile update syncs phone to existing registration leader

     Tests:    21 passed (98 assertions)
     Duration: 1.53s
     ```
     *Status: 100% Lulus (21 tests, 98 assertions).*



