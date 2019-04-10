<?php
use App\Models\Part as Part;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\Route;
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

/* Debugging Routes */

app('debugbar')->disable();

Route::get('replenish','PurchaseOrder\AutoPurchaseOrderController@partsToReplenish');

Route::get('stogenerate', 'StockTransfer\StockTransferController@getListOfPartsToTransfer');

Route::get('menu', function(){
    $menu = HelperController::getDeviceListForNavigationMenu();
    dd($menu);
});

/* End Debugging Routes */

Route::get('/setLocation/{location}', function(Location $location){
    HelperController::setCurrentLocation($location);
    session()->flash('success',['Your Location is updated to : '.$location->location]);
    $to = session('_previous')['url'];
    return redirect($to);
});

Route::group(['prefix'=>'ajax','middleware'=>'auth'],function (){

    Route::put('/operation','AjaxRequestController@processRequest');

});


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
        Route::get('/','PurchaseOrder\SupplierController@index');
        Route::get('create','PurchaseOrder\SupplierController@create');
        Route::post('create','PurchaseOrder\SupplierController@insert');
        Route::get('view/{id}','PurchaseOrder\SupplierController@view');
        Route::get('edit/{id}','PurchaseOrder\SupplierController@edit');
        Route::put('edit/{id}','PurchaseOrder\SupplierController@update');
    });
    Route::prefix('purchase')->group(function (){
        Route::get('/','PurchaseOrder\PurchaseOrderController@index');
        Route::get('/create','PurchaseOrder\PurchaseOrderController@create');
        Route::post('create','PurchaseOrder\PurchaseOrderController@insert');
        Route::get('view/{id}','PurchaseOrder\PurchaseOrderController@view');
        Route::get('edit/{purchaseOrder}','PurchaseOrder\PurchaseOrderController@edit');
        Route::put('edit/{purchaseOrder}','PurchaseOrder\PurchaseOrderController@update');
        Route::get('delete/{purchaseOrder}','PurchaseOrder\PurchaseOrderController@delete');
        Route::get('verify/{purchaseOrder}','PurchaseOrder\PurchaseOrderActionsController@verify');
        Route::post('receiveItem/{sku}/{purchaseOrderID}','PurchaseOrder\PurchaseOrderActionsController@itemReceived');
        Route::get('finalize/{purchaseOrder}','PurchaseOrder\PurchaseOrderActionsController@finalizeShipment');
        Route::get('shortexcess/{purchaseOrderDiff}','PurchaseOrder\PurchaseOrderController@viewDiff');
        Route::get('distribute/{purchaseOrder}','PurchaseOrder\PurchaseOrderActionsController@distributeShipment');
        Route::put('distribute/item/edit','PurchaseOrder\PurchaseOrderActionsController@editDistributionRecord');

        Route::prefix('mark')->group(function (){
            Route::get('verified/{purchaseOrder}','PurchaseOrder\PurchaseOrderActionsController@markVerified');
            Route::get('distributed/{purchaseOrder}','PurchaseOrder\PurchaseOrderActionsController@generateStockTransfer');
            Route::get('completed/{purchaseOrder}','PurchaseOrder\PurchaseOrderActionsController@markCompleted');

        });

        Route::get('generate','PurchaseOrder\AutoPurchaseOrderController@initiatePurchaseOrder');
        Route::get('replenish','PurchaseOrder\AutoPurchaseOrderController@createPurchaseOrderForReplishment');

        Route::prefix('payment')->group(function (){
            Route::get('/','PurchaseOrder\PurchaseOrderPaymentController@index');
            Route::get('create','PurchaseOrder\PurchaseOrderPaymentController@create');
            Route::post('create','PurchaseOrder\PurchaseOrderPaymentController@insert');
            Route::get('edit/{purchaseOrderPayment}','PurchaseOrder\PurchaseOrderPaymentController@edit');
            Route::put('edit/{purchaseOrderPayment}','PurchaseOrder\PurchaseOrderPaymentController@update');
            Route::get('delete/{purchaseOrderPayment}','PurchaseOrder\PurchaseOrderPaymentController@delete');
        });

        Route::prefix('export')->group(function (){
            Route::get('PDF/{id}','PurchaseOrder\AutoPurchaseOrderController@exportPDF');
            Route::get('CSV/{id}','PurchaseOrder\AutoPurchaseOrderController@exportCSV');
        });
        Route::prefix('item')->group(function (){
            Route::get('/','PurchaseOrder\PurchaseOrderItemController@index');
            Route::get('/create','PurchaseOrder\PurchaseOrderItemController@create');
            Route::post('create','PurchaseOrder\PurchaseOrderItemController@insert');
            Route::get('view/{id}','PurchaseOrder\PurchaseOrderItemController@view');
            Route::get('edit/{id}','PurchaseOrder\PurchaseOrderItemController@edit');
            Route::put('edit/{id}','PurchaseOrder\PurchaseOrderItemController@update');
            Route::delete('delete/{id}','PurchaseOrder\PurchaseOrderItemController@delete');
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
    Route::get('/stock_name','Import\ImportUpdatedStockAndNameController@index');
    Route::post('/upload/stock_name','Import\ImportUpdatedStockAndNameController@upload');
    Route::prefix('sales')->group(function (){
        Route::get('/','Import\ImportSalesDataController@index');
        Route::post('/upload','Import\ImportSalesDataController@upload');
    });
});


Route::group([ 'prefix' => 'stocktransfer', 'middleware' => 'auth' ],function (){

    Route::get('/','StockTransfer\StockTransferController@index');
    Route::prefix('list')->group(function (){
        Route::get('/','StockTransfer\StockTransferController@index');
        Route::get('/{filter}','StockTransfer\StockTransferController@index');
    });

    Route::get('create','StockTransfer\StockTransferController@create');
    Route::post('create','StockTransfer\StockTransferController@requestInsert');
    Route::get('edit/{stockTransfer}','StockTransfer\StockTransferController@edit');
    Route::put('update/{stockTransfer}','StockTransfer\StockTransferController@update');
    Route::get('delete/{stockTransfer}','StockTransfer\StockTransferController@delete');
    Route::get('exportCSV/{stockTransfer}','StockTransfer\StockTransferController@exportCSV');

    Route::prefix('item')->group(function(){
        Route::post('add','StockTransfer\StockTransferController@requestAddItem');
        Route::delete('delete/{stockTransferItem}','StockTransfer\StockTransferController@requestDeleteItem');
        Route::put('update/{stockTransferItem}','StockTransfer\StockTransferController@requestUpdateItem');
        Route::put('send','StockTransfer\StockTransferController@requestAddItemToSentBySKU');
        Route::put('receive','StockTransfer\StockTransferController@requestAddItemToReceivedBySKU');
    });

    Route::prefix('mark')->group(function (){
        Route::get('completed/{stockTransfer}','StockTransfer\StockTransferController@requestMarkVerified');
    });

    Route::get('generate','StockTransfer\StockTransferController@generateTransferOrder');

});


Route::get('/sales','SalesDataController@index');
Route::get('/sales/monthly','SalesDataController@listByMonth');
Route::get('/sales/part/{id}','SalesDataController@part');
Route::get('/sales/monthly/part/{id}','SalesDataController@partByMonth');

Route::get('/sales/reorder', 'SalesDataController@reorderStrategy');


