<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePoPaymentsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_payments', function (Blueprint $table) {
            $table->dropColumn(['amount','currency']);
            $table->float('amount_CAD', 8, 2)->nullable(true);
            $table->float('amount_USD', 8, 2)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_payments', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dropColumn(['amount_CAD','amount_USD']);
            $table->float('amount');
            $table->char('currency');
        });
    }
}
