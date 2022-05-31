<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BorderController extends Controller
{
    public function list(Request $request)
    {

        if (auth("api")->user()->user_role == 2) {
            return response()->json([
                'status' => 'fail',
                'message' => 'You are not allowed to access the page',
                "data" => false
            ], 200);
        }

        $users = User::whereNot('user_role', 0)
        ->join("daily_meals","users.id","=","daily_meals.user_id")
        ->select("users.*","daily_meals.manager_status as manager_status")
        ->orderByRaw('CONVERT(border_no, SIGNED)')
        ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            "data" => $users
        ], 200);
    }
}
