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

Route::prefix('lookup')->group(function(){
    Route::get('/','LookupController@lookup_master');
    Route::get('sku/{sku}','LookupController@lookup_part_sku');
    Route::get('item/{id}','LookupController@lookup_part_id');
    Route::get('device/{id}','LookupController@lookup_device_id');
});

Route::prefix('stock')->group(function(){
    Route::get('/','StockCountController@index');
    Route::get('view/{id}','StockCountController@details');
    Route::get('create','StockCountController@create');
    Route::post('additem','StockCountController@additem');
    Route::get('summary/{id}','StockCountController@aggregate');
});

Route::prefix('search')->group(function(){
    Route::get('barcode','LookupController@searchBarcode');
    Route::get('{term}','SearchController@search')->name('search');
});

// TODO: Create API controller group
Route::prefix('api')->group(function(){
    Route::get('findModelWithBrandID/{id}','LookupController@findModelWithBrandID');
    Route::get('findPartWithDeviceID/{id}','LookupController@findPartWithDeviceID');
    Route::get('getPartDetailsWithID/{id}','LookupController@getPartDetailsWithID');
    Route::get('getPartDetailsWithSKU/{sku}','LookupController@getPartDetailsWithSKU');
});

// All routes below here require cleanup/removal and handlers to be adjusted accordingly

Route::get('/', 'LookupController@index');

Route::view('/main','main');

Route::get('itemlookup/sku/{sku}','LookupController@lookup_part_sku');

Route::get('itemlookup/id/{id}','LookupController@lookup_part_id');

Route::get('devicelookup/id/{id}','LookupController@lookup_device_id');

Route::get('lookup','LookupController@lookup_master');

Route::get('findModelWithBrandID/{id}','LookupController@findModelWithBrandID');

Route::get('findPartWithDeviceID/{id}','LookupController@findPartWithDeviceID');

Route::get('getPartDetailsWithID/{id}','LookupController@getPartDetailsWithID');

Route::get('getPartDetailsWithSKU/{sku}','LookupController@getPartDetailsWithSKU');

Route::get('search/barcode','LookupController@searchBarcode');

Route::get('search/{term}','SearchController@search')->name('search');

Route::get('device/{id}','LookupController@lookup_device_id');

Route::get('stockcounts','StockCountController@index');

Route::get('stockcount/count/id/{id}','StockCountController@details');

Route::get('stockcounts/create','StockCountController@create');

Route::post('stockcount/additem','StockCountController@additem');

Route::get('stockcount/aggregate/id/{id}','StockCountController@aggregate');

Route::get('stockcount/{status_update}/id/{id}','StockCountController@statusupdate')->where('status_update','(restart|pause|end)');



Route::get('/part-price', function () {
    // return Part::all();
    echo "<pre>";
    echo json_encode(Part::with('price')->get(),JSON_PRETTY_PRINT);
    echo "</pre>";
    // return view('welcome');
});

Route::get('/login', function(){

});
