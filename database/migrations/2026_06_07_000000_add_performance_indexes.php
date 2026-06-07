<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->index(['activity_type', 'price'], 'items_activity_type_price_index');
            $table->index('price', 'items_price_index');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->index(['activity_type', 'price'], 'trips_activity_type_price_index');
            $table->index('event_date', 'trips_event_date_index');
            $table->index(['guide_id', 'event_date'], 'trips_guide_id_event_date_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['item_id', 'created_at'], 'reviews_item_id_created_at_index');
            $table->index(['trip_id', 'created_at'], 'reviews_trip_id_created_at_index');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['user_id', 'item_id', 'status'], 'bookings_user_id_item_id_status_index');
            $table->index(['user_id', 'trip_id', 'status'], 'bookings_user_id_trip_id_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_user_id_item_id_status_index');
            $table->dropIndex('bookings_user_id_trip_id_status_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_item_id_created_at_index');
            $table->dropIndex('reviews_trip_id_created_at_index');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropIndex('trips_activity_type_price_index');
            $table->dropIndex('trips_event_date_index');
            $table->dropIndex('trips_guide_id_event_date_index');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('items_activity_type_price_index');
            $table->dropIndex('items_price_index');
        });
    }
};
