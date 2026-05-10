<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #1f2937;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .container {
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a; 
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-cell {
            width: 70px;
            vertical-align: middle;
        }
        .logo-img {
            width: 60px;
            height: auto;
            display: block;
        }
        .company-cell {
            vertical-align: middle;
            padding-left: 10px;
        }
        .company-name {
            font-size: 16px;
            font-weight: 900;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: white;
            padding: 8px 5px;
            text-align: left;
            border: 1px solid #1e3a8a;
        }
        .data-table td {
            padding: 6px 5px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .status-badge {
            font-weight: bold;
            font-size: 8px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            color: #666;
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
                    <div style="font-size: 9px; color: #4b5563;">Laporan Arsip Member - Dicetak pada {{ date('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>

        <div class="report-title">{{ $title }}</div>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">Coach</th>
                    <th width="10%">Nama Member</th>
                    <th width="8%">Hari</th>
                    <th width="7%">Jam</th>
                    <th width="8%">Periode</th>
                    <th width="12%">Email</th>
                    <th width="10%">No. HP</th>
                    <th width="15%">Paket Latihan</th>
                    <th width="7%">Status</th>
                    <th width="10%">Tgl Arsip</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $index => $record)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="background-color: #fef3c7; font-weight: bold;">{{ $record->coach_name ?? '—' }}</td>
                    <td style="font-weight: bold;">{{ $record->name }}</td>
                    <td style="text-align: center; font-weight: bold; color: #1e3a8a;">{{ $record->training_day ?? '—' }}</td>
                    <td style="text-align: center;">{{ $record->training_time ?? '—' }}</td>
                    <td>{{ $record->archive_period }}</td>
                    <td>{{ $record->email }}</td>
                    <td>{{ $record->phone }}</td>
                    <td>{{ $record->training_package_name }}</td>
                    <td style="text-align: center;">{{ $record->status }}</td>
                    <td>{{ $record->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Halaman 1 dari 1 | Cikampek Swimming Club &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
