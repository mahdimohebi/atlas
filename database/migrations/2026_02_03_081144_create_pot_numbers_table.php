<?php

// database/migrations/xxxx_xx_xx_create_pot_numbers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotNumbersTable extends Migration
{
    public function up()
    {
        Schema::create('pot_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pottype_id')->constrained('pot_types')->onDelete('cascade')->nullable();
            $table->string('pot_number')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pot_numbers');
    }
}

