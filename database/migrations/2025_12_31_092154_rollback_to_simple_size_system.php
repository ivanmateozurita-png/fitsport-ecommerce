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
        Schema::table('products', function (Blueprint $table) {
            // Add back the simple 'size' column
            $table->string('size')->nullable()->after('stock');
        });

        // Migrate data: take first size from sizes array
        DB::table('products')->get()->each(function ($product) {
            $sizes = json_decode($product->sizes, true);
            $singleSize = is_array($sizes) && count($sizes) > 0 ? $sizes[0] : 'Única';
            
            DB::table('products')
                ->where('id', $product->id)
                ->update(['size' => $singleSize]);
        });

        Schema::table('products', function (Blueprint $table) {
            // Drop the sizes JSON column
            $table->dropColumn('sizes');
        });

        // Keep the 'size' column in order_items for historical data
        // No changes needed there
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('sizes')->nullable()->after('stock');
        });

        // Migrate back: convert single size to array
        DB::table('products')->get()->each(function ($product) {
            $sizeArray = $product->size ? [$product->size] : ['Única'];
            
            DB::table('products')
                ->where('id', $product->id)
                ->update(['sizes' => json_encode($sizeArray)]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('size');
        });
    }
};
