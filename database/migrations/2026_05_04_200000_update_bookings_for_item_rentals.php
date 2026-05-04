<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('item_id')->nullable()->after('trip_id')->constrained()->nullOnDelete();
            $table->time('start_time')->nullable()->after('booking_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->integer('hours')->nullable()->after('people');
            $table->foreignId('trip_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('item_id');
            $table->dropColumn(['start_time', 'end_time', 'hours']);
            $table->foreignId('trip_id')->nullable(false)->change();
        });
    }
};
