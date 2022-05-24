<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function monthReport(Request $request)
    {

        $user_id = auth("api")->user()->id;

        $date = $request->date;

        $meal = Meal::whereMonth("date", date("m", strtotime($date)))->whereYear("date", date("Y", strtotime($date)))->where("user_id", $user_id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Meal report',
            "data" => $meal
        ], 200);
    }


    public function dayWiseReport(Request $request)
    {

        if (auth("api")->user()->user_role == 2) {
            return response()->json([
                'status' => 'fail',
                'message' => 'You are not allowed to view the report',
                "data" => false
            ], 200);
        }

        $date = $request->date;

        $meal = Meal::whereDate("date", $date)
            ->where("meal_status", 0)
            ->join("users", "users.id", "=", "meals.user_id")
            ->select("users.name as name", "users.room_no as room_no", "users.border_no as border_no")
            ->orderBy("users.border_no")
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Meal report',
            "data" => $meal
        ], 200);
    }
}
