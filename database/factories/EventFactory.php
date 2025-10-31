<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sale_start = $this->faker->dateTimeBetween('-5 days', 'now');
        $sale_end = (clone $sale_start)->modify('+'.$this->faker->numberBetween(5,10).' days');
        $event_date = (clone $sale_end)->modify('+'.$this->faker->numberBetween(1,5).' days');

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->text(200),
            'event_date_at' => $event_date,
            'sale_start_at' => $sale_start,
            'sale_end_at' => $sale_end,
            'is_dynamic_price' => $this->faker->boolean(),
            'max_number_allowed' => $this->faker->numberBetween(1,5),
            'cover_image' => $this->faker->imageUrl(640,480,'concert',true),
        ];
    }
}
