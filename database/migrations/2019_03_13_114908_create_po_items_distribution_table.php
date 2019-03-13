<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoItemsDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_items_distribution', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('purchaseOrder_id');
            $table->uuid('purchaseOrder_item_id');
            $table->uuid('part_id');
            $table->uuid('location_id');
            $table->integer('qty_on_hand');
            $table->integer('qty_to_receive');
            $table->integer('qty_scanned')->default(0);
            $table->timestamps();
        });

        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->foreign('purchaseOrder_id')->references('id')->on('purchase_orders');
        });

        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->foreign('purchaseOrder_item_id')->references('id')->on('purchase_order_items');
        });

        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->foreign('part_id')->references('id')->on('parts');
        });

        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->foreign('location_id')->references('id')->on('locations');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
        });
        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->dropForeign(['part_id']);
        });
        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->dropForeign(['purchaseOrder_item_id']);
        });
        Schema::table('purchase_order_items_distribution', function (Blueprint $table) {
            $table->dropForeign(['purchaseOrder_id']);
        });

        Schema::dropIfExists('purchase_order_items_distribution');
    }
}
