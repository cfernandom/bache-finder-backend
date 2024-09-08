<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:8',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();
            $result['token'] = $user->createToken($request->device_name ?? 'default')->plainTextToken;
            $result['name'] = $user->name;
            $result['email'] = $user->email;
            $result['id'] = $user->id;

            return $this->sendResponse($result, 'User logged in successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {

        try {
            $request->user()->currentAccessToken()->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout.'], 500);
        }

        return $this->sendResponse([], 'User logged out successfully.');
    }

    public function register(CreateUserRequest $request)
    {
        User::create(
            $request->validated(),
        );

        return $this->sendResponse([], 'User registered successfully.');
    }
}
