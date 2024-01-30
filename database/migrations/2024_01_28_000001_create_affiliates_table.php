<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('referral_code')->unique();
            $table->boolean('approved')->default(false);
            $table->decimal('earnings', 10, 2)->default(0.00);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('website')->nullable();
            $table->string('company_name')->nullable();
            $table->string('street_name');
            $table->string('city');
            $table->string('country');
            $table->string('state');
            $table->string('zipcode');
            $table->string('phone_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->decimal('minimum_payout', 10, 2)->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->string('payout_method')->default('paypal');
            $table->string('paypal_email')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliates');
    }
}
