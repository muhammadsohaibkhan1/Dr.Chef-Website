<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dietitian;
use App\Models\DietPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class DietitianController extends Controller
{
    public function login(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            return redirect()->route('dietitian/portal');
        } else {
            $dietitian_login_data = Dietitian::where(['dietitian_email' => $request->dietitian_email])->first();
            if (!$dietitian_login_data || !Hash::check($request->dietitian_password, $dietitian_login_data->dietitian_password)) {
                return redirect()
                    ->back()
                    ->with('message', 'Incorrect email or password!');
            } else {
                $request->session()->put('dietitian_login_data', $dietitian_login_data);
                $verificationStatus=$request->session()->get('dietitian_login_data.verification_status');

                if (session()->has('dietitian_login_data')&&$verificationStatus=="approved") {
                    session(['dietitian_login_data' => $dietitian_login_data]);
                    //dd(session()->get('dietitian_login_data.dietitian_username'));
                    return redirect()
                        ->route('dietitian/portal')
                        ->withInput();
                }
                else if (session()->has('dietitian_login_data')&&$verificationStatus=="pending") {
                    session()->pull('dietitian_login_data', null);
                    return redirect()
                    ->back()
                    ->with('message', 'Not Verified Yet!');
                }
                else if (session()->has('dietitian_login_data')&&$verificationStatus=="disapproved") {
                    session()->pull('dietitian_login_data', null);
                    return redirect()
                    ->back()
                    ->with('message', 'Your application has been disapproved!');
                }
            }
        }
    }

    public function logout()
    {
        if (session()->has('dietitian_login_data')) {
            session()->pull('dietitian_login_data', null);
            return redirect()->route('dietitians/loginform');
        }
        return redirect()->route('dlogin');
    }

    public function displayDietitianSignupForm(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            return redirect()
                ->route('dietitian/portal');
        } else {
            return view('layouts.dietitian_signup_form');
        }
    }

    public function displayDietitianLoginForm(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            return view('layouts.dietitian_login_form');
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function dietitianPortal()
    {
        if (session()->has('dietitian_login_data')) {
            $dietitianId = session()->get('dietitian_login_data.dietitian_id');
            $data = DB::select('select * from diet_plans
                JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
                where diet_plans.dietitian_id = "' . $dietitianId . '" AND dietitians.dietitian_id = "' . $dietitianId . '"');
            $profile = DB::select('select dietitian_profile_pic, dietitian_full_name,dietitian_username,dietitian_likes from dietitians WHERE dietitian_id = "' . $dietitianId . '"');
            return view('layouts.dietitian_portal')->with('profile', $profile)->with('data', $data);
        } else {
            return redirect()->route('dietitians/loginform');
        }
    }

    public function displayAddDietPlanForm(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            return view('layouts.add_diet_plans');
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function addDietPlan(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            $planLikes = 0;
            $planReports = 0;

            $data = $request->validate([
                'diet_plan_duration' => 'required |integer|between:1,30',
                'diet_plan_type' => 'required',
                'diet_plan_user_type' => 'required',
                'diet_plan_weight_goal' => 'required|integer|between:1,6',
                'diet_plan_meals' => 'required',
            ]);

            $diet_plan_pdf_name = session()->get('dietitian_login_data.dietitian_username');
            $now = Carbon::now();
            $datetime = str_replace([':', ' ', '-'], '', $now->toDateTimeString());
            // Storing pdf name in variable and original pdf in public/diet_plan;
            $dietPlanName = $diet_plan_pdf_name . '_' . $datetime . '.' . $request->diet_plan_meals->extension();
            $request->diet_plan_meals->move(public_path('diet_plans'), $dietPlanName);

            $dietitianId = session()->get('dietitian_login_data.dietitian_id');

            DietPlan::create([
                'dietitian_id' => $dietitianId,
                'diet_plan_duration' => $data['diet_plan_duration'],
                'diet_plan_likes' => $planLikes,
                'diet_plan_reports' => $planReports,
                'diet_plan_type' => $data['diet_plan_type'],
                'diet_plan_user_type' => $data['diet_plan_user_type'],
                'diet_plan_weight_goal' => $data['diet_plan_weight_goal'],
                'diet_plan_meals' => $dietPlanName,
            ]);

            return redirect()
                ->route('dietitian/portal')
                ->withInput();
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function registerDietitian(Request $request)
    {

        $email = $request->input('dietitian_email');
        $password = $request->input('dietitian_password');
        $username = $request->input('dietitian_username');
        $phone_number = $request->input('dietitian_phone_number');
        $dietitianLikes = 0;
        $verificationStatus = "pending";

        $data = $request->validate([
            'dietitian_full_name' => 'required | regex:/^[\pL\s\-]+$/u',
            'dietitian_username' => 'required|unique:dietitians,dietitian_username|regex:/^[a-z0-9_]+$/',
            'dietitian_email' => 'required |regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix| unique:dietitians,dietitian_email',
            'dietitian_password' => 'required | min:8',
            'dietitian_phone_number' => 'required | unique:dietitians,dietitian_phone_number',
            'dietitian_profile_pic' => 'required | image | mimes:png,jpg,jpeg',
            'dietitian_certificate' => 'required | image | mimes:png,jpg,jpeg',
        ]);

        $imageName = $username . '.' . $request->dietitian_profile_pic->extension();
        $request->dietitian_profile_pic->move(public_path('images/dietitian_profile_images'), $imageName);

        $certificateImageName = $username . '.' . $request->dietitian_certificate->extension();
        $request->dietitian_certificate->move(public_path('images/dietitian_certificates'), $certificateImageName);

        Dietitian::create([
            'dietitian_full_name' => $data['dietitian_full_name'],
            'dietitian_email' => $data['dietitian_email'],
            'dietitian_username' => $data['dietitian_username'],
            'dietitian_phone_number' => $data['dietitian_phone_number'],
            'dietitian_password' => Hash::make($data['dietitian_password']),
            'dietitian_profile_pic' => $imageName,
            'dietitian_certificate' => $certificateImageName,
            'verification_status' => $verificationStatus,
            'dietitian_likes' => $dietitianLikes,
        ]);
        return redirect()
            ->route('dlogin')
            ->withInput();
    }

    public function showDietPlans()
    {
        if (session()->has('user_login_data')) {
            $userId = session()->get('user_login_data.user_id');
            $data = DB::select(
                'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"'
            );
            return view('layouts.diet_plan_search')->with('data', $data);
        } else {
            return redirect()
                ->route('user/login');
        }
    }

    public function displayDietPlan($id)
    {
        $data = DB::table('diet_plans')
            ->select('diet_plan_meals',)
            ->where(['diet_plan_id' => $id])
            ->first();
        return response()->file("diet_plans/$data->diet_plan_meals");
    }

    public function search(Request $request)
    {
        if (session()->has('user_login_data')) {
            $goal = $request->diet_plan_weight_goal;
            $filter = $request->filter;
            $duration = $request->duration;
            if ($goal == null && $filter == null && $duration == null) {
                return redirect()->route('display_diet_plans');
            } elseif (!$goal == null && $filter == null && $duration == null) {
                if (session()->has('user_login_data')) {
                    $userId = session()->get('user_login_data.user_id');
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"
             where diet_plan_type = "' . $goal . '"'
                    );
                } else {
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_full_name,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL
             where diet_plan_type = "' . $goal . '"'
                    );
                }
                return view('layouts.diet_plan_search')->with('data', $data);
            } elseif ($goal == null && !$filter == null && $duration == null) {
                if (session()->has('user_login_data')) {
                    $userId = session()->get('user_login_data.user_id');
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"
             where diet_plan_user_type = "' . $filter . '"'
                    );
                } else {
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_full_name,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL
             where diet_plan_user_type = "' . $filter . '"'
                    );
                }
                return view('layouts.diet_plan_search')->with('data', $data);
            } elseif ($goal == null && $filter == null && !$duration == null) {
                if (session()->has('user_login_data')) {
                    $userId = session()->get('user_login_data.user_id');
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"
             where diet_plan_duration = "' . $duration . '"'
                    );
                } else {
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_full_name,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL
             where diet_plan_user_type = "' . $filter . '"'
                    );
                }
                return view('layouts.diet_plan_search')->with('data', $data);
            } elseif (!$goal == null && !$filter == null && $duration == null) {
                if (session()->has('user_login_data')) {
                    $userId = session()->get('user_login_data.user_id');
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"
             where diet_plan_type = "' . $goal . '" and diet_plan_user_type = "' . $filter . '"'
                    );
                } else {
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_full_name,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL
             where diet_plan_type = "' . $goal . '" and diet_plan_user_type = "' . $filter . '"'
                    );
                }
                return view('layouts.diet_plan_search')->with('data', $data);
            } elseif (!$goal == null && $filter == null && !$duration == null) {
                if (session()->has('user_login_data')) {
                    $userId = session()->get('user_login_data.user_id');
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"
             where diet_plan_type = "' . $goal . '" and diet_plan_duration = "' . $duration . '"'
                    );
                } else {
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_full_name,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL
             where diet_plan_type = "' . $goal . '" and diet_plan_duration = "' . $duration . '"'
                    );
                }
                return view('layouts.diet_plan_search')->with('data', $data);
            } elseif ($goal == null && !$filter == null && !$duration == null) {
                if (session()->has('user_login_data')) {
                    $userId = session()->get('user_login_data.user_id');
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"
             where diet_plan_user_type = "' . $filter . '" and diet_plan_duration = "' . $duration . '"'
                    );
                } else {
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_full_name,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL
             where diet_plan_user_type = "' . $filter . '" and diet_plan_duration = "' . $duration . '"'
                    );
                }
                return view('layouts.diet_plan_search')->with('data', $data);
            } elseif (!$goal == null && !$filter == null && !$duration == null) {
                if (session()->has('user_login_data')) {
                    $userId = session()->get('user_login_data.user_id');
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_id,
                dietitians.dietitian_username,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = "' . $userId . '"
             where diet_plan_user_type = "' . $filter . '" and diet_plan_duration = "' . $duration . '"'
                    );
                } else {
                    $data = DB::select(
                        'Select
                diet_plans.diet_plan_id,
                diet_plan_duration,
                diet_plan_likes,
                diet_plan_type,
                diet_plan_user_type,
                diet_plan_weight_goal,
                dietitians.dietitian_full_name,
                IF(diet_plan_likes.user_id IS NULL, 0, 1) AS is_liked
             FROM diet_plans
             JOIN dietitians ON diet_plans.dietitian_id = dietitians.dietitian_id
             LEFT JOIN diet_plan_likes ON diet_plans.diet_plan_id = diet_plan_likes.diet_plan_id AND diet_plan_likes.user_id = NULL
             where diet_plan_type = "' . $goal . '" and diet_plan_user_type = "' . $filter . '" and diet_plan_duration = "' . $duration . '"'
                    );
                }
                return view('layouts.diet_plan_search')->with('data', $data);
            }
        } else {
            return redirect()
                ->route('user/login');
        }
    }
    public function destroy(Request $request, $id)
    {
        if ($request->session()->has('dietitian_login_data')) {
            $data = DB::select('select diet_plan_meals from diet_plans where diet_plan_id = ?', [$id]);
            foreach ($data as $img) {
                $filename = public_path('images/diet_plans/' . $img->diet_plan_meals);
                if (File::exists($filename)) {
                    File::delete($filename);
                }
            }
            DB::delete('delete from diet_plan_likes where diet_plan_id = ?', [$id]);
            DB::delete('delete from report_diet_plans where diet_plan_id = ?', [$id]);
            DB::delete('delete from diet_plans where diet_plan_id = ?', [$id]);
            return back()->withInput();
        } else {
            return view('layouts.dietitian_login_form');
        }
    }
    public function editDietPlan(Request $request, $id)
    {
        if ($request->session()->has('dietitian_login_data')) {
            $data = DB::select('select * from diet_plans where diet_plan_id = ?', [$id]);
            return view('layouts.update_diet_plan')->with('data', $data);
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function updateDietPlan(Request $request, $id)
    {
        if ($request->session()->has('dietitian_login_data')) {
            $plan = DB::select('select diet_plan_meals from diet_plans where diet_plan_id = ?', [$id]);
            foreach ($plan as $img) {
                $filename = public_path('images/diet_plans/' . $img->diet_plan_meals);
                if (File::exists($filename)) {
                    File::delete($filename);
                }
            }

            $data = $request->validate([
                'diet_plan_duration' => 'required',
                'diet_plan_type' => 'required',
                'diet_plan_user_type' => 'required',
                'diet_plan_weight_goal' => 'required',
                'diet_plan_meals' => 'required',
            ]);

            $diet_plan_pdf_name = session()->get('dietitian_login_data.dietitian_username');
            $now = Carbon::now();
            $datetime = str_replace([':', ' ', '-'], '', $now->toDateTimeString());
            // Storing pdf name in variable and original pdf in public/diet_plan;
            $dietPlanName = $diet_plan_pdf_name . '_' . $datetime . '.' . $request->diet_plan_meals->extension();
            $request->diet_plan_meals->move(public_path('images\diet_plans'), $dietPlanName);

            DB::update('update diet_plans set
                diet_plan_duration = "' . $data['diet_plan_duration'] . '",
                diet_plan_type = "' . $data['diet_plan_type'] . '",
                diet_plan_user_type = "' . $data['diet_plan_user_type'] . '",
                diet_plan_weight_goal = "' . $data['diet_plan_weight_goal'] . '",
                diet_plan_meals = "' . $dietPlanName . '"
                where diet_plan_id = "' . $id . '"');

            return redirect()
                ->route('dietitian/portal')
                ->withInput();
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function dietitianProfileSetting(Request $request)
    {
        if (session()->has('dietitian_login_data')) {
            $dietitianId = $request->session()->get('dietitian_login_data.dietitian_id');
            $dietitian = Dietitian::where('dietitian_id', $dietitianId)->get()->first();
            return view('layouts.dietitian_profile_setting', ['dietitian' => $dietitian]);
        } else {
            return redirect()
                ->route('dlogin');
        }
    }

    public function updateDietitianProfile(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            $username = $request->input('dietitian_username');
            $dietitianEmail = $request->input('dietitian_email');
            $dietitianId = session()->get('dietitian_login_data.dietitian_id');
            $dietitianFirstUsername = session()->get('dietitian_login_data.dietitian_username');
            $dietitianProfilePicName = session()->get('dietitian_login_data.dietitian_profile_pic');

            // Change the profile pic name if username changed
            if ($username != $dietitianFirstUsername) {
                $oldImagePath = public_path('images/user_profile_images/' . $dietitianProfilePicName);

                // Get the extension of the old image
                $extension = pathinfo($oldImagePath, PATHINFO_EXTENSION);
                $newImageName = $username . '.' . $extension;
                $newImagePath = public_path('images/dietitian_profile_images/' . $newImageName);

                // Rename the image file
                if (file_exists($oldImagePath)) {
                    rename($oldImagePath, $newImagePath);
                }

                Dietitian::where('dietitian_id', $dietitianId)
                        ->update([
                            'dietitian_profile_pic' => $newImageName
                        ]);
            }

            $dietitianEmailCheck = Dietitian::select('dietitian_id')
                ->where('dietitian_email', '=', $dietitianEmail)
                ->whereNotIn('dietitian_id', [$dietitianId])
                ->get();

            $dietitianUsernameCheck = Dietitian::select('dietitian_id')
                ->where('dietitian_username', $username)
                ->whereNotIn('dietitian_id', [$dietitianId])
                ->get();

            if ($dietitianEmailCheck->isNotEmpty() || $dietitianUsernameCheck->isNotEmpty()) {
                if ($dietitianUsernameCheck->isNotEmpty())
                    return back()->withInput()->with('activeTab', 'profile')->with('username-alert', 'This Username is already taken');

                if ($dietitianEmailCheck->isNotEmpty())
                    return back()->withInput()->with('activeTab', 'profile')->with('email-alert', 'This email is already registered');
            } else {

                $data = $request->validate([
                    'dietitian_full_name' => 'required | regex:/^[\pL\s\-]+$/u',
                    'dietitian_email' => 'required |regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                    'dietitian_username' => 'required | regex:/^[a-z0-9_]+$/',
                    'dietitian_profile_pic' => 'nullable | image | mimes:jpg,png,jpeg',
                ]);

                if (!array_key_exists('dietitian_profile_pic', $data)) {
                    Dietitian::where('dietitian_id', $dietitianId)
                        ->update([
                            'dietitian_full_name' => $data['dietitian_full_name'],
                            'dietitian_email' => $data['dietitian_email'],
                            'dietitian_username' => $data['dietitian_username'],
                        ]);
                    if (session()->has('dietitian_login_data')) {
                        session()->pull('dietitian_login_data', null);
                        $dietitianLoginData = Dietitian::where(['dietitian_email' => $dietitianEmail])->first();
                        $request->session()->put('dietitian_login_data', $dietitianLoginData);

                        if (session()->has('dietitian_login_data')) {
                            session(['dietitian_login_data' => $dietitianLoginData]);

                            return redirect()->back()->withInput()->with('activeTab', 'profile')->with('save-success', 'Changes Saved Successfully!');
                        }
                    }
                } else {
                    $imgName = public_path('images/dietitian_profile_images/' . $username . '.' . $request->dietitian_profile_pic->extension());
                    if (File::exists($imgName)) {
                        File::delete($imgName);
                    }

                    $imageName = $username . '.' . $request->dietitian_profile_pic->extension();
                    $request->dietitian_profile_pic->move(public_path('images/dietitian_profile_images'), $imageName);

                    dietitian::where('dietitian_id', $dietitianId)
                        ->update([
                            'dietitian_full_name' => $data['dietitian_full_name'],
                            'dietitian_email' => $data['dietitian_email'],
                            'dietitian_username' => $data['dietitian_username'],
                            'dietitian_profile_pic' => $imageName,

                        ]);
                    if (session()->has('dietitian_login_data')) {
                        session()->pull('dietitian_login_data', null);
                        $dietitianLoginData = Dietitian::where(['dietitian_email' => $dietitianEmail])->first();
                        $request->session()->put('dietitian_login_data', $dietitianLoginData);

                        if (session()->has('dietitian_login_data')) {
                            session(['dietitian_login_data' => $dietitianLoginData]);

                            return redirect()->back()->withInput()->with('activeTab', 'profile')->with('save-success', 'Changes Saved Successfully!');
                        }
                    }
                }
            }
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function updateDietitianPassword(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            $dietitianId = session()->get('dietitian_login_data.dietitian_id');
            $new_pswrd = $request->input('new_password');

            if (strlen($new_pswrd) >= 8) {
                Dietitian::where('dietitian_id', $dietitianId)
                    ->update([
                        'dietitian_password' => Hash::make($new_pswrd),
                    ]);
                return redirect()->back()->with('activeTab', 'change-password')->with('password-change-success', 'Password Changed Successfully!');
            } else {
                return redirect()->back()->with('activeTab', 'change-password')->with('password-alert', 'Password must be at least 8 characters long!');
            }
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function deleteDietitian(Request $request)
    {
        if ($request->session()->has('dietitian_login_data')) {
            $dietitianId = session()->get('dietitian_login_data.dietitian_id');
            $dietitianPassword = $request->input('deletion_password');
            $publicPath = public_path();

            $dietitian = dietitian::select('dietitian_password')->where('dietitian_id', $dietitianId)->first();

            if (Hash::check($dietitianPassword, $dietitian->dietitian_password)) {
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
                DB::table('diet_plan_likes')->wherein('diet_plan_id', $dietPlanIds)->delete();

                // Delete all diet plans reports by dietitian
                DB::table('report_diet_plans')->wherein('diet_plan_id', $dietPlanIds)->delete();

                // Delete all diet plans uploaded by dietitian
                DB::table('diet_plans')->where('dietitian_id', $dietitianId)->delete();

                 // Delete all dietian likes
                 DB::table('dietitian_likes')->where('dietitian_id', $dietitianId)->delete();

                // Delete dietitian
                Dietitian::where('dietitian_id', $dietitianId)->delete();

                session()->flush();

                return redirect()->route('dietitians/loginform')->with('delete-account-success', 'Account Deleted Successfully!');
            } else {
                return redirect()->back()->with('activeTab', 'delete-account')->with('delete-account-alert', 'Invalid Password!');
            }
        } else {
            return view('layouts.dietitian_login_form');
        }
    }

    public function displayRecipesDietitian()
    {
        $accessedBy = 'dietitian';
        return redirect()->route('recipes/display', ['accessedBy' => $accessedBy]);
    }

    public function displayRecipeDetailsDietitian()
    {
        $accessedBy = 'dietitian';
        return redirect()->route('recipe/display/detail', ['accessedBy' => $accessedBy]);
    }
}
