<?php

namespace App\Http\Controllers;

use App\Models\DailyMeal;
use Illuminate\Http\Request;

class DailyMealController extends Controller
{
    public function toggle(Request $request)
    {

        $request->validate([
            'status' => 'required|integer',
        ]);

        $dailyMeal = DailyMeal::where('user_id', auth("api")->user()->id)->first();

        if ($dailyMeal->manager_status == 0) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Your meal can not be changed. Contact Manager.',
                "data" => false
            ], 200);
        }

        DailyMeal::updateOrCreate([
            'user_id'   => auth("api")->user()->id,
        ], [
            'meal_status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $request->status ? "Meal turn on " : "Meal turn off " . ' successfully',
            "data" => $request->status ? true : false
        ], 200);
    }

    public function toggleWeb(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'meal_status' => 'required|integer',
        ]);

        $dailyMeal = DailyMeal::where('user_id', auth()->user()->id)->first();

        if ($dailyMeal->manager_status == 0) {
            return back()->withErrors([
                'meal_status' => "Your meal cant be changed. Contact Manager."
            ]);
        }

        DailyMeal::updateOrCreate([
            'user_id'   => auth()->user()->id,
        ], [
            'meal_status' => $request->meal_status
        ]);

        return back();
    }

    public function toggleManager(Request $request)
    {

        if (auth("api")->user()->user_role == 2) {
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

        DailyMeal::updateOrCreate([
            'user_id'   => $request->user_id,
        ], [
            'manager_status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $request->status ? "Meal turn on successfully" : "Meal turn off successfully"
        ], 200);
    }


    public function mealStatus(Request $request)
    {
        $user_id = auth("api")->user()->id;

        $dailyMeal = DailyMeal::where('user_id', $user_id)->first();

        if ($dailyMeal->manager_status == 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Meal OFF',
                "data" => false
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Meal ON',
                "data" => $dailyMeal->meal_status ? true : false
            ], 200);
        }
    }
}
