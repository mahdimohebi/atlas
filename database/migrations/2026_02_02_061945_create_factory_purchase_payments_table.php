<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('factory_purchase_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('factory_purchase_id')
                  ->constrained('factory_purchases')
                  ->cascadeOnDelete();

            $table->date('payment_date');
            $table->decimal('amount', 14, 2);
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factory_purchase_payments');
    }
};
