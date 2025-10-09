<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Formulir - {{ $form->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.4;
        }
        
        /* INFO DOKUMEN */
        .doc-info {
            background: #eff6ff;
            border-left: 4px solid #1e40af;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        .doc-info h3 {
            font-size: 12pt;
            color: #1e40af;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .doc-info p {
            font-size: 8pt;
            color: #4b5563;
            margin: 2px 0;
        }
        
        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table thead {
            background: linear-gradient(to bottom, #1e40af, #1e3a8a);
        }
        table thead th {
            color: white;
            font-weight: 600;
            font-size: 8.5pt;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #1e3a8a;
            vertical-align: middle;
        }
        table tbody td {
            padding: 6px;
            border: 1px solid #d1d5db;
            font-size: 8pt;
            vertical-align: top;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        table tbody tr:hover {
            background-color: #eff6ff;
        }
        
        /* NOMOR URUT */
        .no-col {
            width: 30px;
            text-align: center;
            font-weight: 600;
            color: #1e40af;
        }
        
        /* FOTO THUMBNAIL */
        .file-thumbnail {
            max-width: 80px;
            max-height: 60px;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            object-fit: cover;
        }
        .file-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            font-size: 7pt;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: 600;
        }
        .no-file {
            color: #9ca3af;
            font-style: italic;
            font-size: 7.5pt;
        }
        
        /* WAKTU SUBMIT */
        .timestamp {
            font-size: 7.5pt;
            color: #6b7280;
            white-space: nowrap;
        }
        
        /* FOOTER */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
        }
        .footer p {
            font-size: 7pt;
            color: #9ca3af;
        }
        .footer .signature {
            margin-top: 40px;
            text-align: right;
            padding-right: 50px;
        }
        .footer .signature p {
            font-size: 8pt;
            color: #374151;
            margin: 3px 0;
        }
        .footer .signature .name {
            font-weight: bold;
            margin-top: 50px;
            border-bottom: 1px solid #333;
            display: inline-block;
            padding-bottom: 2px;
        }
    </style>
</head>
<body>
    <table style="width: 100%; border-bottom: 3px solid #1e40af; padding-bottom: 12px; margin-bottom: 20px;">
        <tr>
            <td style="width: 80px; vertical-align: middle; padding-right: 15px; border: none;">
                {{-- Ganti dengan URL logo instansi lu --}}
                <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="width: 70px; height: 70px; object-fit: contain;">
                {{-- Atau pake URL online: --}}
                {{-- <img src="https://via.placeholder.com/70" alt="Logo"> --}}
            </td>
            <td style="vertical-align: middle; text-align: center; border: none;">
                <h1 style="font-size: 18pt; font-weight: bold; color: #1e40af; margin: 0 0 3px 0; text-transform: uppercase;">NAMA INSTANSI / PERUSAHAAN</h1>
                <h2 style="font-size: 14pt; font-weight: 600; color: #374151; margin: 0 0 4px 0;">DIVISI / DEPARTEMEN</h2>
                <p style="font-size: 8pt; color: #6b7280; line-height: 1.3; margin: 0;">
                    Jl. Alamat Lengkap Instansi No. 123, Kota, Provinsi 12345<br>
                    Telp: (021) 1234567 | Email: info@instansi.com | Website: www.instansi.com
                </p>
            </td>
        </tr>
    </table>

    <div class="doc-info">
        <h3>{{ $form->name }}</h3>
        @if($form->description)
            <p><strong>Deskripsi:</strong> {{ $form->description }}</p>
        @endif
        
        <table style="width: 100%; margin-top: 6px; padding-top: 6px; border-top: 1px solid #bfdbfe;">
            <tr>
                <td style="text-align: left; font-size: 7.5pt; color: #6b7280; border: none; padding: 0;">
                    <strong>Total Submissions:</strong> {{ $submissions->count() }} data
                </td>
                <td style="text-align: center; font-size: 7.5pt; color: #6b7280; border: none; padding: 0;">
                    <strong>Dicetak:</strong> {{ now()->format('d F Y, H:i') }} WIB
                </td>
                <td style="text-align: right; font-size: 7.5pt; color: #6b7280; border: none; padding: 0;">
                    <strong>Dicetak oleh:</strong> {{ auth()->user()->name ?? 'Admin' }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-col">No</th>
                @foreach($fields as $field)
                    <th style="width: {{ isset($field['type']) && $field['type'] === 'file' ? '100px' : 'auto' }};">
                        {{ $field['label'] ?? ucfirst($field['name']) }}
                    </th>
                @endforeach
                <th style="width: 90px;">Waktu Submit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $index => $submission)
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    @foreach($fields as $field)
                        <td>
                            @if(isset($field['type']) && $field['type'] === 'file')
                                @if(isset($submission->data[$field['name']]) && $submission->data[$field['name']])
                                    @php
                                        $filePath = storage_path('app/public/' . $submission->data[$field['name']]);
                                        $fileExtension = pathinfo($submission->data[$field['name']], PATHINFO_EXTENSION);
                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), $imageExtensions) && file_exists($filePath))
                                        <img src="{{ $filePath }}" class="file-thumbnail" alt="File">
                                    @else
                                        <span class="file-badge">📎 {{ strtoupper($fileExtension) }}</span>
                                    @endif
                                @else
                                    <span class="no-file">Tidak ada file</span>
                                @endif
                            @else
                                {{ $submission->data[$field['name']] ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                    <td class="timestamp">
                        {{ $submission->submitted_at->format('d/m/Y') }}<br>
                        {{ $submission->submitted_at->format('H:i') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem dan sah tanpa tanda tangan basah</p>
        
        <div class="signature">
            <p>Jakarta, {{ now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>
            <p style="margin-top: 60px;">
                <span class="name">Nama Pejabat / Manager</span><br>
                Jabatan / Posisi
            </p>
        </div>
    </div>
</body>
</html>