<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Arsip Member - {{ $archive->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .container {
            padding: 40px;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #1e3a8a; 
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .logo-cell {
            width: 90px;
            vertical-align: middle;
        }
        .logo-img {
            width: 80px;
            height: auto;
            display: block;
        }
        .company-cell {
            vertical-align: middle;
            padding-left: 10px;
        }
        .company-name {
            font-size: 20px;
            font-weight: 900;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .company-address {
            font-size: 11px;
            color: #4b5563;
        }
        .title-box {
            text-align: center;
            margin-bottom: 30px;
        }
        .doc-title {
            font-size: 18px;
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .doc-number {
            font-size: 11px;
            color: #666;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .content-table td {
            padding: 10px 5px;
            border-bottom: 1px solid #f3f4f6;
        }
        .label {
            width: 150px;
            font-weight: bold;
            color: #4b5563;
        }
        .separator {
            width: 10px;
            text-align: center;
        }
        .value {
            color: #111;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-aktif { background-color: #dcfce7; color: #166534; }
        .status-tidak-aktif { background-color: #fee2e2; color: #991b1b; }
        
        .footer-table {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            width: 200px;
            text-align: center;
        }
        .signature-space {
            height: 80px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td class="company-cell">
                    <div class="company-name">Cikampek Swimming Club</div>
                    <div class="company-address">C4, Jl. Mashudi No.30, Pucung, Kec. Kota Baru, Karawang, Jawa Barat 41374</div>
                    <div class="company-address">Email: cikampekscl@gmail.com | WA: (+62) 858-9496-1449</div>
                </td>
            </tr>
        </table>

        <div class="title-box">
            <div class="doc-title">DATA ARSIP MEMBER</div>
            <div class="doc-number">Periode Arsip: {{ $archive->archive_period }}</div>
        </div>

        <table class="content-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->name }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->email }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Telepon</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->phone }}</td>
            </tr>
            <tr>
                <td class="label">Paket Latihan</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->training_package_name }}</td>
            </tr>
            <tr>
                <td class="label">Coach / Pelatih</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->coach_name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Jadwal Latihan</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->training_day ?? '—' }} {{ $archive->training_time ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Status Terakhir</td>
                <td class="separator">:</td>
                <td class="value">
                    <span class="status-badge {{ $archive->status === 'AKTIF' ? 'status-aktif' : 'status-tidak-aktif' }}">
                        {{ $archive->status }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal Bergabung</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->start_date ? $archive->start_date->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Berakhir</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->end_date ? $archive->end_date->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Diarsipkan</td>
                <td class="separator">:</td>
                <td class="value">{{ $archive->created_at->format('d F Y H:i') }}</td>
            </tr>
        </table>

        <table class="footer-table">
            <tr>
                <td width="60%"></td>
                <td class="signature-box">
                    <div>Cikampek, {{ date('d F Y') }}</div>
                    <div style="margin-top: 5px;">Mengetahui,</div>
                    <div class="signature-space">
                        @if(file_exists(public_path('images/ttd.png')))
                            <img src="{{ public_path('images/ttd.png') }}" style="height:70px; margin: 10px auto; display:block;">
                        @endif
                    </div>
                    <div class="signature-name">Moh Luthfi Adistira W.</div>
                    <div style="font-size: 10px; color: #666;">Owner / Head Coach</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
