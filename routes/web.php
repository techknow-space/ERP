<?php

use App\Models\Part as Part;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Route as Route;
use Illuminate\Http\Request;

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

    /*Temp Solution for AJAX search Results*/
    // TODO: Clean and Organize the Ajax Search.
    Route::get('ajax', function (Request $request) {
        return Part::search($request->input('search'))->get();
    });
    /* End AJAX Search*/

});

Route::group([ 'prefix' => 'order', 'middleware' => 'auth' ],function (){

    Route::prefix('supplier')->group(function (){
        Route::get('/','SupplierController@index');
        Route::get('create','SupplierController@create');
        Route::post('create','SupplierController@insert');
        Route::get('view/{id}','SupplierController@view');
        Route::get('edit/{id}','SupplierController@edit');
        Route::put('edit/{id}','SupplierController@update');
    });

    Route::prefix('purchase')->group(function (){

        Route::get('/','PurchaseOrderController@index');
        Route::get('/create','PurchaseOrderController@create');
        Route::post('create','PurchaseOrderController@insert');
        Route::get('view/{id}','PurchaseOrderController@view');
        Route::get('edit/{id}','PurchaseOrderController@edit');
        Route::put('edit/{id}','PurchaseOrderController@update');

        Route::prefix('item')->group(function (){
            Route::get('/','PurchaseOrderItemController@index');
            Route::get('/create','PurchaseOrderItemController@create');
            Route::post('create','PurchaseOrderItemController@insert');
            Route::get('view/{id}','PurchaseOrderItemController@view');
            Route::get('edit/{id}','PurchaseOrderItemController@edit');
            Route::put('edit/{id}','PurchaseOrderItemController@update');
            Route::delete('delete/{id}','PurchaseOrderItemController@delete');
        });

    });

});

// TODO: Create API controller group

Route::group([ 'prefix' => 'api', 'middleware' => 'auth' ],function (){

    Route::get('findModelWithBrandID/{id}','LookupController@findModelWithBrandID');
    Route::get('findPartWithDeviceID/{id}','LookupController@findPartWithDeviceID');
    Route::get('getPartDetailsWithID/{id}','LookupController@getPartDetailsWithID');
    Route::get('getPartDetailsWithSKU/{sku}','LookupController@getPartDetailsWithSKU');

    Route::prefix('part')->group(function (){

        Route::put('stock/increase/{id}','PartOperationController@increaseStock');
        Route::put('stock/decrease/{id}','PartOperationController@reduceStock');

    });

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
    Route::get('/','Import\ImportController@index');
    Route::post('/upload','Import\ImportController@upload');

    Route::prefix('sales')->group(function (){

        Route::get('/','Import\ImportSalesDataController@index');
        Route::post('/upload','Import\ImportSalesDataController@upload');

    });


});

Route::get('/sales','SalesDataController@index');
Route::get('/sales/monthly','SalesDataController@listByMonth');
Route::get('/sales/part/{id}','SalesDataController@part');
Route::get('/sales/monthly/part/{id}','SalesDataController@partByMonth');
