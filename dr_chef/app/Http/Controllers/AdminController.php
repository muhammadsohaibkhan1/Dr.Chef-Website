<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Recipe;
use App\Models\Dietitian;
use App\Models\DietPlan;
use App\Models\Chef;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function adminLogin(Request $request)
    {
        $invalidAttempts = $request->session()->get('admin_invalid_login_attempts', 0);
        $lastAttemptTime = $request->session()->get('admin_last_attempt_time');

        if ($invalidAttempts >= 3 && $lastAttemptTime !== null) {
            $waitTime = 5; // Wait time in minutes
            $currentTime = Carbon::now();
            $minutesSinceLastAttempt = $lastAttemptTime->diffInMinutes($currentTime);

            if ($minutesSinceLastAttempt < $waitTime) {
                $remainingWaitTime = $waitTime - $minutesSinceLastAttempt;

                return redirect()
                    ->back()
                    ->with('invalid-alert', "Too many invalid login attempts, please try again after $remainingWaitTime minutes.");
            }

            // Reset the invalid login attempts since the wait time has passed
            $request->session()->forget('admin_invalid_login_attempts');
            $request->session()->forget('admin_last_attempt_time');
        }

        $admin_login_data = Admin::where(['admin_email' => $request->admin_email])->first();

        if (!$admin_login_data || !Hash::check($request->admin_password, $admin_login_data->admin_password)) {
            $invalidAttempts++;
            $request->session()->put('admin_invalid_login_attempts', $invalidAttempts);
            $request->session()->put('admin_last_attempt_time', Carbon::now());

            return redirect()
                ->back()
                ->with('invalid-alert', 'Incorrect email or password!');
        } else {

            // Reset the invalid login attempts since the login was successful
            $request->session()->forget('admin_invalid_login_attempts');
            $request->session()->forget('admin_last_attempt_time');

            $request->session()->put('admin_login_data', $admin_login_data);
            return redirect()
                ->route('admin/dashboard')
                ->withInput();
        }
    }

    public function adminLogout()
    {
        if (session()->has('admin_login_data')) {
            session()->pull('admin_login_data', null);
            return redirect()->route('admin/loginform');
        }
        return redirect()->route('admin/dashboard');
    }

    public function adminLoginForm()
    {
        if (session()->has('admin_login_data')) {
            return redirect()->route('admin/dashboard');
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function displayAdminDashboard()
    {
        if (session()->has('admin_login_data')) {
            $reportedRecipes = DB::select('select * from recipes, chefs where recipes.recipe_reports >= ? AND chefs.chef_id = recipes.chef_id', [100]);
            $reportedDietPlans = DB::select('select * from diet_plans, dietitians where diet_plans.diet_plan_reports >= ? AND dietitians.dietitian_id = diet_plans.dietitian_id', [100]);
            $chefs = DB::select('select * from chefs');
            $dietitians = DB::select('select * from dietitians');
            $dietitianRequests = DB::select('select dietitian_id, dietitian_full_name, dietitian_username, dietitian_certificate from dietitians where verification_status = "pending"');

            $dietitianRequestsCount = count($dietitianRequests);
            $reportedRecipesCount = count($reportedRecipes);
            $reportedDietPlansCount = count($reportedDietPlans);

            return view('layouts.admin_dashboard')
                ->with('reportedRecipes', $reportedRecipes)
                ->with('reportedDietPlans', $reportedDietPlans)
                ->with('chefs', $chefs)
                ->with('dietitians', $dietitians)
                ->with('dietitianRequests', $dietitianRequests)
                ->with('dietitianRequestsCount', $dietitianRequestsCount)
                ->with('reportedRecipesCount', $reportedRecipesCount)
                ->with('reportedDietPlansCount', $reportedDietPlansCount);
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function approveRecipe($id)
    {
        if (session()->has('admin_login_data')) {
            Recipe::where('recipe_id', $id)
                ->update([
                    'recipe_reports' => 0,
                ]);
            return back()->with('activeTab', 'recipe-approved');
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function delRecipe($id)
    {
        if (session()->has('admin_login_data')) {
            $data = DB::select('select recipe_id, recipe_image, recipe_video from recipes where recipe_id = ?', [$id]);
            foreach ($data as $img) {
                $filename = public_path('images/recipe_images/' . $img->recipe_image);
                if (File::exists($filename)) {
                    File::delete($filename);
                }
            }

            foreach ($data as $recipeId) {
                DB::delete('delete from recipe_logs where recipe_id = ?', [$recipeId->recipe_id]);
            }

            foreach ($data as $video) {
                $filename = public_path('videos/recipe_videos/' . $video->recipe_video);
                if (File::exists($filename)) {
                    File::delete($filename);
                }
            }
            DB::delete("delete from recipe_likes where recipe_id = ?", [$id]);
            DB::delete('delete from recipe_nutrients where recipe_id = ?', [$id]);
            DB::delete('delete from recipes where recipe_id = ?', [$id]);
            return back()->with('activeTab', 'recipe-deleted');
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function approveDietPlan($id)
    {
        if (session()->has('admin_login_data')) {
            DietPlan::where('diet_plan_id', $id)
                ->update([
                    'diet_plan_reports' => 0,
                ]);
            return back()->with('activeTab', 'diet-plan-approved');
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function delDietPlan($id)
    {
        if (session()->has('admin_login_data')) {
            $data = DB::select('select diet_plan_meals from diet_plans where diet_plan_id = ?', [$id]);
            foreach ($data as $img) {
                $filename = public_path('images/diet_plans/' . $img->diet_plan_meals);
                if (File::exists($filename)) {
                    File::delete($filename);
                }
            }

            DB::delete('delete from diet_plans where diet_plan_id = ?', [$id]);
            DB::delete('delete from diet_plan_likes where diet_plan_id = ?', [$id]);
            DB::delete('delete from diet_plan_likes where diet_plan_id = ?', [$id]);
            DB::delete('delete from report_diet_plans where diet_plan_id = ?', [$id]);
            DB::delete('delete from diet_plans where diet_plan_id = ?', [$id]);

            return back()->with('activeTab', 'diet-plan-deleted');
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function approveDietitian($id)
    {
        if (session()->has('admin_login_data')) {
            Dietitian::where('dietitian_id', $id)
                ->update([
                    'verification_status' => 'approved',
                ]);
            return back()->with('activeTab', 'dttn-approved');
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function disapproveDietitian($id)
    {
        if (session()->has('admin_login_data')) {
            Dietitian::where('dietitian_id', $id)
                ->update([
                    'verification_status' => 'disapproved',
                ]);
            return back()->with('activeTab', 'dttn-disapproved');
        } else {
            return view('layouts.admin_login_form');
        }
    }

    public function deleteChef(Request $request, $chefId)
    {
        $publicPath = public_path();

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

        // Logout chef
        if ($request->session()->has('login_data')) {
            session()->pull('login_data', null);
        }

        return back()->with('activeTab', 'chef-deleted');
    }

    public function deleteDietitian(Request $request, $dietitianId)
    {

        $publicPath = public_path();

        // Get all diet plan ids that are uploaded by dietitian
        $dietPlanIds = DB::table('diet_plans')
            ->where('dietitian_id', $dietitianId)
            ->pluck('diet_plan_id');

        // Get dietitian profile image name
        $dietitianPic = DB::table('dietitians')
            ->select('dietitian_profile_pic')
            ->where('dietitian_id', $dietitianId)
            ->get()
            ->first();

        // Get dietitian certificate name
        $dietitianCertificate = DB::table('dietitians')
            ->select('dietitian_certificate')
            ->where('dietitian_id', $dietitianId)
            ->get()
            ->first();

        // Delete dietitian certificate from public directory
        $dietitianCertificatePath = $publicPath . '/images/dietitian_certificates/' . $dietitianCertificate->dietitian_certificate;
        if (file_exists($dietitianCertificatePath)) {
            unlink($dietitianCertificatePath);
        }

        // Delete dietitian profile pic from public directory
        $dietitianProfileImagePath = $publicPath . '/images/dietitian_profile_images/' . $dietitianPic->dietitian_profile_pic;
        if (file_exists($dietitianProfileImagePath)) {
            unlink($dietitianProfileImagePath);
        }

        // Get the diet plan PDF names for the given diet plan IDs
        $diet_plans = DB::table('diet_plans')
            ->select('diet_plan_meals')
            ->whereIn('diet_plan_id', $dietPlanIds)
            ->get();

        foreach ($diet_plans as $diet_plan) {
            // Delete the diet plan PDF from public directory
            $dietPlansPdfPath = $publicPath . '/diet_plans/' . $diet_plan->diet_plan_meals;
            if (file_exists($dietPlansPdfPath)) {
                unlink($dietPlansPdfPath);
            }
        }

        // Delete all diet plan likes
        DB::table('diet_plan_likes')->where('diet_plan_id', $dietitianId)->delete();

        // Delete all diet plans uploaded by dietitian
        DB::table('diet_plans')->where('dietitian_id', $dietitianId)->delete();

        // Delete dietitian
        dietitian::where('dietitian_id', $dietitianId)->delete();

        // Logout Dietitian
        if ($request->session()->has('dietitian_login_data')) {
            session()->pull('dietitian_login_data', null);
        }

        return back()->with('activeTab', 'dietitian-deleted');
    }
}
