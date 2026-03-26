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
       Schema::table('contracts', function (Blueprint $table) {
        $table->enum('pricing_type', ['per_item', 'per_kg'])
              ->after('end_date');

        $table->decimal('price_per_item', 10, 2)
              ->nullable()
              ->after('pricing_type');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     Schema::table('contracts', function (Blueprint $table) {
        $table->dropColumn(['pricing_type', 'price_per_item']);
    });
    }
};
