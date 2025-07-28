<?php

namespace App\DTO;

class SheetRowDto
{
    public function __construct(
        public readonly int $position,
        public readonly int $id,
        public readonly string $text,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly ?string $comment,
    ) {
    }
}
