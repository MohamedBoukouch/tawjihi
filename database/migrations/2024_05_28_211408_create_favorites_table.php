<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('publication_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Adding a unique constraint to ensure a user can only favorite a publication once
            $table->unique(['user_id', 'publication_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
