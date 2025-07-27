<?php

namespace App\Models;

use App\Enums\RowStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextRow extends Model
{
    use HasFactory;

    public const TABLE = 'text_rows';
    public const ATTRIBUTE_ID = 'id';
    public const ATTRIBUTE_TEXT = 'text';
    public const ATTRIBUTE_STATUS = 'status';

    protected $fillable = [
        'text',
        'status',
    ];

    protected $casts = [
        'status' => RowStatusEnum::class,
    ];

    public function scopeAllowed($query): Builder
    {
        return $query->where('status', RowStatusEnum::Allowed->value);
    }
}
