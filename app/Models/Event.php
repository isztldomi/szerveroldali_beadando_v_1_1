<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'event_date_at',
        'sale_start_at',
        'sale_end_at',
        'is_dynamic_price',
        'max_number_allowed',
        'cover_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date_at' => 'datetime',
            'sale_start_at' => 'datetime',
            'sale_end_at' => 'datetime',
            'is_dynamic_price' => 'boolean',
        ];
    }

    /**
     * Get all of the tickets for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function remainingSeatsCount()
    {
        $totalSeats = Seat::count(); // minden szék az adott helyszínen
        $soldSeats = $this->tickets()->count();
        return max($totalSeats - $soldSeats, 0);
    }
}
