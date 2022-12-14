<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        /*return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);*/
        

        $success['token'] = $token;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->accessToken;

        /*return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);*/


        $success['token'] =  $token;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User login successfully.');
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        /*return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];*/
    }
}
