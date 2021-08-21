<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function generateProductRedis(Request $request){
        $dataProduct = DB::table('flash_products')->get();
        foreach($dataProduct as $product){
            app('redis')->set("product_".$product->id, $product->quantity);
        }
        return response()->json(['success' => true, 'message' => 'success generate product redis data', 'data' => $dataProduct]); 
    }

    public function getAll(){
        $dataProduct = DB::table('flash_products')->get();
        foreach($dataProduct as &$product){
            ($product->flagStock == 0) ? $product->flagStock = "Out of stock" : $product->flagStock = "Available";
        };
        return response()->json(['success' => true, 'message' => 'success generate product redis data', 'data' => $dataProduct]); 
    }
}
