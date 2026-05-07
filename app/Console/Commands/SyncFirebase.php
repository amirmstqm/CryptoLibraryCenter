<?php

namespace App\Console\Commands;

use App\Services\FirebaseSyncService;
use Illuminate\Console\Command;

class SyncFirebase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'firebase:sync';

    /**
     * The console command description.
     */
    protected $description = 'Sync all Firebase Firestore libraries and articles to MySQL';

    public function handle(FirebaseSyncService $syncService): int
    {
        $this->info('Starting Firebase → MySQL sync...');

        $results = $syncService->syncAll();

        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Synced',  $results['synced']],
                ['⏭  Skipped', $results['skipped']],
                ['❌ Errors',  $results['errors']],
            ]
        );

        if ($results['errors'] > 0) {
            $this->warn('Some records had errors. Check Laravel logs for details.');
            return Command::FAILURE;
        }

        $this->info('Sync completed successfully!');
        return Command::SUCCESS;
    }
}
