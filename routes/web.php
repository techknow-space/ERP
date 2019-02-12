<?php

use App\Models\Part as Part;
use Illuminate\Support\Facades\Route as Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LookupController@index');

Route::get('itemlookup/sku/{sku}','LookupController@lookup_part_sku');

Route::get('itemlookup/id/{id}','LookupController@lookup_part_id');

Route::get('devicelookup/id/{id}','LookupController@lookup_device_id');

Route::get('lookup','LookupController@lookup_master');

Route::get('findModelWithBrandID/{id}','LookupController@findModelWithBrandID');

Route::get('findPartWithDeviceID/{id}','LookupController@findPartWithDeviceID');

Route::get('getPartDetailsWithID/{id}','LookupController@getPartDetailsWithID');

Route::get('getPartDetailsWithSKU/{sku}','LookupController@getPartDetailsWithSKU');

Route::get('device/{id}','LookupController@lookup_device_id');

Route::get('search/barcode','LookupController@searchBarcode');

Route::get('stockcounts','StockCountController@index');

Route::get('stockcount/count/id/{id}','StockCountController@details');

Route::get('stockcounts/create','StockCountController@create');

Route::post('stockcount/additem','StockCountController@additem');

Route::get('stockcount/aggregate/id/{id}','StockCountController@aggregate');


Route::get('/part-price', function () {
    // return Part::all();
    echo "<pre>";
    echo json_encode(Part::with('price')->get(),JSON_PRETTY_PRINT);
    echo "</pre>";
    // return view('welcome');
});

Route::get('/login', function(){

});
