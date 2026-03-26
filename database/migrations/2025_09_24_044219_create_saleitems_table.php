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
        Schema::create('saleitems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');        
            $table->string('pot_type'); 
            $table->string('pot_number')->nullable();
            $table->string('pot_design')->nullable();            
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); 
            $table->decimal('total_price', 10, 2);            
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saleitems');
    }
};
