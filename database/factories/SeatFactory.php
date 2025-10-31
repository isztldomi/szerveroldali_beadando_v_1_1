<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $seatNumberCounter = 1;

        $letter = chr(65 + floor(($seatNumberCounter-1)/100)); // A, B, C, ...
        $number = str_pad($seatNumberCounter, 3, '0', STR_PAD_LEFT);
        $seatNumberCounter++;

        return [
            'seat_number' => $letter . $number,
            'base_price' => $this->faker->numberBetween(1000, 5000),
        ];
    }
}
