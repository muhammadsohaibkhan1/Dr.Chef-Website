<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('css/user_signup_form.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="icon" type="image/png" href="images/favicon.png">

    <title>User Sign Up</title>
</head>

<body>
    {{-- Navbar Code Block Start --}}
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="/images/logo.png" alt="Logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('recipes/display') }}">Recipes</a>
                    </li>
                    @if (session()->has('user_login_data'))
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('display_diet_plans') }}">Diet
                                Plans</a></li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('calorietracker/today') }}">Calorie Tracker</a>
                    </li>
                    @if (session()->has('user_login_data'))
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('display/save/recipe') }}"><span
                                    class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                                    </svg></span></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link px-3 dropdown-toggle" href="#" id="contact-dropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                    </svg>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user/profile') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('user/logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    {{-- Navbar Code Block End --}}

    <form id="regForm" method="POST" action="uregister" enctype="multipart/form-data" style="margin-top:50px;">
        @csrf
        <!-- Dashes which indicates the steps of the form: -->
        <div style="text-align:center;">
            <span class="step"></span>
            <span class="step"></span>

        </div>
        <!-- One "tab" for each step in the form: -->
        <div class="tab">
            <h2 class="title">Create Account</h2>
            <div class="profile-img-div">
                <input class="input--style-3" type="file" name="user_profile_pic" onchange="loadImage(event)"
                    style="display:none" id="profile_pic">
                <img id="preview" class="chef-profile-img"
                    src="{{ asset('images/styling_images/signup_img_icon.png') }}">
                <br>
                <label for="profile_pic" style="cursor: pointer;" id="pic_lbl">Upload Profile Picture</label>
            </div>
            <br><input class="input--style-3 placeholder-text" type="text" placeholder="Name"
                name="user_full_name" value="{{ old('user_full_name', $data['user_full_name'] ?? '') }}">
            @error('user_full_name')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror

            <br><br><input class="input--style-3 placeholder-text" type="text" placeholder="Username"
                name="user_username" value="{{ old('user_username', $data['user_username'] ?? '') }}">
            @error('user_username')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror

            <br><br><input class="input--style-3 placeholder-text" type="email" placeholder="Email"
                name="user_email" value="{{ old('user_email', $data['user_email'] ?? '') }}">
            @error('user_email')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror

            <br><br><input class="input--style-3 placeholder-text" type="password" placeholder="Password"
                name="user_password" value="{{ old('user_password', $data['user_password'] ?? '') }}">
            @error('user_password')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror

            <div class="p-t-10 text-center">
                <br><br><small>Already have an account? <a href="{{ route('users/loginform') }}"
                        class="login-reg-link">Login</a></small><br><br>
            </div>
        </div>

        <div class="tab">
            <h2 class="title">Create Account</h2>


            <input class="input--style-3 placeholder-text" type="number" placeholder="Age" name="user_age"
                value="{{ old('user_age', $data['user_age'] ?? '') }}">
            @error('user_age')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror


            <br><br><input class="input--style-3 placeholder-text" type="number" min="1.00" step="1.01"
                placeholder="Height" name="user_height"
                value="{{ old('user_height', $data['user_height'] ?? '') }}">
            @error('user_height')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror


            <br><br><input class="input--style-3 placeholder-text" type="number" placeholder="Weight"
                name="user_weight" value="{{ old('user_weight', $data['user_weight'] ?? '') }}">
            @error('user_weight')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror

            <br><br><select required class="input--style-3 placeholder-text border-0" style="width: 100%"
                type="text" name="user_activity">
                <option value=""
                    {{ old('user_activity', $data['user_activity'] ?? '') == '' ? 'selected' : '' }} selected disabled
                    hidden>Activity Level</option>
                <option value="sedentary"
                    {{ old('user_activity', $data['user_activity'] ?? '') == 'sedentary' ? 'selected' : '' }}>Sedentary
                </option>
                <option value="light"
                    {{ old('user_activity', $data['user_activity'] ?? '') == 'light' ? 'selected' : '' }}>Light
                </option>
                <option value="moderate"
                    {{ old('user_activity', $data['user_activity'] ?? '') == 'moderate' ? 'selected' : '' }}>Moderate
                </option>
                <option value="very active"
                    {{ old('user_activity', $data['user_activity'] ?? '') == 'very active' ? 'selected' : '' }}>Very
                    Active</option>
                <option value="extra active"
                    {{ old('user_activity', $data['user_activity'] ?? '') == 'extra active' ? 'selected' : '' }}>Extra
                    Active</option>
            </select>
            @error('user_activity')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror

            <br><br><select required class="input--style-3 placeholder-text border-0" type="text"
                style="width: 100%" name="user_disease">
                <option value="" {{ old('user_disease', $data['user_disease'] ?? '') == '' ? 'selected' : '' }}
                    selected hidden>Disease (If any)</option>
                <option value="none"
                    {{ old('user_disease', $data['user_disease'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                <option value="diabetic"
                    {{ old('user_disease', $data['user_disease'] ?? '') == 'diabetic' ? 'selected' : '' }}>Diabatec
                    Patient</option>
                <option value="cardiac"
                    {{ old('user_disease', $data['user_disease'] ?? '') == 'cardiac' ? 'selected' : '' }}>Cardiac
                    Patient</option>
                <option value="blood pressure"
                    {{ old('user_disease', $data['user_disease'] ?? '') == 'blood pressure' ? 'selected' : '' }}>Blood
                    Pressure Patient</option>
            </select>
            @error('user_disease')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror


            <br><br><select required class="input--style-3 placeholder-text border-0" type="text"
                style="width: 100%" name="user_gender">
                <option value="" {{ old('user_gender', $data['user_gender'] ?? '') == '' ? 'selected' : '' }}
                    selected disabled hidden>Gender</option>
                <option value="male"
                    {{ old('user_gender', $data['user_gender'] ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female"
                    {{ old('user_gender', $data['user_gender'] ?? '') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('user_gender')
                <br><label style="color: red; ">{{ $message }}</label>
            @enderror

            <br><br><label class="input--style-3 placeholder-text" style="width: 100%; text-align:left">Weight
                Goal</label><br><br>

            <div class="input-group">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="user_weight_goal" value="maintain weight"
                        aria-label="Maintain Weight" required>
                    <label class="form-check-label" for=""user_weight_goal"">
                        Maintain Weight
                    </label>
                </div>
            </div>

            <div class="input-group">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="user_weight_goal" value="gain weight"
                        aria-label="Gain Weight" required>
                    <label class="form-check-label" for="user_weight_goal">
                        Gain Weight
                    </label>
                </div>
            </div>

            <div class="input-group">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="user_weight_goal" value="lose weight"
                        aria-label="Lose Weight" required>
                    <label class="form-check-label" for=""user_weight_goal"">
                        Lose Weight
                    </label>
                </div>
            </div>
        </div>

        <div style="overflow:auto;">
            <div style="text-align:center;">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                <button class="nextbtn" type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
            </div>
        </div>
        </div>
    </form>

    <script src="js/add_recipes.js"></script>

    {{-- Footer Code Block Start --}}
    <footer>
        <div class="container padding-t">
            <div class="row">
                <div class="col-lg-6" style="margin-right: 100px">
                    <h3 style="font-weight: bold">Dr. Chef</h3>
                    <ul class="list-unstyled">
                        <li class="justify-col1">Our website provides a platform where chefs and dietitians can share
                            their delicious recipes
                            and effective diet plans. People can browse through our extensive collection of recipes and
                            diet plans, and even track their daily calorie intake for a healthier lifestyle. Join us
                            today and start your journey towards a healthier you!</li>
                    </ul>
                </div>
                <div class="col pd-top">
                    <h5>Explore</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('calorietracker/today') }}">Calorie Tracker</a></li><br>
                        <li><a href="{{ route('display_diet_plans') }}">Diet Plans</a></li><br>
                        <li><a href="{{ route('recipes/display') }}">Recipes</a></li>
                    </ul>
                </div>
                <div class="col pd-top">
                    <h5>Useful Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('index') }}">Home</a></li><br>
                        <li><a href="{{ route('chefs/signupform') }}">Share Recipes</a></li><br>
                        <li><a href="{{ route('dietitians/signupform') }}">Share Diet Plans</a></li>
                    </ul>
                </div>
                <div class="col pd-top">
                    <h5>Social</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.facebook.com/profile.php?id=100092157263113&mibextid=ZbWKwL"><span
                                    class="material-icons"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-facebook"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                    </svg></span></li>
                        <li><a href="https://instagram.com/dr.chef25?igshid=MmJiY2I4NDBkZg== "><span
                                    class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                        <path
                                            d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                    </svg>
                                </span></li>
                        <li><a href="https://twitter.com/drchef25?t=48c6X2FSeFi7UQ-PaWr5Zw&s=08 "><span
                                    class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                        <path
                                            d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                    </svg>
                                </span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center py-3">
            <hr>
            Copyright &copy;2023 Dr. Chef
        </div>

    </footer>
    {{-- Footer Code Block End --}}

    {{-- JS Code for displaying chef image just after upload --}}
    <script>
        var loadImage = function(event) {
            var image = document.getElementById('preview');
            image.src = URL.createObjectURL(event.target.files[0]);
            // document.getElementById('pic_lbl').innerHTML = " ";
        };
    </script>

</body>

</html>
