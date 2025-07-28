<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetSyncer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GoogleSheetSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-sheet:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Google Sheet';

    public function __construct(private readonly GoogleSheetSyncer $syncer)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $this->syncer->sync();
            Log::info('Google Sheet Synced: ' . now());
        } catch (Exception $exception) {
            Log::error('Error while syncing ' . $exception->getMessage());
        }
    }
}
