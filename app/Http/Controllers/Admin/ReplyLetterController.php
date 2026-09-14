<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\ReplyLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReplyLetterController extends Controller
{
    public function index()
    {
        $registrations = Registration::with(['user', 'department', 'leader', 'institution', 'replyLetter'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        return view('admin.reply-letters.index', compact('registrations'));
    }

    public function upload(Request $request, $registrationId)
    {
        $request->validate([
            'reply_letter' => 'required|file|mimes:pdf|max:10240',
        ]);

        $registration = Registration::findOrFail($registrationId);

        if ($request->hasFile('reply_letter')) {
            $file = $request->file('reply_letter');
            $path = $file->store('reply_letters', 'public');

            ReplyLetter::updateOrCreate(
                ['registration_id' => $registration->id],
                [
                    'file_path' => $path,
                    'uploaded_by' => Auth::id(),
                    'uploaded_at' => now(),
                ]
            );

            return back()->with('success', 'File Surat Balasan PDF berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah file.');
    }
}
