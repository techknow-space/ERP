<?php
/* Migrations generated by: Skipper (http://www.skipper18.com) */
/* Migration id: f3788a77-dbd7-494c-a62c-fa66203d5565 */
/* Migration datetime: 2019-02-19 09:33:28.586751 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Migrations20190219093328 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
  */
  public function up()
  {
    Schema::table('device_types', function (Blueprint $table) {
      $table->integer('import_ref')->nullable(true);
    });
  }
  /**
   * Reverse the migrations.
   *
   * @return void
  */
  public function down()
  {
    Schema::table('device_types', function (Blueprint $table) {
      $table->dropColumn('import_ref');
    });
  }
}