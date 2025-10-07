<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function __construct()
    {
        
    }
    public function user(Request $request)
    {
        // return response()->json([
        //     'user'=>auth()->guard('web')->user()
        // ]);
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($user);
    }

    public function create() {
        
    }
}
