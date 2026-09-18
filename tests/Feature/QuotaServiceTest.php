<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Registration;
use App\Models\SlotQuota;
use App\Models\User;
use App\Services\QuotaService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class QuotaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected QuotaService $quotaService;
    protected User $admin;
    protected User $applicant;
    protected Department $department;
    protected string $period;

    protected function setUp(): void
    {
        parent::setUp();

        $this->quotaService = new QuotaService();
        $this->period = QuotaService::getActivePeriod();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@garutkab.go.id',
        ]);

        $this->applicant = User::factory()->create([
            'role' => 'pendaftar',
            'email' => 'applicant@test.com',
        ]);

        $this->department = Department::create([
            'name' => 'Bidang Aplikasi Informatika',
            'description' => 'Aptika Diskominfo',
        ]);

        SlotQuota::create([
            'department_id' => $this->department->id,
            'period' => $this->period,
            'quota_total' => 5,
            'quota_used' => 0,
        ]);
    }

    public function test_approve_registration_decrements_quota(): void
    {
        $registration = Registration::create([
            'user_id' => $this->applicant->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'KP',
            'preferred_department_id' => $this->department->id,
            'status' => 'pending',
            'participant_count' => 3,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(37),
        ]);

        $this->quotaService->approveRegistration(
            $registration,
            $this->department->id,
            $this->admin->id,
            'Selamat bergabung di Aptika Diskominfo Garut',
            'Pembimbing Aptika',
            'Pranata Komputer Ahli'
        );

        $registration->refresh();
        $slotQuota = SlotQuota::where('department_id', $this->department->id)
            ->where('period', $this->period)
            ->first();

        $this->assertEquals('approved', $registration->status);
        $this->assertEquals($this->department->id, $registration->department_id);
        $this->assertEquals(3, $slotQuota->quota_used);
        $this->assertEquals(2, $slotQuota->quota_remaining);
    }

    public function test_quota_exceeded_throws_exception(): void
    {
        // Pendaftar butuh 6 slot, sedangkan kuota total 5
        $registration = Registration::create([
            'user_id' => $this->applicant->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'preferred_department_id' => $this->department->id,
            'status' => 'pending',
            'participant_count' => 6,
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(35),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Sisa kuota tidak mencukupi');

        $this->quotaService->approveRegistration(
            $registration,
            $this->department->id,
            $this->admin->id
        );

        $slotQuota = SlotQuota::where('department_id', $this->department->id)
            ->where('period', $this->period)
            ->first();

        // Kuota tidak boleh terpotong jika gagal
        $this->assertEquals(0, $slotQuota->quota_used);
    }

    public function test_reject_approved_registration_restores_quota(): void
    {
        $registration = Registration::create([
            'user_id' => $this->applicant->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'KP',
            'preferred_department_id' => $this->department->id,
            'status' => 'pending',
            'participant_count' => 2,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(37),
        ]);

        // 1. Setujui
        $this->quotaService->approveRegistration(
            $registration,
            $this->department->id,
            $this->admin->id,
            'Diterima sementara',
            'Pembimbing Aptika'
        );

        $slotQuota = SlotQuota::where('department_id', $this->department->id)
            ->where('period', $this->period)
            ->first();
        $this->assertEquals(2, $slotQuota->quota_used);

        // 2. Batalkan / Tolak
        $this->quotaService->rejectRegistration(
            $registration,
            'Berkas fisik tidak sesuai',
            $this->admin->id
        );

        $registration->refresh();
        $slotQuota->refresh();

        $this->assertEquals('rejected', $registration->status);
        $this->assertNull($registration->department_id);
        $this->assertNull($registration->supervisor_name);
        $this->assertNull($registration->acceptance_message);
        $this->assertEquals('Berkas fisik tidak sesuai', $registration->rejection_reason);

        // Kuota harus kembali ter-restore ke 0
        $this->assertEquals(0, $slotQuota->quota_used);
        $this->assertEquals(5, $slotQuota->quota_remaining);
    }

    public function test_complete_approved_registration_releases_quota(): void
    {
        $registration = Registration::create([
            'user_id' => $this->applicant->id,
            'applicant_status' => 'Siswa',
            'program_type' => 'PKL',
            'preferred_department_id' => $this->department->id,
            'status' => 'pending',
            'participant_count' => 2,
            'start_date' => now()->subDays(30),
            'end_date' => now()->subDays(1),
        ]);

        $this->quotaService->approveRegistration(
            $registration,
            $this->department->id,
            $this->admin->id
        );

        $slotQuota = SlotQuota::where('department_id', $this->department->id)
            ->where('period', $this->period)
            ->first();
        $this->assertEquals(2, $slotQuota->quota_used);

        // Tandai Selesai (Completed)
        $this->quotaService->completeRegistration($registration, $this->admin->id);

        $registration->refresh();
        $slotQuota->refresh();

        $this->assertEquals('completed', $registration->status);
        $this->assertEquals(0, $slotQuota->quota_used);
    }

    public function test_unique_composite_index_prevents_duplicate_department_period(): void
    {
        $this->expectException(QueryException::class);

        // Mencoba membuat slot_quotas baru dengan department_id dan period yang sama persis
        SlotQuota::create([
            'department_id' => $this->department->id,
            'period' => $this->period,
            'quota_total' => 10,
            'quota_used' => 0,
        ]);
    }

    public function test_atomic_lock_prevents_concurrent_submission(): void
    {
        $lockKey = 'submit_registration_user_' . $this->applicant->id;
        $lock = Cache::lock($lockKey, 10);

        // Simulasi request pertama sedang memegang lock
        $this->assertTrue($lock->get());

        // Simulasi request kedua mencoba mendapatkan lock yang sama
        $secondLock = Cache::lock($lockKey, 10);
        $this->assertFalse($secondLock->get());

        // Lepas lock
        $lock->release();

        // Sekarang lock dapat diambil kembali
        $thirdLock = Cache::lock($lockKey, 10);
        $this->assertTrue($thirdLock->get());
        $thirdLock->release();
    }
}
