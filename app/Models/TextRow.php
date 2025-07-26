<?php

namespace App\Models;

use App\Enums\RowStatusEnum;
use Illuminate\Database\Eloquent\Model;

class TextRow extends Model
{
    protected $fillable = [
        'text',
        'status',
    ];

    protected $casts = [
        'status' => RowStatusEnum::class,
    ];

    public function scopeAllowed($query)
    {
        return $query->where('status', RowStatusEnum::Allowed->value);
    }
}
