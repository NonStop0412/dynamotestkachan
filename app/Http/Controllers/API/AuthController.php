<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->balance = 10;

        $success = [
            'account_id' => $user->id,
            'email' => $user->email,
            'balance' => $user->balance,
            'token' =>  $user->createToken('MyApp')->accessToken
        ];

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required',
           'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)) {
            return $this->sendError('Invalid email or password', $validator->errors());
        }

        $user = $request->user();
        $tokenResult = $user->createToken('MyApp');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $success = [
            'account_id' => $user->id,
            'email' => $user->email,
            'balance' => $user->balance,
            'token' => $tokenResult->accessToken,
        ];


        return $this->sendResponse($success, 'Success');
    }
}
