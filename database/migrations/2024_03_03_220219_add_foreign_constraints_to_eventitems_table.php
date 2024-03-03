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
        Schema::table('event_items', function (Blueprint $table) {
            $table->foreign('piece_id')->references('id')->on('pieces')->change();
            $table->foreign('item_id')->references('id')->on('items')->change();
            $table->foreign('event_id')->references('id')->on('events')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_items', function (Blueprint $table) {
            //
        });
    }
};
