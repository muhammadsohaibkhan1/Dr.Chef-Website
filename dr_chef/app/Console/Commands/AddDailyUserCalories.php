<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCalories;
use Illuminate\Support\Facades\DB;
use DateTime;

class AddDailyUserCalories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calorie:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $yesterday = new DateTime();
        $yesterday->modify('-1 day');
        $yesterdayDate = $yesterday->format('Y-m-d');

        // $userCalories = DB::select('SELECT user_calorie_need FROM user_calories WHERE = ?', [$yesterdayDate]);
        $dateToday = date('Y-m-d');
        $userId = DB::select('SELECT user_id FROM users');

        foreach ($userId as $users) {
            $userCalories = DB::select('SELECT user_calorie_need FROM user_calories WHERE date = ? AND user_id = ?', [$yesterdayDate, $users->user_id]);
            UserCalories::create([
                'user_id' => $users->user_id,
                'user_calorie_need' => $userCalories[0]->user_calorie_need,
                'user_daily_calorie_intake' => 0,
                'user_weight_gain' => 0,
                'user_weight_loss' => 0,
                'date' => $dateToday,
                'user_carbs_intake' => 0,
                'user_protien_intake' => 0,
                'user_iron_intake' => 0,
                'user_dietaryfiber_intake' => 0,
                'user_sugar_intake' => 0,
                'user_calcium_intake' => 0,
                'user_magnesium_intake' => 0,
                'user_potassium_intake' => 0,
                'user_sodium_intake' => 0,
                'user_vitamin_c_intake' => 0,
                'user_vitamin_d_intake' => 0,
                'user_vitamin_b6_intake' => 0,
                'user_vitamin_b12_intake' => 0,
                'user_cholesterol_intake' => 0,
                'user_fats_intake'  => 0
            ]);
        }
        return Command::SUCCESS;
    }
}
