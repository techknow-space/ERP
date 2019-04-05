<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function search()
    {
        $searchTerm = request('query');

        // Test for numeric (barcode input) or text (freeform search)
        if ( is_numeric($searchTerm) ) {
            return redirect('itemlookup/sku/'.$searchTerm);
        } else {

            $parts = \App\Models\Part::search($searchTerm)->paginate(5);
            return view('search.results')->with('results', $parts);
        }
    }

    public function searchBarcode()
    {
        $barcode = request('part-barcode');
        return redirect('itemlookup/sku/'.$barcode);
    }
}
