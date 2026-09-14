<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $statusType === 'approved' ? 'Pengajuan Diterima' : 'Status Pengajuan' }} — Diskominfo Kab. Garut</title>
    <style>
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            color: #1e293b;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #014495 0%, #0B6FBB 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #e0f2fe;
            font-weight: 500;
        }
        .content {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 15px;
            margin-bottom: 18px;
            color: #334155;
        }
        .status-card {
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .status-approved {
            background-color: #ecfdf5;
            border: 1.5px solid #a7f3d0;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fff1f2;
            border: 1.5px solid #fecdd3;
            color: #9f1239;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .badge-approved {
            background: #059669;
            color: #ffffff;
        }
        .badge-rejected {
            background: #e11d48;
            color: #ffffff;
        }
        .status-title {
            font-size: 17px;
            font-weight: 700;
            margin: 0 0 8px;
        }
        .status-desc {
            font-size: 13px;
            margin: 0;
            line-height: 1.6;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
            font-size: 13px;
        }
        .info-table tr {
            border-bottom: 1px solid #f1f5f9;
        }
        .info-table td {
            padding: 10px 4px;
        }
        .info-table td.label {
            color: #64748b;
            width: 38%;
            font-weight: 600;
        }
        .info-table td.value {
            color: #0f172a;
            font-weight: 700;
        }
        .details-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin: 20px 0;
            font-size: 13px;
        }
        .details-box h4 {
            margin: 0 0 8px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .attachment-alert {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 22px 0;
            font-size: 13px;
            color: #1e40af;
            display: flex;
            align-items: center;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0 16px;
        }
        .btn {
            display: inline-block;
            background: #014495;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(1, 68, 149, 0.25);
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
        .footer strong {
            color: #334155;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>PORMA DISKOMINFO</h1>
            <p>Dinas Komunikasi dan Informatika Kabupaten Garut</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Yth. <strong>{{ $registration->leader->full_name ?? $registration->user->name }}</strong>,<br>
                @if($registration->institution)
                    dari <strong>{{ $registration->institution->institution_name }}</strong>
                @endif
            </div>

            <p style="font-size: 14px; color: #475569; margin: 0 0 16px;">
                Terima kasih telah mengajukan permohonan <strong>{{ $registration->program_type }}</strong> melalui Portal Resmi Diskominfo Kabupaten Garut. Berikut ini adalah informasi hasil verifikasi dan tinjauan berkas administrasi Anda:
            </p>

            @if($statusType === 'approved')
                <!-- Status Box DITERIMA -->
                <div class="status-card status-approved">
                    <span class="status-badge badge-approved">✓ DITERIMA (LOLOS)</span>
                    <h2 class="status-title" style="color: #065f46;">Selamat! Pengajuan Anda Disetujui</h2>
                    <p class="status-desc" style="color: #047857;">
                        Permohonan Anda dinyatakan <strong>LOLOS</strong> verifikasi administrasi dan telah resmi dialokasikan kuota pada bidang operasional Diskominfo Kabupaten Garut.
                    </p>
                </div>

                <!-- Detail Penempatan Bidang & Pembimbing -->
                <div class="details-box">
                    <h4>Informasi Penempatan & Pembimbing Lapangan</h4>
                    <table class="info-table" style="margin: 0;">
                        <tr>
                            <td class="label">Bidang Definitif</td>
                            <td class="value" style="color: #014495;">{{ $registration->department->name ?? '-' }}</td>
                        </tr>
                        @if($registration->supervisor_name)
                            <tr>
                                <td class="label">Pembimbing Lapangan</td>
                                <td class="value">{{ $registration->supervisor_name }}</td>
                            </tr>
                            @if($registration->supervisor_position)
                                <tr>
                                    <td class="label">Jabatan Pembimbing</td>
                                    <td class="value" style="font-weight: 500;">{{ $registration->supervisor_position }}</td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>

                @if($registration->acceptance_message)
                    <!-- Arahan / Catatan Admin -->
                    <div class="details-box" style="background: #f0fdf4; border-color: #bbf7d0;">
                        <h4 style="color: #166534;">Arahan & Catatan Khusus dari Verifikator:</h4>
                        <p style="margin: 0; color: #14532d; font-size: 13px; white-space: pre-line;">
                            {{ $registration->acceptance_message }}
                        </p>
                    </div>
                @endif

            @else
                <!-- Status Box DITOLAK -->
                <div class="status-card status-rejected">
                    <span class="status-badge badge-rejected">✕ BELUM DAPAT DITERIMA</span>
                    <h2 class="status-title" style="color: #9f1239;">Pengajuan Belum Dapat Disetujui</h2>
                    <p class="status-desc" style="color: #be123c;">
                        Mohon maaf, permohonan Anda untuk saat ini <strong>belum dapat disetujui</strong> oleh tim kepegawaian Diskominfo Kabupaten Garut.
                    </p>
                </div>

                @if($registration->rejection_reason)
                    <!-- Alasan Penolakan -->
                    <div class="details-box" style="background: #fff5f5; border-color: #fecdd3;">
                        <h4 style="color: #991b1b;">Alasan Penolakan dari Admin:</h4>
                        <p style="margin: 0; color: #7f1d1d; font-size: 13px; white-space: pre-line;">
                            {{ $registration->rejection_reason }}
                        </p>
                    </div>
                @endif
            @endif

            <!-- Ringkasan Data Pengajuan -->
            <div class="details-box">
                <h4>Ringkasan Berkas Pendaftaran</h4>
                <table class="info-table" style="margin: 0;">
                    <tr>
                        <td class="label">Nomor Registrasi</td>
                        <td class="value">#REG-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Program Kerja</td>
                        <td class="value">{{ $registration->program_type }} ({{ $registration->applicant_status }})</td>
                    </tr>
                    <tr>
                        <td class="label">Asal Institusi</td>
                        <td class="value">{{ $registration->institution->institution_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Program Studi / Jurusan</td>
                        <td class="value">{{ $registration->participants->first()->major ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jumlah Anggota Tim</td>
                        <td class="value">{{ $registration->participant_count }} Orang</td>
                    </tr>
                    <tr>
                        <td class="label">Rencana Periode</td>
                        <td class="value">
                            {{ \Carbon\Carbon::parse($registration->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($registration->end_date)->translatedFormat('d M Y') }}
                        </td>
                    </tr>
                </table>
            </div>

            @if($registration->replyLetter)
                <!-- Notifikasi Lampiran File PDF -->
                <div class="attachment-alert">
                    <span>
                        📄 <strong>Surat Balasan Resmi Diskominfo</strong> telah kami lampirkan bersama email ini (format PDF). Anda dapat mengunduh dan mencetaknya langsung sebagai berkas fisik.
                    </span>
                </div>
            @endif

            <!-- Button CTA -->
            <div class="btn-container">
                <a href="{{ route('applicant.dashboard') }}" class="btn">
                    Buka Dashboard Pengajuan Saya →
                </a>
            </div>

            <p style="font-size: 12px; color: #94a3b8; text-align: center; margin-top: 24px;">
                Jika tombol di atas tidak dapat diklik, salin tautan berikut ke browser Anda:<br>
                <a href="{{ route('applicant.dashboard') }}" style="color: #014495; word-break: break-all;">
                    {{ route('applicant.dashboard') }}
                </a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>Dinas Komunikasi dan Informatika Kabupaten Garut</strong><br>
            Jl. Pembangunan No. 181, Sukagalih, Kec. Tarogong Kidul, Kabupaten Garut, Jawa Barat 44151<br>
            Website: <a href="https://diskominfo.garutkab.go.id" style="color: #014495;">diskominfo.garutkab.go.id</a> | Email: <a href="mailto:diskominfo@garutkab.go.id" style="color: #014495;">diskominfo@garutkab.go.id</a><br><br>
            <span style="font-size: 11px; color: #94a3b8;">
                Email ini dikirim secara otomatis oleh sistem PORMA Diskominfo Garut. Harap tidak membalas langsung ke alamat email ini.
            </span>
        </div>
    </div>
</body>
</html>
