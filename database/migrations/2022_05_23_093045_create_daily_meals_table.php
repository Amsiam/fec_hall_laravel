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
        Schema::create('daily_meals', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('meal_status')->default(1)->comment("0: OFF, 1:ON");
            $table->boolean('manager_status')->default(1)->comment("0: OFF, 1:ON");
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
        Schema::dropIfExists('daily_meals');
    }
};
