<?php

namespace App\Services;

use App\Models\BaseSheetConfig;

interface ExternalTableServiceInterface
{
    public function setUp(BaseSheetConfig $config): static;

    public function getAllRows(): array;

    public function addRows(array $rows): void;

    public function deleteRows(array $rows): void;

    public function updateRows(array $dbRows, array $sheetRows): void;
}
