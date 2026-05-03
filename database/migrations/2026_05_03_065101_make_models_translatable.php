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
        // Users table
        Schema::table('users', function (Blueprint $table) {
            $table->json('name_new')->nullable()->after('name');
        });
        DB::table('users')->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'name_new' => json_encode(['en' => $user->name])
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->json('name')->nullable()->after('id');
        });
        DB::table('users')->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'name' => $user->name_new
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name_new');
        });

        // Customers table
        Schema::table('customers', function (Blueprint $table) {
            $table->json('customer_name_new')->nullable();
            $table->json('shop_name_new')->nullable();
            $table->json('shop_address_new')->nullable();
            $table->json('city_new')->nullable();
        });
        DB::table('customers')->get()->each(function ($customer) {
            DB::table('customers')->where('id', $customer->id)->update([
                'customer_name_new' => json_encode(['en' => $customer->customer_name]),
                'shop_name_new' => json_encode(['en' => $customer->shop_name]),
                'shop_address_new' => json_encode(['en' => $customer->shop_address]),
                'city_new' => json_encode(['en' => $customer->city]),
            ]);
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'shop_name', 'shop_address', 'city']);
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->json('customer_name')->nullable();
            $table->json('shop_name')->nullable();
            $table->json('shop_address')->nullable();
            $table->json('city')->nullable();
        });
        DB::table('customers')->get()->each(function ($customer) {
            DB::table('customers')->where('id', $customer->id)->update([
                'customer_name' => $customer->customer_name_new,
                'shop_name' => $customer->shop_name_new,
                'shop_address' => $customer->shop_address_new,
                'city' => $customer->city_new,
            ]);
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_name_new', 'shop_name_new', 'shop_address_new', 'city_new']);
        });

        // Products table
        Schema::table('products', function (Blueprint $table) {
            $table->json('product_name_new')->nullable()->after('product_name');
        });
        DB::table('products')->get()->each(function ($product) {
            DB::table('products')->where('id', $product->id)->update([
                'product_name_new' => json_encode(['en' => $product->product_name])
            ]);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->json('product_name')->nullable()->after('customer_id');
        });
        DB::table('products')->get()->each(function ($product) {
            DB::table('products')->where('id', $product->id)->update([
                'product_name' => $product->product_name_new
            ]);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_name_new');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverting users
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_old')->nullable();
        });
        DB::table('users')->get()->each(function ($user) {
            $name = json_decode($user->name, true);
            DB::table('users')->where('id', $user->id)->update([
                'name_old' => $name['en'] ?? ''
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });
        DB::table('users')->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'name' => $user->name_old
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name_old');
        });

        // Reverting customers
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_name_old')->nullable();
            $table->string('shop_name_old')->nullable();
            $table->text('shop_address_old')->nullable();
            $table->string('city_old')->nullable();
        });
        DB::table('customers')->get()->each(function ($customer) {
            $customer_name = json_decode($customer->customer_name, true);
            $shop_name = json_decode($customer->shop_name, true);
            $shop_address = json_decode($customer->shop_address, true);
            $city = json_decode($customer->city, true);
            DB::table('customers')->where('id', $customer->id)->update([
                'customer_name_old' => $customer_name['en'] ?? '',
                'shop_name_old' => $shop_name['en'] ?? '',
                'shop_address_old' => $shop_address['en'] ?? '',
                'city_old' => $city['en'] ?? '',
            ]);
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'shop_name', 'shop_address', 'city']);
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->string('shop_name')->nullable();
            $table->text('shop_address')->nullable();
            $table->string('city')->nullable();
        });
        DB::table('customers')->get()->each(function ($customer) {
            DB::table('customers')->where('id', $customer->id)->update([
                'customer_name' => $customer->customer_name_old,
                'shop_name' => $customer->shop_name_old,
                'shop_address' => $customer->shop_address_old,
                'city' => $customer->city_old,
            ]);
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_name_old', 'shop_name_old', 'shop_address_old', 'city_old']);
        });

        // Reverting products
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_name_old')->nullable();
        });
        DB::table('products')->get()->each(function ($product) {
            $name = json_decode($product->product_name, true);
            DB::table('products')->where('id', $product->id)->update([
                'product_name_old' => $name['en'] ?? ''
            ]);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('customer_id');
        });
        DB::table('products')->get()->each(function ($product) {
            DB::table('products')->where('id', $product->id)->update([
                'product_name' => $product->product_name_old
            ]);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_name_old');
        });
    }
};
