<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Move old remaining/cash type values into payment_type, reset order column to 'sell'
        DB::statement("UPDATE orders SET payment_type = 'cash', type = 'sell' WHERE type = 'cash'");
        DB::statement("UPDATE orders SET payment_type = 'remaining', type = 'sell' WHERE type = 'remaining'");

        // Rename column (MySQL CHANGE syntax preserves data)
        DB::statement("ALTER TABLE orders CHANGE `type` `order_type` ENUM('sell', 'purchase') NOT NULL DEFAULT 'sell'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders CHANGE `order_type` `type` ENUM('sell', 'purchase') NOT NULL DEFAULT 'sell'");
    }
};
