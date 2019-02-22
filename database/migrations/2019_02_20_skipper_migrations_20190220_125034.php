<?php
/* Migrations generated by: Skipper (http://www.skipper18.com) */
/* Migration id: 05478eec-6e84-40c8-b30e-e486456a01bb */
/* Migration datetime: 2019-02-20 12:50:34.602923 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Migrations20190220125034 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
  */
  public function up()
  {
    Schema::create('suppliers', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->char('name',50)->unique();
      $table->char('country',100)->nullable(true);
      $table->integer('lead_time')->nullable(true);
      $table->multiLineString('payment_details')->nullable(true);
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->unique(['id'],'supplier_pk');
    });
    Schema::create('purchase_order_statuses', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->char('status',50);
      $table->char('description',250)->nullable(true);
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->unique(['id'],'purchase_order_status_pk');
    });
    Schema::create('purchase_order_payments', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->dateTime('transaction_date');
      $table->longText('transaction_details');
      $table->float('amount',2);
      $table->char('currency',10);
      $table->float('exchange_rate_to_CAD',4)->nullable(true);
      $table->unique(['id'],'purchase_order_payment_pk');
    });
    Schema::create('purchase_order_payment_statuses', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->char('status',50);
      $table->char('description',250)->nullable(true);
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->unique(['id'],'purchase_order_payment_status_pk');
    });
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->char('number',50);
      $table->uuid('supplier_id')->nullable(true);
      $table->uuid('purchaseOrderStatus_id')->nullable(true);
      $table->uuid('purchaseOrderPayment_id')->nullable(true);
      $table->uuid('purchaseOrderPaymentStatus_id')->nullable(true);
      $table->float('value_CAD',2)->nullable(true);
      $table->float('value_USD',2)->nullable(true);
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->unique(['id'],'purchase_order_pk');
      $table->unique(['number'],'purchase_order_identifier');
    });
    Schema::create('purchase_order_items', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->uuid('part_id');
      $table->uuid('purchaseOrder_id');
      $table->integer('qty');
      $table->float('cost',2);
      $table->char('cost_currency',20);
      $table->integer('qty_received')->nullable(true)->default(0);
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->unique(['id'],'purchase_order_items_pk');
    });
    Schema::create('purchase_order_cdstatuses', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->char('status',50);
      $table->text('description')->nullable(true);
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->unique(['id'],'purchase_order_credit_debit_status_pk');
    });
    Schema::create('purchase_order_diffs', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->char('number',50);
      $table->integer('qty_diff')->nullable(true)->default(0);
      $table->float('value_diff_CAD',0)->nullable(true)->default(0);
      $table->float('value_diff_USD')->nullable(true)->default(0);
      $table->text('credit_debit_details')->nullable(true);
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->uuid('purchaseOrder_id')->nullable(true)->unique();
      $table->unique(['id'],'purchase_order_difference_pk');
    });
    Schema::create('purchase_order_diff_items', function (Blueprint $table) {
      $table->uuid('id')->unique();
      $table->integer('qty_paid_for')->nullable(true);
      $table->float('cost',2)->nullable(true);
      $table->char('cost_currency',20)->nullable(true);
      $table->integer('qty_received')->nullable(true);
      $table->integer('qty_diff')->nullable(true)->virtualAs('qty_received - qty_paid_for');
      $table->float('value_diff',2)->nullable(true)->virtualAs('( (qty_received * cost) - (qty_paid_for*cost) )');
      $table->timestamp('created_at')->nullable(true);
      $table->timestamp('updated_at')->nullable(true);
      $table->uuid('part_id')->nullable(true);
      $table->uuid('purchaseOrderDiff_id')->nullable(true);
      $table->unique(['id'],'purchase_order_difference_items_pk');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->foreign('supplier_id')->references('id')->on('suppliers');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->foreign('purchaseOrderStatus_id')->references('id')->on('purchase_order_statuses');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->foreign('purchaseOrderPayment_id')->references('id')->on('purchase_order_payments');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->foreign('purchaseOrderPaymentStatus_id')->references('id')->on('purchase_order_payment_statuses');
    });
    Schema::table('purchase_order_items', function (Blueprint $table) {
      $table->foreign('part_id')->references('id')->on('parts');
    });
    Schema::table('purchase_order_items', function (Blueprint $table) {
      $table->foreign('purchaseOrder_id')->references('id')->on('purchase_orders');
    });
    Schema::table('purchase_order_diffs', function (Blueprint $table) {
      $table->foreign('purchaseOrder_id')->references('id')->on('purchase_orders');
    });
    Schema::table('purchase_order_diff_items', function (Blueprint $table) {
      $table->foreign('part_id')->references('id')->on('parts');
    });
    Schema::table('purchase_order_diff_items', function (Blueprint $table) {
      $table->foreign('purchaseOrderDiff_id')->references('id')->on('purchase_order_diffs');
    });
  }
  /**
   * Reverse the migrations.
   *
   * @return void
  */
  public function down()
  {
    Schema::table('purchase_order_diff_items', function (Blueprint $table) {
      $table->dropForeign('purchaseOrderDiff_id');
    });
    Schema::table('purchase_order_diff_items', function (Blueprint $table) {
      $table->dropForeign('part_id');
    });
    Schema::table('purchase_order_diffs', function (Blueprint $table) {
      $table->dropForeign('purchaseOrder_id');
    });
    Schema::table('purchase_order_items', function (Blueprint $table) {
      $table->dropForeign('purchaseOrder_id');
    });
    Schema::table('purchase_order_items', function (Blueprint $table) {
      $table->dropForeign('part_id');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->dropForeign('purchaseOrderPaymentStatus_id');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->dropForeign('purchaseOrderPayment_id');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->dropForeign('purchaseOrderStatus_id');
    });
    Schema::table('purchase_orders', function (Blueprint $table) {
      $table->dropForeign('supplier_id');
    });
    Schema::dropIfExists('purchase_order_diff_items');
    Schema::dropIfExists('purchase_order_diffs');
    Schema::dropIfExists('purchase_order_cdstatuses');
    Schema::dropIfExists('purchase_order_items');
    Schema::dropIfExists('purchase_orders');
    Schema::dropIfExists('purchase_order_payment_statuses');
    Schema::dropIfExists('purchase_order_payments');
    Schema::dropIfExists('purchase_order_statuses');
    Schema::dropIfExists('suppliers');
  }
}