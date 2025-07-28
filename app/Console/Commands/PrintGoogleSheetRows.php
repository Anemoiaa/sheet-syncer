<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Throwable;

class PrintGoogleSheetRows extends Command
{
    protected $signature = 'google-sheet:print {--count=10 : Кол-во строк}';
    protected $description = 'Выводит ID и комментарии из Google Sheets';
    private GoogleSheetService $sheetService;

    public function __construct(GoogleSheetService $sheetService)
    {
        parent::__construct();
        $this->sheetService = $sheetService;
    }

    public function handle(): int
    {
        $count = (int) $this->option('count');

        if ($count <= 0) {
            $this->warn('Параметр --count должен быть больше 0. Установлено дефолтное значение: 10.');
            $count = 10;
        }

        try {
            $this->info('Получаем данные из Google Sheets...');

            $rows = $this->sheetService->getRows($count);

            if ($rows) {
                $bar = $this->output->createProgressBar(count($rows));
                $bar->start();

                $output = [];

                foreach ($rows as $row) {
                    $output[] = "ID: {$row->id} | Comment: {$row->comment}";
                    $bar->advance();
                }

                $bar->finish();
                $this->newLine(2);

                foreach ($output as $line) {
                    $this->line($line);
                }
            } else {
                $this->info('В таблице нет записей.');
            }

            $this->info('Завершено!');

            return CommandAlias::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Ошибка при получении данных: ' . $e->getMessage());
            return CommandAlias::FAILURE;
        }
    }
}
