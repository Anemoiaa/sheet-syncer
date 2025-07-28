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
            'spreadsheet_id' => '1xiZgQwJ5q7mzy-c34DcD54U2QBqlbPitagA2FpBjtBk',
            'sheet_name'     => 'Sheet1',
            'url'            => 'https://docs.google.com/spreadsheets/d/1xiZgQwJ5q7mzy-c34DcD54U2QBqlbPitagA2FpBjtBk/edit',
        ]);
    }
}
