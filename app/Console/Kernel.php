<?php

namespace App\Console;

use App\Models\DailyMeal;
use App\Models\HallStatus;
use App\Models\Meal;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $hallStatus = HallStatus::find(1);

            if ($hallStatus->meal_status == 0) {
                $daily_meals = DailyMeal::all();

                $arr = [];
                foreach ($daily_meals as $daily_meal) {
                    array_push($arr, ["user_id" => $daily_meal->user_id, "meal_status" => $daily_meal->meal_status, "date" => date("Y-m-d", strtotime("+1 day"))]);
                }

                DB::beginTransaction();
                try {
                    Meal::insert($arr);
                } catch (Exception $e) {
                    DB::rollBack();
                }
                DB::commit();
            } else {
                $daily_meals = DailyMeal::all();

                $arr = [];
                foreach ($daily_meals as $daily_meal) {
                    array_push($arr, ["user_id" => $daily_meal->user_id, "meal_status" => $hallStatus->meal_status, "date" => date("Y-m-d", strtotime("+1 day"))]);
                }

                DB::beginTransaction();
                try {
                    Meal::insert($arr);
                } catch (Exception $e) {
                    DB::rollBack();
                }
                DB::commit();
            }
        })->dailyAt("22:00");
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
