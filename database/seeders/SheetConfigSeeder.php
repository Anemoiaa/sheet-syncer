<?php

namespace Database\Seeders;

use App\Models\SheetConfig;
use Illuminate\Database\Seeder;

class SheetConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SheetConfig::create([
            'spreadsheet_id' => config('sheet-syncer.default_spreadsheet_id'),
            'sheet_name'     => config('sheet-syncer.default_sheet_name'),
            'url'            => config('sheet-syncer.default_url'),
        ]);
    }
}
