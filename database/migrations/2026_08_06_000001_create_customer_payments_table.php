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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            // Month the payment belongs to, in "YYYY-MM" format
            $table->string('month_year', 7);
            $table->date('payment_date')->nullable();
            $table->decimal('payment_amount', 12, 2)->nullable();
            $table->string('account')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'month_year']);
            $table->unique(['user_id', 'customer_id', 'month_year'], 'customer_payments_user_customer_month_unique');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payments');
    }
};
