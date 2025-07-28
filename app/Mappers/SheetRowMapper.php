<?php

namespace App\Mappers;

use App\DTO\SheetRowDto;

class SheetRowMapper
{
    public static function fromArray(array $rawRow, int $position): SheetRowDto
    {
        return new SheetRowDto(
            position: $position,
            id: (int) $rawRow[0],
            text: $rawRow[1],
            status: $rawRow[2],
            createdAt: $rawRow[3],
            updatedAt: $rawRow[4],
            comment: $rawRow[5] ?? null,
        );
    }
}
