<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Document;
use App\Models\Registration;
use App\Models\RegistrationInstitution;
use App\Models\RegistrationParticipant;
use App\Models\ReplyLetter;
use App\Models\SlotQuota;
use App\Models\User;
use App\Services\QuotaService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Admin Kepegawaian Diskominfo
        $admin = User::firstOrCreate(
            ['email' => 'admin@garutkab.go.id'],
            [
                'name' => 'Admin Kepegawaian Diskominfo',
                'phone' => '081234567890',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Demo User Pendaftar
        $pendaftarDemo = User::firstOrCreate(
            ['email' => 'pendaftar@gmail.com'],
            [
                'name' => 'Budi Santoso (Mahasiswa Demo)',
                'phone' => '089876543210',
                'password' => bcrypt('password123'),
                'role' => 'pendaftar',
                'email_verified_at' => now(),
            ]
        );

        // 3. Data Bidang Diskominfo Garut
        $departmentsData = [
            [
                'name' => 'Bidang Aplikasi Informatika (Aptika)',
                'description' => 'Pengembangan perangkat lunak, integrasi sistem informasi, pengelolaan portal web, dan infrastruktur aplikasi daerah.',
                'quota' => 25,
            ],
            [
                'name' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'description' => 'Pengelolaan kehumasan, jurnalistik digital, penyebaran informasi publik, dan multimedia pemkab Garut.',
                'quota' => 15,
            ],
            [
                'name' => 'Bidang Statistik & Persandian',
                'description' => 'Pengelolaan data statistik sektoral, tata kelola data terpadu (Satu Data Garut), dan keamanan persandian.',
                'quota' => 12,
            ],
            [
                'name' => 'Bidang Infrastruktur Telematika & E-Government',
                'description' => 'Jaringan intra pemerintah, server room, internet publik, IoT, dan infrastruktur SPBE.',
                'quota' => 15,
            ],
        ];

        $period = QuotaService::getActivePeriod();
        $createdDepts = [];

        foreach ($departmentsData as $deptData) {
            $dept = Department::firstOrCreate(
                ['name' => $deptData['name']],
                ['description' => $deptData['description']]
            );

            SlotQuota::firstOrCreate(
                [
                    'department_id' => $dept->id,
                    'period' => $period,
                ],
                [
                    'quota_total' => $deptData['quota'],
                    'quota_used' => 0,
                ]
            );

            $createdDepts[$deptData['name']] = $dept;
        }

        // 4. Data Dummy Registrasi Komprehensif (Multi-Kelompok per Pembimbing Lapangan)
        $dummyRegistrations = [
            // ==========================================
            // 1. PEMBIMBING: Anry Sutrisno, S.Pd., M.Si (Aptika - 2 Kelompok)
            // ==========================================
            [
                'user_name' => 'Ahmad Fauzi',
                'user_email' => 'ahmad.smkn1@gmail.com',
                'applicant_status' => 'Siswa',
                'program_type' => 'PKL',
                'institution_name' => 'SMKN 1 Garut',
                'major' => 'Rekayasa Perangkat Lunak (RPL)',
                'level' => 'SMA/SMK',
                'teacher_name' => 'Drs. H. Ahmad Sudrajat, M.Pd',
                'teacher_phone' => '081223344556',
                'teacher_email' => 'ahmad.sudrajat@smkn1garut.sch.id',
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'supervisor_name' => 'Anry Sutrisno, S.Pd., M.Si',
                'supervisor_position' => 'Kepala Bidang Aplikasi & Informatika',
                'status' => 'approved',
                'acceptance_message' => 'Selamat, pengajuan PKL tim Anda disetujui pada Bidang Aptika. Silakan membawa surat fisik pengantar ke kantor Diskominfo Garut.',
                'participants' => [
                    ['name' => 'Ahmad Fauzi', 'nim' => '2021001', 'is_leader' => true],
                    ['name' => 'Dina Rosdiana', 'nim' => '2021002', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Fajar Ramadhan',
                'user_email' => 'fajar.ui@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'Magang',
                'institution_name' => 'Universitas Indonesia (UI)',
                'major' => 'Ilmu Komputer',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'supervisor_name' => 'Anry Sutrisno, S.Pd., M.Si',
                'supervisor_position' => 'Kepala Bidang Aplikasi & Informatika',
                'status' => 'approved',
                'acceptance_message' => 'Diterima untuk magang riset integrasi microservices pada arsitektur Satu Data & Layanan Publik Garut.',
                'participants' => [
                    ['name' => 'Fajar Ramadhan', 'nim' => '2006512340', 'is_leader' => true],
                    ['name' => 'Alifia Zahra', 'nim' => '2006512341', 'is_leader' => false],
                    ['name' => 'Dimas Surya', 'nim' => '2006512342', 'is_leader' => false],
                ]
            ],

            // ==========================================
            // 2. PEMBIMBING: Deden Ferry Martin, S.IP (Aptika - 3 Kelompok)
            // ==========================================
            [
                'user_name' => 'Rian Hidayat',
                'user_email' => 'rian.uniga@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Universitas Garut (UNIGA)',
                'major' => 'Teknik Informatika',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'supervisor_name' => 'Deden Ferry Martin, S.IP',
                'supervisor_position' => 'Sub Koordinator Pengelolaan Aplikasi Informatika',
                'status' => 'approved',
                'acceptance_message' => 'Permohonan Kerja Praktik (KP) disetujui di Bidang Aptika untuk pengembangan fitur portal terpadu.',
                'participants' => [
                    ['name' => 'Rian Hidayat', 'nim' => '240601201300', 'is_leader' => true],
                    ['name' => 'Fikri Ramdani', 'nim' => '240601201301', 'is_leader' => false],
                    ['name' => 'Nabila Putri', 'nim' => '240601201302', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Cindy Claudia',
                'user_email' => 'cindy.telkom@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Telkom University',
                'major' => 'Sistem Informasi',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'supervisor_name' => 'Deden Ferry Martin, S.IP',
                'supervisor_position' => 'Sub Koordinator Pengelolaan Aplikasi Informatika',
                'status' => 'approved',
                'acceptance_message' => 'Disetujui untuk analisis kebutuhan sistem dan perancangan UI/UX dashboard pelayanan publik.',
                'participants' => [
                    ['name' => 'Cindy Claudia', 'nim' => '1202204112', 'is_leader' => true],
                    ['name' => 'Reza Firmansyah', 'nim' => '1202204113', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Hendra Wijaya',
                'user_email' => 'hendra.smkn1@gmail.com',
                'applicant_status' => 'Siswa',
                'program_type' => 'PKL',
                'institution_name' => 'SMKN 1 Garut',
                'major' => 'Rekayasa Perangkat Lunak (RPL)',
                'level' => 'SMA/SMK',
                'teacher_name' => 'Drs. H. Ahmad Sudrajat, M.Pd',
                'teacher_phone' => '081223344556',
                'teacher_email' => 'ahmad.sudrajat@smkn1garut.sch.id',
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'supervisor_name' => 'Deden Ferry Martin, S.IP',
                'supervisor_position' => 'Sub Koordinator Pengelolaan Aplikasi Informatika',
                'status' => 'approved',
                'acceptance_message' => 'Diterima untuk praktik pengujian aplikasi web dan quality assurance modul sistem.',
                'participants' => [
                    ['name' => 'Hendra Wijaya', 'nim' => '2021045', 'is_leader' => true],
                    ['name' => 'Maya Anggraeni', 'nim' => '2021046', 'is_leader' => false],
                ]
            ],

            // ==========================================
            // 3. PEMBIMBING: Ahmad Hasyim, S.T., MT (Infrastruktur - 2 Kelompok)
            // ==========================================
            [
                'user_name' => 'Irvan Gunawan',
                'user_email' => 'irvan.smkn2@gmail.com',
                'applicant_status' => 'Siswa',
                'program_type' => 'PKL',
                'institution_name' => 'SMKN 2 Garut',
                'major' => 'Teknik Komputer & Jaringan (TKJ)',
                'level' => 'SMA/SMK',
                'teacher_name' => 'Yudi Pratama, S.T',
                'teacher_phone' => '081399887766',
                'teacher_email' => 'yudi.p@smkn2garut.sch.id',
                'preferred_dept' => 'Bidang Infrastruktur Telematika & E-Government',
                'assigned_dept' => 'Bidang Infrastruktur Telematika & E-Government',
                'supervisor_name' => 'Ahmad Hasyim, S.T., MT',
                'supervisor_position' => 'Kabid Persandian & Keamanan Informasi',
                'status' => 'approved',
                'acceptance_message' => 'Pengajuan PKL disetujui pada Bidang Infrastruktur Jaringan & Server Room.',
                'participants' => [
                    ['name' => 'Irvan Gunawan', 'nim' => '10219981', 'is_leader' => true],
                    ['name' => 'Rizal Syahputra', 'nim' => '10219982', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Bagas Pratama',
                'user_email' => 'bagas.polban@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Politeknik Negeri Bandung (POLBAN)',
                'major' => 'Teknik Telekomunikasi & Jaringan',
                'level' => 'D4',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Infrastruktur Telematika & E-Government',
                'assigned_dept' => 'Bidang Infrastruktur Telematika & E-Government',
                'supervisor_name' => 'Ahmad Hasyim, S.T., MT',
                'supervisor_position' => 'Kabid Persandian & Keamanan Informasi',
                'status' => 'approved',
                'acceptance_message' => 'Disetujui untuk monitoring jaringan fiber optic intra OPD dan konfigurasi firewall server Pemkab Garut.',
                'participants' => [
                    ['name' => 'Bagas Pratama', 'nim' => '211511030', 'is_leader' => true],
                    ['name' => 'Lukman Hakim', 'nim' => '211511031', 'is_leader' => false],
                    ['name' => 'Kevin Sanjaya', 'nim' => '211511032', 'is_leader' => false],
                ]
            ],

            // ==========================================
            // 4. PEMBIMBING: Herman, S.KOM (IKP - 3 Kelompok)
            // ==========================================
            [
                'user_name' => 'Siti Nurhaliza',
                'user_email' => 'siti.upi@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'Magang',
                'institution_name' => 'Universitas Pendidikan Indonesia (UPI)',
                'major' => 'Ilmu Komunikasi',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'assigned_dept' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'supervisor_name' => 'Herman, S.KOM',
                'supervisor_position' => 'Kepala Bidang Informasi Komunikasi Publik',
                'status' => 'approved',
                'acceptance_message' => 'Pengajuan Magang disetujui di Bidang IKP fokus liputan jurnalistik dan publikasi berita daerah.',
                'participants' => [
                    ['name' => 'Siti Nurhaliza', 'nim' => '2105432', 'is_leader' => true],
                ]
            ],
            [
                'user_name' => 'Aditya Pratama',
                'user_email' => 'aditya.smkn4@gmail.com',
                'applicant_status' => 'Siswa',
                'program_type' => 'PKL',
                'institution_name' => 'SMKN 4 Garut',
                'major' => 'Desain Komunikasi Visual (DKV)',
                'level' => 'SMA/SMK',
                'teacher_name' => 'Eni Nuraeni, S.Kom',
                'teacher_phone' => '085220011223',
                'teacher_email' => 'eni.nuraeni@smkn4garut.sch.id',
                'preferred_dept' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'assigned_dept' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'supervisor_name' => 'Herman, S.KOM',
                'supervisor_position' => 'Kepala Bidang Informasi Komunikasi Publik',
                'status' => 'approved',
                'acceptance_message' => 'Disetujui untuk produksi konten infografis media sosial resmi Pemkab Garut.',
                'participants' => [
                    ['name' => 'Aditya Pratama', 'nim' => '22234101', 'is_leader' => true],
                    ['name' => 'Cantika Dewi', 'nim' => '22234102', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Tiara Andini',
                'user_email' => 'tiara.unpad@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Universitas Padjadjaran (UNPAD)',
                'major' => 'Jurnalistik Digital',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'assigned_dept' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'supervisor_name' => 'Herman, S.KOM',
                'supervisor_position' => 'Kepala Bidang Informasi Komunikasi Publik',
                'status' => 'approved',
                'acceptance_message' => 'Diterima untuk liputan siaran pers, dokumentasi media center, dan penulisan feature news daerah.',
                'participants' => [
                    ['name' => 'Tiara Andini', 'nim' => '140810210088', 'is_leader' => true],
                    ['name' => 'Galih Rakasiwi', 'nim' => '140810210089', 'is_leader' => false],
                ]
            ],

            // ==========================================
            // 5. PEMBIMBING: Efita Fitri Irianti, SP., M.EC.DEV (Statistik - 2 Kelompok)
            // ==========================================
            [
                'user_name' => 'Dewi Lestari',
                'user_email' => 'dewi.telkom@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Telkom University',
                'major' => 'Sistem Informasi',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Statistik & Persandian',
                'assigned_dept' => 'Bidang Statistik & Persandian',
                'supervisor_name' => 'Efita Fitri Irianti, SP., M.EC.DEV',
                'supervisor_position' => 'Kabid Penyelenggaraan Statistik Sektoral',
                'status' => 'approved',
                'acceptance_message' => 'Diterima di Bidang Statistik untuk riset integrasi metadata Satu Data Garut.',
                'participants' => [
                    ['name' => 'Dewi Lestari', 'nim' => '1202204001', 'is_leader' => true],
                    ['name' => 'Bagus Prayoga', 'nim' => '1202204002', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Farhan Maulana',
                'user_email' => 'farhan.itg@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Institut Teknologi Garut (ITG)',
                'major' => 'Teknik Industri',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Statistik & Persandian',
                'assigned_dept' => 'Bidang Statistik & Persandian',
                'supervisor_name' => 'Efita Fitri Irianti, SP., M.EC.DEV',
                'supervisor_position' => 'Kabid Penyelenggaraan Statistik Sektoral',
                'status' => 'approved',
                'acceptance_message' => 'Diterima untuk pengolahan data statistik sektoral kecamatan dan visualisasi indikator makro daerah.',
                'participants' => [
                    ['name' => 'Farhan Maulana', 'nim' => '1906011', 'is_leader' => true],
                    ['name' => 'Sarah Nabila', 'nim' => '1906012', 'is_leader' => false],
                    ['name' => 'Dika Kurniawan', 'nim' => '1906013', 'is_leader' => false],
                ]
            ],

            // ==========================================
            // 6. PEMBIMBING: Rifki, S.Kom (Aptika / Programmer - 2 Kelompok)
            // ==========================================
            [
                'user_name' => 'Randy Pangalila',
                'user_email' => 'randy.uniga@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Universitas Garut (UNIGA)',
                'major' => 'Sistem Informasi',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'supervisor_name' => 'Rifki, S.Kom',
                'supervisor_position' => 'Programmer & Analis Sistem',
                'status' => 'approved',
                'acceptance_message' => 'Diterima di tim programmer Diskominfo Garut untuk refactoring modul backend dan integrasi API RESTful.',
                'participants' => [
                    ['name' => 'Randy Pangalila', 'nim' => '240601201991', 'is_leader' => true],
                    ['name' => 'Putri Ayu', 'nim' => '240601201992', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Zahra Amelia',
                'user_email' => 'zahra.smkn2@gmail.com',
                'applicant_status' => 'Siswa',
                'program_type' => 'PKL',
                'institution_name' => 'SMKN 2 Garut',
                'major' => 'Rekayasa Perangkat Lunak (RPL)',
                'level' => 'SMA/SMK',
                'teacher_name' => 'Yudi Pratama, S.T',
                'teacher_phone' => '081399887766',
                'teacher_email' => 'yudi.p@smkn2garut.sch.id',
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'supervisor_name' => 'Rifki, S.Kom',
                'supervisor_position' => 'Programmer & Analis Sistem',
                'status' => 'approved',
                'acceptance_message' => 'Disetujui untuk praktik pengkodean frontend modern dan implementasi form validasi.',
                'participants' => [
                    ['name' => 'Zahra Amelia', 'nim' => '10219901', 'is_leader' => true],
                    ['name' => 'Wildan Fauzan', 'nim' => '10219902', 'is_leader' => false],
                ]
            ],

            // ==========================================
            // PENGAJUAN PENDING (Antrean Menunggu Verifikasi)
            // ==========================================
            [
                'user_name' => 'Muhammad Rizky',
                'user_email' => 'rizky.smkn4@gmail.com',
                'applicant_status' => 'Siswa',
                'program_type' => 'PKL',
                'institution_name' => 'SMKN 4 Garut',
                'major' => 'Desain Komunikasi Visual (DKV)',
                'level' => 'SMA/SMK',
                'teacher_name' => 'Eni Nuraeni, S.Kom',
                'teacher_phone' => '085220011223',
                'teacher_email' => 'eni.nuraeni@smkn4garut.sch.id',
                'preferred_dept' => 'Bidang Informasi & Komunikasi Publik (IKP)',
                'assigned_dept' => null,
                'status' => 'pending',
                'participants' => [
                    ['name' => 'Muhammad Rizky', 'nim' => '22234011', 'is_leader' => true],
                    ['name' => 'Annisa Rahmawati', 'nim' => '22234012', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Rina Kurnia',
                'user_email' => 'rina.unpad@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Universitas Padjadjaran (UNPAD)',
                'major' => 'Teknik Informatika',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => null,
                'status' => 'pending',
                'participants' => [
                    ['name' => 'Rina Kurnia', 'nim' => '140810210015', 'is_leader' => true],
                    ['name' => 'Dwi Prasetyo', 'nim' => '140810210016', 'is_leader' => false],
                ]
            ],
            [
                'user_name' => 'Bayu Pratama',
                'user_email' => 'bayu.smkn1@gmail.com',
                'applicant_status' => 'Siswa',
                'program_type' => 'PKL',
                'institution_name' => 'SMKN 1 Garut',
                'major' => 'Teknik Jaringan Komputer & Telekomunikasi (TJKT)',
                'level' => 'SMA/SMK',
                'teacher_name' => 'Asep Saepuloh, M.Kom',
                'teacher_phone' => '082119988771',
                'teacher_email' => 'asep.saepuloh@smkn1garut.sch.id',
                'preferred_dept' => 'Bidang Infrastruktur Telematika & E-Government',
                'assigned_dept' => null,
                'status' => 'pending',
                'participants' => [
                    ['name' => 'Bayu Pratama', 'nim' => '22231045', 'is_leader' => true],
                ]
            ],

            // ==========================================
            // PENGAJUAN DITOLAK
            // ==========================================
            [
                'user_name' => 'Gilang Ramadhan',
                'user_email' => 'gilang.itg@gmail.com',
                'applicant_status' => 'Mahasiswa',
                'program_type' => 'KP',
                'institution_name' => 'Institut Teknologi Garut (ITG)',
                'major' => 'Teknik Informatika',
                'level' => 'S1',
                'teacher_name' => null,
                'teacher_phone' => null,
                'teacher_email' => null,
                'preferred_dept' => 'Bidang Aplikasi Informatika (Aptika)',
                'assigned_dept' => null,
                'status' => 'rejected',
                'rejection_reason' => 'Mohon maaf, kuota peserta magang untuk periode bulan ini pada Bidang Aptika sudah melebihi kapasitas alokasi.',
                'participants' => [
                    ['name' => 'Gilang Ramadhan', 'nim' => '1806041', 'is_leader' => true],
                    ['name' => 'Maya Sandi', 'nim' => '1806042', 'is_leader' => false],
                ]
            ]
        ];

        foreach ($dummyRegistrations as $regData) {
            $user = User::firstOrCreate(
                ['email' => $regData['user_email']],
                [
                    'name' => $regData['user_name'],
                    'phone' => '08' . rand(100000000, 999999999),
                    'password' => bcrypt('password123'),
                    'role' => 'pendaftar',
                    'email_verified_at' => now(),
                ]
            );

            $prefDept = $createdDepts[$regData['preferred_dept']] ?? null;
            $assDept = isset($regData['assigned_dept']) ? ($createdDepts[$regData['assigned_dept']] ?? null) : null;

            $reg = Registration::create([
                'user_id' => $user->id,
                'applicant_status' => $regData['applicant_status'],
                'program_type' => $regData['program_type'],
                'preferred_department_id' => $prefDept ? $prefDept->id : null,
                'department_id' => $assDept ? $assDept->id : null,
                'participant_count' => count($regData['participants']),
                'start_date' => now()->addDays(7),
                'end_date' => now()->addMonths(2),
                'status' => $regData['status'],
                'rejection_reason' => $regData['rejection_reason'] ?? null,
                'acceptance_message' => $regData['acceptance_message'] ?? null,
                'supervisor_name' => $regData['supervisor_name'] ?? null,
                'supervisor_position' => $regData['supervisor_position'] ?? null,
                'verified_by' => $regData['status'] !== 'pending' ? $admin->id : null,
                'verified_at' => $regData['status'] !== 'pending' ? now() : null,
                'placed_at' => $regData['status'] === 'approved' ? now() : null,
                'submitted_at' => now()->subDays(2),
            ]);

            // Create Institution with Teacher/Dosen info
            RegistrationInstitution::create([
                'registration_id' => $reg->id,
                'institution_name' => $regData['institution_name'],
                'institution_address' => 'Kabupaten Garut / Jawa Barat',
                'contact_person' => 'Bagian Kesiswaan / Akademik',
                'contact_phone' => '0262-' . rand(100000, 999999),
                'teacher_name' => $regData['teacher_name'] ?? null,
                'teacher_phone' => $regData['teacher_phone'] ?? null,
                'teacher_email' => $regData['teacher_email'] ?? null,
            ]);

            // Create Participants
            foreach ($regData['participants'] as $p) {
                RegistrationParticipant::create([
                    'registration_id' => $reg->id,
                    'full_name' => $p['name'],
                    'nis_nim' => $p['nim'],
                    'institution_level' => $regData['level'],
                    'major' => $regData['major'],
                    'semester_or_grade' => $regData['level'] === 'SMA/SMK' ? 'Kelas XII' : 'Semester 6',
                    'phone' => '08' . rand(100000000, 999999999),
                    'email' => strtolower(str_replace(' ', '.', $p['name'])) . '@gmail.com',
                    'is_leader' => $p['is_leader'],
                ]);
            }

            // Dummy Document
            Document::create([
                'registration_id' => $reg->id,
                'document_category' => 'surat_pengantar',
                'file_name' => 'Surat_Pengantar_' . str_replace(' ', '_', $regData['institution_name']) . '.pdf',
                'file_path' => 'documents/demo_surat_pengantar.pdf',
                'file_type' => 'application/pdf',
                'uploaded_at' => now(),
            ]);

            // Reply Letter for Approved
            if ($regData['status'] === 'approved') {
                ReplyLetter::create([
                    'registration_id' => $reg->id,
                    'file_path' => 'reply_letters/demo_surat_balasan.pdf',
                    'uploaded_by' => $admin->id,
                    'uploaded_at' => now(),
                ]);
            }
        }

        // 5. Auto sync quota statistics strictly matching approved DB registrations
        QuotaService::syncDepartmentQuotas();
    }
}
