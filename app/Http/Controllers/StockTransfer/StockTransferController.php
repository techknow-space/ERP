<?php


namespace App\Http\Controllers\StockTransfer;

use App\Http\Controllers\Controller;
use App\Models\StockTransfer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class StockTransferController extends Controller
{
    /**
     * @param null $filter
     * @return View
     */
    public function index($filter = null): View
    {
        if(null === $filter){
            $stockTransfers = StockTransfer::all();
        }
        else{
            $stockTransfers = $this->filter($filter);
        }

        return view('stockTransfer.index')->with('stockTransfers',$stockTransfers);
    }

    /**
     * @param $filter
     * @return Collection
     */
    public function filter($filter): Collection
    {
        $stockTransfers = StockTransfer::all();
        return $stockTransfers;
    }
}
