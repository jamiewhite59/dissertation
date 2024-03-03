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
        Schema::table('event_customer', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events')->change();
            $table->foreign('customer_id')->references('id')->on('customers')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_customer', function (Blueprint $table) {
            //
        });
    }
};
