<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $salary->coach->user->name ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'Calibri', Arial, sans-serif;
            background: #fff;
            color: #222;
            line-height: 1.6;
            padding: 40px;
            margin: 0;
        }

        .container {
            max-width: 780px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 35px 45px;
            position: relative;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #555;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .header img {
            height: 60px;
            margin-right: 15px;
        }

        .header h1 {
            font-size: 20px;
            color: #000;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 13px;
            color: #555;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        .info-table th, .info-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            font-size: 13px;
        }

        .info-table th {
            background: #00b3ff;
            text-align: left;
            font-weight: 600;
        }

        .total-row td {
            font-weight: bold;
            background: #f8f8f8;
        }

        /* BAGIAN TANDA TANGAN YANG DIPERBAIKI */
        .signature-section {
            margin-top: 30px;
            position: relative;
        }

        .signature-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .signature-box {
            text-align: center;
            width: 300px;
            padding: 15px;
            margin-left: auto; 
        }

        .signature-date {
            margin-bottom: 20px;
            font-size: 13px;
        }

        .signature-image-container {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
        }

        .signature-image {
            max-height: 70px;
            max-width: 200px;
            filter: brightness(0) saturate(100%) contrast(1000%);
        }

        .signature-line {
            border-top: 1.5px solid #000;
            width: 250px;
            margin: 0 auto;
            height: 1px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 8px;
            font-size: 14px;
        }

        .powered {
            text-align: center;
            font-size: 11px;
            color: #888;
            border-top: 1px solid #eee;
        }

        /* Untuk memastikan konsistensi print */
        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo CSC">
        <div>
            <h1>CIKAMPEK SWIMMING CLUB</h1>
            <h2>Slip Gaji Pelatih — {{ ucfirst($salary->month) }} {{ date('Y') }}</h2>
        </div>
    </div>

    <table class="info-table">
        <tr><th>Keterangan</th><th>Detail</th></tr>
        <tr><td>Nama Pelatih</td><td>{{ $salary->coach->user->name ?? 'N/A' }}</td></tr>
        <tr><td>Jumlah Pertemuan</td><td>{{ $salary->training_sessions }}</td></tr>
        <tr><td>Jumlah Atlet</td><td>{{ $memberCount }}</td></tr>
        <tr><td>Bonus</td><td>Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td></tr>
        <tr><td>Transport</td><td>Rp {{ number_format($salary->transport_fee, 0, ',', '.') }}</td></tr>
        <tr><td>Per Pertemuan</td><td>Rp {{ number_format($salary->per_meeting_fee, 0, ',', '.') }}</td></tr>
        <tr><td>Per Atlet</td><td>Rp {{ number_format($salary->per_member_fee, 0, ',', '.') }}</td></tr>
        <tr><td>Kesehatan</td><td>Rp {{ number_format($salary->health_fee, 0, ',', '.') }}</td></tr>
        <tr class="total-row"><td>Total</td><td>Rp {{ number_format($salary->total_amount, 0, ',', '.') }}</td></tr>
    </table>

    <!-- BAGIAN TANDA TANGAN YANG SUDAH DIPERBAIKI -->
    <div class="signature-section">
        <div class="signature-wrapper">
            <div class="signature-box">
                <div class="signature-date">Cikampek, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="signature-image-container">
                    <img src="{{ public_path('images/ttd.png') }}" alt="TTD" class="signature-image">
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">Moh Luthfi Adistira Wirawan</div>
            </div>
        </div>
    </div>

    <div class="powered">
        © {{ date('Y') }} Cikampek Swimming Club Management System
    </div>
</div>
</body>
</html>