<?php
/* Migrations generated by: Skipper (http://www.skipper18.com) */
/* Migration id: bdea689c-6c4a-4d7f-bd6f-b5dd36cd1a3d */
/* Migration datetime: 2019-02-12 10:01:01.872417 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Migrations20190212100101 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
  */
  public function up()
  {
    Schema::table('stock_counts', function (Blueprint $table) {
      //$table->char('number',30)->unique()->change();
    });

    Schema::table('stock_count_items_seqs',function (Blueprint $table) {
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
    Schema::table('stock_counts', function (Blueprint $table) {
      //$table->('number')->unique()->change();
    });
  }
}