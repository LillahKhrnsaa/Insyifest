<?php

use App\Models\Member;
use App\Models\MemberArchive;
use App\Models\User;
use App\Actions\ArchiveMembersAction;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- MEMULAI PENGETESAN ARSIP BULANAN ---\n";

// 1. Pastikan ada member AKTIF buat dites
$activeCount = Member::where('status', 'AKTIF')->count();
if ($activeCount === 0) {
    echo "Info: Tidak ada member AKTIF. Membuat 1 member dummy untuk pengetesan...\n";
    $user = User::factory()->create([
        'phone' => '08' . rand(10000000, 99999999),
        'gender' => 'MALE'
    ]);
    Member::create([
        'user_id' => $user->id,
        'status' => 'AKTIF',
        'training_package_id' => \App\Models\TrainingPackage::first()->id ?? null,
    ]);
    $activeCount = 1;
}

echo "Jumlah member AKTIF saat ini: $activeCount\n";

// 2. Jalankan Action Arsip
echo "Menjalankan ArchiveMembersAction...\n";
$action = new ArchiveMembersAction();
$archivedCount = $action->execute();

echo "Jumlah member yang berhasil diarsipkan: $archivedCount\n";

// 3. Verifikasi Data di Tabel Arsip
$period = date('Y-m');
$archiveInDb = MemberArchive::where('archive_period', $period)->count();

echo "Verifikasi Tabel Arsip (Periode $period): $archiveInDb data ditemukan.\n";

// 4. Verifikasi Status di Tabel Member
$remainingActive = Member::where('status', 'AKTIF')->count();
echo "Verifikasi Tabel Member: $remainingActive member AKTIF tersisa.\n";

if ($archivedCount > 0 && $archiveInDb >= $archivedCount && $remainingActive === 0) {
    echo "\n🎉 PENGETESAN BERHASIL: Sistem arsip dan reset status berjalan sempurna!\n";
} else {
    echo "\n❌ PENGETESAN GAGAL: Ada ketidaksesuaian data.\n";
}
