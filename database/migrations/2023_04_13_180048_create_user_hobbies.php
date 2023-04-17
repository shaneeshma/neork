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
        Schema::create('hobby_user_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('user_detail_id')->references('id')->on('user_details');
            $table->integer('hobby_id')->references('id')->on('hobbies');  
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
        Schema::dropIfExists('hobby_user_detail');
    }
};