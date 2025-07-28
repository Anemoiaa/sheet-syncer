<?php

namespace App\Services;

use App\DTO\SheetRowDto;
use App\Models\TextRow;
use App\Repositories\TextRowRepository;
use Illuminate\Support\Facades\Log;

class GoogleSheetSyncer implements SheetSyncerInterface
{
    public function __construct(
        private readonly TextRowRepository $textRowRepository,
        private readonly ExternalTableServiceInterface $sheetService
    ) {
    }

    public function sync(): void
    {
        Log::info('Starting Google Sheet synchronization process.');

        $dbRows = $this->textRowRepository->allowed()->get();
        $sheetRows = $this->sheetService->getAllRows();

        $dbRowsById = $dbRows->keyBy(TextRow::ATTRIBUTE_ID);
        $sheetRowsById = collect($sheetRows)->keyBy(TextRow::ATTRIBUTE_ID);

        $rowsToAdd = [];
        $rowsToUpdate = [
            'db'    => [],
            'sheet' => [],
        ];
        $rowsToDelete = [];

        foreach ($dbRows as $dbRow) {
            $id = $dbRow->id;
            if (!$sheetRowsById->has($id)) {
                $rowsToAdd[] = $dbRow;
            } else {
                $sheetRow = $sheetRowsById->get($id);

                if ($this->needsUpdate($dbRow, $sheetRow)) {
                    $rowsToUpdate['db'][] = $dbRow;
                    $rowsToUpdate['sheet'][] = $sheetRow;
                }
            }
        }

        foreach ($sheetRows as $sheetRow) {
            $id = $sheetRow->id;

            if (!$dbRowsById->has($id)) {
                $rowsToDelete[] = $sheetRow;
            }
        }

        $this->performAdditions($rowsToAdd);
        $this->performUpdates($rowsToUpdate['db'], $rowsToUpdate['sheet']);
        $this->performDeletions($rowsToDelete);

        Log::info('Google Sheet synchronization completed successfully.', [
            'added_count'   => count($rowsToAdd),
            'updated_count' => count($rowsToUpdate['db']),
            'deleted_count' => count($rowsToDelete),
        ]);
    }

    private function needsUpdate(TextRow $dbRow, SheetRowDto $sheetRow): bool
    {
        return $dbRow->text !== $sheetRow->text
            || $dbRow->status->value !== $sheetRow->status;
    }

    private function performAdditions(array $rowsToAdd): void
    {
        if (empty($rowsToAdd)) {
            Log::debug('No rows to add to Google Sheet.');
            return;
        }
        Log::info('Adding ' . count($rowsToAdd) . ' rows to Google Sheet.');
        $this->sheetService->addRows($rowsToAdd);
    }

    private function performUpdates(array $dbRows, array $sheetRows): void
    {
        if (empty($dbRows) || empty($sheetRows)) {
            Log::debug('No rows to update in Google Sheet.');
            return;
        }
        Log::info('Updating ' . count($dbRows) . ' rows in Google Sheet.');
        $this->sheetService->updateRows($dbRows, $sheetRows);
    }

    private function performDeletions(array $rowsToDelete): void
    {
        if (empty($rowsToDelete)) {
            Log::debug('No rows to delete from Google Sheet.');
            return;
        }
        Log::info('Deleting ' . count($rowsToDelete) . ' rows from Google Sheet.');
        $this->sheetService->deleteRows($rowsToDelete);
    }
}
