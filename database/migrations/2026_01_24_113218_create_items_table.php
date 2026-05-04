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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->enum('activity_type', [
                'гидроцикл',
                'банан',
                'флайборд',
                'сапборд',
                'катамаран'
            ]);
            $table -> string('title');
            $table -> unsignedInteger('price');
            $table -> text('description')->nullable();
            $table -> unsignedTinyInteger('max_people')->nullable();
            $table -> json('gallery')->nullable();
            $table -> unsignedSmallInteger('duration_minutes')->nullable();
            $table -> unsignedTinyInteger('min_age')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
