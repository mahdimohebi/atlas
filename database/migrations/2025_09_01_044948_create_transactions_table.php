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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['purchase', 'sale']); // خرید یا فروش
            $table->enum('category', ['soft', 'hard']); // نرم یا سخت
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade');
            $table->decimal('quantity', 15, 2);
            $table->decimal('price_per_unit', 15, 2);
            $table->enum('currency', ['AFN', 'USD']);
            $table->decimal('exchange_rate', 15, 4)->nullable(); // نرخ روز
            $table->decimal('total_price', 15, 2);
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
