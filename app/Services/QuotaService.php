<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\SlotQuota;
use Illuminate\Support\Facades\DB;
use Exception;

class QuotaService
{
    /**
     * Dapatkan kode periode kuota aktif saat ini secara konsisten (contoh: '2026-Q3').
     */
    public static function getActivePeriod(): string
    {
        $month = (int) date('n');
        $quarter = ceil($month / 3);
        return date('Y') . "-Q{$quarter}";
    }

    /**
     * Menyetujui pendaftaran dan mengunci kuota secara thread-safe / pessimistic locking.
     */
    public function approveRegistration(Registration $registration, int $departmentId, int $adminId, ?string $acceptanceMessage = null, ?string $supervisorName = null, ?string $supervisorPosition = null): Registration
    {
        return DB::transaction(function () use ($registration, $departmentId, $adminId, $acceptanceMessage, $supervisorName, $supervisorPosition) {
            $period = self::getActivePeriod();

            // Kunci baris slot_quotas untuk mencegah race condition (NFR-05)
            $slotQuota = SlotQuota::where('department_id', $departmentId)
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (!$slotQuota) {
                // Buat slot quota baru jika belum ada
                $slotQuota = SlotQuota::create([
                    'department_id' => $departmentId,
                    'period' => $period,
                    'quota_total' => 10,
                    'quota_used' => 0,
                ]);
                $slotQuota = SlotQuota::where('id', $slotQuota->id)->lockForUpdate()->first();
            }

            $neededSlots = max(1, $registration->participant_count);

            if ($registration->status !== 'approved' && $slotQuota->quota_remaining < $neededSlots) {
                throw new Exception("Sisa kuota tidak mencukupi untuk {$neededSlots} peserta. Sisa kuota saat ini: {$slotQuota->quota_remaining}.");
            }

            // Jika sebelumnya sudah disetujui di bidang lain, kurangi dari bidang lama
            if ($registration->status === 'approved' && $registration->department_id && $registration->department_id != $departmentId) {
                $oldSlot = SlotQuota::where('department_id', $registration->department_id)
                    ->where('period', $period)
                    ->lockForUpdate()
                    ->first();
                if ($oldSlot) {
                    $oldSlot->quota_used = max(0, $oldSlot->quota_used - $neededSlots);
                    $oldSlot->save();
                }
            }

            // Update kuota bidang baru hanya jika status sebelumnya bukan approved
            if ($registration->status !== 'approved') {
                $slotQuota->quota_used += $neededSlots;
                $slotQuota->save();
            }

            // Update status registrasi
            $registration->update([
                'department_id' => $departmentId,
                'status' => 'approved',
                'rejection_reason' => null,
                'acceptance_message' => $acceptanceMessage,
                'supervisor_name' => $supervisorName,
                'supervisor_position' => $supervisorPosition,
                'verified_by' => $adminId,
                'verified_at' => now(),
                'placed_at' => now(),
            ]);

            return $registration;
        });
    }

    /**
     * Menolak pendaftaran (jika sebelumnya disetujui, kembalikan kuota).
     */
    public function rejectRegistration(Registration $registration, string $reason, int $adminId): Registration
    {
        return DB::transaction(function () use ($registration, $reason, $adminId) {
            $period = self::getActivePeriod();

            if ($registration->status === 'approved' && $registration->department_id) {
                $neededSlots = max(1, $registration->participant_count);
                $slotQuota = SlotQuota::where('department_id', $registration->department_id)
                    ->where('period', $period)
                    ->lockForUpdate()
                    ->first();

                if ($slotQuota) {
                    $slotQuota->quota_used = max(0, $slotQuota->quota_used - $neededSlots);
                    $slotQuota->save();
                }
            }

            $registration->update([
                'department_id' => null,
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'acceptance_message' => null,
                'supervisor_name' => null,
                'supervisor_position' => null,
                'verified_by' => $adminId,
                'verified_at' => now(),
                'placed_at' => null,
            ]);

            return $registration;
        });
    }

    /**
     * Sinkronisasi total kuota terpakai bidang berdasarkan registrasi disetujui aktual di DB.
     */
    public static function syncDepartmentQuotas()
    {
        // Otomatis ubah status pendaftar yang masa magangnya sudah lewat menjadi "completed" (Selesai).
        // Ini memastikan kuota mereka lepas dan kembali tersedia.
        Registration::where('status', 'approved')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->toDateString())
            ->update(['status' => 'completed']);

        $period = self::getActivePeriod();
        $departments = \App\Models\Department::all();

        foreach ($departments as $dept) {
            $usedCount = Registration::where('department_id', $dept->id)
                ->where('status', 'approved')
                ->sum('participant_count');

            $slot = SlotQuota::firstOrCreate(
                ['department_id' => $dept->id, 'period' => $period],
                ['quota_total' => 10, 'quota_used' => 0]
            );

            $slot->quota_used = $usedCount;
            $slot->save();
        }
    }
}
