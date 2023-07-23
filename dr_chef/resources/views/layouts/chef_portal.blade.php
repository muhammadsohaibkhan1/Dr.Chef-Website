<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Title Page-->
    @foreach ($profile as $chef)
        <title>{{ $chef->chef_full_name }}</title>
    @endforeach


    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">

    <!-- Font special for pages-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="images/favicon.png">

    {{-- Custom stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/chef_portal.css') }}" media="all">
</head>

<body>
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
    <br><br>

    <div class="d-flex flex-wrap main-div">
        <div class="col-12 col-md-3 p-2 d-flex align-items-center justify-content-md-end justify-content-start">
            <div>
                @foreach ($profile as $d)
                    <img src="{{ url('/images/chef_profile_images/' . $d->chef_profile_pic) }}" alt="profile picture"
                        class="img-fluid profile-image">
                @endforeach
            </div>
        </div>
        <div class="col-12 col-md-9 p-2">
            <div class="profile-info">
                @foreach ($profile as $d)
                    <h4 class="card-text-color mb-1 chef-full-name">{{ $d->chef_full_name }}</h4>
                    <h6 class="card-text-color mb-3">{{ $d->chef_username }}</h6>
                    <form action="">
                        <button type="submit" class="like-chef-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-heart-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                            </svg>
                        </button>
                        <span> {{ $d->chef_likes }} </span>
                    </form>
                @endforeach
                <br>
                <a href="{{ route('add_recipes_form') }}" class="add-recipe-btn">Add Recipe</a>
            </div>
        </div>
        <div class="col-12 p-2">
            <hr>
            <div class="recipes-bg">
                <!-- Grid of Recipes Code Start -->
                <div class="container">
                    <div class="row row-cols-auto" style="row-gap: 25px;">
                        @foreach ($recipes as $d)
                            <div class="col-md-4">
                                <div class="card" style="height: 390px">
                                    <img src="{{ url('/images/recipe_images/' . $d->recipe_image) }}"
                                        class="card-img-top" alt="" height="210px"
                                        style="object-fit: cover;">
                                    <div class="card-body">
                                        <a
                                            href="{{ route('recipe/display/detail', ['id' => $d->recipe_id, 'accessedBy' => 'chef']) }}">
                                            <h6 style="font-weight: bold" class="card-title card-text-color">
                                                {{ $d->recipe_name }}</h6>
                                        </a>
                                        <p class="card-text card-text-color">{{ $d->recipe_calories }} Calories</p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="footer-buttons" style="float: left;">

                                            <button type="submit"
                                                style="outline: none; background:none; border:none">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                                </svg>
                                            </button>
                                            <span>{{ $d->recipe_likes }}</span>
                                        </div>
                                        <div class="footer-buttons-right"
                                            style="display: inline-block; float: right;">
                                            <a href="{{ url('edit/recipe', $d->recipe_id) }}" class="edit-button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                </svg>
                                            </a>
                                            <a href="{{ url('deleteRecipe', $d->recipe_id) }}" class="delete-button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash" style="color: #ba0e02"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                    <path fill-rule="evenodd"
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</body>

</html>
