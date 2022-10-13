<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends BaseController
{
    public function account(Request $request)
    {
        $user = auth('api')->user();

        $success = [
            'account_id' => $user->id,
            'email' => $user->email,
            'balance' => $user->balance,
        ];

        return $this->sendResponse($success, 'Success');
    }
}
