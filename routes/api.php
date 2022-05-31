<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BorderController;
use App\Http\Controllers\DailyMealController;
use App\Http\Controllers\HallStatusController;
use App\Http\Controllers\MealController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("user")->group(function () {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);
});




Route::middleware("auth:api")->group(function () {
    Route::get("/user/me", [AuthController::class, "me"]);
    Route::post("/user/toggle_manager", [AuthController::class, "toggleManager"]);
    Route::post("/user/logout", [AuthController::class, "logout"]);



    Route::prefix("daily_meal")->group(function () {
        Route::post("/toggle", [DailyMealController::class, "toggle"]);
        Route::post("/toggle_manager", [DailyMealController::class, "toggleManager"]);
        Route::get("/meal_status", [DailyMealController::class, "mealStatus"]);
    });

    Route::prefix("hall")->group(function () {
        Route::post("/toggle", [HallStatusController::class, "toggle"]);
        Route::get("/status", [HallStatusController::class, "hallStatus"]);
    });

    Route::prefix("meal")->group(function () {
        Route::post("/month_report", [MealController::class, "monthReport"]);
        Route::post("/day_wise_report", [MealController::class, "dayWiseReport"]);
    });
    Route::get("/border/list", [BorderController::class, "list"]);
});
