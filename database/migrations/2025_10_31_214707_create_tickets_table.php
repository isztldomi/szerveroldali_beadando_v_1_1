<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('barcode', 9)->unique();
            $table->timestamp('admission_time')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('event_id')->constrained()->onDelete('restrict');
            $table->foreignId('seat_id')->constrained()->onDelete('restrict');
            $table->float('price');
            $table->timestamps();

            // Egy jegy csak egyszer kapcsolódhat userhez, eseményhez és ülőhelyhez
            $table->unique(['user_id', 'event_id', 'seat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
