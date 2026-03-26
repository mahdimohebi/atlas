<?php

// database/migrations/xxxx_xx_xx_create_pot_subtypes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotSubtypesTable extends Migration
{
    public function up()
    {
        Schema::create('pot_subtypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('potnumber_id')->constrained('pot_numbers')->onDelete('cascade')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pot_subtypes');
    }
}
