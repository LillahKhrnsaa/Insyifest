<?php

use App\Models\MemberArchive;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::transaction(function () {
    $archives = MemberArchive::all();
    
    foreach ($archives as $archive) {
        if (str_contains($archive->coach_name, ',')) {
            $coaches = explode(',', $archive->coach_name);
            
            // Ambil coach pertama buat update record yang sekarang
            $archive->update([
                'coach_name' => trim($coaches[0])
            ]);
            
            // Buat record baru buat coach sisanya
            for ($i = 1; $i < count($coaches); $i++) {
                $newData = $archive->toArray();
                unset($newData['id']);
                $newData['coach_name'] = trim($coaches[$i]);
                MemberArchive::create($newData);
            }
            
            echo "Splitted archive ID {$archive->id} for coaches: " . implode(', ', $coaches) . "\n";
        }
    }
});

echo "Done splitting old archives.\n";
