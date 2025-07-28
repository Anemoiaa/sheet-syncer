<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SheetConfig extends Model
{
    protected $fillable = [
        'spreadsheet_id',
        'sheet_name',
        'url',
    ];
}
