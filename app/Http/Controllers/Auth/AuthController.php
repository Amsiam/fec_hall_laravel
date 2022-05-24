<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\DailyMeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {


        $data = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'room_no' => 'required|string|max:255',
            'border_no' => 'required|string|max:255'
        ]);

        if ($data->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'User can not be created',
                'errors' => $data->errors()
            ], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'room_no' => $request->room_no,
            'border_no' => $request->border_no,
            'user_role' => 2
        ]);

        DailyMeal::create([
            'user_id' => $user->id,
            'meal_status' => 0,
            'manager_status' => 0
        ]);

        $token = $user->createToken($user->email)->accessToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'access_token' => $token
        ], 201);
    }

    public function login(Request $request)
    {


        $data = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($data)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Authentication Failed'
            ], 200);
        }



        $user = User::where("email", $request->email)->first();



        $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'user' => $user,
            'access_token' => $token
        ], 201);
    }

    public function logout(Request $request)
    {
        auth("api")->user()->token()->revoke();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ], 200);
    }

    public function me(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'user' => auth("api")->user(),
        ], 200);
    }
}
