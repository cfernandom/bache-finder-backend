<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends BaseController
{
    public function update(UpdateProfileRequest $request) 
    {
        try {
            auth()->user()->update($request->validated());
            $user = UserResource::make(auth()->user()->fresh());

            return $this->sendResponse($user, 'User updated successfully.');

        } catch (\Exception $e) {
            Log::error('Update User Error: ' . $e->getMessage(), ['exception' => $e]);
            return $this->sendError('Error.', 'An error occurred while updating the user.', 500);
        }
    }
}
