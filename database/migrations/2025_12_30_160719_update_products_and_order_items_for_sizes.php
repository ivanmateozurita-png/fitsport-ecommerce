<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add valid sizes column to products
        Schema::table('products', function (Blueprint $table) {
            $table->json('sizes')->nullable()->after('stock');
        });

        // Migrate existing data if any
        $products = DB::table('products')->whereNotNull('size')->get();
        foreach ($products as $product) {
            if (!empty($product->size)) {
                DB::table('products')->where('id', $product->id)->update([
                    'sizes' => json_encode([$product->size])
                ]);
            }
        }

        // Drop the old single 'size' column from products
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('size');
        });

        // Add 'size' column to order_items to store selected size
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('size');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('size')->nullable()->after('stock');
        });

        // Restore data (best effort, takes first size)
        $products = DB::table('products')->whereNotNull('sizes')->get();
        foreach ($products as $product) {
            $sizes = json_decode($product->sizes, true);
            if (is_array($sizes) && count($sizes) > 0) {
                DB::table('products')->where('id', $product->id)->update([
                    'size' => $sizes[0]
                ]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sizes');
        });
    }
};
