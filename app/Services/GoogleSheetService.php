<?php

namespace App\Services;

use App\DTO\SheetRowDto;
use App\Helpers\SpreadsheetHelper;
use App\Mappers\SheetRowMapper;
use App\Models\SheetConfig;
use App\Models\TextRow;
use Google\Client;
use Google\Exception;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google_Service_Sheets;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class GoogleSheetService implements ExternalTableServiceInterface
{
    private const APPLICATION_NAME = 'SheetSyncer';
    private const SCOPES = [
        Google_Service_Sheets::SPREADSHEETS,
    ];
    private const CREDENTIALS_PATH = 'credentials.json';

    private Sheets $service;
    private string $sheetName;
    private string $spreadsheetId;
    private int $sheetId;
    private string $firstColumnLetter = 'A';
    private string $lastColumnLetter;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $client = new Client();
        $client->setApplicationName(self::APPLICATION_NAME);
        $client->setScopes(self::SCOPES);
        $client->setAuthConfig(base_path(self::CREDENTIALS_PATH));

        $this->service = new Sheets($client);

        $config = SheetConfig::first();
        $this->spreadsheetId = $config->spreadsheet_id;
        $this->sheetName = $config->sheet_name;
        $this->sheetId = $this->getSheetId();

        $columnsCount = count(Schema::getColumnListing(TextRow::TABLE));
        $this->lastColumnLetter = SpreadsheetHelper::numberToColumnLetter($columnsCount + 1);
    }

    /**
     * @return SheetRowDto[]
     * @throws \Google\Service\Exception
     */
    public function getAllRows(): array
    {
        $batchSize = 5000;
        $allRows = [];
        $startRow = 2;

        /* @var SheetRowDto[] $rows */
        while (true) {
            $rows = $this->fetchBatch($startRow, $batchSize);
            $allRows = array_merge($allRows, $rows);

            if (count($rows) < $batchSize) {
                break;
            }

            $startRow += $batchSize;
        }

        return $allRows;
    }

    /**
     * @param TextRow[] $rows
     * @throws \Google\Service\Exception
     */
    public function addRows(array $rows): void
    {
        $values = array_map(function (TextRow $row) {
            return [
                $row->id,
                $row->text,
                $row->status->value,
                $row->created_at,
                $row->updated_at,
            ];
        }, $rows);

        $range = "{$this->sheetName}!{$this->firstColumnLetter}2";

        $body = new Sheets\ValueRange([
            'values' => $values,
        ]);

        $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            $range,
            $body,
            ['valueInputOption' => 'RAW']
        );
    }

    /**
     * @param TextRow[] $dbRows
     * @param SheetRowDto[] $sheetRows
     * @throws \Google\Service\Exception
     */
    public function updateRows(array $dbRows, array $sheetRows): void
    {
        if (empty($dbRows) || empty($sheetRows)) {
            return;
        }

        $dbRowsById = collect($dbRows)->keyBy('id');
        $data = [];

        foreach ($sheetRows as $sheetRow) {
            $dbRow = $dbRowsById->get($sheetRow->id);

            if (!$dbRow) {
                continue;
            }

            $range = "{$this->sheetName}!{$this->firstColumnLetter}{$sheetRow->position}:{$this->lastColumnLetter}{$sheetRow->position}";

            $data[] = new Sheets\ValueRange([
                'range'  => $range,
                'values' => [[
                    $dbRow->id,
                    $dbRow->text,
                    $dbRow->status->value,
                    $dbRow->created_at,
                    $dbRow->updated_at,
                    $sheetRow->comment ?? '',
                ]],
            ]);
        }

        if (empty($data)) {
            return;
        }

        $body = new BatchUpdateValuesRequest([
            'valueInputOption' => 'RAW',
            'data'             => $data,
        ]);

        try {
            $this->service->spreadsheets_values->batchUpdate($this->spreadsheetId, $body);
        } catch (\Google\Service\Exception $e) {
            Log::error('Failed to update rows in Google Sheet: ' . $e->getMessage(), [
                'exception'     => $e,
                'updates_count' => count($data),
            ]);
            throw $e;
        }
    }

    /**
     * @param SheetRowDto[] $rows
     * @throws \Google\Service\Exception
     */
    public function deleteRows(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $positions = array_map(fn (SheetRowDto $row) => $row->position - 1, $rows);
        sort($positions);

        $requests = [];

        $requests[] = [
            'deleteDimension' => [
                'range' => [
                    'sheetId'    => $this->sheetId,
                    'dimension'  => 'ROWS',
                    'startIndex' => $positions[0],
                    'endIndex'   => $positions[count($positions) - 1] + 1,
                ],
            ],
        ];

        $batchUpdateRequest = new Sheets\BatchUpdateSpreadsheetRequest([
            'requests' => $requests,
        ]);

        $this->service->spreadsheets->batchUpdate($this->spreadsheetId, $batchUpdateRequest);
    }

    /**
     * @return SheetRowDto[]
     * @throws \Google\Service\Exception
     */
    private function fetchBatch(int $startRow, int $batchSize): array
    {
        $endRow = $startRow + $batchSize - 1;
        $range = "{$this->sheetName}!{$this->firstColumnLetter}{$startRow}:{$this->lastColumnLetter}{$endRow}";

        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        $rawData = $response->getValues();

        if (!$rawData || count($rawData) === 0) {
            return [];
        }

        return array_map(
            fn ($index, $row) => SheetRowMapper::fromArray($row, $startRow + $index),
            array_keys($rawData),
            $rawData
        );
    }

    /**
     * @throws \Google\Service\Exception
     */
    private function getSheetId(): int
    {
        $spreadsheet = $this->service->spreadsheets->get($this->spreadsheetId);
        foreach ($spreadsheet->getSheets() as $sheet) {
            if ($sheet->getProperties()->getTitle() === $this->sheetName) {
                return $sheet->getProperties()->getSheetId();
            }
        }

        throw new RuntimeException("Sheet {$this->sheetName} not found");
    }
}
