<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Slip Gaji - {{ $salary->coach->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }
        .container {
            padding: 40px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a; 
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo-cell {
            width: 80px;
            vertical-align: middle;
        }
        .logo-img {
            width: 70px;
            height: auto;
            display: block;
        }
        .company-cell {
            vertical-align: middle;
            padding-left: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: 900;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .company-address {
            font-size: 10px;
            color: #555;
        }
        .slip-cell {
            vertical-align: middle;
            text-align: right;
        }
        .slip-title {
            font-size: 16px;
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .slip-period {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border: 1.5px solid #16a34a;
            color: #16a34a;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            background-color: #f0fdf4;
        }

        .info-box {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        .info-label { font-weight: bold; color: #64748b; width: 110px; }
        .info-value { color: #0f172a; font-weight: 700; }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .details-table th {
            background-color: #1e3a8a;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .details-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        
        .row-group-header td {
            background-color: #eef2ff; 
            color: #1e3a8a;
            font-weight: 800;
            font-size: 10px;
            padding-top: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #c7d2fe;
        }

        .row-subtotal td {
            font-weight: bold;
            color: #334155;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .col-label { color: #475569; }
        .col-value { color: #0f172a; text-align: center; font-family: Consolas, monospace; font-size: 10px; }
        .col-total { text-align: right; font-weight: 700; color: #0f172a; }

        .badge-sub {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            margin-left: 4px;
            vertical-align: middle;
        }

        .total-box {
            background-color: #1e3a8a;
            color: white;
            padding: 15px 20px;
            display: flex;
        }
        .total-table { width: 100%; border-collapse: collapse; }
        .total-title { font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .total-amount { font-size: 20px; font-weight: 800; text-align: right; }

        .signature-box {
            width: 200px;
            float: right;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    @php
        $coachName = $salary->coach->user->name ?? '-';
        $period = ucfirst($salary->month);
        
        $originalCount = $salary->coach ? $salary->coach->members()->count() : 0;
        $additionalData = $salary->additional_athletes;
        $additionalCount = 0;
        $additionalNamesString = '';

        if (is_array($additionalData)) {
            $additionalCount = count($additionalData);
            $additionalNamesString = implode(', ', $additionalData);
        } elseif (is_string($additionalData) && !empty($additionalData)) {
            $decoded = json_decode($additionalData, true);
            if (is_array($decoded)) {
                $additionalCount = count($decoded);
                $additionalNamesString = implode(', ', $decoded);
            }
        }
        $totalMembers = $originalCount + $additionalCount;
        
        $totalMeetingFee = $salary->training_sessions * $salary->per_meeting_fee;
        $totalMemberFee = $totalMembers * $salary->per_member_fee;
    @endphp

    <div class="container">
        
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                
                <td class="company-cell">
                    <div class="company-name">Cikampek Swimming Club</div>
                    <div class="company-address">C4, Jl. Mashudi No.30, Pucung, Kec. Kota Baru, Karawang, Jawa Barat 41374</div>
                </td>
                
                <td class="slip-cell">
                    <div class="slip-title">SLIP GAJI</div>
                    <div class="slip-period">Periode: {{ $period }}</div>
                    @if($salary->status === 'paid')
                        <div style="margin-top: 4px;">
                            <span class="status-badge">LUNAS / PAID</span>
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <div class="info-box">
            <table width="100%">
                <tr>
                    <td class="info-label">Nama Pelatih</td>
                    <td class="info-value">: {{ $coachName }}</td>
                    <td class="info-label" style="text-align:right; padding-right:10px;">Tanggal Cetak</td>
                    <td class="info-value" width="120">: {{ date('d F Y') }}</td>
                </tr>
            </table>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th width="45%">KETERANGAN</th>
                    <th width="25%" style="text-align:center;">DETAIL / TARIF</th>
                    <th width="30%" style="text-align:right;">SUBTOTAL (IDR)</th>
                </tr>
            </thead>
            <tbody>
                
                <tr class="row-group-header"><td colspan="3">A. PENDAPATAN PERTEMUAN</td></tr>
                <tr>
                    <td class="col-label">Jumlah Pertemuan</td>
                    <td class="col-value">{{ $salary->training_sessions }} Kali</td>
                    <td class="col-total">-</td>
                </tr>
                <tr>
                    <td class="col-label">Nominal per Pertemuan</td>
                    <td class="col-value">Rp {{ number_format($salary->per_meeting_fee, 0, ',', '.') }}</td>
                    <td class="col-total">-</td>
                </tr>
                <tr class="row-subtotal">
                    <td colspan="2">Total Pendapatan Pertemuan</td>
                    <td class="col-total">Rp {{ number_format($totalMeetingFee, 0, ',', '.') }}</td>
                </tr>

                <tr class="row-group-header"><td colspan="3">B. PENDAPATAN ATLET</td></tr>
                <tr>
                    <td class="col-label" style="vertical-align: top; padding-bottom: 10px;">
                        <span style="display:block; margin-bottom: 2px;">Jumlah Atlet</span>
                        
                        <div style="font-size:9px; color:#64748b;">
                            ({{ $originalCount }} Binaan + {{ $additionalCount }} Tambahan)
                        </div>
                        
                        @if($additionalCount > 0)
                            <div style="margin-top: 4px; font-size: 8px;">
                                <span style="font-weight: bold; color: #1e40af;">Tambahan:</span>
                                <span style="color: #334155; font-style: italic;">
                                    {{ $additionalNamesString }}
                                </span>
                            </div>
                        @endif
                    </td>
                    <td class="col-value" style="vertical-align: top;">{{ $totalMembers }} Orang</td>
                    <td class="col-total" style="vertical-align: top;">-</td>
                </tr>
                <tr>
                    <td class="col-label">Nominal per Atlet</td>
                    <td class="col-value">Rp {{ number_format($salary->per_member_fee, 0, ',', '.') }}</td>
                    <td class="col-total">-</td>
                </tr>
                <tr class="row-subtotal">
                    <td colspan="2">Total Pendapatan Atlet</td>
                    <td class="col-total">Rp {{ number_format($totalMemberFee, 0, ',', '.') }}</td>
                </tr>

                <tr class="row-group-header"><td colspan="3">C. TUNJANGAN LAIN</td></tr>
                <tr>
                    <td class="col-label">Uang Transport</td>
                    <td class="col-value">-</td>
                    <td class="col-total">Rp {{ number_format($salary->transport_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="col-label">Uang Kesehatan</td>
                    <td class="col-value">-</td>
                    <td class="col-total">Rp {{ number_format($salary->health_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="col-label">Bonus Tambahan</td>
                    <td class="col-value">-</td>
                    <td class="col-total">Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                </tr>

            </tbody>
        </table>

        <div class="total-box">
            <table class="total-table">
                <tr>
                    <td class="total-title">TOTAL DITERIMA</td>
                    <td class="total-amount">Rp {{ number_format($salary->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="signature-box">
            <div style="margin-bottom:8px;">Cikampek, {{ date('d F Y') }}</div>
            <div style="font-size:10px; color:#64748b;">Disetujui Oleh,</div>
            
            <img src="{{ public_path('images/ttd.png') }}" style="height:65px; margin:5px auto; display:block;">
            
            <div style="font-weight:bold; border-top:1px solid #1e293b; padding-top:6px; margin-top:2px;">
                Moh Luthfi Adistira W.
            </div>
            <div style="font-size:9px; color:#64748b;">Owner / Head Coach</div>
        </div>

    </div>
</body>
</html>