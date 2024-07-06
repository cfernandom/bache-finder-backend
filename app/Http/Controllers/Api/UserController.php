<?php

namespace App\Http\Controllers\Api;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserController extends BaseController
{

    public function index()
    {

        try {
            $user = User::find(auth()->user()->id);
            $result['name'] = $user->name;
            $result['email'] = $user->email;
            $result['id'] = $user->id;
            $result['role'] = $user->getRoleNames();
            $result['permissions'] = $user->hasRole(Roles::SUPER_ADMIN->value) 
                ? Permission::select('name')->get()->pluck('name')
                : $user->getPermissionNames();

        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage(), 500);
        }

        return $this->sendResponse($result, 'User retrieved successfully.');
    }
}
