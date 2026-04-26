<?php

require __DIR__.'/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$admins = [
    ['name' => 'Luthfi', 'role' => 'OWNER', 'phone' => '0811111111'],
    ['name' => 'Admin', 'role' => 'STAFF', 'phone' => '085885060925'],
    ['name' => 'IT Team', 'role' => 'STAFF', 'phone' => '0855555555'],
];

$coaches = [
    ['name' => 'Asri Suci Alfiani', 'phone' => '085219301750', 'email' => 'asri.suci@cikampekswimming.gmail.com'],
    ['name' => 'Dony Adhi Nugroho Hidayat', 'phone' => '081292626315', 'email' => 'dony.adhi@cikampekswimming.gmail.com'],
    ['name' => 'Fabiyan Fahliyansyah', 'phone' => '089638534624', 'email' => 'fabiyan.fahliyansyah@cikampekswimming.gmail.com'],
    ['name' => 'Fauzan Noer Afrizal', 'phone' => '08972703645', 'email' => 'fauzan.noer@cikampekswimming.gmail.com'],
    ['name' => 'Endah Khairun Nissa', 'phone' => '08978683958', 'email' => 'endah.khairun@cikampekswimming.gmail.com'],
    ['name' => 'Mohammad Hafid Siddik', 'phone' => '089657439609', 'email' => 'mohammad.hafid@cikampekswimming.gmail.com'],
    ['name' => 'Salsa Ramdiyani Eki Putri', 'phone' => '089539724070', 'email' => 'salsa.ramdiyani@cikampekswimming.gmail.com'],
    ['name' => 'Iman Fala Handoko', 'phone' => '082297010357', 'email' => 'iman.fala@cikampekswimming.gmail.com'],
    ['name' => 'Juan Njawi Wandhira', 'phone' => '089632305678', 'email' => 'juan.njawi@cikampekswimming.gmail.com'],
    ['name' => 'Rindy Antika', 'phone' => '085322328887', 'email' => 'rindy.antika@cikampekswimming.gmail.com'],
    ['name' => 'Muhammad Tegar Satrio', 'phone' => '085886515053', 'email' => 'muhammad.tegar@cikampekswimming.gmail.com'],
    ['name' => 'Alif Ikrar Prabu', 'phone' => '083804665952', 'email' => 'alif.ikrar@cikampekswimming.gmail.com'],
    ['name' => 'Moh Lutfi Adistira Wirawan', 'phone' => '081293438506', 'email' => 'moh.luthfi@cikampekswimming.gmail.com'],
];

$adminRows = '';
foreach ($admins as $a) {
    $adminRows .= "<tr><td>{$a['name']}</td><td><span class='role-badge role-".strtolower($a['role'])."'>{$a['role']}</span></td><td>{$a['phone']}</td><td><span class='password-cell'>1234567890</span></td></tr>";
}

$coachRows = '';
$no = 1;
foreach ($coaches as $c) {
    $coachRows .= "<tr><td>{$no}. {$c['name']}</td><td>{$c['phone']}</td><td>{$c['email']}</td><td><span class='password-cell'>1234567890</span></td></tr>";
    $no++;
}

$html = "
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.4; padding: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { color: #1e40af; margin: 0; font-size: 20px; }
        .header p { color: #64748b; margin: 5px 0 0 0; font-size: 12px; }
        .section { margin-bottom: 20px; }
        .section-title { background: #eff6ff; color: #1e40af; padding: 8px; font-weight: bold; border-left: 4px solid #2563eb; margin-bottom: 10px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        th { background: #f8fafc; text-align: left; padding: 8px; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 11px; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; word-wrap: break-word; }
        .role-badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .role-owner { background: #dcfce7; color: #166534; }
        .role-staff { background: #fef9c3; color: #854d0e; }
        .password-cell { font-family: 'Courier', monospace; background: #f1f5f9; padding: 2px 4px; border-radius: 4px; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class='header'>
        <h1>Cikampek Swimming Club</h1>
        <p>Dokumentasi Kredensial Login Sistem (Production)</p>
    </div>

    <div class='section'>
        <div class='section-title'>Akun Administrator Utama (Total: " . count($admins) . ")</div>
        <table>
            <thead>
                <tr>
                    <th style='width: 25%;'>Nama Lengkap</th>
                    <th style='width: 15%;'>Role</th>
                    <th style='width: 30%;'>No HP (Login)</th>
                    <th style='width: 30%;'>Password</th>
                </tr>
            </thead>
            <tbody>{$adminRows}</tbody>
        </table>
    </div>

    <div class='section'>
        <div class='section-title'>Akun Para Pelatih (Total: " . count($coaches) . ")</div>
        <table>
            <thead>
                <tr>
                    <th style='width: 35%;'>Nama Pelatih</th>
                    <th style='width: 20%;'>No HP (Login)</th>
                    <th style='width: 25%;'>Email</th>
                    <th style='width: 20%;'>Password</th>
                </tr>
            </thead>
            <tbody>{$coachRows}</tbody>
        </table>
    </div>

    <div class='footer'>
        <p>Dokumen ini bersifat rahasia. Dicetak pada: " . date('d F Y H:i') . "</p>
    </div>
</body>
</html>";

echo "Generating PDF with " . count($coaches) . " coaches...\n";
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output = $dompdf->output();
file_put_contents(__DIR__ . '/../admin_credentials_report.pdf', $output);
echo "Done! File saved as admin_credentials_report.pdf in project root.\n";
