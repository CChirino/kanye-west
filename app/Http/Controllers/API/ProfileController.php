<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProfileController extends BaseController
{
    public function show(Request $request)
    {
        $userId = User::find(Auth::id());

        if (is_null($userId)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($userId, 'User data.');
    }
 
    public function update(Request $request)
    {
        $user = auth()->user();
    
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->lastname = $request->input('lastname', $user->lastname);
    
        $user->save();
    
        return $this->sendResponse($user, 'User data updated successfully.');
    }
}
