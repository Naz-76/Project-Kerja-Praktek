<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationStatusMail;
use App\Models\Department;
use App\Models\Registration;
use App\Services\QuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    protected $quotaService;

    public function __construct(QuotaService $quotaService)
    {
        $this->quotaService = $quotaService;
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $search = $request->query('search');

        $query = Registration::with(['user', 'preferredDepartment', 'department', 'leader', 'institution']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('leader', function ($q2) use ($search) {
                    $q2->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nis_nim', 'like', "%{$search}%");
                })->orWhereHas('institution', function ($q2) use ($search) {
                    $q2->where('institution_name', 'like', "%{$search}%");
                });
            });
        }

        if ($status === 'pending') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $registrations = $query->paginate(10)->withQueryString();
        $departments = Department::with('slotQuotas')->get();

        return view('admin.verification.index', compact('registrations', 'departments', 'status', 'search'));
    }

    public function show($id)
    {
        $registration = Registration::with(['user', 'preferredDepartment', 'department', 'participants', 'institution', 'documents', 'replyLetter', 'verifier'])
            ->findOrFail($id);

        $departments = Department::with(['slotQuotas', 'fieldSupervisors'])->get();

        return view('admin.verification.show', compact('registration', 'departments'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'acceptance_message' => 'nullable|string|max:1000',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_position' => 'nullable|string|max:255',
            'supervisor_phone' => 'nullable|string|max:255',
            'reply_letter' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $registration = Registration::findOrFail($id);

        try {
            $this->quotaService->approveRegistration(
                $registration,
                $request->department_id,
                Auth::id(),
                $request->acceptance_message,
                $request->supervisor_name,
                $request->supervisor_position
            );

            // Simpan nomor HP pembimbing lapangan
            $registration->update(['supervisor_phone' => $request->supervisor_phone]);

            if ($request->hasFile('reply_letter')) {
                // Hapus file surat balasan lama jika sudah ada
                $existingLetter = \App\Models\ReplyLetter::where('registration_id', $registration->id)->first();
                if ($existingLetter && $existingLetter->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingLetter->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existingLetter->file_path);
                }

                $file = $request->file('reply_letter');
                $path = $file->store('reply_letters', 'public');
                \App\Models\ReplyLetter::updateOrCreate(
                    ['registration_id' => $registration->id],
                    [
                        'file_path' => $path,
                        'uploaded_by' => Auth::id(),
                        'uploaded_at' => now(),
                    ]
                );
            }

            // Kirim notifikasi email ke Gmail pendaftar
            $this->sendStatusEmail($registration);

            return redirect()->route('admin.verification.index')
                ->with('success', 'Pengajuan berhasil disetujui, ditempatkan pada bidang pilihan, dan surat notifikasi dikirimkan ke email pendaftar.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'reply_letter' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $registration = Registration::findOrFail($id);

        try {
            $this->quotaService->rejectRegistration($registration, $request->rejection_reason, Auth::id());

            if ($request->hasFile('reply_letter')) {
                // Hapus file surat balasan lama jika sudah ada
                $existingLetter = \App\Models\ReplyLetter::where('registration_id', $registration->id)->first();
                if ($existingLetter && $existingLetter->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingLetter->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existingLetter->file_path);
                }

                $file = $request->file('reply_letter');
                $path = $file->store('reply_letters', 'public');
                \App\Models\ReplyLetter::updateOrCreate(
                    ['registration_id' => $registration->id],
                    [
                        'file_path' => $path,
                        'uploaded_by' => Auth::id(),
                        'uploaded_at' => now(),
                    ]
                );
            }

            // Kirim notifikasi email ke Gmail pendaftar
            $this->sendStatusEmail($registration);

            return redirect()->route('admin.verification.index')
                ->with('warning', 'Pengajuan telah ditolak dan surat pemberitahuan telah dikirimkan ke email pendaftar.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mengirim notifikasi email status penerimaan / penolakan ke pendaftar secara aman.
     */
    protected function sendStatusEmail(Registration $registration): void
    {
        try {
            $registration->load(['user', 'department', 'leader', 'participants', 'institution', 'replyLetter']);

            $recipientEmail = $registration->user->email ?? $registration->leader?->email;

            if ($recipientEmail && filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                $mailable = new RegistrationStatusMail($registration);
                $mail = Mail::to($recipientEmail);

                // Jika ada email guru pembimbing (untuk siswa SMK), sertakan sebagai tembusan CC
                $teacherEmail = $registration->institution?->teacher_email;
                if ($teacherEmail && filter_var($teacherEmail, FILTER_VALIDATE_EMAIL) && $teacherEmail !== $recipientEmail) {
                    $mail->cc($teacherEmail);
                }

                $mail->send($mailable);
                Log::info("Email notifikasi status [{$registration->status}] berhasil dikirim ke {$recipientEmail} untuk Registrasi #{$registration->id}");
            }
        } catch (\Throwable $e) {
            // Catat log jika pengiriman gagal tanpa membatalkan proses verifikasi database
            Log::error("Gagal mengirim email notifikasi status Registrasi #{$registration->id}: " . $e->getMessage());
        }
    }
}

