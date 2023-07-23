<?php

namespace App\Http\Controllers;

use App\Models\RecipeNutrient;
use App\Models\ReportRecipe;
use App\Models\ReportDietPlan;
use App\Models\RecipeLike;
use App\Models\RecipeLog;
use App\Models\Recipe;
use App\Models\SavedRecipe;
use App\Models\DietPlanLike;
use App\Models\ChefLike;
use App\Models\DietitianLike;
use App\Models\User;
use App\Models\UserCalories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;

class UserController extends Controller
{
    public function index()
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $userType = "";
            $userDisease = DB::select('SELECT user_disease FROM users WHERE user_id =?', [$userId]);
            if ($userDisease[0]->user_disease == "none") {
                $userType = "healthy person";
            } elseif ($userDisease[0]->user_disease == "diabetic ") {
                $userType = "diabetic patient";
            } elseif ($userDisease[0]->user_disease == "cardiac") {
                $userType = "cardiac  patient";
            } elseif ($userDisease[0]->user_disease == "blood pressure") {
                $userType = "blood pressure  patient";
            }

            $data = DB::select('SELECT
            recipes.recipe_id,
            recipes.recipe_name,
            recipes.recipe_image,
            recipes.recipe_likes,
            recipe_nutrients.recipe_calories,
            chefs.chef_id,
            chefs.chef_username,
            IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
            IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
        FROM recipes
        JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
        JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
        JOIN chefs ON recipes.chef_id = chefs.chef_id
        LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
        LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
        WHERE recipes.recipe_user_type=?
        ORDER BY recipes.recipe_likes DESC LIMIT 3', [$userType]);
        } else {
            $userId = session()->get('user_login_data.user_id');
            $data = DB::select('SELECT
                recipes.recipe_id,
                recipes.recipe_name,
                recipes.recipe_image,
                recipes.recipe_likes,
                recipe_nutrients.recipe_calories,
                chefs.chef_id,
                chefs.chef_username,
                IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                FROM recipes
                JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                JOIN chefs ON recipes.chef_id = chefs.chef_id
                LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = NULL
                LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = NULL
                ORDER BY recipes.recipe_likes DESC LIMIT 3');
        }

        return view('layouts.home')->with('data', $data);
    }

    public function login(Request $request)
    {
        if (session()->has('user_login_data')) {
            return redirect()->route('index');
        } else {
            $user_login_data = User::where(['user_email' => $request->user_email])->first();
            if (!$user_login_data || !Hash::check($request->user_password, $user_login_data->user_password)) {
                return redirect()
                    ->back()
                    ->with('message', 'Incorrect email or password!');
            } else {
                $request->session()->put('user_login_data', $user_login_data);
                if (session()->has('user_login_data')) {
                    session(['user_login_data' => $user_login_data]);
                    return redirect()
                        ->route('calorietracker/today')
                        ->withInput();
                }
            }
        }
    }

    public function logout()
    {
        if (session()->has('user_login_data')) {
            session()->pull('user_login_data', null);
            return redirect()->route('index');
        }
    }

    public function displayUserLoginForm(Request $request)
    {
        if (session()->has('user_login_data')) {
            return redirect()->route('index');
        } else {
            // Get the previous URL
            $previousUrl = url()->previous();

            // Store the previous URL in the session
            session(['previous_url' => $previousUrl]);
            return view('layouts.user_login_form');
        }
    }

