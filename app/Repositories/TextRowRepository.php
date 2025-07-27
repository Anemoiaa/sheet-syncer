<?php

namespace App\Repositories;

use App\Models\TextRow;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class TextRowRepository
{
    public function allowed(): Builder
    {
        return TextRow::allowed();
    }

    public function getAll(): Collection
    {
        return TextRow::all();
    }

    public function delete(TextRow $textRow): void
    {
        $textRow->delete();
    }

    public function deleteAll(): void
    {
        TextRow::query()->delete();
    }

    public function generate(int $count): void
    {
        $half = (int) floor($count / 2);
        $rest = $count - ($half * 2);

        $allowed = TextRow::factory()->count($half)->allowed()->make()->toArray();
        $prohibited = TextRow::factory()->count($half)->prohibited()->make()->toArray();

        $data = array_merge($allowed, $prohibited);

        if ($rest > 0) {
            $data[] = TextRow::factory()->allowed()->make()->toArray();
        }

        TextRow::insert($data);
    }
}
