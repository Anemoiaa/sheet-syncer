<?php

namespace Database\Factories;

use App\Enums\RowStatusEnum;
use App\Models\TextRow;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<TextRow>
 */
class TextRowFactory extends Factory
{
    private int $mxNbChars = 100;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            TextRow::ATTRIBUTE_TEXT   => $this->faker->text($this->mxNbChars),
            TextRow::ATTRIBUTE_STATUS => $this->faker->randomElement([
                RowStatusEnum::Allowed->value,
                RowStatusEnum::Prohibited->value,
            ]),
            Model::CREATED_AT        => now(),
            Model::UPDATED_AT        => now(),
        ];
    }

    public function allowed(): static
    {
        return $this->state([
            TextRow::ATTRIBUTE_STATUS => RowStatusEnum::Allowed->value,
        ]);
    }

    public function prohibited(): static
    {
        return $this->state([
            TextRow::ATTRIBUTE_STATUS => RowStatusEnum::Prohibited->value,
        ]);
    }
}