    public function register(Request $request)
    {
        $user_email = $request->input('user_email');
        $password = $request->input('user_password');
        $username = $request->input('user_username');

        $data = $request->validate([
            'user_full_name' => 'required | regex:/^[\pL\s\-]+$/u',
            'user_email' => 'required |regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix| unique:users,user_email',
            'user_username' => 'required|unique:users,user_username|regex:/^[a-z0-9_]+$/',
            'user_password' => 'required | min:8',
            'user_profile_pic' => 'required | image | mimes:jpg,png,jpeg',
            'user_age' => 'required | integer | gte:0',
            'user_height' => 'required | between:0,99.99|gte:0',
            'user_weight' => 'required |integer|gte:0',
            'user_activity' => 'required',
            'user_disease' => 'required',
            'user_gender' => 'required',
            'user_weight_goal' => 'required'
        ]);

        $imageName = $username . '.' . $request->user_profile_pic->extension();

        $request->user_profile_pic->move(public_path('images/user_profile_images'), $imageName);

        User::create([
            'user_full_name' => $data['user_full_name'],
            'user_email' => $data['user_email'],
            'user_username' => $data['user_username'],
            'user_password' => Hash::make($data['user_password']),
            'user_profile_pic' => $imageName,
            'user_age' => $data['user_age'],
            'user_height' => $data['user_height'],
            'user_weight' => $data['user_weight'],
            'user_activity' => $data['user_activity'],
            'user_disease' => $data['user_disease'],
            'user_gender' => $data['user_gender'],
            'user_weight_goal' => $data['user_weight_goal']
        ]);

        // User Calorie Need Calculation According to Weight Goal
        $userData = DB::select('SELECT user_id, user_age, user_height, user_weight, user_activity,
                                    user_gender, user_weight_goal FROM users WHERE user_email=?', [$user_email]);

        $userId = $userData[0]->user_id;
        $userGender = $userData[0]->user_gender;
        $userAge = $userData[0]->user_age;
        $userWeightGoal = $userData[0]->user_weight_goal;
        $userWeightInLb = $userData[0]->user_weight * 2.20462;
        $userHeightInInches = $userData[0]->user_height * 12;
        $userActivity = $userData[0]->user_activity;
        $dateToday = date('Y-m-d');
        $userCalorieNeed = 0;
        $userActivityValue = 0;

        if ($userActivity == "sedentary")
            $userActivityValue = 1.2;

        if ($userActivity == "light")
            $userActivityValue = 1.375;

        if ($userActivity == "moderate")
            $userActivityValue = 1.55;

        if ($userActivity == "very active")
            $userActivityValue = 1.725;

        if ($userActivity == "extra active")
            $userActivityValue = 1.9;

        if ($userGender == "male") {
            $manBMR = ($userWeightInLb * 4.35) + ($userHeightInInches * 4.7) - ($userAge * 6.8) + 66;

            if ($userWeightGoal == "lose weight")
                $userCalorieNeed = $manBMR * $userActivityValue - 1000;
            else if ($userWeightGoal == "gain weight")
                $userCalorieNeed = ($manBMR * $userActivityValue) + 1000;
            else
                $userCalorieNeed = ($manBMR * $userActivityValue);
        }

        if ($userGender == "female") {
            $womanBMR = ($userWeightInLb * 4.35) + ($userHeightInInches * 4.7) - ($userAge * 4.7) + 655;

            if ($userWeightGoal == "lose weight")
                $userCalorieNeed = $womanBMR * $userActivityValue - 1000;
            else if ($userWeightGoal == "gain weight")
                $userCalorieNeed = ($womanBMR * $userActivityValue) + 1000;
            else
                $userCalorieNeed = ($womanBMR * $userActivityValue);
        }

        // Insert Data into user_calories Table
        UserCalories::create([
            'user_id' => $userId,
            'user_calorie_need' => $userCalorieNeed,
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

        // Login Session
        $user_login_data = User::where(['user_email' => $user_email])->first();
        $request->session()->put('user_login_data', $user_login_data);

        if (session()->has('user_login_data')) {
            session(['user_login_data' => $user_login_data]);

            return redirect()
                ->route('index');
        }
    }

    public function calorieTrackerToday(Request $request)
    {
        if (session()->has('user_login_data')) {
            $dateToday = date('Y-m-d');
            $dayName = date('l', strtotime($dateToday));
            $userEmail = $request->session()->get('user_login_data.user_email');
            $userId =  User::where('user_email', $userEmail)->pluck('user_id');

            $data = UserCalories::where('user_id', $userId)->where('date', $dateToday)->get()->first();

            $recipeIntakeIds = RecipeLog::where('user_id', $userId)->where('intake_date', $dateToday)->pluck('recipe_id');

            $recipes = Recipe::join('recipe_logs', 'recipes.recipe_id', '=', 'recipe_logs.recipe_id')
                ->join('recipe_nutrients', 'recipes.recipe_id', '=', 'recipe_nutrients.recipe_id')
                ->join('recipe_categories', 'recipes.category_id', '=', 'recipe_categories.category_id')
                ->whereIn('recipe_logs.recipe_id', $recipeIntakeIds)
                ->where('recipe_logs.intake_date', $dateToday)
                ->select('recipes.recipe_id', 'recipes.recipe_name', 'recipe_nutrients.recipe_calories', 'recipe_categories.category_name')
                ->get();

            return view('layouts.calorie_tracker_today')
                ->with('userData', $data)
                ->with('recipesIntake', $recipes)
                ->with('day', $dayName)
                ->with('date', $dateToday);
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function calorieTrackerWeekly(Request $request)
    {
        $userEmail = $request->session()->get('user_login_data.user_email');
        $userId =  User::where('user_email', $userEmail)->pluck('user_id');

        $date = date('Y') . '-W' . date('W');

        $dateSplit = explode("-W", $date);
        $startDate = (new DateTime())->setISODate($dateSplit[0], $dateSplit[1])->format('Y-m-d');
        $endDate = (new DateTime())->setISODate($dateSplit[0], $dateSplit[1], 7)->format('Y-m-d');

        $chartData = UserCalories::select('user_daily_calorie_intake', 'date')->where('user_id', $userId)->whereBetween('date', [$startDate, $endDate])->get();
        $nutrientsData = DB::table('user_calories')
            ->select(
                DB::raw('SUM(user_carbs_intake) as weekly_carbs'),
                DB::raw('SUM(user_protien_intake) as weekly_protein'),
                DB::raw('SUM(user_iron_intake) as weekly_iron'),
                DB::raw('SUM(user_dietaryfiber_intake) as weekly_fiber'),
                DB::raw('SUM(user_sugar_intake) as weekly_sugar'),
                DB::raw('SUM(user_calcium_intake) as weekly_calcium'),
                DB::raw('SUM(user_magnesium_intake) as weekly_magnesium'),
                DB::raw('SUM(user_potassium_intake) as weekly_potassium'),
                DB::raw('SUM(user_sodium_intake) as weekly_sodium'),
                DB::raw('SUM(user_vitamin_c_intake) as weekly_vitamin_c'),
                DB::raw('SUM(user_vitamin_d_intake) as weekly_vitamin_d'),
                DB::raw('SUM(user_vitamin_b6_intake) as weekly_vitamin_b6'),
                DB::raw('SUM(user_vitamin_b12_intake) as weekly_vitamin_b12'),
                DB::raw('SUM(user_cholesterol_intake) as weekly_cholesterol'),
                DB::raw('SUM(user_fats_intake) as weekly_fats')
            )
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->first();

        $dataArray = [];
        $weekDate = [];
        $dateRange = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime($endDate),
        );

        foreach ($dateRange as $d) {
            array_push($weekDate, $d->format('Y-m-d'));
            array_push($dataArray, 0);
        }

        array_push($weekDate, $endDate);
        array_push($dataArray, 0);

        $tempData = [];
        $tempDate = [];
        foreach ($chartData as $data) {
            array_push($tempData, $data->user_daily_calorie_intake);
            array_push($tempDate, $data->date);
        }

        for ($i = 0; $i < count($weekDate); $i++) {
            for ($j = 0; $j < count($tempDate); $j++) {
                if ($weekDate[$i] == $tempDate[$j]) {
                    $dataArray[$i] = $tempData[$j];
                    break;
                }
            }
        }

        $recipeIntakeIds = RecipeLog::where('user_id', $userId)
            ->whereBetween('intake_date', [$startDate, $endDate])
            ->pluck('recipe_id');

        $recipesIntake = RecipeLog::join('recipes', 'recipe_logs.recipe_id', '=', 'recipes.recipe_id')
            ->join('recipe_nutrients', 'recipes.recipe_id', '=', 'recipe_nutrients.recipe_id')
            ->join('recipe_categories', 'recipes.category_id', '=', 'recipe_categories.category_id')
            ->whereIn('recipe_logs.recipe_id', $recipeIntakeIds)
            ->select('recipe_logs.intake_date', 'recipes.recipe_id', 'recipes.recipe_name', 'recipe_nutrients.recipe_calories', 'recipe_categories.category_name')
            ->orderBy('recipe_logs.intake_date')
            ->get()
            ->groupBy('intake_date');

        return view(
            'layouts.calorie_tracker_weekly',
            ['data' => $dataArray, 'labels' => $weekDate, 'nutrients' => $nutrientsData, 'recipesIntake' => $recipesIntake]
        );
    }

    public function calorieTrackerMonthly(Request $request)
    {
        if (session()->has('user_login_data')) {
            $userEmail = $request->session()->get('user_login_data.user_email');
            $userId =  User::where('user_email', $userEmail)->pluck('user_id');

            $month = date('Y') . '-' . date('m');

            $dateSplit = explode("-", $month);
            $startDate = date($dateSplit[0] . '-' . $dateSplit[1] . '-01');
            $endDate = date($dateSplit[0] . '-' . $dateSplit[1] . '-t');

            $chartData = UserCalories::select('user_daily_calorie_intake', 'date')
                ->where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $nutrientsData = DB::table('user_calories')
                ->select(
                    DB::raw('SUM(user_carbs_intake) as monthly_carbs'),
                    DB::raw('SUM(user_protien_intake) as monthly_protein'),
                    DB::raw('SUM(user_iron_intake) as monthly_iron'),
                    DB::raw('SUM(user_dietaryfiber_intake) as monthly_fiber'),
                    DB::raw('SUM(user_sugar_intake) as monthly_sugar'),
                    DB::raw('SUM(user_calcium_intake) as monthly_calcium'),
                    DB::raw('SUM(user_magnesium_intake) as monthly_magnesium'),
                    DB::raw('SUM(user_potassium_intake) as monthly_potassium'),
                    DB::raw('SUM(user_sodium_intake) as monthly_sodium'),
                    DB::raw('SUM(user_vitamin_c_intake) as monthly_vitamin_c'),
                    DB::raw('SUM(user_vitamin_d_intake) as monthly_vitamin_d'),
                    DB::raw('SUM(user_vitamin_b6_intake) as monthly_vitamin_b6'),
                    DB::raw('SUM(user_vitamin_b12_intake) as monthly_vitamin_b12'),
                    DB::raw('SUM(user_cholesterol_intake) as monthly_cholesterol'),
                    DB::raw('SUM(user_fats_intake) as monthly_fats')
                )
                ->where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->first();

            $dataArray = [];
            $monthDate = [];
            $dateRange = new DatePeriod(
                new DateTime($startDate),
                new DateInterval('P1D'),
                new DateTime($endDate),
            );

            foreach ($dateRange as $d) {
                array_push($monthDate, $d->format('Y-m-d'));
                array_push($dataArray, 0);
            }

            array_push($monthDate, $endDate);
            array_push($dataArray, 0);

            $tempData = [];
            $tempDate = [];
            foreach ($chartData as $data) {
                array_push($tempData, $data->user_daily_calorie_intake);
                array_push($tempDate, $data->date);
            }

            for ($i = 0; $i < count($monthDate); $i++) {
                for ($j = 0; $j < count($tempDate); $j++) {
                    if ($monthDate[$i] == $tempDate[$j]) {
                        $dataArray[$i] = $tempData[$j];
                        break;
                    }
                }
            }

            $recipeIntakeIds = RecipeLog::where('user_id', $userId)
                ->whereBetween('intake_date', [$startDate, $endDate])
                ->pluck('recipe_id');

            $recipesIntake = RecipeLog::join('recipes', 'recipe_logs.recipe_id', '=', 'recipes.recipe_id')
                ->join('recipe_nutrients', 'recipes.recipe_id', '=', 'recipe_nutrients.recipe_id')
                ->join('recipe_categories', 'recipes.category_id', '=', 'recipe_categories.category_id')
                ->whereIn('recipe_logs.recipe_id', $recipeIntakeIds)
                ->select('recipe_logs.intake_date', 'recipes.recipe_id', 'recipes.recipe_name', 'recipe_nutrients.recipe_calories', 'recipe_categories.category_name')
                ->orderBy('recipe_logs.intake_date')
                ->get()
                ->groupBy('intake_date');

            return view(
                'layouts.calorie_tracker_monthly',
                ['data' => $dataArray, 'labels' => $monthDate, 'nutrients' => $nutrientsData, 'recipesIntake' => $recipesIntake]
            );
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function signupForm()
    {
        if (session()->has('user_login_data')) {
            return redirect()
                ->route('index');
        } else {
            return view('layouts.user_signup_form');
        }
    }

    public function exercises()
    {
        if (session()->has('user_login_data')) {
            $exercises = DB::select('select * from exercises');
            $userId = session()->get('user_login_data.user_id');
            // $user = DB::select('select * from users where user_id = "' . $userId . '"');
            $user = User::select('user_weight')->where('user_id', $userId)->get()->first();
            return view('layouts.exercise')->with('exercises', $exercises)->with('user', $user);
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function burnedCalories(Request $request)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $burnedCalories = $request->input('burned_calories');
            $date = date('Y-m-d');
            $userDailyCalorieIntake = UserCalories::select('user_daily_calorie_intake', 'date')
                ->where('user_id', $userId)
                ->where('date', $date)
                ->value('user_daily_calorie_intake');

            if ($burnedCalories <= $userDailyCalorieIntake) {
                $newCalories = $userDailyCalorieIntake - $burnedCalories;
                DB::table('user_calories')
                    ->where('user_id', $userId)
                    ->where('date', $date)
                    ->Update([
                        'user_daily_calorie_intake' => $newCalories
                    ]);

                return redirect()->route('calorietracker/today');
            } else {
                return redirect()->route('exercises')->with('alert-msg', 'Cannot burn calories!');
            }
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function addCalories(Request $request)
    {
        if (session()->has('user_login_data')) {
            $date = date('Y-m-d');
            $recipeId = $request->input('recipe_id');
            $userId = session()->get('user_login_data.user_id');
            $recipeNutrients = RecipeNutrient::where('recipe_id', $recipeId)->get();

            foreach ($recipeNutrients as $value) {
                $calories = $value->recipe_calories;
                $carbs = $value->recipe_carbs;
                $protein = $value->recipe_protien;
                $iron = $value->recipe_iron;
                $fiber = $value->recipe_dietaryfiber;
                $sugar = $value->recipe_sugar;
                $calcium = $value->recipe_calcium;
                $magnesium = $value->recipe_magnesium;
                $potassium = $value->recipe_potassium;
                $sodium = $value->recipe_sodium;
                $vitaminC = $value->recipe_vitamin_c;
                $vitaminD = $value->recipe_vitamin_d;
                $vitaminB6 = $value->recipe_vitamin_b6;
                $vitaminB12 = $value->recipe_vitamin_b12;
                $cholesterol = $value->recipe_cholesterol;
                $fats = $value->recipe_fats;
            }

            UserCalories::where('user_id', $userId)
                ->where('date', $date)
                ->update([
                    'user_daily_calorie_intake' => DB::raw("user_daily_calorie_intake + $calories"),
                    'user_carbs_intake' => DB::raw("user_carbs_intake + $carbs"),
                    'user_protien_intake' => DB::raw("user_protien_intake + $protein"),
                    'user_iron_intake' => DB::raw("user_iron_intake + $iron"),
                    'user_dietaryfiber_intake' => DB::raw("user_dietaryfiber_intake + $fiber"),
                    'user_sugar_intake' => DB::raw("user_sugar_intake + $sugar"),
                    'user_calcium_intake' => DB::raw("user_calcium_intake + $calcium"),
                    'user_magnesium_intake' => DB::raw("user_magnesium_intake + $magnesium"),
                    'user_potassium_intake' => DB::raw("user_potassium_intake + $potassium"),
                    'user_sodium_intake' => DB::raw("user_sodium_intake + $sodium"),
                    'user_vitamin_c_intake' => DB::raw("user_vitamin_c_intake + $vitaminC"),
                    'user_vitamin_d_intake' => DB::raw("user_vitamin_d_intake + $vitaminD"),
                    'user_vitamin_b6_intake' => DB::raw("user_vitamin_b6_intake + $vitaminB6"),
                    'user_vitamin_b12_intake' => DB::raw("user_vitamin_b12_intake + $vitaminB12"),
                    'user_cholesterol_intake' => DB::raw("user_cholesterol_intake + $cholesterol"),
                    'user_fats_intake' => DB::raw("user_fats_intake + $fats"),
                ]);

            RecipeLog::create([
                'user_id' => $userId,
                'recipe_id' => $recipeId,
                'intake_date' => $date,
            ]);

            return redirect()->back()->with('success', 'Recipe calories are successfully added to Calorie Tracker');
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function userProfile(Request $request)
    {
        if (session()->has('user_login_data')) {
            $userEmail = $request->session()->get('user_login_data.user_email');
            $userId =  User::where('user_email', $userEmail)->pluck('user_id');

            $user = User::where('user_id', $userId)->get()->first();
            return view('layouts.user_profile', ['user' => $user]);
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function updateUserProfile(Request $request)
    {
        if (session()->has('user_login_data')) {
            $username = $request->input('user_username');
            $userEmail = $request->input('user_email');
            $userId = session()->get('user_login_data.user_id');
            $userFirstUsername = session()->get('user_login_data.user_username');
            $userProfilePicName = session()->get('user_login_data.user_profile_pic');

            // Change the profile pic name if username changed
            if ($username != $userFirstUsername) {
                $oldImagePath = public_path('images/user_profile_images/' . $userProfilePicName);

                // Get the extension of the old image
                $extension = pathinfo($oldImagePath, PATHINFO_EXTENSION);
                $newImageName = $username . '.' . $extension;
                $newImagePath = public_path('images/user_profile_images/' . $newImageName);

                // Rename the image file
                if (file_exists($oldImagePath)) {
                    rename($oldImagePath, $newImagePath);
                }

                User::where('user_id', $userId)
                    ->update([
                        'user_profile_pic' => $newImageName
                    ]);
            }

            $user_email_check = User::select('user_id')
                ->where('user_email', '=', $userEmail)
                ->whereNotIn('user_id', [$userId])
                ->get();

            $user_uname_check = User::select('user_id')
                ->where('user_username', $username)
                ->whereNotIn('user_id', [$userId])
                ->get();

            if ($user_email_check->isNotEmpty() || $user_uname_check->isNotEmpty()) {
                if ($user_uname_check->isNotEmpty())
                    return back()->withInput()->with('activeTab', 'profile')->with('username-alert', 'This Username is already taken');

                if ($user_email_check->isNotEmpty())
                    return back()->withInput()->with('activeTab', 'profile')->with('email-alert', 'This email is already registered');
            } else {

                $data = $request->validate([
                    'user_full_name' => 'required | regex:/^[\pL\s\-]+$/u',
                    'user_email' => 'required |regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                    'user_username' => 'required | regex:/^[a-z0-9_]+$/',
                    'user_profile_pic' => 'nullable | image | mimes:jpg,png,jpeg',
                    'user_age' => 'required | integer | gte:0',
                    'user_height' => 'required | between:0,99.99 | gte:0',
                    'user_weight' => 'required | integer | gte:0',
                    'user_activity' => 'required',
                    'user_disease' => 'required',
                    'user_gender' => 'required',
                    'user_weight_goal' => 'required'
                ]);

                if (!array_key_exists('user_profile_pic', $data)) {
                    User::where('user_id', $userId)
                        ->update([
                            'user_full_name' => $data['user_full_name'],
                            'user_email' => $data['user_email'],
                            'user_username' => $data['user_username'],
                            'user_age' => $data['user_age'],
                            'user_height' => $data['user_height'],
                            'user_weight' => $data['user_weight'],
                            'user_activity' => $data['user_activity'],
                            'user_disease' => $data['user_disease'],
                            'user_gender' => $data['user_gender'],
                            'user_weight_goal' => $data['user_weight_goal']
                        ]);
                    // User Calorie Need Calculation According to Weight Goal
                    $userData = DB::select('SELECT user_id, user_age, user_height, user_weight, user_activity,
                            user_gender, user_weight_goal FROM users WHERE user_email=?', [$userEmail]);

                    $userId = $userData[0]->user_id;
                    $userGender = $userData[0]->user_gender;
                    $userAge = $userData[0]->user_age;
                    $userWeightGoal = $userData[0]->user_weight_goal;
                    $userWeightInLb = $userData[0]->user_weight * 2.20462;
                    $userHeightInInches = $userData[0]->user_height * 12;
                    $userActivity = $userData[0]->user_activity;
                    $dateToday = date('Y-m-d');
                    $userCalorieNeed = 0;
                    $userActivityValue = 0;

                    if ($userActivity == "sedentary")
                        $userActivityValue = 1.2;

                    if ($userActivity == "light")
                        $userActivityValue = 1.375;

                    if ($userActivity == "moderate")
                        $userActivityValue = 1.55;

                    if ($userActivity == "very active")
                        $userActivityValue = 1.725;

                    if ($userActivity == "extra active")
                        $userActivityValue = 1.9;

                    if ($userGender == "male") {
                        $manBMR = ($userWeightInLb * 4.35) + ($userHeightInInches * 4.7) - ($userAge * 6.8) + 66;

                        if ($userWeightGoal == "lose weight")
                            $userCalorieNeed = $manBMR * $userActivityValue - 1000;
                        else if ($userWeightGoal == "gain weight")
                            $userCalorieNeed = ($manBMR * $userActivityValue) + 1000;
                        else
                            $userCalorieNeed = ($manBMR * $userActivityValue);
                    }

                    if ($userGender == "female") {
                        $womanBMR = ($userWeightInLb * 4.35) + ($userHeightInInches * 4.7) - ($userAge * 4.7) + 655;

                        if ($userWeightGoal == "lose weight")
                            $userCalorieNeed = $womanBMR * $userActivityValue - 1000;
                        else if ($userWeightGoal == "gain weight")
                            $userCalorieNeed = ($womanBMR * $userActivityValue) + 1000;
                        else
                            $userCalorieNeed = ($womanBMR * $userActivityValue);
                    }

                    UserCalories::where('user_id', $userId)
                        ->where('date', $dateToday)
                        ->update([
                            'user_calorie_need' => $userCalorieNeed
                        ]);

                    if (session()->has('user_login_data')) {
                        session()->pull('user_login_data', null);
                        $userLoginData = User::where(['user_email' => $userEmail])->first();
                        $request->session()->put('user_login_data', $userLoginData);

                        if (session()->has('user_login_data')) {
                            session(['user_login_data' => $userLoginData]);

                            return redirect()->back()->withInput()->with('activeTab', 'profile')->with('save-success', 'Changes Saved Successfully!');
                        }
                    }
                } else {
                    $imgName = public_path('images/user_profile_images/' . $username . '.' . $request->user_profile_pic->extension());
                    if (File::exists($imgName)) {
                        File::delete($imgName);
                    }

                    $imageName = $username . '.' . $request->user_profile_pic->extension();
                    $request->user_profile_pic->move(public_path('images/user_profile_images'), $imageName);

                    User::where('user_id', $userId)
                        ->update([
                            'user_full_name' => $data['user_full_name'],
                            'user_email' => $data['user_email'],
                            'user_username' => $data['user_username'],
                            'user_profile_pic' => $imageName,
                            'user_age' => $data['user_age'],
                            'user_height' => $data['user_height'],
                            'user_weight' => $data['user_weight'],
                            'user_activity' => $data['user_activity'],
                            'user_disease' => $data['user_disease'],
                            'user_gender' => $data['user_gender'],
                            'user_weight_goal' => $data['user_weight_goal']
                        ]);

                    // User Calorie Need Calculation According to Weight Goal
                    $userData = DB::select('SELECT user_id, user_age, user_height, user_weight, user_activity,
                user_gender, user_weight_goal FROM users WHERE user_email=?', [$userEmail]);

                    $userId = $userData[0]->user_id;
                    $userGender = $userData[0]->user_gender;
                    $userAge = $userData[0]->user_age;
                    $userWeightGoal = $userData[0]->user_weight_goal;
                    $userWeightInLb = $userData[0]->user_weight * 2.20462;
                    $userHeightInInches = $userData[0]->user_height * 12;
                    $userActivity = $userData[0]->user_activity;
                    $dateToday = date('Y-m-d');
                    $userCalorieNeed = 0;
                    $userActivityValue = 0;

                    if ($userActivity == "sedentary")
                        $userActivityValue = 1.2;

                    if ($userActivity == "light")
                        $userActivityValue = 1.375;

                    if ($userActivity == "moderate")
                        $userActivityValue = 1.55;

                    if ($userActivity == "very active")
                        $userActivityValue = 1.725;

                    if ($userActivity == "extra active")
                        $userActivityValue = 1.9;

                    if ($userGender == "male") {
                        $manBMR = ($userWeightInLb * 4.35) + ($userHeightInInches * 4.7) - ($userAge * 6.8) + 66;

                        if ($userWeightGoal == "lose weight")
                            $userCalorieNeed = $manBMR * $userActivityValue - 1000;
                        else if ($userWeightGoal == "gain weight")
                            $userCalorieNeed = ($manBMR * $userActivityValue) + 1000;
                        else
                            $userCalorieNeed = ($manBMR * $userActivityValue);
                    }

                    if ($userGender == "female") {
                        $womanBMR = ($userWeightInLb * 4.35) + ($userHeightInInches * 4.7) - ($userAge * 4.7) + 655;

                        if ($userWeightGoal == "lose weight")
                            $userCalorieNeed = $womanBMR * $userActivityValue - 1000;
                        else if ($userWeightGoal == "gain weight")
                            $userCalorieNeed = ($womanBMR * $userActivityValue) + 1000;
                        else
                            $userCalorieNeed = ($womanBMR * $userActivityValue);
                    }

                    UserCalories::where('user_id', $userId)
                        ->where('date', $dateToday)
                        ->update([
                            'user_calorie_need' => $userCalorieNeed
                        ]);

                    if (session()->has('user_login_data')) {
                        session()->pull('user_login_data', null);
                        $userLoginData = User::where(['user_email' => $userEmail])->first();
                        $request->session()->put('user_login_data', $userLoginData);

                        if (session()->has('user_login_data')) {
                            session(['user_login_data' => $userLoginData]);

                            return redirect()->back()->withInput()->with('activeTab', 'profile')->with('save-success', 'Changes Saved Successfully!');
                        }
                    }
                }
            }
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function updateUserPassword(Request $request)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $new_pswrd = $request->input('new_password');

            if (strlen($new_pswrd) >= 8) {
                User::where('user_id', $userId)
                    ->update([
                        'user_password' => Hash::make($new_pswrd),
                    ]);
                return redirect()->back()->with('activeTab', 'change-password')->with('password-change-success', 'Password Changed Successfully!');
            } else {
                return redirect()->back()->with('activeTab', 'change-password')->with('password-alert', 'Password must be at least 8 characters long!');
            }
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function deleteUser(Request $request)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $userPassword = $request->input('deletion_password');
            $publicPath = public_path();

            $user = User::select('user_password')->where('user_id', $userId)->first();

            if (Hash::check($userPassword, $user->user_password)) {
                DB::table('user_calories')->where('user_id', $userId)->delete();

                // Get user profile image name
                $userPic = DB::table('users')
                    ->select('user_profile_pic')
                    ->where('user_id', $userId)
                    ->get()
                    ->first();

                // Delete user profile pic from public directory
                $userProfileImagePath = $publicPath . '/images/user_profile_images/' . $userPic->user_profile_pic;

                if (file_exists($userProfileImagePath)) {
                    unlink($userProfileImagePath);
                }

                $recipeId = DB::table('recipe_likes')->select('recipe_id')->where('user_id', $userId)->get();
                $reportRecipeId = DB::table('report_recipes')->select('recipe_id')->where('user_id', $userId)->get();
                $chefId = DB::table('chef_likes')->select('chef_id')->where('user_id', $userId)->get();
                $dietitianId = DB::table('dietitian_likes')->select('dietitian_id')->where('user_id', $userId)->get();
                $dietPlanId = DB::table('diet_plan_likes')->select('diet_plan_id')->where('user_id', $userId)->get();
                $reportDietPlanId = DB::table('report_diet_plans')->select('diet_plan_id')->where('user_id', $userId)->get();

                SavedRecipe::where('user_id', $userId)->delete();
                RecipeLog::where('user_id', $userId)->delete();
                RecipeLike::where('user_id', $userId)->delete();
                ReportRecipe::where('user_id', $userId)->delete();
                ChefLike::where('user_id', $userId)->delete();
                DietitianLike::where('user_id', $userId)->delete();
                DietPlanLike::where('user_id', $userId)->delete();
                ReportDietPlan::where('user_id', $userId)->delete();
                RecipeLog::where('user_id', $userId)->delete();


                foreach ($recipeId as $rcpId) {
                    DB::table('recipes')->where('recipe_id', $rcpId)->decrement('recipe_likes');
                }
                foreach ($reportRecipeId as $rptrcpId) {
                    DB::table('recipes')->where('recipe_id', $rptrcpId)->decrement('recipe_reports');
                }

                foreach ($chefId as $chfId) {
                    DB::table('chefs')->where('chef_id', $chfId)->decrement('chef_likes');
                }

                foreach ($dietitianId as $dtnId) {
                    DB::table('dietitians')->where('dietitian_id', $dtnId)->decrement('dietitian_likes');
                }

                foreach ($dietPlanId as $dpId) {
                    DB::table('diet_plans')->where('diet_plan_id', $dpId)->decrement('diet_plan_likes');
                }
                foreach ($reportDietPlanId as $rptdpId) {
                    DB::table('diet_plans')->where('diet_plan_id', $rptdpId)->decrement('diet_plan_reports');
                }

                // Delete user
                User::where('user_id', $userId)->delete();

                session()->pull('user_login_data', null);

                return redirect()->route('user/login')->with('delete-account-success', 'Account Deleted Successfully!');
            } else {
                return redirect()->back()->with('activeTab', 'delete-account')->with('delete-account-alert', 'Invalid Password!');
            }
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function saveRecipe($id)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $recipeId = $id;
            $check = DB::select('select * from saved_recipes where user_id = "' . $userId . '" and recipe_id = "' . $recipeId . '"');

            if ($check == null) {
                SavedRecipe::create([
                    'user_id' => $userId,
                    'recipe_id' => $recipeId
                ]);
            } else {
                DB::delete('delete from saved_recipes where user_id = "' . $userId . '" and recipe_id = "' . $recipeId . '"');
            }
            return redirect()->back()->withInput();
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function displaySavedRecipes()
    {
        if (session()->has('user_login_data')) {
        } else {
            return redirect()
                ->route('user/login');
        }
        $userId = session()->get('user_login_data.user_id');
        $recipeIds = DB::table('saved_recipes')
            ->select('recipe_id')
            ->where('user_id', $userId)
            ->get()
            ->pluck('recipe_id')
            ->toArray();

        $data = DB::select('SELECT
                    recipes.recipe_id,
                    recipes.recipe_name,
                    recipes.recipe_image,
                    recipes.recipe_likes,
                    recipe_nutrients.recipe_calories,
                    chefs.chef_username,
                    chefs.chef_id,
                    saved_recipes.saved_recipe_id,
                    saved_recipes.user_id,
                    CASE WHEN recipe_likes.recipe_like_id IS NULL THEN null ELSE recipe_likes.recipe_like_id END AS recipe_like_id
                    FROM recipes
                    JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                    JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                    JOIN chefs ON recipes.chef_id = chefs.chef_id
                    LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id
                        AND saved_recipes.user_id = "' . $userId . '"
                    LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id
                        AND recipe_likes.user_id = "' . $userId . '"
                    WHERE (saved_recipes.user_id IS NOT NULL OR recipe_likes.user_id IS NOT NULL)
                    AND (saved_recipes.user_id = "' . $userId . '" OR recipe_likes.user_id = "' . $userId . '") ');

        return view('layouts.save_recipes')->with('data', $data);
    }

    public function likeRecipe($id)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $recipeId = $id;
            $check = DB::select('select * from recipe_likes where user_id = "' . $userId . '" and recipe_id = "' . $recipeId . '"');

            if ($check == null) {
                RecipeLike::create([
                    'user_id' => $userId,
                    'recipe_id' => $recipeId
                ]);
                $like = DB::select('select recipe_likes from recipes where recipe_id = "' . $recipeId . '"');
                $addLike = $like[0]->recipe_likes;
                DB::table('recipes')
                    ->where('recipe_id', $recipeId)
                    ->update(['recipe_likes' => ++$addLike]);
            } else {
                DB::delete('delete from recipe_likes where user_id = "' . $userId . '" and recipe_id = "' . $recipeId . '"');
                $like = DB::select('select recipe_likes from recipes where recipe_id = "' . $recipeId . '"');
                if ($like[0]->recipe_likes == 0) {
                    return redirect()->back()->withInput();
                } else {
                    $minusLike = $like[0]->recipe_likes;
                    DB::table('recipes')
                        ->where('recipe_id', $recipeId)
                        ->update(['recipe_likes' => --$minusLike]);
                }
            }
            return back()->withInput();
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function likeDietPlan($id)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $dietPlanId = $id;
            $check = DB::select('select * from diet_plan_likes where user_id = "' . $userId . '" and diet_plan_id = "' . $dietPlanId . '"');

            if ($check == null) {
                DietPlanLike::create([
                    'user_id' => $userId,
                    'diet_plan_id' => $dietPlanId
                ]);
                $like = DB::select('select diet_plan_likes from diet_plans where diet_plan_id = "' . $dietPlanId . '"');
                $addLike = $like[0]->diet_plan_likes;
                DB::table('diet_plans')
                    ->where('diet_plan_id', $dietPlanId)
                    ->update(['diet_plan_likes' => ++$addLike]);
            } else {
                DB::delete('delete from diet_plan_likes where user_id = "' . $userId . '" and diet_plan_id = "' . $dietPlanId . '"');
                $like = DB::select('select diet_plan_likes from diet_plans where diet_plan_id = "' . $dietPlanId . '"');
                if ($like[0]->diet_plan_likes == 0) {
                    return redirect()->back()->withInput();
                } else {
                    $minusLike = $like[0]->diet_plan_likes;
                    DB::table('diet_plans')
                        ->where('diet_plan_id', $dietPlanId)
                        ->update(['diet_plan_likes' => --$minusLike]);
                }
            }
            return redirect()->back()->withInput();
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function likeChef($id)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $chefId = $id;
            $check = DB::select('select * from chef_likes where user_id = "' . $userId . '" and chef_id = "' . $chefId . '"');
            //dd($check);
            if ($check == null) {
                ChefLike::create([
                    'user_id' => $userId,
                    'chef_id' => $chefId
                ]);
                $like = DB::select('select chef_likes from chefs where chef_id = "' . $chefId . '"');
                $addLike = $like[0]->chef_likes;
                DB::table('chefs')
                    ->where('chef_id', $chefId)
                    ->update(['chef_likes' => ++$addLike]);
            } else {
                DB::delete('delete from chef_likes where user_id = "' . $userId . '" and chef_id = "' . $chefId . '"');
                $like = DB::select('select chef_likes from chefs where chef_id = "' . $chefId . '"');
                if ($like[0]->chef_likes == 0) {
                    return redirect()->back()->withInput();
                } else {
                    $minusLike = $like[0]->chef_likes;
                    DB::table('chefs')
                        ->where('chef_id', $chefId)
                        ->update(['chef_likes' => --$minusLike]);
                }
            }
            return redirect()->back()->withInput();
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function likeDietitian($id)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $dietitianId = $id;
            $check = DB::select('select * from dietitian_likes where user_id = "' . $userId . '" and dietitian_id = "' . $dietitianId . '"');
            //dd($check);
            if ($check == null) {
                DietitianLike::create([
                    'user_id' => $userId,
                    'dietitian_id' => $dietitianId
                ]);
                $like = DB::select('select dietitian_likes from dietitians where dietitian_id = "' . $dietitianId . '"');
                $addLike = $like[0]->dietitian_likes;
                DB::table('dietitians')
                    ->where('dietitian_id', $dietitianId)
                    ->update(['dietitian_likes' => ++$addLike]);
            } else {
                DB::delete('delete from dietitian_likes where user_id = "' . $userId . '" and dietitian_id = "' . $dietitianId . '"');
                $like = DB::select('select dietitian_likes from dietitians where dietitian_id = "' . $dietitianId . '"');
                if ($like[0]->dietitian_likes == 0) {
                    return redirect()->back()->withInput();
                } else {
                    $minusLike = $like[0]->dietitian_likes;
                    DB::table('dietitians')
                        ->where('dietitian_id', $dietitianId)
                        ->update(['dietitian_likes' => --$minusLike]);
                }
            }
            return redirect()->back()->withInput();
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function showChefProfile($id, Request $request)
    {
        $chefId = $id;
        $accessedBy = $request->accessedBy;
        if ($accessedBy == "") {
            $accessedBy = "user";
        }
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $data = DB::select('SELECT
                recipes.recipe_id,
                recipes.recipe_name,
                recipes.recipe_image,
                recipes.recipe_likes,
                recipe_nutrients.recipe_calories,
                IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                FROM recipes
                JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                JOIN chefs ON recipes.chef_id = chefs.chef_id AND chefs.chef_id = "' . $chefId . '"
                LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"');

            $profile = DB::select('SELECT
                chefs.chef_id,
                chef_profile_pic,
                chef_full_name,
                chef_username,
                chef_likes,
                IF(chef_likes.user_id IS NULL, 0, 1) AS is_liked_chef
                FROM chefs
                LEFT JOIN chef_likes ON chefs.chef_id = chef_likes.chef_id AND chef_likes.user_id = "' . $userId . '"
                WHERE chefs.chef_id = "' . $chefId . '"');
        } else {
            $data = DB::select('SELECT
            recipes.recipe_id,
                recipes.recipe_name,
                recipes.recipe_image,
                recipes.recipe_likes,
                recipe_nutrients.recipe_calories,
                IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                FROM recipes
                JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                JOIN chefs ON recipes.chef_id = chefs.chef_id AND chefs.chef_id = "' . $chefId . '"
                LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = NULL
                LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = NULL');

            $profile = DB::select('SELECT
                chefs.chef_id,
                chef_profile_pic,
                chef_full_name,
                chef_username,
                chef_likes,
                IF(chef_likes.user_id IS NULL, 0, 1) AS is_liked_chef
                FROM chefs
                LEFT JOIN chef_likes ON chefs.chef_id = chef_likes.chef_id AND chef_likes.user_id = NULL
                WHERE chefs.chef_id = "' . $chefId . '"');
        }
        return view('layouts.chef_profile')->with('data', $data)->with('profile', $profile)->with('accessedBy', $accessedBy);
    }

    public function showDietitianProfile($id, Request $request)
    {
        if (session()->has('user_login_data')) {
            $accessedBy = $request->accessedBy;
            if ($accessedBy == "") {
                $accessedBy = "user";
            }
            $dietitianId = $id;
            if (session()->has('user_login_data')) {
                $userId = session()->get('user_login_data.user_id');
                $data = DB::select('SELECT
                diet_plans.diet_plan_id,
                diet_plans.diet_plan_meals,
                diet_plans.diet_plan_duration,
                diet_plans.diet_plan_type,
                diet_plans.diet_plan_user_type,
                diet_plans.diet_plan_weight_goal,
                diet_plans.diet_plan_likes,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
                FROM diet_plans
                JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id AND dietitians.dietitian_id = "' . $dietitianId . '"
                LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"');

                $profile = DB::select('SELECT
                dietitians.dietitian_id,
                dietitian_profile_pic,
                dietitian_full_name,
                dietitian_username,
                dietitian_likes,
                dietitian_phone_number,
                IF(dietitian_likes.user_id IS NULL, 0, 1) AS is_liked_dietitian
                FROM dietitians
                LEFT JOIN dietitian_likes ON dietitians.dietitian_id = dietitian_likes.dietitian_id AND dietitian_likes.user_id = "' . $userId . '"
                WHERE dietitians.dietitian_id = "' . $dietitianId . '"');
            } else {
                $data = DB::select('SELECT
            diet_plans.diet_plan_id,
            diet_plans.diet_plan_name,
            diet_plans.diet_plan_meals,
            diet_plans.diet_plan_duration,
            diet_plans.diet_plan_type,
            diet_plans.diet_plan_user_type,
            diet_plans.diet_plan_weight_goal,
            diet_plans.diet_plan_likes,
            IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
            FROM diet_plans
            JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id AND dietitians.dietitian_id = "' . $dietitianId . '"
            LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL');

                $profile = DB::select('SELECT
            dietitians.dietitian_id,
            dietitian_profile_pic,
            dietitian_full_name,
            dietitian_username,
            dietitian_likes,
            dietitian_phone_number,
            IF(dietitian_likes.user_id IS NULL, 0, 1) AS is_liked_dietitian
            FROM dietitians
            LEFT JOIN dietitian_likes ON dietitians.dietitian_id = dietitian_likes.dietitian_id AND dietitian_likes.user_id = NULL
            WHERE dietitians.dietitian_id = "' . $dietitianId . '"');
            }
            return view('layouts.dietitian_profile')->with('data', $data)->with('profile', $profile)->with('accessedBy', $accessedBy);
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function reportRecipe($id)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $recipeId = $id;
            $check = DB::select('select * from report_recipes where user_id = "' . $userId . '" and recipe_id = "' . $recipeId . '"');
            //dd($check);
            if ($check == null) {
                ReportRecipe::create([
                    'user_id' => $userId,
                    'recipe_id' => $recipeId
                ]);
                DB::table('recipes')
                    ->where('recipe_id', $recipeId)
                    ->increment('recipe_reports');
            } else {

                return redirect()->back()->with(session()->flash('message', 'Already reported the recipe'));
            }

            return redirect()->back()->with(session()->flash('message', 'Recipe has been reported'));
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function reportDietPlan($id)
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $dietPlanId = $id;
            $check = DB::select('select * from report_diet_plans where user_id = "' . $userId . '" and diet_plan_id = "' . $dietPlanId . '"');
            if ($check == null) {
                ReportDietPlan::create([
                    'user_id' => $userId,
                    'diet_plan_id' => $dietPlanId
                ]);
            } else {

                return redirect()->back()->with(session()->flash('message', 'Already reported the diet plan'));
            }

            return redirect()->back()->with(session()->flash('message', 'Diet plan has been reported'));
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function personalizedDietPlan()
    {
        if (session()->has('user_login_data')) {
            $data = DB::select('select * from dietitians');
            return view('layouts.dietitians')->with('data', $data);
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function displayRecipes(Request $request)
    {
        $accessedBy = $request->accessedBy;
        if ($accessedBy == "") {
            $accessedBy = "user";
        }
        $search = '';
        $userId = session()->get('user_login_data.user_id');

        $data = DB::select('SELECT
            recipes.recipe_id,
            recipes.recipe_name,
            recipes.recipe_image,
            recipes.recipe_likes,
            recipe_nutrients.recipe_calories,
            chefs.chef_id,
            chefs.chef_username,
            IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
            IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
        FROM recipes
        JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
        JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
        JOIN chefs ON recipes.chef_id = chefs.chef_id
        LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
        LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"');
        return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
    }

    public function searchRecipe(Request $request)
    {
        $name = $request->search;
        $category = $request->recipe_category;
        $disease = $request->disease;
        $userId = session()->get('user_login_data.user_id');
        $accessedBy = $request->accessedBy;
        if ($accessedBy == "") {
            $accessedBy = "user";
        }
        $search = "";
        if ($name == null && $category == null && $disease == null) {
            return redirect()->route('recipes/display');
        } elseif (!$name == null && !$category == null && !$disease == null) {

            $data = DB::select(
                'SELECT
                        recipes.recipe_id,
                        recipes.recipe_name,
                        recipes.recipe_image,
                        recipes.recipe_likes,
                        recipe_nutrients.recipe_calories,
                        chefs.chef_id,
                        chefs.chef_username,
                        IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                        IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                    FROM recipes
                    JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                    JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                    JOIN chefs ON recipes.chef_id = chefs.chef_id
                    LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                    LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
                    Where recipes.recipe_user_type=? and recipe_categories.category_name=? and recipes.recipe_name like ?',
                [$disease, $category, '%' . $name . '%'],
            );
            $search = $category . ' > ' . $disease;
            return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
        } elseif ($name == null && !$category == null && !$disease == null) {

            $data = DB::select(
                'SELECT
                        recipes.recipe_id,
                        recipes.recipe_name,
                        recipes.recipe_image,
                        recipes.recipe_likes,
                        recipe_nutrients.recipe_calories,
                        chefs.chef_id,
                        chefs.chef_username,
                        IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                        IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                    FROM recipes
                    JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                    JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                    JOIN chefs ON recipes.chef_id = chefs.chef_id
                    LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                    LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
                    Where recipes.recipe_user_type=? and recipe_categories.category_name=?',
                [$disease, $category],
            );
            $search = $category . ' > ' . $disease;
            return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
        } elseif (!$name == null && !$category == null && $disease == null) {
            $data = DB::select(
                'SELECT
                    recipes.recipe_id,
                    recipes.recipe_name,
                    recipes.recipe_image,
                    recipes.recipe_likes,
                    recipe_nutrients.recipe_calories,
                    chefs.chef_id,
                    chefs.chef_username,
                    IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                    IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                FROM recipes
                JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                JOIN chefs ON recipes.chef_id = chefs.chef_id
                LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
                Where recipe_categories.category_name=? and recipes.recipe_name like ?',
                [$category, '%' . $name . '%'],
            );
            $search = $category . " recipes";
            return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
        } elseif (!$name == null && $category == null && !$disease == null) {

            $data = DB::select(
                'SELECT
                            recipes.recipe_id,
                            recipes.recipe_name,
                            recipes.recipe_image,
                            recipes.recipe_likes,
                            recipe_nutrients.recipe_calories,
                            chefs.chef_id,
                            chefs.chef_username,
                            IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                            IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                        FROM recipes
                        JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                        JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                        JOIN chefs ON recipes.chef_id = chefs.chef_id
                        LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                        LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
                        Where recipes.recipe_name like ? and recipes.recipe_user_type=?',
                [$name, $disease],
            );
            $search = $disease . " recipes";
            return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
        } elseif (!$name == null && $category == null && $disease == null) {
            $data = DB::select(
                'SELECT
                    recipes.recipe_id,
                    recipes.recipe_name,
                    recipes.recipe_image,
                    recipes.recipe_likes,
                    recipe_nutrients.recipe_calories,
                    chefs.chef_id,
                    chefs.chef_username,
                    IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                    IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                FROM recipes
                JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                JOIN chefs ON recipes.chef_id = chefs.chef_id
                LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
                Where recipes.recipe_name like ?',
                ['%' . $name . '%'],
            );
            return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
        } elseif ($name == null && !$category == null && $disease == null) {
            $data = DB::select(
                'SELECT
                    recipes.recipe_id,
                    recipes.recipe_name,
                    recipes.recipe_image,
                    recipes.recipe_likes,
                    recipe_nutrients.recipe_calories,
                    chefs.chef_id,
                    chefs.chef_username,
                    IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                    IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                FROM recipes
                JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                JOIN chefs ON recipes.chef_id = chefs.chef_id
                LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
                Where recipe_categories.category_name=?',
                [$category],
            );
            $search = $category . " recipes";
            return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
        } elseif ($name == null && $category == null && !$disease == null) {

            $data = DB::select(
                'SELECT
                        recipes.recipe_id,
                        recipes.recipe_name,
                        recipes.recipe_image,
                        recipes.recipe_likes,
                        recipe_nutrients.recipe_calories,
                        chefs.chef_id,
                        chefs.chef_username,
                        IF(saved_recipes.user_id IS NULL, 0, 1) AS is_saved,
                        IF(recipe_likes.user_id IS NULL, 0, 1) AS is_liked
                    FROM recipes
                    JOIN recipe_nutrients ON recipes.recipe_id = recipe_nutrients.recipe_id
                    JOIN recipe_categories ON recipes.category_id = recipe_categories.category_id
                    JOIN chefs ON recipes.chef_id = chefs.chef_id
                    LEFT JOIN saved_recipes ON recipes.recipe_id = saved_recipes.recipe_id AND saved_recipes.user_id = "' . $userId . '"
                    LEFT JOIN recipe_likes ON recipes.recipe_id = recipe_likes.recipe_id AND recipe_likes.user_id = "' . $userId . '"
                    Where recipes.recipe_user_type=?',
                [$disease],
            );
            $search = $disease . " recipes";
            return view('layouts.recipe_search')->with('data', $data)->with('accessedBy', $accessedBy)->with('search', $search);
        }
    }
}
