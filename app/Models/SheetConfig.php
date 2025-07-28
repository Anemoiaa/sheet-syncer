<?php

namespace App\Models;

class SheetConfig extends BaseSheetConfig
{
    protected $fillable = [
        'spreadsheet_id',
        'sheet_name',
        'url',
    ];
}
