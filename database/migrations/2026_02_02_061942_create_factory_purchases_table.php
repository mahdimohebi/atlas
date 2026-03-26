<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('factory_purchases', function (Blueprint $table) {
            $table->id();

            // مشخصات فروشنده
            $table->string('name');
            $table->string('f_name')->nullable();

            // مشخصات المونیم
            $table->enum('category', ['hard', 'soft']);
            $table->date('purchase_date');

            // وزن‌ها
            $table->decimal('quantity', 10, 2);          // مقدار خام
            $table->decimal('waste', 10, 2)->default(0)->nullable(); // ضایعات
            $table->decimal('net_weight', 10, 2);        // وزن خالص

            // قیمت
            $table->decimal('price_per_unit', 12, 2);
            $table->decimal('total_price', 14, 2);

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factory_purchases');
    }
};
