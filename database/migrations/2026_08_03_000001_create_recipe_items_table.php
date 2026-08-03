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
        Schema::create('recipe_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('material_name');
            $table->string('unit', 20)->default('kg');
            $table->decimal('quantity', 10, 3)->default(0);
            // How much finished product the material quantities are for, in the
            // product's own unit (e.g. 20 Nang, or 1 kg). Used to scale to an order.
            $table->decimal('base_yield_quantity', 10, 3)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'product_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_items');
    }
};
