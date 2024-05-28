<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('titel1');
            $table->string('titel2')->nullable();
            $table->string('link')->nullable();
            $table->string('titel_link')->nullable();
            $table->string('image'); // Adjust size if needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
