<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\DailyMeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

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

        if (substr($request->room_no, -2) > 10) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Room no not allowed.',
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

    public function toggleManager(Request $request)
    {

        if (auth("api")->user()->user_role != 0) {
            return response()->json([
                'status' => 'fail',
                'message' => 'You are not allowed to access the page',
                "data" => false
            ], 200);
        }

        $request->validate([
            'status' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        User::where(
            'id',
            $request->user_id
        )->update([
            'user_role' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $request->status == 1 ? "Marked as Manager successfully" : "Remove from manager successfully"
        ], 200);
    }


    //web
    public function loginView(Request $request)
    {

        return Inertia::render('login');
    }

    public function loginPost(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->intended();
        }

        return back()->withErrors(
            [
                "email" => "Email not valid or not registerd yet."
            ]
        );
    }
    public function logoutWeb(Request $request)
    {
        Auth::logout();
        return redirect()->route("login");
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'room_no' => 'required|string|max:3',
            'border_no' => 'required|string|max:255'
        ]);

        if (substr($request->room_no, -2) > 10) {
            return back()->withErrors(
                [
                    "room_no" => "Room no is not valid."
                ]
            );
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

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->intended();
        }
    }
}
