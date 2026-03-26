<?php

// database/migrations/xxxx_xx_xx_create_pot_types_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotTypesTable extends Migration
{
    public function up()
    {
        Schema::create('pot_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pot_types');
    }
}
