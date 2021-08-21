<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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

        DB::table('users')->insert([
            "name" => $request->name
        ]);
        return response()->json(['success' => true, 'message' => 'success created user data', 'data' => new \stdClass()]); 
    }
}
