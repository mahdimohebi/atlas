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
        Schema::create('pouring_pots', function (Blueprint $table) {
            $table->id();
            $table->string('pot_type');               // نوع دیگ: پلوی 01, پلوی 02, پلوی 03
            $table->string('pot_sub_type')->nullable();           // زیرنوع: کوتک, سرپوش, خود دیگ
            $table->tinyInteger('pot_number')->nullable();        // شماره بخش: 1,2,3,4,5
            $table->date('date');                     // تاریخ ریخت
            $table->decimal('weight_per_pot', 15, 2); // وزن فی دیگ
            $table->integer('quantity');              // تعداد دیگ
            $table->decimal('total_weight', 15, 2);   // وزن مجموعی
            $table->decimal('price_per_pot', 15, 2);  // فی دیگ
            $table->decimal('total_price', 15, 2);    // قیمت مجموعی
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete(); // کارمند ریخت‌گر
            $table->text('note')->nullable(); // توضیحات اختیاری
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pouring_pots');
    }
};
