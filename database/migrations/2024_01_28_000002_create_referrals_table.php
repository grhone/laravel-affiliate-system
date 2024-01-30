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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('referred_user_id');
            //$table->boolean('conversion')->default(false);
            //$table->decimal('earnings', 10, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('referred_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referrals');
    }
};
