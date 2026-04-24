<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ToggleDemoMode extends Command
{
    protected $signature = 'demo:toggle {state? : on or off}';
    protected $description = 'Toggle Demo Mode label on the website';

    public function handle()
    {
        $state = $this->argument('state');
        $envFile = base_path('.env');

        if (!File::exists($envFile)) {
            $this->error('.env file not found!');
            return;
        }

        $content = File::get($envFile);
        $hasDemoMode = strpos($content, 'APP_DEMO_MODE=') !== false;

        if ($state === 'on') {
            $newValue = 'true';
        } elseif ($state === 'off') {
            $newValue = 'false';
        } else {
            // Toggle
            if ($hasDemoMode) {
                preg_match('/APP_DEMO_MODE=(true|false)/', $content, $matches);
                $current = $matches[1] ?? 'false';
                $newValue = ($current === 'true') ? 'false' : 'true';
            } else {
                $newValue = 'true';
            }
        }

        if ($hasDemoMode) {
            $content = preg_replace('/APP_DEMO_MODE=(true|false)/', 'APP_DEMO_MODE=' . $newValue, $content);
        } else {
            $content .= "\nAPP_DEMO_MODE=" . $newValue;
        }

        File::put($envFile, $content);

        $this->info("Demo Mode is now: " . ($newValue === 'true' ? 'ENABLED' : 'DISABLED'));
    }
}
