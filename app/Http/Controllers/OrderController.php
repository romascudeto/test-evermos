<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    // This function is to create order based on cart with threshold method
    public function createThreshold(Request $request){
        DB::beginTransaction();
        try {
            $orderID = DB::table('orders')->insertGetId([
                "userID" => $request->userID,
                "name" => $request->name,
                "address" => $request->address,
                "phone" => $request->phone,
            ]);
            $dataCart = DB::table('cart_items')->where("cartID", $request->cartID)->get();
            $bulkOrderItems = [];
            foreach($dataCart as $cart){
                
                // Check flash product where stock status available 
                $product = DB::table('flash_products')
                ->where("id", $cart->productID)
                ->where("flagStock", 1)
                ->first();
                if (isset($product)){
                    $order['orderID'] = $orderID;
                    $order['productID'] = $cart->productID;
                    $order['quantity'] = $cart->quantity;
                    $quantityReal = $product->quantity - $cart->quantity;
                    
                    // Check if quantity after lower than quantity threshold 
                    if ($quantityReal < $product->quantityThreshold){
                        return response()->json(['success' => false, 'message' => 'out of stock !', 'data' => new \stdClass()]); 
                    }

                    // Check if quantity equals 0 then set stock status to "out of stock"
                    if ($quantityReal == $product->quantityThreshold){
                        DB::table('flash_products')->where("id", $cart->productID)->update(["flagStock"=> 0]);
                    }

                    // Decreasing quantity stock
                    DB::table('flash_products')->where("id", $cart->productID)->decrement("quantity", $cart->quantity);
                    $order['price'] = $cart->price;
                    array_push($bulkOrderItems, $order);
                }else{
                    return response()->json(['success' => false, 'message' => 'out of stock !', 'data' => new \stdClass()]); 
                }
            }
            DB::table('order_items')->insert($bulkOrderItems);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => new \stdClass()]); 
        }
        return response()->json(['success' => true, 'message' => 'success created cart data', 'data' => new \stdClass()]); 
    }

    // This function is to create order based on cart with check quantity redis method
    public function create(Request $request){
        DB::beginTransaction();
        try {
            $orderID = DB::table('orders')->insertGetId([
                "userID" => $request->userID,
                "name" => $request->name,
                "address" => $request->address,
                "phone" => $request->phone,
            ]);
            $dataCart = DB::table('cart_items')->where("cartID", $request->cartID)->get();
            $bulkOrderItems = [];
            foreach($dataCart as $cart){

                // Check validity product quantity in redis 
                $isValidQty = $this->checkQuantityInRedis($cart->productID, $cart->quantity);
                if (!$isValidQty){
                    return response()->json(['success' => false, 'message' => 'out of stock !', 'data' => new \stdClass()]); 
                }
                $order['orderID'] = $orderID;
                $order['productID'] = $cart->productID;
                $order['quantity'] = $cart->quantity;

                // Decreasing quantity stock
                DB::table('flash_products')->where("id", $cart->productID)->decrement("quantity", $cart->quantity);
                $order['price'] = $cart->price;
                array_push($bulkOrderItems, $order);
            }
            DB::table('order_items')->insert($bulkOrderItems);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'data' => new \stdClass()]); 
        }
        return response()->json(['success' => true, 'message' => 'success created cart data', 'data' => new \stdClass()]); 
    }
    
    public function checkQuantityInRedis($productID, $quantity){
        $quantityRedis = app('redis')->get("product_".$productID);
        $quantityRedisAfter = $quantityRedis - $quantity;

        // Check if quantity lower than product quantity in redis 
        if ($quantityRedisAfter < 0){
            return false;
        }

        // Check if quantity equals 0 then set stock status to "out of stock"
        if ($quantityRedisAfter == 0){
            DB::table('flash_products')->where("id", $productID)->update(["flagStock"=> 0]);
        }
        app('redis')->set("product_".$productID, $quantityRedisAfter);
        return true;
    }
}
