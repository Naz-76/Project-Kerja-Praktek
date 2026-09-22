<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Department;
use App\Models\FieldSupervisor;
use App\Models\Registration;
use App\Models\RegistrationInstitution;
use App\Models\RegistrationParticipant;
use App\Models\SlotQuota;
use App\Models\User;
use App\Services\QuotaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SupervisorEvaluationFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: NIM/NIS min 5 characters validation
     */
    public function test_registration_rejects_nis_nim_with_less_than_5_characters(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'pendaftar']);
        $dept = Department::create(['name' => 'Bidang Aptika']);

        $response = $this->actingAs($user)->post(route('applicant.registration.store'), [
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'preferred_department_id' => $dept->id,
            'institution_name' => 'Institut Teknologi Garut',
            'major' => 'Informatika',
            'leader_phone' => '081234567890',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(35)->format('Y-m-d'),
            'participants' => [
                [
                    'full_name' => 'Budi Santoso',
                    'nis_nim' => '123', // Hanya 3 karakter -> harus ditolak!
                ],
            ],
            'doc_surat_pengantar' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('participants.0.nis_nim');
    }

    public function test_registration_accepts_nis_nim_with_5_or_more_characters(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'pendaftar']);
        $dept = Department::create(['name' => 'Bidang Aptika']);

        $response = $this->actingAs($user)->post(route('applicant.registration.store'), [
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'preferred_department_id' => $dept->id,
            'institution_name' => 'Institut Teknologi Garut',
            'major' => 'Informatika',
            'leader_phone' => '081234567890',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(35)->format('Y-m-d'),
            'participants' => [
                [
                    'full_name' => 'Budi Santoso',
                    'nis_nim' => '2106001', // 7 karakter -> valid
                ],
            ],
            'doc_surat_pengantar' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('applicant.dashboard'));
        $this->assertDatabaseHas('registration_participants', [
            'nis_nim' => '2106001',
        ]);
    }

    /**
     * Test 2: Completed status message displayed
     */
    public function test_applicant_dashboard_and_detail_display_completion_message(): void
    {
        $user = User::factory()->create(['role' => 'pendaftar']);
        $dept = Department::create(['name' => 'Bidang IKP']);

        $registration = Registration::create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'KP',
            'status' => 'completed',
            'start_date' => now()->subMonth(),
            'end_date' => now()->subDay(),
            'participant_count' => 1,
            'submitted_at' => now()->subMonth(),
        ]);

        RegistrationParticipant::create([
            'registration_id' => $registration->id,
            'full_name' => $user->name,
            'nis_nim' => '2106001',
            'major' => 'Teknik Informatika',
            'is_leader' => true,
        ]);

        // Dashboard
        $dashResponse = $this->actingAs($user)->get(route('applicant.dashboard'));
        $dashResponse->assertOk();
        $dashResponse->assertSee('Selamat Anda telah menyelesaikan kegiatan sesuai program');
        $dashResponse->assertSee('surat penilaian');

        // Detail
        $detailResponse = $this->actingAs($user)->get(route('applicant.my-registration.show', $registration->id));
        $detailResponse->assertOk();
        $detailResponse->assertSee('Selamat Anda telah menyelesaikan kegiatan sesuai program');
    }

    /**
     * Test 3: Admin Quota Management View
     */
    public function test_admin_can_view_department_quotas_with_stats(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dept = Department::create(['name' => 'Bidang Statistik & Persandian']);
        $period = QuotaService::getActivePeriod();

        SlotQuota::create([
            'department_id' => $dept->id,
            'period' => $period,
            'quota_total' => 15,
            'quota_used' => 5,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.departments.index'));
        $response->assertOk();
        $response->assertSee('Bidang Statistik & Persandian');
        $response->assertSee('Total Daya Tampung');
        $response->assertSee('Status Kuota');
    }

    /**
     * Test 4: Report Page with 3 Tabs and Date Range Filtering
     */
    public function test_admin_reports_filters_with_applicant_status_and_date_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create(['role' => 'pendaftar']);
        $user2 = User::factory()->create(['role' => 'pendaftar']);

        $regMhs = Registration::create([
            'user_id' => $user1->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'status' => 'approved',
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-30',
            'participant_count' => 1,
            'submitted_at' => now(),
        ]);

        $regSiswa = Registration::create([
            'user_id' => $user2->id,
            'applicant_status' => 'Siswa',
            'program_type' => 'PKL',
            'status' => 'approved',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-31',
            'participant_count' => 1,
            'submitted_at' => now(),
        ]);

        // Filter Mahasiswa tab
        $response = $this->actingAs($admin)->get(route('admin.reports.index', ['applicant_status' => 'Mahasiswa']));
        $response->assertOk();
        $response->assertSee('Semua Pendaftar');
        $response->assertSee('Mahasiswa');
        $response->assertSee('Siswa');
        $response->assertSee('Dari Tanggal');
        $response->assertSee('Sampai Tanggal');

        // Filter rentang waktu bulan September
        $dateFilterResponse = $this->actingAs($admin)->get(route('admin.reports.index', [
            'date_from' => '2026-09-01',
            'date_to' => '2026-09-30',
        ]));
        $dateFilterResponse->assertOk();
        $dateFilterResponse->assertSee('2026-09-01');
    }

    /**
     * Test 5: REST API Authentication & Data Endpoints
     */
    public function test_api_endpoints_reject_unauthorized_access(): void
    {
        // Tanpa API Key
        $response = $this->getJson('/api/v1/departments');
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
        ]);

        // Dengan API Key palsu
        $responseInvalid = $this->withHeaders(['X-API-KEY' => 'porma_invalid_key_123'])
            ->getJson('/api/v1/departments');
        $responseInvalid->assertStatus(401);
    }

    public function test_api_endpoints_return_data_with_valid_api_key(): void
    {
        $apiKey = ApiKey::create([
            'name' => 'Satu Data Garut',
            'key' => 'porma_valid_test_token_123456789',
            'is_active' => true,
        ]);

        $dept = Department::create(['name' => 'Bidang Aptika']);
        $period = QuotaService::getActivePeriod();
        SlotQuota::create([
            'department_id' => $dept->id,
            'period' => $period,
            'quota_total' => 10,
            'quota_used' => 2,
        ]);

        // 1. Departments endpoint
        $responseDept = $this->withHeaders(['X-API-KEY' => $apiKey->key])
            ->getJson('/api/v1/departments');

        $responseDept->assertOk();
        $responseDept->assertJsonPath('success', true);
        $responseDept->assertJsonPath('data.0.name', 'Bidang Aptika');
        $responseDept->assertJsonPath('data.0.quota.total', 10);
        $responseDept->assertJsonPath('data.0.quota.remaining', 8);

        // 2. Statistics endpoint
        $responseStats = $this->withHeaders(['X-API-KEY' => $apiKey->key])
            ->getJson('/api/v1/statistics');

        $responseStats->assertOk();
        $responseStats->assertJsonPath('success', true);
        $responseStats->assertJsonStructure([
            'data' => [
                'current_period',
                'overview' => [
                    'total_registrations',
                    'total_quota_capacity',
                    'total_quota_used',
                    'total_quota_remaining',
                ],
                'by_status',
                'by_applicant_status',
                'by_program_type',
            ],
        ]);

        // Pastikan last_used_at ter-update
        $this->assertNotNull($apiKey->fresh()->last_used_at);
    }

    /**
     * Test: Batch addition of field supervisors
     */
    public function test_admin_can_add_multiple_supervisors_simultaneously(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dept = Department::create(['name' => 'Bidang IKP']);

        $response = $this->actingAs($admin)->post(route('admin.supervisors.store'), [
            'department_id' => $dept->id,
            'supervisors' => [
                [
                    'name' => 'Dr. Ir. Hendra',
                    'position' => 'Pranata Komputer Ahli Muda',
                    'phone' => '081234567891',
                ],
                [
                    'name' => 'Siti Nurhaliza, S.Kom',
                    'position' => 'Sandiman Ahli Pertama',
                    'phone' => '081298765432',
                ],
                [
                    'name' => 'Ahmad Fauzi, M.T',
                    'position' => 'Kepala Seksi Infrastruktur',
                    'phone' => '081223344556',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('field_supervisors', [
            'department_id' => $dept->id,
            'name' => 'Dr. Ir. Hendra',
            'position' => 'Pranata Komputer Ahli Muda',
            'phone' => '081234567891',
        ]);

        $this->assertDatabaseHas('field_supervisors', [
            'department_id' => $dept->id,
            'name' => 'Siti Nurhaliza, S.Kom',
            'position' => 'Sandiman Ahli Pertama',
            'phone' => '081298765432',
        ]);

        $this->assertDatabaseHas('field_supervisors', [
            'department_id' => $dept->id,
            'name' => 'Ahmad Fauzi, M.T',
            'position' => 'Kepala Seksi Infrastruktur',
            'phone' => '081223344556',
        ]);

        $this->assertEquals(3, FieldSupervisor::where('department_id', $dept->id)->count());
    }

    /**
     * Test 9: Admin silent polling check-updates endpoint
     */
    public function test_admin_can_check_updates_without_hard_reload(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Check without existing registrations
        $response = $this->actingAs($admin)->getJson(route('admin.check-updates', [
            'last_id' => 0,
            'pending_count' => 0,
        ]));

        $response->assertOk()
            ->assertJsonStructure([
                'has_new',
                'new_count',
                'latest_id',
                'pending_count',
            ]);

        // Create new pending registration
        $user = User::factory()->create(['role' => 'pendaftar']);
        $reg = Registration::create([
            'user_id' => $user->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'status' => 'pending',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(37),
            'participant_count' => 1,
            'submitted_at' => now(),
        ]);

        // Check updates with older last_id
        $updateResponse = $this->actingAs($admin)->getJson(route('admin.check-updates', [
            'last_id' => $reg->id - 1,
            'pending_count' => 0,
        ]));

        $updateResponse->assertOk()
            ->assertJson([
                'has_new' => true,
                'new_count' => 1,
                'latest_id' => $reg->id,
                'pending_count' => 1,
            ]);
    }

    /**
     * Test: Admin approval requires department_id and supervisor_name to prevent human error
     */
    public function test_admin_approval_requires_department_and_supervisor_to_prevent_human_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'pendaftar']);
        $dept = Department::create(['name' => 'Bidang Aptika']);

        $reg = Registration::create([
            'user_id' => $user->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'preferred_department_id' => $dept->id,
            'status' => 'pending',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(37),
            'participant_count' => 1,
            'submitted_at' => now(),
        ]);

        // 1. Submit without department_id
        $responseNoDept = $this->actingAs($admin)->post(route('admin.verification.approve', $reg->id), [
            'supervisor_name' => 'Dr. Pembimbing',
        ]);
        $responseNoDept->assertSessionHasErrors('department_id');

        // 2. Submit without supervisor_name
        $responseNoSup = $this->actingAs($admin)->post(route('admin.verification.approve', $reg->id), [
            'department_id' => $dept->id,
        ]);
        $responseNoSup->assertSessionHasErrors('supervisor_name');

        // Verify status remains pending
        $this->assertEquals('pending', $reg->fresh()->status);
    }

    /**
     * Test: Admin approval succeeds when both department and supervisor are provided
     */
    public function test_admin_approval_succeeds_when_department_and_supervisor_are_provided(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'pendaftar']);
        $dept = Department::create(['name' => 'Bidang IKP']);
        $period = QuotaService::getActivePeriod();

        SlotQuota::create([
            'department_id' => $dept->id,
            'period' => $period,
            'quota_total' => 10,
            'quota_used' => 0,
        ]);

        $reg = Registration::create([
            'user_id' => $user->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'preferred_department_id' => $dept->id,
            'status' => 'pending',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
            'participant_count' => 1,
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.verification.approve', $reg->id), [
            'department_id' => $dept->id,
            'supervisor_name' => 'Ahmad Fauzi, S.Kom',
            'supervisor_position' => 'Pranata Komputer Ahli Muda',
            'supervisor_phone' => '08123456789',
            'acceptance_message' => 'Selamat datang di Diskominfo Garut.',
        ]);

        $response->assertRedirect(route('admin.verification.index'));
        $response->assertSessionHas('success');

        $fresh = $reg->fresh();
        $this->assertEquals('approved', $fresh->status);
        $this->assertEquals($dept->id, $fresh->department_id);
        $this->assertEquals('Ahmad Fauzi, S.Kom', $fresh->supervisor_name);
        $this->assertEquals('Pranata Komputer Ahli Muda', $fresh->supervisor_position);
        $this->assertEquals('08123456789', $fresh->supervisor_phone);
    }

    /**
     * Test: Registration submission synchronizes WhatsApp phone to user profile
     */
    public function test_registration_form_submission_syncs_phone_to_user_profile(): void
    {
        Storage::fake('public');
        $user = User::factory()->create([
            'role' => 'pendaftar',
            'phone' => null,
        ]);
        $dept = Department::create(['name' => 'Bidang Aptika']);

        $response = $this->actingAs($user)->post(route('applicant.registration.store'), [
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'preferred_department_id' => $dept->id,
            'institution_name' => 'Institut Teknologi Garut',
            'major' => 'Informatika',
            'leader_phone' => '081234567899',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(35)->format('Y-m-d'),
            'participants' => [
                [
                    'full_name' => 'Budi Santoso',
                    'nis_nim' => '20261001',
                ],
            ],
            'doc_surat_pengantar' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('applicant.dashboard'));
        $this->assertEquals('081234567899', $user->fresh()->phone);
    }

    /**
     * Test: Updating profile phone synchronizes to existing registration leader phone
     */
    public function test_user_profile_update_syncs_phone_to_existing_registration_leader(): void
    {
        $user = User::factory()->create([
            'role' => 'pendaftar',
            'phone' => '081111111111',
        ]);

        $reg = Registration::create([
            'user_id' => $user->id,
            'applicant_status' => 'Mahasiswa',
            'program_type' => 'Magang',
            'status' => 'pending',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(37),
            'participant_count' => 1,
            'submitted_at' => now(),
        ]);

        $participant = RegistrationParticipant::create([
            'registration_id' => $reg->id,
            'full_name' => $user->name,
            'nis_nim' => '20261001',
            'major' => 'Informatika',
            'phone' => '081111111111',
            'email' => $user->email,
            'is_leader' => true,
        ]);

        $response = $this->actingAs($user)->post(route('applicant.profile.update'), [
            'name' => $user->name,
            'phone' => '082222222222',
        ]);

        $response->assertRedirect();
        $this->assertEquals('082222222222', $user->fresh()->phone);
        $this->assertEquals('082222222222', $participant->fresh()->phone);
    }
}

