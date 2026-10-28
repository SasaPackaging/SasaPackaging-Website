<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Shop Now page: the products shown in the Pinterest-style grid.
 * Only a NEW table is created; nothing existing is touched (it is skipped when it already exists).
 */
class CreateShopProductsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('shop_products')) {
            return;
        }

        Schema::create('shop_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 160);
            $table->string('slug', 190)->unique();
            $table->string('category', 80)->nullable();          // free text, used for the filter chips
            $table->string('summary', 220)->nullable();          // the mini description on the card
            $table->text('description')->nullable();             // full text on the product page
            $table->decimal('price', 12, 2)->nullable();         // empty = "Price on request"
            $table->string('min_order', 60)->nullable();         // e.g. "Min. order 500 pcs"
            $table->text('images')->nullable();                  // JSON list of file names, first = cover
            $table->decimal('cover_ratio', 7, 4)->nullable();    // cover width / height (keeps the grid from jumping while it loads)
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index('category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_products');
    }
}
