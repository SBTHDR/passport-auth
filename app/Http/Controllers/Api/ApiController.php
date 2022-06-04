<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // Register New User
    public function register (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|',
            'type' => 'integer',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $request['password'] = Hash::make($request['password']);

        $user = User::create($request->toArray());

        $token = $user->createToken('authToken')->accessToken;
        $response = ['token' => $token];

        return response($response, 200);
    }


    
    // Login User
    public function login (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('authToken')->accessToken;
                $response = ['token' => $token];

                return response($response, 200);
            } else {
                $response = ["message" => "Invalid Credentials"];

                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];

            return response($response, 422);
        }
    }



    // Logout user
    public function logout (Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response(['message' => 'logged out', 200]);
    }
    

}
