<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RegistrationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Registration $registration;
    public string $statusType;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
        $this->statusType = $registration->status;
    }

    /**
     * Dapatkan amplop pesan email (Subjek & Penerima).
     */
    public function envelope(): Envelope
    {
        $program = $this->registration->program_type ?? 'Magang/PKL';

        if ($this->statusType === 'approved') {
            $subject = "[RESMI] Pengajuan {$program} Anda DITERIMA — Diskominfo Kab. Garut";
        } else {
            $subject = "[PEMBERITAHUAN] Hasil Seleksi Pengajuan {$program} — Diskominfo Kab. Garut";
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Dapatkan definisi konten email (Template Blade View).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-status',
            with: [
                'registration' => $this->registration,
                'statusType' => $this->statusType,
            ],
        );
    }

    /**
     * Lampiran email (menyematkan surat balasan resmi jika diunggah).
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->registration->replyLetter && $this->registration->replyLetter->file_path) {
            $path = $this->registration->replyLetter->file_path;
            if (Storage::disk('public')->exists($path)) {
                $attachments[] = Attachment::fromStorageDisk('public', $path)
                    ->as('Surat_Balasan_Resmi_Diskominfo_Garut.pdf')
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}
