<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('description');
            $table->float('price');
            $table->integer('bedroom_num');
            $table->integer('bathroom_num');
            $table->integer('beds_num');
            $table->integer('level');
            $table->enum('furniture',['yes','no']);
            $table->enum('type',['apartment','room','studio']);
            $table->enum('status',['not rented','rented']);
            $table->float('area');
            $table->string('address');
            $table->string('Latitude');
            $table->string('Longitude');
            $table->softDeletes();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            //locations
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
        Schema::dropIfExists('advertisements');
    }
};
