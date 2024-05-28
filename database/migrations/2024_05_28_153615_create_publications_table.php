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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('localisation');
            $table->string('type');
            $table->date('date');
            $table->string('titel'); // Assuming this is meant to be 'title' instead of 'titel'
            $table->text('description'); // Corrected 'longtxt' to 'text' for description
            $table->integer('numberlike')->default(0); // Corrected 'int' to 'integer'
            $table->integer('numbercomment')->default(0); // Corrected 'int' to 'integer'
            $table->string('link')->nullable();
            $table->string('link_titel')->nullable();
            $table->json('file')->nullable(); // Assuming storing file names in JSON format

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
