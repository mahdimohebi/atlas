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
        Schema::create('design_pots', function (Blueprint $table) {
            $table->id();
            $table->string('pot_type');             // نوع دیگ: پلوی 01, پلوی 02, پلوی 03
            $table->string('pot_number')->nullable();           
            $table->string('design_type');          // نوع دیزاین: رنگ یا پالش
            $table->date('date');                   // تاریخ دیزاین
            $table->decimal('price_per_pot', 15, 2); // فی دیگ
            $table->integer('quantity');            // تعداد دیگ
            $table->decimal('total_price', 15, 2);  // قیمت مجموعی
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete(); // کارمند دیزاینر
            $table->text('note')->nullable(); // توضیحات اختیاری
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_pots');
    }
};
