<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DailyMealController;
use App\Models\DailyMeal;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', [AuthController::class, "loginView"])->name("login");
Route::post('/login', [AuthController::class, "loginPost"]);


Route::post('/logout', [AuthController::class, "logoutWeb"])->middleware("auth");

Route::get('/register', function () {
    return Inertia::render('register');
});
Route::post('/register', [AuthController::class, "store"]);


Route::middleware("auth")->group(function () {
    Route::get('/', function () {
        $data = DailyMeal::where('user_id', auth()->user()->id)->first();

        return Inertia::render('home', [
            "meal_status" => $data->manager_status == 0 ? 0 : $data->meal_status,
            "date" => date("H") > 22 ? date("d/M/Y", strtotime("+1days")) : date("d/M/Y")
        ]);
    });

    Route::post('/toggle', [DailyMealController::class, "toggleWeb"]);



    Route::get('/meal_history', function () {
        return Inertia::render('meal_history');
    });

    Route::get('/daily_meal', function () {
        return Inertia::render('daily_meal');
    });

    Route::get('/border_list', function () {
        return Inertia::render('border_list');
    });
});
