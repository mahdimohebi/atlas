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
        Schema::create('guarantees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->enum('guarantee_type', ['naqdi', 'shakhs']);
            $table->decimal('amount', 15, 2); // فقط برای نقدی
            $table->string('name')->nullable();           // فقط برای شخص
            $table->string('father_name')->nullable();
            $table->string('tazkira_no')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();          // عکس تعهدنامه
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantees');
    }
};
