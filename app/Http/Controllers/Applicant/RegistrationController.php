<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Document;
use App\Models\Registration;
use App\Models\RegistrationInstitution;
use App\Models\RegistrationParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        
        // Cek jika pendaftar sudah memiliki pengajuan aktif yang pending / approved
        $existing = Registration::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('applicant.my-registration.show', $existing->id)
                ->with('info', 'Anda sudah memiliki pengajuan aktif.');
        }

        $departments = Department::all();

        return view('applicant.registration.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_status' => 'required|in:Siswa,Mahasiswa',
            'program_type' => 'required|in:PKL,KP,Magang',
            
            'participants' => 'required|array|min:1',
            'participants.*.full_name' => 'required|string|max:255',
            'participants.*.nis_nim' => 'required|string|max:100',
            'leader_phone' => 'required|string|max:20',
            'teacher_name' => 'required_if:applicant_status,Siswa|nullable|string|max:255',
            'teacher_phone' => 'required_if:applicant_status,Siswa|nullable|string|max:50',
            'teacher_email' => 'nullable|email|max:255',
            
            'institution_name' => 'required|string|max:255',
            'major' => 'required|string|max:100',
            
            'preferred_department_id' => 'nullable|exists:departments,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            
            'doc_surat_pengantar' => 'required|file|mimes:pdf|max:5120',
        ]);

        $user = Auth::user();

        // Cek kembali untuk mencegah double-submit / eksploitasi jika user mem-bypass halaman create
        $existing = Registration::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('applicant.dashboard')
                ->with('error', 'Gagal mengirim pengajuan. Anda sudah memiliki pengajuan yang aktif.');
        }

        DB::transaction(function () use ($request, $validated, $user) {
            $participants = $request->input('participants');
            $participantCount = count($participants);

            // 1. Create Registration
            $registration = Registration::create([
                'user_id' => $user->id,
                'applicant_status' => $validated['applicant_status'],
                'program_type' => $validated['program_type'],
                'preferred_department_id' => $validated['preferred_department_id'] ?? null,
                'status' => 'pending',
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'participant_count' => $participantCount,
                'submitted_at' => now(),
            ]);

            // 2. Create Participants
            foreach ($participants as $index => $pData) {
                $isLeader = ($index == 0);
                RegistrationParticipant::create([
                    'registration_id' => $registration->id,
                    'full_name' => $pData['full_name'],
                    'nis_nim' => $pData['nis_nim'],
                    'institution_level' => null, // No longer required in form
                    'major' => $validated['major'], // Apply same major to all
                    'semester_or_grade' => null, // No longer required
                    'phone' => $isLeader ? $validated['leader_phone'] : null,
                    'email' => $isLeader ? $user->email : null,
                    'is_leader' => $isLeader,
                ]);
            }

            // 3. Create Institution
            RegistrationInstitution::create([
                'registration_id' => $registration->id,
                'institution_name' => $validated['institution_name'],
                'institution_address' => '-', // Address is no longer asked, can be updated later if needed or we can ask it. User didn't mention it, but we can just use '-'
                'contact_person' => null,
                'contact_phone' => null,
                'teacher_name' => $validated['teacher_name'],
                'teacher_email' => $validated['teacher_email'] ?? null,
                'teacher_phone' => $validated['teacher_phone'],
            ]);

            // 4. Handle Document (Only Surat Pengantar for now)
            if ($request->hasFile('doc_surat_pengantar')) {
                $file = $request->file('doc_surat_pengantar');
                $path = $file->store('documents', 'public');
                
                Document::create([
                    'registration_id' => $registration->id,
                    'document_category' => 'surat_pengantar',
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'uploaded_at' => now(),
                ]);
            }
        });

        return redirect()->route('applicant.dashboard')->with('success', 'Pengajuan pendaftaran berhasil dikirim! Silakan tunggu verifikasi admin.');
    }
}
