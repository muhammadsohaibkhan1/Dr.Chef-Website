<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DietitianController;
use App\Http\Controllers\AdminController;


Route::get('/', [UserController::class, 'index'])->name('index');
Route::get('/register', [ChefController::class, 'displayChefSignupForm'])->name('chefs/signupform');
Route::post('/register', [ChefController::class, 'registerChef'])->name('register');
Route::get('/validate', [ChefController::class, 'register'])->name('validate');
Route::get('/add_recipes', [ChefController::class, 'displayAddRecipesForm'])->name('add_recipes_form');
Route::post('/add_recipes', [ChefController::class, 'addRecipes'])->name('add_recipes');
Route::get('edit/recipe/{id}',[ChefController::class, 'update'])->name('edit/recipe');
Route::post('update/recipe/{id}',[ChefController::class, 'edit'])->name('update/recipe');
Route::get('deleteRecipe/{id}',[ChefController::class, 'deleteRecipe'])->name('deleteRecipe');
Route::get('/chef_portal', [ChefController::class, 'chefPortal'])->name('chef/portal');
Route::get('/chef_profile_setting', [ChefController::class, 'chefProfileSetting'])->name('chef/profile/setting');
Route::post('/update_chef_profile', [ChefController::class, 'updateChefProfile'])->name('chef/profile/update');
Route::post('/update_chef_password', [ChefController::class, 'updateChefPassword'])->name('chef/password/update');
Route::post('/delete_chef_account', [ChefController::class, 'deleteChef'])->name('chef/delete');
Route::get('/login', [ChefController::class, 'displayChefLoginForm'])->name('chefs/loginform');
Route::post('/login', [ChefController::class, 'login'])->name('login');
Route::get('/display_recipes', [UserController::class, 'displayRecipes'])->name('recipes/display');
Route::post('/display_recipes', [UserController::class, 'searchRecipe'])->name('search/recipe');
Route::get('/display_recipe_detail/{id}', [ChefController::class, 'displayRecipeDetail'])->name('recipe/display/detail');
Route::get('/get_nutrients', [ChefController::class, 'getNutrientValue'])->name('recipe/nutrients');
Route::get('/logout', [ChefController::class, 'logout'])->name('logout');
Route::get('/user_profile', [UserController::class, 'userProfile'])->name('user/profile');
Route::post('/update_user_profile', [UserController::class, 'updateUserProfile'])->name('user/profile/update');
Route::post('/update_user_password', [UserController::class, 'updateUserPassword'])->name('user/password/update');
Route::post('/delete_user_account', [UserController::class, 'deleteUser'])->name('user/delete');
Route::get('/calorie_tracker_today', [UserController::class, 'calorieTrackerToday'])->name('calorietracker/today');
Route::get('/calorie_tracker_weekly', [UserController::class, 'calorieTrackerWeekly'])->name('calorietracker/weekly');
Route::get('/calorie_tracker_monthly', [UserController::class, 'calorieTrackerMonthly'])->name('calorietracker/monthly');
Route::get('/personalized_diet_plans', [UserController::class, 'personalizedDietPlan'])->name('personalized_diet_plans');
Route::get('/uregister', [UserController::class, 'signupForm'])->name('user/signupform');
Route::post('/uregister', [UserController::class, 'register'])->name('uregister');
Route::get('/ulogin', [UserController::class, 'displayUserLoginForm'])->name('users/loginform');
Route::post('/ulogin', [UserController::class, 'login'])->name('user/login');
Route::get('/ulogout', [UserController::class, 'logout'])->name('user/logout');
Route::get('/save/recipe/{id}', [UserController::class, 'saveRecipe'])->name('save/recipe');
Route::get('/report/recipe/{id}', [UserController::class, 'reportRecipe'])->name('report/recipe');
Route::get('/report/diet/plan/{id}', [UserController::class, 'reportDietPlan'])->name('report/diet/plan');
Route::get('/display/save/recipe', [UserController::class, 'displaySavedRecipes'])->name('display/save/recipe');
Route::get('/like/recipe/{id}', [UserController::class, 'likeRecipe'])->name('like/recipe');
Route::get('/like/chef/{id}', [UserController::class, 'likeChef'])->name('like/chef');
Route::get('/view/chef/profile/{id}', [UserController::class, 'showChefProfile'])->name('view/chef/profile');
Route::get('/view/dietitian/profile/{id}', [UserController::class, 'showDietitianProfile'])->name('view/dietitian/profile');
Route::get('/like/dietplan/{id}', [UserController::class, 'likeDietPlan'])->name('like/diet/plan');
Route::get('/like/dietitian/{id}', [UserController::class, 'likeDietitian'])->name('like/dietitian');
Route::get('/exercises', [UserController::class, 'exercises'])->name('exercises');
Route::get('/burnedcalories', [UserController::class, 'burnedCalories'])->name('burned_calories');
Route::get('/add_calories', [UserController::class, 'addCalories'])->name('add_calories');
Route::get('/dregister', [DietitianController::class, 'displayDietitianSignupForm'])->name('dietitians/signupform');
Route::post('/dregister', [DietitianController::class, 'registerDietitian'])->name('dregister');
Route::get('/dietitian_portal', [DietitianController::class, 'dietitianPortal'])->name('dietitian/portal');
Route::get('/dietitian_profile_setting', [DietitianController::class, 'dietitianProfileSetting'])->name('dietitian/profile/setting');
Route::post('/update_dietitian_profile', [DietitianController::class, 'updateDietitianProfile'])->name('dietitian/profile/update');
Route::post('/update_dietitian_password', [DietitianController::class, 'updateDietitianPassword'])->name('dietitian/password/update');
Route::post('/delete_dietitian_account', [DietitianController::class, 'deleteDietitian'])->name('dietitian/delete');
Route::get('/dlogin', [DietitianController::class, 'displayDietitianLoginForm'])->name('dietitians/loginform');
Route::post('/dlogin', [DietitianController::class, 'login'])->name('dlogin');
Route::get('/dprofile', [DietitianController::class, 'showDietitianProfile'])->name('dprofile');
Route::get('/display_dietitians', [DietitianController::class, 'displayDietitians'])->name('display/dietitians');
Route::get('/add_diet_plans', [DietitianController::class, 'displayAddDietPlanForm'])->name('add_diet_plan_form');
Route::post('/add_diet_plan', [DietitianController::class, 'addDietPlan'])->name('add_diet_plan');
Route::get('/display_diet_plans', [DietitianController::class, 'showDietPlans'])->name('display_diet_plans');
Route::get('/dietitian/display/recipes', [DietitianController::class, 'displayRecipesDietitian'])->name('dietitian/recipes/display');
Route::get('/display_diet_plan_pdf/{id}', [DietitianController::class, 'displayDietPlan'])->name('display_diet_plans_pdf');
Route::post('/display_diet_plans', [DietitianController::class, 'search'])->name('display_diet_plans');
Route::get('delete/dietPlan/{id}',[DietitianController::class, 'destroy'])->name('delete/dietPlan');
Route::get('edit/dietPlan/{id}',[DietitianController::class, 'editDietPlan'])->name('edit/dietPlan');
Route::post('update/dietPlan/{id}',[DietitianController::class, 'updateDietPlan'])->name('update/dietPlan');
Route::get('/dlogout', [DietitianController::class, 'logout'])->name('dlogout');
Route::get('/admin_dashboard', [AdminController::class, 'displayAdminDashboard'])->name('admin/dashboard');
Route::get('/admin_login', [AdminController::class, 'adminLoginForm'])->name('admin/loginform');
Route::post('/admin_login', [AdminController::class, 'adminLogin'])->name('admin/login');
Route::get('/admin_logout', [AdminController::class, 'adminLogout'])->name('admin/logout');
Route::get('approveRecipe/{id}',[AdminController::class, 'approveRecipe']);
Route::get('delRecipe/{id}',[AdminController::class, 'delRecipe']);
Route::get('approveDietPlan/{id}',[AdminController::class, 'approveDietPlan']);
Route::get('delDietPlan/{id}',[AdminController::class, 'delDietPlan']);
Route::get('approveDietitian/{id}',[AdminController::class, 'approveDietitian'])->name('approve/dietitian');
Route::get('disapproveDietitian/{id}',[AdminController::class, 'disapproveDietitian'])->name('disapprove/dietitian');
Route::get('delete/chef/{id}',[AdminController::class, 'deleteChef'])->name('delete/chef');
Route::get('delete/dietitian/{id}',[AdminController::class, 'deleteDietitian'])->name('delete/dietitian');
