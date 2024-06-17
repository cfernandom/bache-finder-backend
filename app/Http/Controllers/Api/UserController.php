<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {

        try {
            $user = auth()->user();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        
        return response()->json($user);
    }
}
