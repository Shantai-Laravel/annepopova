<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lang;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\SetTranslation;
use App\Models\Set;

class SearchController extends Controller
{
    public function index(Request $request) {
        $findProducts = ProductTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                    ->orWhere('body', 'like',  '%'.$request->get('value').'%')
                                    ->pluck('product_id')->toArray();

        $products = Product::whereIn('id', $findProducts)->limit(5)->get();

        $searchResultSet = SetTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                    ->orWhere('description', 'like',  '%'.$request->get('value').'%')
                                    ->pluck('set_id')->toArray();

        $sets = Set::whereIn('id', $searchResultSet)->limit(5)->get();

        $search = $request->get('value');

        $data = view('front.inc.searchResults', compact('products', 'sets', 'search'))->render();

        return json_encode($data);
    }

    public function search(Request $request)
    {
        $products = [];
        $sets = [];
        if ($request->get('search')) {
            $findProducts = ProductTranslation::where('name', 'like',  '%'.$request->get('search').'%')
                                        ->orWhere('body', 'like',  '%'.$request->get('search').'%')
                                        ->pluck('product_id')->toArray();

            $products = Product::whereIn('id', $findProducts)->limit(5)->get();

            $searchResultSet = SetTranslation::where('name', 'like',  '%'.$request->get('search').'%')
                                        ->orWhere('description', 'like',  '%'.$request->get('search').'%')
                                        ->pluck('set_id')->toArray();

            $sets = Set::whereIn('id', $searchResultSet)->limit(5)->get();
        }

        $search = $request->get('search');

        return view('front.products.search',  compact('products', 'sets', 'search'));
    }
}
