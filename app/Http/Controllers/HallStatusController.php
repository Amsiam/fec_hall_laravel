<?php

namespace App\Http\Controllers;

use App\Models\HallStatus;
use Illuminate\Http\Request;

class HallStatusController extends Controller
{
    public function toggle(Request $request)
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
        ]);

        HallStatus::updateOrCreate([
            'id'   => 1,
        ], [
            'meal_status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            "data" => $request->status
        ], 200);
    }

    public function hallStatus(Request $request)
    {

        $hallStatus = HallStatus::find(1);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            "data" => $hallStatus->meal_status
        ], 200);
    }
}
