<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search()
    {
        $searchTerm = request('query');

        // Test for numeric (barcode input) or text (freeform search)
        if ( is_numeric($searchTerm) ) {
            return redirect('itemlookup/sku/'.$barcode);
        } else {
            $parts = \App\Models\Part::search($searchTerm)->paginate(10);
            return view('search.results')->with('results', $parts);
        }
    }

    public function searchBarcode()
    {
        $barcode = request('part-barcode');
        return redirect('itemlookup/sku/'.$barcode);
    }
}
