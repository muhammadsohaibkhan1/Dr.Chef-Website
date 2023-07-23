<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Title Page-->
    <title>{{ $recipe->recipe_name }}</title>

    <!-- Font special for pages-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" media="all">

    <!-- display_recipes CSS-->
    <link href="{{ asset('css/recipe_search.css') }}" rel="stylesheet" media="all">

    <!-- recipe_detailed_view CSS-->
    <link href="{{ asset('css/recipe_detailed_view.css') }}" rel="stylesheet" media="all">

    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

</head>

<body>
    @if (session()->has('message'))
        <script>
            alert('{{ session('message') }}');
        </script>
    @endif

    @if ($accessedBy == 'dietitian')
        {{-- Dietitian Navbar Code Block Start --}}
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dietitian/portal') }}">
                    <img src="/images/logo.png" alt="Logo" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav d-flex align-items-center"">
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('dietitian/portal') }}">Portal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 active" href="{{ route('dietitian/recipes/display') }}">Recipes</a>
                        </li>
                        @php
                            $dttnLoginData = session('dietitian_login_data');
                            $dttnProfilePic = $dttnLoginData['dietitian_profile_pic'];
                        @endphp
                        <li class="nav-item dropdown disable-hover">
                            <a class="nav-link px-3 dropdown-toggle" href="#" id="contact-dropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="circular-image">
                                    <img src="{{ asset('/images/dietitian_profile_images/' . $dttnProfilePic) }}"
                                        alt="Profile Picture" class="profile-picture">
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('dietitian/profile/setting') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('dlogout') }}">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        {{-- Dietitian Navbar Code Block End --}}
    @elseif($accessedBy == 'chef')
        {{-- Chef Navbar Code Block Start --}}
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('chef/portal') }}">
                    <img src="/images/logo.png" alt="Logo" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav  d-flex align-items-center">
                        <li class="nav-item">
                            <a class="nav-link px-3 active" href="{{ route('chef/portal') }}">Portal</a>
                        </li>
                        @php
                            $chefLoginData = session('login_data');
                            $chefProfilePic = $chefLoginData['chef_profile_pic'];
                        @endphp
                        <li class="nav-item dropdown disable-hover">
                            <a class="nav-link px-3 dropdown-toggle" href="#" id="contact-dropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="circular-image">
                                    <img src="{{ asset('/images/chef_profile_images/' . $chefProfilePic) }}"
                                        alt="Profile Picture" class="profile-picture">
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('chef/profile/setting') }}">Profile</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        {{-- Chef Navbar Code Block End --}}
    @elseif($accessedBy == 'admin')
        {{-- Admin Navbar Code Block Start --}}
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('admin/dashboard') }}">
                    <img src="/images/logo.png" alt="Logo" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link px-3 active" href="{{ route('admin/dashboard') }}">Portal</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        {{-- Admin Navbar Code Block End --}}
    @else
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
                    <ul class="navbar-nav d-flex align-items-center">
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('index') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 active" href="{{ route('recipes/display') }}">Recipes</a>
                        </li>
                        @if (session()->has('user_login_data'))
                            <li class="nav-item"><a class="nav-link px-3"
                                    href="{{ route('display_diet_plans') }}">Diet
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
                            @php
                                $userLoginData = session('user_login_data');
                                $userProfilePic = $userLoginData['user_profile_pic'];
                            @endphp
                            <li class="nav-item dropdown disable-hover">
                                <a class="nav-link px-3 dropdown-toggle" href="#" id="contact-dropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="circular-image">
                                        <img src="{{ asset('/images/user_profile_images/' . $userProfilePic) }}"
                                            alt="Profile Picture" class="profile-picture">
                                    </div>
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
    @endif
    <br><br>
    <div class="text-center">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert"
                data-bs-delay="3000">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <!-- Recipe View Code Block Start-->
    <div class="container recipe-view-bg">
        <div class="d-flex flex-column mb-3">
            <div class="p-2 text-center">
                <h4 style="color: #3D251E; font-weight: bold; position: relative;">
                    {{ $recipe->recipe_name }}
                    @if ($accessedBy == 'user')
                        <span style="position: absolute; top: 0; right: 0;">
                            <a href="{{ route('report/recipe', $recipe->recipe_id) }}"
                                style="color: #aa8e7b; text-decoration:none;" class="card-text card-text-color">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                    <path
                                        d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353L4.54.146zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1H5.1z" />
                                    <path
                                        d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                                </svg>
                            </a>
                        </span>
                    @endif
                </h4>
                <br>
                <!-- Likes -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-heart-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                </svg> &nbsp; <span class="gap-after"> {{ $recipe->recipe_likes }} </span>

                <!-- Cooking time -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-clock-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                </svg> &nbsp;
                <span class="gap-after"> {{ $recipe->recipe_cooking_time }} Min.</span>

                <!-- Number of servings -->
                <img src="{{ asset('images/styling_images/servings.png') }}"> &nbsp;
                <span> {{ $recipe->recipe_servings }} Servings </span>
                <br><br>
                <div class="i-made-it-btn">
                    @if ($accessedBy == 'user')
                        @if (session()->has('user_login_data'))
                            <form method="get" action="{{ route('add_calories') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $recipe->recipe_id }}" name="recipe_id">
                                <button class="btn btn--pill btn-outline-primary btn-block" type="submit"
                                    title="Cook and eat the recipe and add recipe calories to the calorie tracker">Track
                                    Calories</button>
                            </form>
                        @else
                            <form method="get" action="{{ route('users/loginform') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <button class="btn btn--pill btn-outline-primary btn-block" type="submit"
                                    title="Cook and eat the recipe and add recipe calories to the calorie tracker">Track
                                    Calories</button>
                            </form>
                        @endif
                    @endif

                </div>
                <br><br>
                <video class="embed-responsive w-75" height="300px" controls crossorigin="anonymous">
                    <source src="{{ url('/videos/recipe_videos/' . $recipe->recipe_video) }}" type="video/mp4">
                </video>
            </div>
            <br>
            <hr><br>
            <div class="p-2">
                <div class="d-flex justify-content-center flex-wrap">
                    <div class="p-2">
                        <div class="nutrients">
                            <h5>Nutritional Information</h5>
                            <p class="text-center">(Per Serving)</p>

                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">Calories</th>
                                        <td style="text-align: right">{{ $recipe->recipe_calories }} cal</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Carbohydrates</th>
                                        <td style="text-align: right">{{ $recipe->recipe_carbs }} g</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Proteins</th>
                                        <td style="text-align: right">{{ $recipe->recipe_protien }} g</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Iron</th>
                                        <td style="text-align: right">{{ $recipe->recipe_iron }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Dietary Fiber</th>
                                        <td style="text-align: right">{{ $recipe->recipe_dietaryfiber }} g</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Sugar</th>
                                        <td style="text-align: right">{{ $recipe->recipe_sugar }} g</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Calcium</th>
                                        <td style="text-align: right">{{ $recipe->recipe_calcium }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Magnesium</th>
                                        <td style="text-align: right">{{ $recipe->recipe_magnesium }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Potassium</th>
                                        <td style="text-align: right">{{ $recipe->recipe_potassium }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Sodium</th>
                                        <td style="text-align: right">{{ $recipe->recipe_sodium }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Vitamin C</th>
                                        <td style="text-align: right">{{ $recipe->recipe_vitamin_c }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Vitamin D</th>
                                        <td style="text-align: right">{{ $recipe->recipe_vitamin_d }} µg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Vitamin B6</th>
                                        <td style="text-align: right">{{ $recipe->recipe_vitamin_b6 }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Vitamin B12</th>
                                        <td style="text-align: right">{{ $recipe->recipe_vitamin_b12 }} µg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Cholesterol</th>
                                        <td style="text-align: right">{{ $recipe->recipe_cholesterol }} mg</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Fats</th>
                                        <td style="text-align: right">{{ $recipe->recipe_fats }} g</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="p-2 w-50">
                        <h5 style="color: #3D251E; font-weight: bold;">Ingredients</h5>
                        <p class="ingrd-font">
                            {!! nl2br($recipe->recipe_ingredients) !!}
                        </p>
                        <br>
                        <h5 style="color: #3D251E; font-weight: bold">Instructions</h5>
                        <p class="ingrd-font">
                            @php
                                $instructions = $recipe->recipe_instructions;
                                $stepNumber = 1;

                                while (strpos($instructions, "Step $stepNumber.") !== false) {
                                    $instructions = str_replace("Step $stepNumber.", "<strong>Step $stepNumber.</strong>", $instructions);
                                    $stepNumber++;
                                }
                            @endphp

                            {!! nl2br(str_replace("\n", "\n\n", $instructions)) !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recipe View Code Block End -->

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

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>
</body>

</html>
