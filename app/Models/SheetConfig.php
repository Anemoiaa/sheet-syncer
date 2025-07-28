<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SheetConfig extends Model
{
    public const TABLE = 'sheet_config';
    public const ATTRIBUTE_ID = 'id';
    public const ATTRIBUTE_SPREADSHEET_ID = 'spreadsheet_id';
    public const ATTRIBUTE_SHEET_NAME = 'sheet_name';
    public const ATTRIBUTE_URL = 'url';

    protected $fillable = [
        'spreadsheet_id',
        'sheet_name',
        'url',
    ];
}
