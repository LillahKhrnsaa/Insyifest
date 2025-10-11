<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Pelatih - {{ $salary->coach->user->name ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            padding: 30px;
            color: #000;
            line-height: 1.4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        .header h2 {
            font-size: 18px;
            font-weight: normal;
            color: #333;
            margin-bottom: 30px;
        }

        /* Table Section */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .info-table th {
            background-color: #e8e8e8;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
            border: 1px solid #000;
        }

        .info-table td {
            padding: 10px 12px;
            border: 1px solid #000;
            font-size: 13px;
        }

        .info-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .label-cell {
            font-weight: bold;
            width: 50%;
        }

        .value-cell {
            width: 50%;
        }

        /* Total Row */
        .total-row {
            background-color: #d9d9d9 !important;
            font-weight: bold;
        }

        .total-row td {
            padding: 12px;
            font-size: 14px;
        }

        /* Footer Section */
        .footer {
            margin-top: 60px;
            text-align: right;
        }

        .footer p {
            margin-bottom: 80px;
            font-size: 13px;
        }

        .footer .signature-line {
            border-top: 2px solid #000;
            display: inline-block;
            width: 200px;
            padding-top: 5px;
            text-align: center;
            font-weight: bold;
            font-size: 13px;
        }

        /* Powered by */
        .powered {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Slip Gaji Pelatih Cikampek Swimming Club</h1>
            <h2>Laporan Gaji Pelatih - {{ $salary->month }}</h2>
        </div>

        <!-- Info Table -->
        <table class="info-table">
            <thead>
                <tr>
                    <th colspan="2">Keterangan Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label-cell">Nama Pelatih</td>
                    <td class="value-cell">{{ $salary->coach->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Jumlah Pertemuan</td>
                    <td class="value-cell">{{ $salary->training_sessions }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Jumlah Atlet</td>
                    <td class="value-cell">{{ $memberCount }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Tambahan/Bonus</td>
                    <td class="value-cell">Rp. {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Uang Transport</td>
                    <td class="value-cell">Rp. {{ number_format($salary->transport_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Nominal Per-Pertemuan</td>
                    <td class="value-cell">Rp. {{ number_format($salary->per_meeting_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Nominal Per-Atlet</td>
                    <td class="value-cell">Rp. {{ number_format($salary->per_member_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Uang Kesehatan</td>
                    <td class="value-cell">Rp. {{ number_format($salary->health_fee, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label-cell">Total Uang</td>
                    <td class="value-cell">Rp. {{ number_format($salary->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer with Signature -->
        <div class="footer">
            <p>Cikampek, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <div class="signature-line">
                Moh Luthfi Adistira Wirawan
            </div>
        </div>

        <!-- Powered By -->
        <div class="powered">
            Powered by Cikampek Swimming Club Management System
        </div>
    </div>
</body>
</html>