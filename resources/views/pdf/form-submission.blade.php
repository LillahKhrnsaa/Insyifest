<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Hasil Formulir - {{ $form->name }}</title>
<style>
    @page {
        size: A4 landscape;
        margin: 20mm;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 9pt;
        color: #222;
        line-height: 1.4;
        /* ✅ Tambahkan jarak dalam di seluruh sisi */
        padding: 15mm 20mm;
        background: #fff;
    }

    /* --- HEADER (KOP SURAT) --- */
    .header-table {
        width: 100%;
        border-bottom: 2px solid #ccc;
        margin-bottom: 20px;
        padding-bottom: 15px;
    }
    .header-table td {
        border: none;
        vertical-align: middle;
    }
    .header-table h1 {
        font-size: 16pt;
        font-weight: bold;
        color: #1e40af;
        text-transform: uppercase;
        text-align: left;
        margin-bottom: 4px;
    }
    .header-table p {
        font-size: 8pt;
        color: #555;
        line-height: 1.3;
        text-align: left;
    }

    /* --- INFO DOKUMEN --- */
    .doc-info {
        width: 100%;
        margin-bottom: 20px;
    }
    .doc-info h3 {
        font-size: 14pt;
        color: #1e40af;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .doc-info p {
        font-size: 9pt;
        color: #444;
        margin: 2px 0 10px 0;
    }
    .meta-table {
        width: 100%;
        margin-top: 10px;
        border-top: 1px solid #eee;
        padding-top: 8px;
    }
    .meta-table td {
        border: none;
        padding: 2px 0;
        font-size: 8pt;
        color: #666;
    }

    /* --- TABEL DATA UTAMA --- */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }
    .data-table thead {
        background-color: #1e40af;
        border: 1px solid #1e40af;
    }
    .data-table th {
        color: #fff;
        font-weight: 600;
        padding: 10px 8px;
        text-align: left;
        text-transform: uppercase;
        font-size: 8pt;
        letter-spacing: 0.5px;
    }
    .data-table td {
        padding: 8px;
        border: 1px solid #ddd;
        font-size: 8.5pt;
        vertical-align: middle;
    }
    .data-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }
    .col-no {
        width: 35px;
        text-align: center;
    }
    .col-timestamp,
    .col-timestamp-header {
        width: 100px;
        text-align: center;
        white-space: nowrap;
    }
    .col-timestamp {
        font-size: 8pt;
        color: #555;
        line-height: 1.5;
    }

    /* --- FOOTER & TANDA TANGAN --- */
    .footer {
        margin-top: 30px;
        padding-top: 10px;
        border-top: 2px solid #ccc;
        overflow: auto;
    }
    .footer-note {
        font-size: 8pt;
        color: #777;
        text-align: left;
        float: left;
    }
    .signature {
        float: right;
        width: 250px; 
        text-align: left; 
        margin-top: 10px;
    }
    .signature p {
        font-size: 9pt;
        color: #222;
        margin-bottom: 2px;
    }
    .signature-img {
        width: 140px; 
        height: auto;
        margin-top: 10px;
        margin-bottom: 5px;
        display: block;
    }
    .signature-name-wrapper {
        margin-top: 0;
        margin-bottom: 1px;
    }
    .signature-name {
        font-weight: bold;
        border-bottom: 1px solid #333;
        display: inline-block;
        padding-bottom: 2px;
    }
    .signature-title {
        font-size: 9pt;
        color: #333;
        margin-top: 0; 
    }
</style>
</head>
<body>

{{-- HEADER --}}
<table class="header-table">
    <tr>
        <td style="width: 85px; padding-right: 15px;">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="width: 75px; height: 75px; object-fit: contain;">
        </td>
        <td>
            <h1>CIKAMPEK SWIMMING CLUB</h1>
            <p>
                C4, Jl. Mashudi No.30, Pucung, Kec. Kota Baru, Karawang, Jawa Barat 41374<br>
                Telp: (+62) 858-9496-1449
            </p>
        </td>
    </tr>
</table>

{{-- INFO FORM --}}
<div class="doc-info">
    <h3>{{ $form->name }}</h3>
    @if($form->description)
        <p>{{ $form->description }}</p>
    @endif

    <table class="meta-table">
        <tr>
            <td style="width: 15%;">
                <strong>Total Submissions:</strong> {{ $submissions->count() }} data
            </td>
            <td style="text-align: center;">
                <strong>Dicetak:</strong> {{ now()->format('d F Y, H:i') }} WIB
            </td>
            <td style="text-align: right; width: 25%;">
                <strong>Dicetak oleh:</strong> {{ auth()->user()->name ?? 'Admin' }}
            </td>
        </tr>
    </table>
</div>

{{-- DATA TABEL --}}
<table class="data-table">
    <thead>
        <tr>
            <th class="col-no">No</th>
            @foreach($fields as $key => $field)
                <th>{{ $field['label'] ?? ucfirst($field['name'] ?? 'Kolom '.$loop->iteration) }}</th>
            @endforeach
            <th class="col-timestamp-header">Waktu Submit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($submissions as $index => $submission)
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                @foreach($fields as $key => $field)
                    @php
                        $value = $submission->data[$field['name']] ?? $submission->data[$key] ?? '-';
                    @endphp
                    <td>{{ $value }}</td>
                @endforeach
                <td class="col-timestamp">
                    {{ $submission->submitted_at->format('d/m/Y') }}<br>
                    {{ $submission->submitted_at->format('H:i') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- FOOTER --}}
<div class="footer">
    <p class="footer-note">
        Dokumen ini digenerate otomatis oleh sistem dan sah tanpa tanda tangan basah
    </p>

    <div class="signature">
        <p>Cikampek, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>

        <img src="{{ public_path('images/ttd.png') }}" alt="Tanda Tangan" class="signature-img">

        <p class="signature-name-wrapper">
            <span class="signature-name">Adinda Larasati</span>
        </p>
        <p class="signature-title">Admin</p>
    </div>
</div>

</body>
</html>
