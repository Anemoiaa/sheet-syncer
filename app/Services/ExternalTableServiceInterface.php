<?php

namespace App\Services;

interface ExternalTableServiceInterface
{
    public function getAllRows(): array;

    public function addRows(array $rows): void;

    public function deleteRows(array $rows): void;

    public function updateRows(array $dbRows, array $sheetRows): void;
}
