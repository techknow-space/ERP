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

Route::group([ 'prefix' => 'lookup', 'middleware' => 'auth' ], function(){
    Route::get('/','LookupController@lookup_master');
    Route::get('sku/{sku}','LookupController@lookup_part_sku');
    Route::get('item/{id}','LookupController@lookup_part_id');
    Route::get('device/{id}','LookupController@lookup_device_id');
});

Route::group([ 'prefix' => 'stock', 'middleware' => 'auth' ], function(){
    Route::get('/','StockCountController@index');
    Route::get('view/{id}','StockCountController@details');
    Route::get('create','StockCountController@create');
    Route::post('additem','StockCountController@additem');
    Route::get('summary/{id}','StockCountController@aggregate');
});

Route::group([ 'prefix' => 'search', 'middleware' => 'auth' ], function(){
    Route::get('barcode','SearchController@searchBarcode');
    Route::get('/','SearchController@search')->name('search');
});

Route::group([ 'prefix' => 'order', 'middleware' => 'auth' ],function (){

    Route::prefix('supplier')->group(function (){
        Route::get('/','SupplierController@index');
        Route::post('create','SupplierController@create');
        Route::get('view/{id}','SupplierController@view');
        Route::get('edit/{$id}','SupplierController@edit');
        Route::put('edit/{id}','SupplierController@update');
    });

});

// TODO: Create API controller group
Route::prefix('api')->group(function(){
    Route::get('findModelWithBrandID/{id}','LookupController@findModelWithBrandID');
    Route::get('findPartWithDeviceID/{id}','LookupController@findPartWithDeviceID');
    Route::get('getPartDetailsWithID/{id}','LookupController@getPartDetailsWithID');
    Route::get('getPartDetailsWithSKU/{sku}','LookupController@getPartDetailsWithSKU');
});

// All routes below here require cleanup/removal and handlers to be adjusted accordingly

Route::get('/', 'HomeController@index');

Auth::routes();
// Auth::routes(['register' => false]);

// Route::view('/','main');
Route::view('/home','main');
Route::view('/main','main');

Route::get('itemlookup/sku/{sku}','LookupController@lookup_part_sku');

Route::get('itemlookup/id/{id}','LookupController@lookup_part_id');

Route::get('devicelookup/id/{id}','LookupController@lookup_device_id');

Route::get('findModelWithBrandID/{id}','LookupController@findModelWithBrandID');

Route::get('findPartWithDeviceID/{id}','LookupController@findPartWithDeviceID');

Route::get('getPartDetailsWithID/{id}','LookupController@getPartDetailsWithID');

Route::get('getPartDetailsWithSKU/{sku}','LookupController@getPartDetailsWithSKU');

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

Route::prefix('import')->group(function (){
    Route::get('/','ImportController@index');
    Route::post('/upload','ImportController@upload');
});

Route::get('/import','ImportController@index');
