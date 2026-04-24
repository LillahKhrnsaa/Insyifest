<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ArchiveMembersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:archive-members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Arsipkan member aktif dan ubah status menjadi tidak aktif untuk periode baru.';

    /**
     * Execute the console command.
     */
    public function handle(\App\Actions\ArchiveMembersAction $action)
    {
        $this->info('Memulai proses pengarsipan member...');
        
        $count = $action->execute();
        
        $this->success("Berhasil mengarsipkan $count member.");
    }
}
