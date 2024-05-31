<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcolevillesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecolevilles', function (Blueprint $table) {
            $table->id();
            $table->string('ville');
            $table->string('type');
            $table->string('logo');
            $table->unsignedBigInteger('ecole_id');
            $table->timestamps();

            // Create a unique index on the combination of 'ville' and 'type'
            $table->unique(['ville', 'type']);

            // Foreign key constraint linking to 'ecoles' table
            $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecolevilles');
    }
}
