<?php

namespace App\Http\Controllers;

use App\Models\Chef;
use App\Models\Recipe;
use App\Models\RecipeNutrient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ChefController extends Controller
{
    public function register()
    {
        if (session()->has('login_data')) {
            return redirect()
                ->route('chef_portal')
                ->withInput();
        } else {
            return view('layouts.chef_signup_form');
        }
    }

    public function login(Request $request)
    {
        $login_data = Chef::where(['chef_email' => $request->chef_email])->first();
        if (!$login_data || !Hash::check($request->chef_password, $login_data->chef_password)) {
            return redirect()
                ->back()
                ->with('message', 'Incorrect email or password!');
        } else {
            $request->session()->put('login_data', $login_data);
            if (session()->has('login_data')) {
                session(['login_data' => $login_data]);
                return redirect()
                    ->route("chef/portal")
                    ->withInput();
            }
        }
    }

    public function logout()
    {
        if (session()->has('login_data')) {
            session()->pull('login_data', null);
            return redirect()->route('chefs/loginform');
        }
        return redirect()->route('index');
    }

    public function displayChefSignupForm(Request $request)
    {
        if ($request->session()->has('login_data')) {
            return redirect()->route("chef/portal");
        } else {
            return view('layouts.chef_signup_form');
        }
    }

    public function displayChefLoginForm(Request $request)
    {
        if ($request->session()->has('login_data')) {
            return redirect()->route("chef/portal");
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function displayRecipeDetail($id, Request $request)
    {
        $accessedBy = $request->accessedBy;
        if ($accessedBy == "") {
            $accessedBy = "user";
        }
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $data = DB::table('recipes')
                ->select(
                    'recipes.recipe_id',
                    'recipe_name',
                    'recipe_image',
                    'recipe_likes',
                    'recipe_cooking_time',
                    'recipe_servings',
                    'recipe_video',
                    'recipe_ingredients',
                    'recipe_instructions',
                    'chef_id',
                    'recipe_nutrients.recipe_calories',
                    'recipe_nutrients.recipe_carbs',
                    'recipe_nutrients.recipe_protien',
                    'recipe_nutrients.recipe_iron',
                    'recipe_nutrients.recipe_dietaryfiber',
                    'recipe_nutrients.recipe_sugar',
                    'recipe_nutrients.recipe_calcium',
                    'recipe_nutrients.recipe_magnesium',
                    'recipe_nutrients.recipe_potassium',
                    'recipe_nutrients.recipe_sodium',
                    'recipe_nutrients.recipe_vitamin_c',
                    'recipe_nutrients.recipe_vitamin_d',
                    'recipe_nutrients.recipe_vitamin_b6',
                    'recipe_nutrients.recipe_vitamin_b12',
                    'recipe_nutrients.recipe_cholesterol',
                    'recipe_nutrients.recipe_fats',
                    'saved_recipes.user_id AS is_saved',
                )
                ->join('recipe_nutrients', 'recipe_nutrients.recipe_id', '=', 'recipes.recipe_id')
                ->leftJoin('saved_recipes', function ($join) use ($userId) {
                    $join->on('recipes.recipe_id', '=', 'saved_recipes.recipe_id')
                        ->where('saved_recipes.user_id', '=', $userId);
                })
                ->where('recipes.recipe_id', '=', $id)
                ->first();
        } else {
            $userId = NULL;
            $data = DB::table('recipes')
                ->select(
                    'recipes.recipe_id',
                    'recipe_name',
                    'recipe_image',
                    'recipe_likes',
                    'recipe_cooking_time',
                    'recipe_servings',
                    'recipe_video',
                    'recipe_ingredients',
                    'recipe_instructions',
                    'chef_id',
                    'recipe_nutrients.recipe_calories',
                    'recipe_nutrients.recipe_carbs',
                    'recipe_nutrients.recipe_protien',
                    'recipe_nutrients.recipe_iron',
                    'recipe_nutrients.recipe_dietaryfiber',
                    'recipe_nutrients.recipe_sugar',
                    'recipe_nutrients.recipe_calcium',
                    'recipe_nutrients.recipe_magnesium',
                    'recipe_nutrients.recipe_potassium',
                    'recipe_nutrients.recipe_sodium',
                    'recipe_nutrients.recipe_vitamin_c',
                    'recipe_nutrients.recipe_vitamin_d',
                    'recipe_nutrients.recipe_vitamin_b6',
                    'recipe_nutrients.recipe_vitamin_b12',
                    'recipe_nutrients.recipe_cholesterol',
                    'recipe_nutrients.recipe_fats'
                )
                ->join('recipe_nutrients', 'recipe_nutrients.recipe_id', '=', 'recipes.recipe_id')
                ->leftJoin('saved_recipes', function ($join) use ($userId) {
                    $join->on('recipes.recipe_id', '=', 'saved_recipes.recipe_id')
                        ->where('saved_recipes.user_id', '=', $userId);
                })
                ->where('recipes.recipe_id', '=', $id)
                ->first();
        }

        return view('layouts.recipe_detailed_view')->with('recipe', $data)->with('accessedBy', $accessedBy);
    }

    public function displayAddRecipesForm(Request $request)
    {
        if ($request->session()->has('login_data')) {
        } else {
            return view('layouts.chef_login_form');
        }
        return view('layouts.add_recipes');
    }

    public function registerChef(Request $request)
    {
        $username = $request->input('chef_username');
        $chefLikes = 0;

        $data = $request->validate([
            'chef_full_name' => 'required|regex:/^[\pL\s\-]+$/u',
            'chef_username' => 'required|unique:chefs,chef_username|regex:/^[a-z0-9_]+$/',
            'chef_email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:chefs,chef_email',
            'chef_password' => 'required|min:8',
            'chef_profile_pic' => 'required|image|mimes:png,jpg,jpeg',

        ]);
        $imageName = $username . '.' . $request->chef_profile_pic->extension();
        $request->chef_profile_pic->move(public_path('images/chef_profile_images'), $imageName);

        Chef::create([
            'chef_full_name' => $data['chef_full_name'],
            'chef_email' => $data['chef_email'],
            'chef_username' => $data['chef_username'],
            'chef_password' => Hash::make($data['chef_password']),
            'chef_profile_pic' => $imageName,
            'chef_likes' => $chefLikes,
        ]);

        $login_data = Chef::where(['chef_email' => $data['chef_email']])->first();
        $request->session()->put('login_data', $login_data);

        $email = $request->session()->get('login_data.chef_email');

        if (session()->has('login_data')) {
            session(['login_data' => $login_data]);
            return redirect()
                ->route('chef/portal')
                ->withInput();
        }
    }

    public function addRecipes(Request $request)
    {
        if ($request->session()->has('login_data')) {
            $recipeLikes = 0;
            $recipeReports = 0;

            // Variables for storing nutrients values
            $calories = 0;
            $carbs = 0;
            $fat = 0;
            $protein = 0;
            $sugar = 0;
            $iron = 0;
            $potassium = 0;
            $magnesium = 0;
            $sodium = 0;
            $vitamin_c = 0;
            $vitamin_d = 0;
            $vitamin_b6 = 0;
            $vitamin_b12 = 0;
            $cholesterol = 0;
            $fiber = 0;
            $calcium = 0;

            $data = $request->validate([
                // general information of recipe
                'recipe_name' => 'required',
                'recipe_image' => 'required | image | mimes:jpg,png,jpeg',
                'recipe_video' => 'required | mimes:mp4,mov,ogg,qt',
                'recipe_time' => 'required | integer',
                'recipe_servings' => 'required | integer | gte:0',
                'recipe_ingredients' => 'required',
                'recipe_instructions' => 'required',
                'recipe_compatibility' => 'required', // the type of user that can use the recipe: patients/healthy
            ]);

            $recipe_name = $request->input('recipe_name');
            $now = Carbon::now();
            $datetime = str_replace([':', ' ', '-'], '', $now->toDateTimeString());

            // Storing image name in variable and original image in public/image
            $recipeImageName = $recipe_name . '_' . $datetime . '.'  . $request->recipe_image->extension();
            $request->recipe_image->move(public_path('images/recipe_images'), $recipeImageName);

            // Storing video name in variable and original video in public/videos
            $recipeVideoName = $recipe_name . '_' . $datetime . '.' . $request->recipe_video->extension();
            $request->recipe_video->move(public_path('videos/recipe_videos'), $recipeVideoName);

            $chefId = session()->get('login_data.chef_id');
            $categoryName = $request->input('recipe_category');
            $category = DB::select('select * from recipe_categories WHERE category_name = "' . $categoryName . '"');
            $categoryId = $category[0]->category_id;

            //dd($recipeImageName,$recipeVideoName,$chefId,$categoryName,$categoryId);
            Recipe::create([
                'chef_id' => $chefId,
                'category_id' => $categoryId,
                'recipe_name' => $data['recipe_name'],
                'recipe_image' => $recipeImageName,
                'recipe_video' => $recipeVideoName,
                'recipe_cooking_time' => $data['recipe_time'],
                'recipe_servings' => $data['recipe_servings'],
                'recipe_ingredients' => $data['recipe_ingredients'],
                'recipe_instructions' => $data['recipe_instructions'],
                'recipe_user_type' => $data['recipe_compatibility'],
                'recipe_likes' => $recipeLikes,
                'recipe_reports' => $recipeReports,
            ]);

            $recipeName = $request->recipe_name;
            $recipeId = Max(DB::select('select recipe_id, recipe_ingredients, recipe_servings from recipes as recipe_id WHERE chef_id = ? AND recipe_name = ?', [$chefId, $recipeName]));
            $recipeServings = (float)$recipeId->recipe_servings;
            $ingredients = $recipeId->recipe_ingredients;
            $toBeReplaced = [" ", "\r\n", ",", "&", "+", "/"];
            $replaceWith   = ["%20", "%20", "%2C", "%26", "%2B", "%2F"];

            $ingrd = str_replace($toBeReplaced, $replaceWith, $ingredients);

            // RAPID_API to get Recipe Nutrients
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://edamam-edamam-nutrition-analysis.p.rapidapi.com/api/nutrition-data?ingr=$ingrd",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: edamam-edamam-nutrition-analysis.p.rapidapi.com",
                    "X-RapidAPI-Key: 40939e5758mshc232ded57e5abddp174d7djsncca8b74268aa"
                ],
            ]);

            $response = curl_exec($curl);
            $output = json_decode($response, true);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {

                $calories = $output['calories'];

                if (array_key_exists("CHOCDF", $output['totalNutrients']))
                    $carbs = number_format($output['totalNutrients']['CHOCDF']['quantity'], 1);

                if (array_key_exists("FAT", $output['totalNutrients']))
                    $fat = number_format($output['totalNutrients']['FAT']['quantity'], 1);

                if (array_key_exists("FIBTG", $output['totalNutrients']))
                    $fiber = number_format($output['totalNutrients']['FIBTG']['quantity'], 1);

                if (array_key_exists("PROCNT", $output['totalNutrients']))
                    $protein = number_format($output['totalNutrients']['PROCNT']['quantity'], 1);

                if (array_key_exists("CHOLE", $output['totalNutrients']))
                    $cholesterol = number_format($output['totalNutrients']['CHOLE']['quantity'], 1);

                if (array_key_exists("NA", $output['totalNutrients']))
                    $sodium = number_format($output['totalNutrients']['NA']['quantity'], 1);

                if (array_key_exists("CA", $output['totalNutrients']))
                    $calcium = number_format($output['totalNutrients']['CA']['quantity'], 1);

                if (array_key_exists("MG", $output['totalNutrients']))
                    $magnesium = number_format($output['totalNutrients']['MG']['quantity'], 1);

                if (array_key_exists("K", $output['totalNutrients']))
                    $potassium = number_format($output['totalNutrients']['K']['quantity'], 1);

                if (array_key_exists("FE", $output['totalNutrients']))
                    $iron = number_format($output['totalNutrients']['FE']['quantity'], 1);

                if (array_key_exists("VITC", $output['totalNutrients']))
                    $vitamin_c = number_format($output['totalNutrients']['VITC']['quantity'], 1);

                if (array_key_exists("VITB6A", $output['totalNutrients']))
                    $vitamin_b6 = number_format($output['totalNutrients']['VITB6A']['quantity'], 1);

                if (array_key_exists("VITB12", $output['totalNutrients']))
                    $vitamin_b12 = number_format($output['totalNutrients']['VITB12']['quantity'], 1);

                if (array_key_exists("SUGAR", $output['totalNutrients']))
                    $sugar = number_format($output['totalNutrients']['SUGAR']['quantity'], 1);

                if (array_key_exists("VITD", $output['totalNutrients']))
                    $vitamin_d = number_format($output['totalNutrients']['VITD']['quantity'], 1);
            }

            RecipeNutrient::create([
                'recipe_id' => $recipeId->recipe_id,
                'recipe_calories' => (int)$calories / $recipeServings,
                'recipe_carbs' => (float)$carbs / $recipeServings,
                'recipe_protien' => (float)$protein / $recipeServings,
                'recipe_iron' => (float)$iron / $recipeServings,
                'recipe_dietaryfiber' => (float)$fiber / $recipeServings,
                'recipe_sugar' => (float)$sugar / $recipeServings,
                'recipe_calcium' => (float)$calcium / $recipeServings,
                'recipe_magnesium' => (float)$magnesium / $recipeServings,
                'recipe_potassium' => (float)$potassium / $recipeServings,
                'recipe_sodium' => (float)$sodium / $recipeServings,
                'recipe_vitamin_c' => (float)$vitamin_c / $recipeServings,
                'recipe_vitamin_d' => (float)$vitamin_d / $recipeServings,
                'recipe_vitamin_b6' => (float)$vitamin_b6 / $recipeServings,
                'recipe_vitamin_b12' => (float)$vitamin_b12 / $recipeServings,
                'recipe_cholesterol' => (float)$cholesterol / $recipeServings,
                'recipe_fats' => (float)$fat / $recipeServings,
            ]);

            return redirect()
                ->route('chef/portal')
                ->withInput();
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function chefPortal(Request $request)
    {
        if ($request->session()->has('login_data')) {
            $chefId = session()->get('login_data.chef_id');
            $recipes = DB::select('select recipe_name, recipe_image, recipe_likes, recipe_calories, recipes.recipe_id from recipes,recipe_nutrients WHERE recipes.recipe_id=recipe_nutrients.recipe_id and chef_id = "' . $chefId . '"');
            $profile = DB::select('select chef_profile_pic, chef_full_name,chef_username,chef_likes from chefs WHERE chef_id = "' . $chefId . '"');
            return view('layouts.chef_portal')->with('recipes', $recipes)->with('profile', $profile);
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function showChefProfile()
    {
        $chefId = session()->get('login_data.chef_id');
        $recipes = DB::select('select recipe_name, recipe_image, recipe_likes, recipe_calories, recipes.recipe_id from recipes,recipe_nutrients WHERE recipes.recipe_id=recipe_nutrients.recipe_id and chef_id = "' . $chefId . '"');
        $profile = DB::select('select chef_profile_pic, chef_full_name,chef_username,chef_likes from chefs WHERE chef_id = "' . $chefId . '"');
        return view('layouts.chef_profile')->with('recipes', $recipes)->with('profile', $profile);
    }

    public function edit(Request $request, $id)
    {
        if ($request->session()->has('login_data')) {

            $data = $request->validate([
                // general information of recipe
                'recipe_name' => 'required',
                'recipe_image' => 'nullable | image | mimes:jpg,png,jpeg',
                'recipe_video' => 'nullable | mimes:mp4,mov,ogg,qt',
                'recipe_time' => 'required | integer',
                'recipe_servings' => 'required | integer | gte:0',
                'recipe_ingredients' => 'required',
                'recipe_instructions' => 'required',
                'recipe_compatibility' => 'required', // the type of user that can use the recipe: patients/healthy
            ]);
            $calories = 0;
            $carbs = 0;
            $fat = 0;
            $protein = 0;
            $sugar = 0;
            $iron = 0;
            $potassium = 0;
            $magnesium = 0;
            $sodium = 0;
            $vitamin_c = 0;
            $vitamin_d = 0;
            $vitamin_b6 = 0;
            $vitamin_b12 = 0;
            $cholesterol = 0;
            $fiber = 0;
            $calcium = 0;

            $recipe_name = $request->input('recipe_name');
            $chefId = session()->get('login_data.chef_id');
            $categoryName = $request->input('recipe_category');
            $category = DB::select('select * from recipe_categories WHERE category_name = "' . $categoryName . '"');
            $categoryId = $category[0]->category_id;
            $now = Carbon::now();
            $datetime = str_replace([':', ' ', '-'], '', $now->toDateTimeString());
            if (array_key_exists('recipe_image', $data) && !array_key_exists('recipe_video', $data)) {
                $old_image = DB::select('select recipe_image from recipes where recipe_id = ?', [$id]);
                foreach ($old_image as $img) {
                    $filename = public_path('images/recipe_images/' . $img->recipe_image);
                    if (File::exists($filename)) {
                        File::delete($filename);
                    }
                }
                // Storing image name in variable and original image in public/image
                $recipeImageName = $recipe_name . '_' . $datetime . '.' . $request->recipe_image->extension();
                $request->recipe_image->move(public_path('images/recipe_images'), $recipeImageName);
                DB::update(
                    'update recipes set
                         category_id = "' . $categoryId . '",
                         recipe_name = "' . $data['recipe_name'] . '",
                         recipe_image ="' . $recipeImageName . '",
                         recipe_cooking_time = "' . $data['recipe_time'] . '",
                         recipe_servings = "' . $data['recipe_servings'] . '",
                         recipe_ingredients = "' . $data['recipe_ingredients'] . '",
                         recipe_instructions = "' . $data['recipe_instructions'] . '",
                         recipe_user_type = "' . $data['recipe_compatibility'] . '"
                         where recipe_id = "' . $id . '"'
                );
            } elseif (!array_key_exists('recipe_image', $data) && array_key_exists('recipe_video', $data)) {
                $old_video = DB::select('select recipe_video from recipes where recipe_id = ?', [$id]);
                foreach ($old_video as $vid) {
                    $filename = public_path('videos/recipe_videos/' . $vid->recipe_video);
                    if (File::exists($filename)) {
                        File::delete($filename);
                    }
                }
                // Storing video name in variable and original video in public/videos
                $recipeVideoName = $recipe_name . '_' . $datetime . '.' . $request->recipe_video->extension();
                $request->recipe_video->move(public_path('videos/recipe_videos'), $recipeVideoName);
                DB::update(
                    'update recipes set
                    category_id = "' . $categoryId . '",
                    recipe_name = "' . $data['recipe_name'] . '",
                    recipe_video ="' . $recipeVideoName . '",
                    recipe_cooking_time = "' . $data['recipe_time'] . '",
                    recipe_servings = "' . $data['recipe_servings'] . '",
                    recipe_ingredients = "' . $data['recipe_ingredients'] . '",
                    recipe_instructions = "' . $data['recipe_instructions'] . '",
                    recipe_user_type = "' . $data['recipe_compatibility'] . '"
                    where recipe_id = "' . $id . '"'
                );
            } elseif (isset($data['recipe_image']) && isset($data['recipe_video'])) {
                $old_img_vid = DB::select('select recipe_image,recipe_video from recipes where recipe_id = ?', [$id]);
                foreach ($old_img_vid as $d) {
                    $filename = public_path('images/recipe_images/' . $d->recipe_image);
                    if (File::exists($filename)) {
                        File::delete($filename);
                    }
                }

                foreach ($old_img_vid as $vid) {
                    $filename = public_path('videos/recipe_videos/' . $vid->recipe_video);
                    if (File::exists($filename)) {
                        File::delete($filename);
                    }
                }
                // Storing image name in variable and original image in public/image
                $recipeImageName = $recipe_name . '_' . $datetime . '.' . $request->recipe_image->extension();
                $request->recipe_image->move(public_path('images/recipe_images'), $recipeImageName);

                // Storing video name in variable and original video in public/videos
                $recipeVideoName = $recipe_name . '_' . $datetime . '.' . $request->recipe_video->extension();
                $request->recipe_video->move(public_path('videos/recipe_videos'), $recipeVideoName);

                DB::update(
                    'update recipes set
                    category_id = "' . $categoryId . '",
                    recipe_name = "' . $data['recipe_name'] . '",
                    recipe_image ="' . $recipeImageName . '",
                    recipe_video = "' . $recipeVideoName . '",
                    recipe_cooking_time = "' . $data['recipe_time'] . '",
                    recipe_servings = "' . $data['recipe_servings'] . '",
                    recipe_ingredients = "' . $data['recipe_ingredients'] . '",
                    recipe_instructions = "' . $data['recipe_instructions'] . '",
                    recipe_user_type = "' . $data['recipe_compatibility'] . '"
                    where recipe_id = "' . $id . '"'
                );
            } else {
                DB::update(
                    'update recipes set
                    category_id = "' . $categoryId . '",
                    recipe_name = "' . $data['recipe_name'] . '",
                    recipe_cooking_time = "' . $data['recipe_time'] . '",
                    recipe_servings = "' . $data['recipe_servings'] . '",
                    recipe_ingredients = "' . $data['recipe_ingredients'] . '",
                    recipe_instructions = "' . $data['recipe_instructions'] . '",
                    recipe_user_type = "' . $data['recipe_compatibility'] . '"
                    where recipe_id = "' . $id . '"'
                );
            }
            $recipeServings = (float)$data['recipe_servings'];
            $ingredients = $data['recipe_ingredients'];
            $toBeReplaced = [" ", "\r\n", ",", "&", "+", "/"];
            $replaceWith   = ["%20", "%20", "%2C", "%26", "%2B", "%2F"];

            $ingrd = str_replace($toBeReplaced, $replaceWith, $ingredients);

            // RAPID_API to get Recipe Nutrients
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://edamam-edamam-nutrition-analysis.p.rapidapi.com/api/nutrition-data?ingr=$ingrd",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: edamam-edamam-nutrition-analysis.p.rapidapi.com",
                    "X-RapidAPI-Key: 40939e5758mshc232ded57e5abddp174d7djsncca8b74268aa"
                ],
            ]);

            $response = curl_exec($curl);
            $output = json_decode($response, true);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {

                $calories = $output['calories'];

                if (array_key_exists("CHOCDF", $output['totalNutrients']))
                    $carbs = number_format($output['totalNutrients']['CHOCDF']['quantity'], 1);

                if (array_key_exists("FAT", $output['totalNutrients']))
                    $fat = number_format($output['totalNutrients']['FAT']['quantity'], 1);

                if (array_key_exists("FIBTG", $output['totalNutrients']))
                    $fiber = number_format($output['totalNutrients']['FIBTG']['quantity'], 1);

                if (array_key_exists("PROCNT", $output['totalNutrients']))
                    $protein = number_format($output['totalNutrients']['PROCNT']['quantity'], 1);

                if (array_key_exists("CHOLE", $output['totalNutrients']))
                    $cholesterol = number_format($output['totalNutrients']['CHOLE']['quantity'], 1);

                if (array_key_exists("NA", $output['totalNutrients']))
                    $sodium = number_format($output['totalNutrients']['NA']['quantity'], 1);

                if (array_key_exists("CA", $output['totalNutrients']))
                    $calcium = number_format($output['totalNutrients']['CA']['quantity'], 1);

                if (array_key_exists("MG", $output['totalNutrients']))
                    $magnesium = number_format($output['totalNutrients']['MG']['quantity'], 1);

                if (array_key_exists("K", $output['totalNutrients']))
                    $potassium = number_format($output['totalNutrients']['K']['quantity'], 1);

                if (array_key_exists("FE", $output['totalNutrients']))
                    $iron = number_format($output['totalNutrients']['FE']['quantity'], 1);

                if (array_key_exists("VITC", $output['totalNutrients']))
                    $vitamin_c = number_format($output['totalNutrients']['VITC']['quantity'], 1);

                if (array_key_exists("VITB6A", $output['totalNutrients']))
                    $vitamin_b6 = number_format($output['totalNutrients']['VITB6A']['quantity'], 1);

                if (array_key_exists("VITB12", $output['totalNutrients']))
                    $vitamin_b12 = number_format($output['totalNutrients']['VITB12']['quantity'], 1);

                if (array_key_exists("SUGAR", $output['totalNutrients']))
                    $sugar = number_format($output['totalNutrients']['SUGAR']['quantity'], 1);

                if (array_key_exists("VITD", $output['totalNutrients']))
                    $vitamin_d = number_format($output['totalNutrients']['VITD']['quantity'], 1);
            }

            DB::update(
                'update recipe_nutrients set
                recipe_calories = "' . (int)$calories / $recipeServings . '",
                recipe_carbs = "' . (float)$carbs / $recipeServings . '",
                recipe_protien = "' . (float)$protein / $recipeServings . '",
                recipe_iron = "' . (float)$iron / $recipeServings . '",
                recipe_dietaryfiber = "' . (float)$fiber / $recipeServings . '",
                recipe_sugar = "' . (float)$sugar / $recipeServings . '",
                recipe_calcium = "' . (float)$calcium / $recipeServings . '",
                recipe_magnesium = "' . (float)$magnesium / $recipeServings . '",
                recipe_potassium = "' . (float)$potassium / $recipeServings . '",
                recipe_sodium = "' . (float)$sodium / $recipeServings . '",
                recipe_vitamin_c = "' . (float)$vitamin_c / $recipeServings . '",
                recipe_vitamin_d = "' . (float)$vitamin_d / $recipeServings . '",
                recipe_vitamin_b6 = "' . (float)$vitamin_b6 / $recipeServings . '",
                recipe_vitamin_b12 = "' . (float)$vitamin_b12 / $recipeServings . '",
                recipe_cholesterol = "' . (float)$cholesterol / $recipeServings . '",
                recipe_fats = "' . (float)$fat / $recipeServings . '"
                where recipe_id = "' . $id . '"'
            );
            return redirect()
                ->route('chef/portal')
                ->withInput();
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->session()->has('login_data')) {
            $data = DB::select('select
            recipe_id,
            recipe_name,
            recipe_image,
            recipe_likes,
            recipe_cooking_time,
            recipe_servings,
            recipe_user_type,
            recipe_video,
            recipe_ingredients,
            recipe_instructions,
            recipe_categories.category_name
            from recipes,recipe_categories
            where recipe_categories.category_id = recipes.category_id and recipe_id=?', [$id]);

            return view('layouts.update_recipes')->with('data', $data);
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function deleteRecipe(Request $request, $id)
    {
        if ($request->session()->has('login_data')) {
            $data = DB::select('select recipe_image, recipe_video from recipes where recipe_id = ?', [$id]);
            foreach ($data as $img) {
                $filename = public_path('images/recipe_images/' . $img->recipe_image);
                if (File::exists($filename)) {
                    File::delete($filename);
                }
            }

            foreach ($data as $vid) {
                $filename = public_path('videos/recipe_videos/' . $vid->recipe_video);
                if (File::exists($filename)) {
                    File::delete($filename);
                }
            }
            DB::delete("delete from recipe_likes where recipe_id = ?", [$id]);
            DB::delete("delete from saved_recipes where recipe_id = ?", [$id]);
            DB::delete('delete from recipe_nutrients where recipe_id = ?', [$id]);
            DB::delete('delete from recipes where recipe_id = ?', [$id]);
            DB::delete('delete from recipe_logs where recipe_id = ?', [$id]);
            return back()->withInput();
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function chefProfileSetting(Request $request)
    {
        if (session()->has('login_data')) {
            $chefEmail = $request->session()->get('login_data.chef_email');
            $chefId =  Chef::where('chef_email', $chefEmail)->pluck('chef_id');

            $chef = Chef::where('chef_id', $chefId)->get()->first();
            return view('layouts.chef_profile_setting', ['chef' => $chef]);
        } else {
            return redirect()
                ->route('chefs/loginform');
        }
    }

    public function updateChefProfile(Request $request)
    {
        if ($request->session()->has('login_data')) {
            $username = $request->input('chef_username');
            $chefEmail = $request->input('chef_email');
            $chefId = session()->get('login_data.chef_id');
            $chefFirstUsername = session()->get('login_data.chef_username');
            $chefProfilePicName = session()->get('login_data.chef_profile_pic');

            // Change the profile pic name if username changed
            if ($username != $chefFirstUsername) {
                $oldImagePath = public_path('images/chef_profile_images/' . $chefProfilePicName);

                // Get the extension of the old image
                $extension = pathinfo($oldImagePath, PATHINFO_EXTENSION);
                $newImageName = $username . '.' . $extension;
                $newImagePath = public_path('images/chef_profile_images/' . $newImageName);

                // Rename the image file
                if (file_exists($oldImagePath)) {
                    rename($oldImagePath, $newImagePath);
                }

                Chef::where('chef_id', $chefId)
                    ->update([
                        'chef_profile_pic' => $newImageName
                    ]);
            }

            $chefEmailCheck = Chef::select('chef_id')
                ->where('chef_email', '=', $chefEmail)
                ->whereNotIn('chef_id', [$chefId])
                ->get();

            $chefUsernameCheck = Chef::select('chef_id')
                ->where('chef_username', $username)
                ->whereNotIn('chef_id', [$chefId])
                ->get();

            if ($chefEmailCheck->isNotEmpty() || $chefUsernameCheck->isNotEmpty()) {
                if ($chefUsernameCheck->isNotEmpty())
                    return back()->withInput()->with('activeTab', 'profile')->with('username-alert', 'This Username is already taken');

                if ($chefEmailCheck->isNotEmpty())
                    return back()->withInput()->with('activeTab', 'profile')->with('email-alert', 'This email is already registered');
            } else {

                $data = $request->validate([
                    'chef_full_name' => 'required|regex:/^[\pL\s\-]+$/u',
                    'chef_email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                    'chef_username' => 'required | regex:/^[a-z0-9_]+$/',
                    'chef_profile_pic' => 'nullable | image | mimes:jpg,png,jpeg',
                ]);

                if (!array_key_exists('chef_profile_pic', $data)) {
                    Chef::where('chef_id', $chefId)
                        ->update([
                            'chef_full_name' => $data['chef_full_name'],
                            'chef_email' => $data['chef_email'],
                            'chef_username' => $data['chef_username'],
                        ]);
                    if (session()->has('login_data')) {
                        session()->pull('login_data', null);
                        $chefLoginData = Chef::where(['chef_email' => $chefEmail])->first();
                        $request->session()->put('login_data', $chefLoginData);

                        if (session()->has('login_data')) {
                            session(['login_data' => $chefLoginData]);

                            return redirect()->back()->withInput()->with('activeTab', 'profile')->with('save-success', 'Changes Saved Successfully!');
                        }
                    }
                } else {
                    $imgName = public_path('images/chef_profile_images/' . $username . '.' . $request->chef_profile_pic->extension());
                    if (File::exists($imgName)) {
                        File::delete($imgName);
                    }

                    $imageName = $username . '.' . $request->chef_profile_pic->extension();
                    $request->chef_profile_pic->move(public_path('images/chef_profile_images'), $imageName);

                    Chef::where('chef_id', $chefId)
                        ->update([
                            'chef_full_name' => $data['chef_full_name'],
                            'chef_email' => $data['chef_email'],
                            'chef_username' => $data['chef_username'],
                            'chef_profile_pic' => $imageName,

                        ]);
                    if (session()->has('login_data')) {
                        session()->pull('login_data', null);
                        $chefLoginData = Chef::where(['chef_email' => $chefEmail])->first();
                        $request->session()->put('login_data', $chefLoginData);

                        if (session()->has('login_data')) {
                            session(['login_data' => $chefLoginData]);

                            return redirect()->back()->withInput()->with('activeTab', 'profile')->with('save-success', 'Changes Saved Successfully!');
                        }
                    }
                }
            }
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function updateChefPassword(Request $request)
    {
        if ($request->session()->has('login_data')) {
            $chefId = session()->get('login_data.chef_id');
            $new_pswrd = $request->input('new_password');

            if (strlen($new_pswrd) >= 8) {
                Chef::where('chef_id', $chefId)
                    ->update([
                        'chef_password' => Hash::make($new_pswrd),
                    ]);
                return redirect()->back()->with('activeTab', 'change-password')->with('password-change-success', 'Password Changed Successfully!');
            } else {
                return redirect()->back()->with('activeTab', 'change-password')->with('password-alert', 'Password must be at least 8 characters long!');
            }
        } else {
            return view('layouts.chef_login_form');
        }
    }

    public function deleteChef(Request $request)
    {
        if ($request->session()->has('login_data')) {
            $chefId = session()->get('login_data.chef_id');
            $chefPassword = $request->input('deletion_password');
            $publicPath = public_path();

            $chef = Chef::select('chef_password')->where('chef_id', $chefId)->first();

            if (Hash::check($chefPassword, $chef->chef_password)) {
                // Get all recipe ids that are uploaded by chef
                $recipeIds = DB::table('recipes')
                    ->where('chef_id', $chefId)
                    ->pluck('recipe_id');

                // Get chef profile image name
                $chefPic = DB::table('chefs')
                    ->select('chef_profile_pic')
                    ->where('chef_id', $chefId)
                    ->get()
                    ->first();

                // Delete chef profile pic from public directory
                $chefProfileImagePath = $publicPath . '/images/chef_profile_images/' . $chefPic->chef_profile_pic;
                if (file_exists($chefProfileImagePath)) {
                    unlink($chefProfileImagePath);
                }

                // Get the recipe image and video names for the given recipe IDs
                $recipes = DB::table('recipes')
                    ->select('recipe_image', 'recipe_video')
                    ->whereIn('recipe_id', $recipeIds)
                    ->get();

                foreach ($recipes as $recipe) {

                    // Delete the recipe image from public directory
                    $recipeImagePath = $publicPath . '/images/recipe_images/' . $recipe->recipe_image;
                    if (file_exists($recipeImagePath)) {
                        unlink($recipeImagePath);
                    }

                    // Delete the recipe video from public directory
                    $recipeVideoPath = $publicPath . '/videos/recipe_videos/' . $recipe->recipe_video;
                    if (file_exists($recipeVideoPath)) {
                        unlink($recipeVideoPath);
                    }
                }

                // Delete all nutrients of recipes
                DB::table('recipe_nutrients')->whereIn('recipe_id', $recipeIds)->delete();

                // Delete all recipe logs of user
                DB::table('recipe_logs')->whereIn('recipe_id', $recipeIds)->delete();

                // Delete all recipes uploaded by chef
                DB::table('recipes')->where('chef_id', $chefId)->delete();

                // Delete chef
                Chef::where('chef_id', $chefId)->delete();

                session()->flush();

                return redirect()->route('chefs/loginform')->with('delete-account-success', 'Account Deleted Successfully!');
            } else {
                return redirect()->back()->with('activeTab', 'delete-account')->with('delete-account-alert', 'Invalid Password!');
            }
        } else {
            return view('layouts.chef_login_form');
        }
    }
}
