<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bukti Pendaftaran - {{ $data['namaLengkap'] ?? '-' }}</title>
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
        .col-value { color: #0f172a; font-family: Consolas, monospace; font-size: 11px; font-weight: 700; }

        .total-box {
            background-color: #1e3a8a;
            color: white;
            padding: 15px 20px;
            display: flex;
        }
        .total-table { width: 100%; border-collapse: collapse; }
        .total-title { font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .total-amount { font-size: 16px; font-weight: 800; text-align: right; color: #facc15; }

        .signature-box {
            width: 200px;
            float: right;
            text-align: center;
            margin-top: 40px;
        }

        .alert {
            background-color: #fffbeb;
            border: 1px solid #fde047;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 10px;
            color: #854d0e;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/logocsc.png') }}" alt="Logo" class="logo-img">
                </td>
                
                <td class="company-cell">
                    <div class="company-name">Cikampek Swimming Club</div>
                    <div class="company-address">C4, Jl. Mashudi No.30, Pucung, Kec. Kota Baru, Karawang, Jawa Barat 41374</div>
                </td>
                
                <td class="slip-cell">
                    <div class="slip-title">BUKTI PENDAFTARAN</div>
                    <div class="slip-period">Waktu: {{ $data['waktuDaftar'] ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <div class="alert">
            <strong>PENTING:</strong> Simpan dokumen ini dengan baik. Informasi di bawah ini digunakan untuk masuk (login) ke dalam sistem informasi Cikampek Swimming Club.
        </div>

        <div class="info-box">
            <table width="100%">
                <tr>
                    <td class="info-label">Nama Lengkap</td>
                    <td class="info-value">: {{ $data['namaLengkap'] ?? '-' }}</td>
                    <td class="info-label" style="text-align:right; padding-right:10px;">Tanggal Cetak</td>
                    <td class="info-value" width="120">: {{ date('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Paket Latihan</td>
                    <td class="info-value">: {{ $data['paketLatihan'] ?? '-' }}</td>
                    <td class="info-label" style="text-align:right; padding-right:10px;">Status</td>
                    <td class="info-value" width="120">: <span style="color:#16a34a; border: 1px solid #16a34a; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold;">AKTIF</span></td>
                </tr>
            </table>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th width="40%">KETERANGAN</th>
                    <th width="60%">DETAIL / DATA DIRI</th>
                </tr>
            </thead>
            <tbody>
                <tr class="row-group-header"><td colspan="2">A. DATA DIRI ATLET</td></tr>
                <tr>
                    <td class="col-label">Jenis Kelamin</td>
                    <td class="col-value">{{ $data['jenisKelamin'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Tanggal Lahir</td>
                    <td class="col-value">{{ $data['tanggalLahir'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Nomor Telepon</td>
                    <td class="col-value">{{ $data['noTelepon'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Pekerjaan Ayah</td>
                    <td class="col-value">{{ $data['pekerjaanAyah'] ?? '-' }}</td>
                </tr>

                <tr class="row-group-header"><td colspan="2">B. DATA PELATIHAN</td></tr>
                <tr>
                    <td class="col-label">Coach Pembina</td>
                    <td class="col-value">{{ $data['namaCoach'] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-box">
            <table class="total-table">
                <tr>
                    <td class="total-title" style="vertical-align: top;">
                        INFORMASI AKUN LOGIN<br>
                        <span style="font-size: 9px; font-weight: normal; color: #cbd5e1; text-transform: none;">Gunakan kredensial ini untuk mengakses sistem.</span>
                    </td>
                    <td class="total-amount" style="text-align: left; padding-left: 30px;">
                        <table style="width: 100%; border: none; font-size: 13px;">
                            <tr>
                                <td style="color: #cbd5e1; width: 60px; text-transform: uppercase; font-size: 9px; font-weight: bold;">Email / Username</td>
                                <td style="color: #facc15; font-family: Consolas, monospace;">: {{ $data['email'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="color: #cbd5e1; text-transform: uppercase; font-size: 9px; font-weight: bold;">Password</td>
                                <td style="color: #facc15; font-family: Consolas, monospace;">: {{ $data['password'] ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="signature-box">
            <div style="margin-bottom:8px;">Cikampek, {{ date('d F Y') }}</div>
            <div style="font-size:10px; color:#64748b;">Administrasi CSC,</div>
            
            <img src="{{ public_path('images/ttd.png') }}" style="height:65px; margin:5px auto; display:block;">
            
            <div style="font-weight:bold; border-top:1px solid #1e293b; padding-top:6px; margin-top:2px;">
                Moh Luthfi Adistira W.
            </div>
            <div style="font-size:9px; color:#64748b;">Owner / Head Coach</div>
        </div>

    </div>
</body>
</html>
