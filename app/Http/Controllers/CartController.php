<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function create(Request $request){
        DB::beginTransaction();
        try {
            $cartID = DB::table('carts')->insertGetId([
                "userID" => $request->userID
            ]);
            $bulkCartItems = $request->cartItems;
            foreach($bulkCartItems as &$cartItems){
                $cartItems['cartID'] = $cartID;
            }
            DB::table('cart_items')->insert($bulkCartItems);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => new \stdClass()]); 
        }
        return response()->json(['success' => true, 'message' => 'success created cart data', 'data' => new \stdClass()]); 
    }
}
