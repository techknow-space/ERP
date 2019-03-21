<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockTransfersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer_statuses', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->char('status',50);
            $table->char('description',250)->nullable(true);
            $table->integer('seq_id')->unique()->nullable(true);
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
        });

        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->char('number',50)->unique();
            $table->char('description',250)->nullable(true);
            $table->uuid('stockTransferStatus_id');
            $table->uuid('fromLocation_id');
            $table->uuid('toLocation_id');
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
        });

        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('stockTransfer_id');
            $table->uuid('part_id');
            $table->integer('qty')->unsigned()->default(0);
            $table->integer('qty_sent')->unsigned()->default(0);
            $table->integer('qty_received')->unsigned()->default(0);
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->foreign('stockTransferStatus_id')->references('id')->on('stock_transfer_statuses');
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->foreign('fromLocation_id')->references('id')->on('locations');
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->foreign('toLocation_id')->references('id')->on('locations');
        });

        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->foreign('stockTransfer_id')->references('id')->on('stock_transfers');
        });

        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->foreign('part_id')->references('id')->on('parts');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->dropForeign(['part_id']);
        });

        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->dropForeign(['stockTransfer_id']);
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropForeign(['toLocation_id']);
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropForeign(['fromLocation_id']);
        });

        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropForeign(['stockTransferStatus_id']);
        });

        Schema::dropIfExists('stock_transfer_items');

        Schema::dropIfExists('stock_transfers');

        Schema::dropIfExists('stock_transfer_statuses');
    }
}
