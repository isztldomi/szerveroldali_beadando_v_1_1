<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saleStart = Carbon::now()->subDays(2);
        $saleEnd = (clone $saleStart)->addDays(7);
        $eventDate = (clone $saleEnd)->addDays(2);

        Event::create([
            'title' => 'Imagine Dragons - Live in Budapest',
            'description' => 'A Grammy-díjas Imagine Dragons zenekar fergeteges élő koncertje a Papp László Budapest Sportarénában. Élőben hallhatod a legnagyobb slágereket: Believer, Thunder, Demons és sok más!',
            'event_date_at' => $eventDate,
            'sale_start_at' => $saleStart,
            'sale_end_at' => $saleEnd,
            'is_dynamic_price' => true,
            'max_number_allowed' => 5,
            'cover_image' => 'event_covers/imagine_dragons_small.png',
        ]);

        $saleStart = Carbon::now()->subDays(100);
        $saleEnd = (clone $saleStart)->addDays(7);
        $eventDate = (clone $saleEnd)->addDays(2);

        Event::create([
            'title' => 'Coldplay - Live in Budapest',
            'description' => 'A Grammy-díjas Coldplay zenekar fantasztikus koncertje a Papp László Budapest Sportarénában. Élőben hallhatod a legnagyobb slágereket: Yellow, Fix You, Viva La Vida és sok más!',
            'event_date_at' => $eventDate,
            'sale_start_at' => $saleStart,
            'sale_end_at' => $saleEnd,
            'is_dynamic_price' => true,
            'max_number_allowed' => 5,
            'cover_image' => 'event_covers/coldplay_small.png',
        ]);

        Event::factory()->count(21)->create();
    }
}
