<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Cat;
use App\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate(['search' => 'required|string|max:64']);

//        dd(Product::search($request->search)->raw()['hits']);
        $cat = Cat::search($request->search)->paginate(1)[0];

        $brand = Brand::search($request->search)->paginate(1)[0];

        $products = Product::search($request->search)->paginate(20)
            ->load('cat:id,name', 'brand:id,name,common_name', 'prices');

        return view('searchs.show', compact('cat', 'brand', 'products'));
    }

    public function instant_search(Request $request)
    {
        $request->validate(['search' => 'required|string|max:64']);

        $cat = Cat::search($request->search)->paginate(1)[0];

        $brand = Brand::search($request->search)->paginate(1)[0];

        $products = Product::search($request->search)->paginate(10)
            ->load('cat:id,name', 'brand:id,name');

        return compact('cat', 'brand', 'products');
    }
}
