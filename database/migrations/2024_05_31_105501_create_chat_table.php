<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->timestamp('date')->useCurrent();
            $table->unsignedBigInteger('expediteur');
            $table->unsignedBigInteger('destinataire');
            $table->integer('user_to_admin')->default(0); // Assuming 0 means user to user, 1 means user to admin
            $table->integer('is_active')->default(1); // Assuming 1 means active, 0 means inactive

            // Foreign key constraints
            $table->foreign('expediteur')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('destinataire')->references('id')->on('admin')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat');
    }
}
