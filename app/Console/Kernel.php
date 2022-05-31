<?php

namespace App\Console;

use App\Models\DailyMeal;
use App\Models\HallStatus;
use App\Models\Meal;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    /**
 * Get the timezone that should be used by default for scheduled events.
 *
 * @return \DateTimeZone|string|null
 */
    protected function scheduleTimezone()
    {
        return 'Asia/Dhaka';
    }
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $hallStatus = HallStatus::find(1);

            if ($hallStatus->meal_status == 0) {
                $daily_meals = DailyMeal::all();

                $arr = [];
                
                foreach ($daily_meals as $daily_meal) {
                    $meal = 1;
                if($daily_meal->meal_status == 1 && $daily_meal->manager_status==1)$meal = 0;
                    array_push($arr, ["user_id" => $daily_meal->user_id, "meal_status" => $meal, "date" => date("Y-m-d", strtotime("+1 day"))]);
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
                    Log::error($e);
                }
                DB::commit();
            }
        })->dailyAt("23:00");


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
