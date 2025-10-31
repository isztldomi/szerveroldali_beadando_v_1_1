<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = \App\Models\Ticket::class;

    // Keeps track of already generated (user_id, event_id, seat_id) combinations
    // to avoid creating duplicate tickets during a single seeding session.
    protected static array $existingCombinations = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $user = User::where('admin', false)->inRandomOrder()->first();
            $event = Event::inRandomOrder()->first();
            $seat = Seat::inRandomOrder()->first();

            $key = "{$user->id}-{$event->id}-{$seat->id}";
            $exists = in_array($key, self::$existingCombinations);

            #dump([
            #    'user_id' => $user->id,
            #    'event_id' => $event->id,
            #    'seat_id' => $seat->id,
            #    'exists_already' => $exists,
            #]);
        } while ($exists);

        // Hozzáadjuk az új kombinációt a listához
        self::$existingCombinations[] = $key;

        // Dinamikus ár számítás
        $daysUntil = max(1, $event->event_date_at->diffInDays(Carbon::now()));
        $occupancy = $event->tickets()->count() / Seat::count();
        $price = $event->is_dynamic_price
            ? round($seat->base_price * (1 - 0.5 * 1 / ($daysUntil + 1)) * (1 + 0.5 * $occupancy), 2)
            : $seat->base_price;

        return [
            'barcode' => $this->faker->unique()->numerify('#########'),
            'admission_time' => null,
            'user_id' => $user->id,
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'price' => $price,
        ];
    }
}
