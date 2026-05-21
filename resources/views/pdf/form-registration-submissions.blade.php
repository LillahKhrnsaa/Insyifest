<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Hasil Formulir - {{ $form->title }}</title>

<style>
    @page { size: A4 landscape; margin: 20mm; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #222; line-height: 1.4; padding: 15mm 20mm; background: #fff; }

    /* HEADER */
    .header-table { width: 100%; border-bottom: 2px solid #ccc; margin-bottom: 20px; padding-bottom: 15px; }
    .header-table td { border: none; vertical-align: middle; }
    .header-table h1 { font-size: 16pt; font-weight: bold; color: #1e40af; text-transform: uppercase; margin-bottom: 4px; }
    .header-table p { font-size: 8pt; color: #555; line-height: 1.3; }

    /* INFO */
    .doc-info { margin-bottom: 20px; }
    .doc-info h3 { font-size: 14pt; color: #1e40af; margin-bottom: 8px; }
    .doc-info p { font-size: 9pt; color: #444; margin-bottom: 10px; }
    .meta-table { width: 100%; border-top: 1px solid #eee; padding-top: 8px; }
    .meta-table td { border: none; font-size: 8pt; color: #666; }

    /* DATA TABLE */
    @php
        $fieldCount = count($fields);
        if ($fieldCount > 10) {
            $fontSize = '5.5pt';
            $padding = '4px 3px';
        } elseif ($fieldCount > 7) {
            $fontSize = '6.5pt';
            $padding = '5px 4px';
        } elseif ($fieldCount > 5) {
            $fontSize = '7.5pt';
            $padding = '6px 5px';
        } else {
            $fontSize = '8.5pt';
            $padding = '8px';
        }
    @endphp

    .data-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
    .data-table thead { background: #1e40af; }
    .data-table th { color: #fff; text-transform: uppercase; letter-spacing: 0.5px; }
    .data-table th, .data-table td {
        font-size: {{ $fontSize }} !important;
        padding: {{ $padding }} !important;
        word-wrap: break-word !important;
        word-break: break-all !important;
        border: 1px solid #ddd !important;
    }
    .data-table tbody tr:nth-child(even) { background: #f9fafb; }
    .col-no { width: 35px; text-align: center; }
    .col-coach { width: 120px; }
    .col-schedule { width: 140px; }
    .col-timestamp { width: 90px; text-align: center; font-size: 8pt; color: #555; }

    /* FOOTER */
    .footer { margin-top: 30px; padding-top: 10px; border-top: 2px solid #ccc; overflow: auto; }
    .footer-note { float: left; font-size: 8pt; color: #777; }
    .signature { float: right; width: 250px; text-align: left; }
    .signature img { width: 140px; margin: 10px 0 5px; }
    .signature-name { font-weight: bold; border-bottom: 1px solid #333; display: inline-block; padding-bottom: 2px; }
</style>
</head>
<body>

{{-- HEADER --}}
<table class="header-table">
    <tr>
        <td style="width: 85px;"><img src="{{ public_path('images/logo.png') }}" style="width: 75px;"></td>
        <td>
            <h1>CIKAMPEK SWIMMING CLUB</h1>
            <p>C4, Jl. Mashudi No.30, Karawang<br>Telp: (+62) 858-9496-1449</p>
        </td>
    </tr>
</table>

{{-- INFO --}}
<div class="doc-info">
    <h3>{{ $form->title }}</h3>
    @if($form->description)
        <p>{{ $form->description }}</p>
    @endif
    <table class="meta-table">
        <tr>
            <td><strong>Total Data:</strong> {{ $submissions->count() }}</td>
            <td style="text-align:center;"><strong>Dicetak:</strong> {{ now()->format('d F Y, H:i') }}</td>
            <td style="text-align:right;"><strong>Oleh:</strong> {{ auth()->user()->name ?? 'Admin' }}</td>
        </tr>
    </table>
</div>

{{-- TABLE --}}
<table class="data-table">
    <thead>
        <tr>
            <th class="col-no">No</th>
            <th class="col-coach">Coach</th>
            <th class="col-schedule">Jadwal</th>
            @foreach($fields as $field)
                <th>{{ $field->label }}</th>
            @endforeach
            <th class="col-timestamp">Waktu Submit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($submissions as $index => $submission)
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-coach">{{ $submission->scheduleCoach?->coach?->user?->name ?? '-' }}</td>
                <td class="col-schedule">
                    @php
                        $schedule = $submission->scheduleCoach?->schedule;
                    @endphp
                    {{ $schedule ? "{$schedule->day}, {$schedule->time}" : '-' }}<br>
                    <small style="color: #666;">{{ $schedule?->date ? \Carbon\Carbon::parse($schedule->date)->format('d M Y') : '' }}</small>
                </td>
                @foreach($fields as $field)
                    @php
                        $answer = $submission->answers->firstWhere('registration_field_id', $field->id);
                    @endphp
                    <td>{{ $answer?->value ?? '-' }}</td>
                @endforeach
                <td class="col-timestamp">{{ $submission->created_at->format('d/m/Y') }}<br>{{ $submission->created_at->format('H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- FOOTER --}}
<div class="footer">
    <p class="footer-note">Dokumen ini digenerate otomatis oleh sistem dan sah tanpa tanda tangan basah</p>
    <div class="signature">
        <p>Cikampek, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <img src="{{ public_path('images/ttd.png') }}">
        <p><span class="signature-name">Adinda Larasati</span></p>
        <p>Admin</p>
    </div>
</div>

</body>
</html>