<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referred_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_id');
            $table->decimal('purchase_amount', 10, 2);
            $table->decimal('earnings', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('referral_id')->references('id')->on('referrals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('referred_transactions');
    }
};
