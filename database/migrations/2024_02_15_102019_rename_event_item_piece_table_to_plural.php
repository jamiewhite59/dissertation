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
        Schema::rename('event_item_piece', 'event_item_pieces');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('event_item_pieces', 'event_item_piece');

    }
};
