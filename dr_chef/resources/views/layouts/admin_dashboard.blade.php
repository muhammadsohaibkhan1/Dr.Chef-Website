<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bi Icons Link  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">


    {{-- Lato Font CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}" media="all">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body>
    <!-- Side-Nav -->
    <div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar"
        style="width: 200px">
        <ul class="nav flex-column text-white w-100">
            <div style="height: 35px"></div>
            <div class="d-flex align-items-center p-3" style="border-block-end: 1px solid #e0cdc8">
                <img src="{{ asset('images/admin_images/sohaib.jpg') }}" alt="Admin profile picture"
                    class="rounded-circle me-3" width="50" height="50">
                <div>
                    <div class="fw-bold" style="color: #e0cdc8">Muhammad Sohaib Khan</div>
                </div>
            </div>
            <br>
            <li href="#" class="nav-link">
                <span class="mx-2">Home</span>
            </li>
            <li href="#" class="nav-link">
                <span class="mx-2" id="click-dttn-req">Dietitians Requests</span>
            </li>
            <li href="#" class="nav-link">
                <span class="mx-2" id="click-reported-recipes">Reported Recipes</span>
            </li>
            <li href="#" class="nav-link">
                <span class="mx-2" id="click-reported-diet-plans">Reported Diet Plans</span>
            </li>
            <li href="#" class="nav-link">
                <span class="mx-2">Chefs</span>
            </li>
            <li href="#" class="nav-link">
                <span class="mx-2">Dietitians</span>
            </li>
        </ul>
        <span href="#" class="nav-link w-100 mb-5 text-center">
            <a href="{{ route('admin/logout') }}" class="logout">Logout</a>
        </span>
    </div>

    <!-- Main Wrapper -->
    <div class="p-1 my-container active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light px-5">
            <a class="btn border-0" id="menu-btn"><i class="bx bx-menu"></i></a>
        </nav>
        <!--End Top Nav -->

        {{-- Home Section --}}
        <div id="home" style="padding: 10px 10% 10px 10%">
            <h4 class="fw-bold text-center">Home</h4>
            <br><br><br><br>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">Dietitians Requests</h5>
                                <br>
                                <div class="d-flex justify-content-center align-items-center">
                                    <h1 class="display-1">{{ $dietitianRequestsCount }}</h1>
                                </div>
                                <br>
                                <button class="btn btn-block mt-auto" onclick="clickDietitianReq()">Check</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">Reported Recipes</h5>
                                <br>
                                <div class="d-flex justify-content-center align-items-center">
                                    <h1 class="display-1">{{ $reportedRecipesCount }}</h1>
                                </div>
                                <br>
                                <button class="btn btn-block mt-auto" onclick="clickReportedRecipes()">Check</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">Reported Diet Plans</h5>
                                <br>
                                <div class="d-flex justify-content-center align-items-center">
                                    <h1 class="display-1">{{ $reportedDietPlansCount }}</h1>
                                </div>
                                <br>
                                <button class="btn btn-block mt-auto" onclick="clickReportedDietPlans()">Check</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- DIETITIANS REQUESTS SECTION --}}
        <div id="dietitians-req" style="padding: 10px 10% 10px 10%">
            <h4 class="fw-bold text-center">Dietitians Requests</h4><br>
            @if (count($dietitianRequests) == 0)
                <p>No dietitian request.</p>
            @endif
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($dietitianRequests as $r)
                        <div class="col">
                            <div class="card h-100">
                                <img style="height: 300px;"
                                    src="{{ url('/images/dietitian_certificates/' . $r->dietitian_certificate) }}"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $r->dietitian_full_name }}</h5>
                                    <p class="card-text"><span>@</span>{{ $r->dietitian_username }}</p>
                                </div>
                                <div class="card-footer d-flex justify-content-end align-items-end">
                                    <a class="bi bi-check-circle-fill text-success me-2 fs-3"
                                        href="{{ route('approve/dietitian', $r->dietitian_id) }}"></a>
                                    <a class="bi bi-x-circle-fill text-danger fs-3"
                                        href="{{ route('disapprove/dietitian', $r->dietitian_id) }}"></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- REPORTED RECIPES SECTION --}}
        <div id="reported-recipes" style="padding: 10px 10% 10px 10%">
            <h4 class="fw-bold text-center">Reported Recipes</h4><br>
            @if (count($reportedRecipes) == 0)
                <p>No reported recipes.</p>
            @endif
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($reportedRecipes as $recipe)
                        <div class="col">
                            <div class="card h-80" style="height: 450px">
                                <img style="height: 260px;"
                                    src="{{ url('/images/recipe_images/' . $recipe->recipe_image) }}"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><a class="name-text"
                                            href="{{ route('recipe/display/detail', ['id' => $recipe->recipe_id, 'accessedBy' => 'admin']) }}">{{ $recipe->recipe_name }}</a>
                                    </h5>
                                    <p class="card-text"> <span class="fw-bold">By Chef</span> <a class="name-text"
                                            href="{{ route('view/chef/profile', ['id' => $recipe->chef_id, 'accessedBy' => 'admin']) }}"
                                            class="chef-name">{{ $recipe->chef_username }}</a></p>
                                </div>
                                <div class="card-footer d-flex justify-content-end align-items-end">
                                    <a href="{{ url('approveRecipe', $recipe->recipe_id) }}"><i
                                            class="bi bi-check-circle-fill text-success me-2 fs-3"></i></a>
                                    <a href="{{ url('delRecipe', $recipe->recipe_id) }}"><i
                                            class="bi bi-x-circle-fill text-danger fs-3"></i></a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- REPORTED DIET PLANS SECTION --}}
        <div id="reported-diet-plans" style="padding: 10px 10% 10px 10%">
            <h4 class="fw-bold text-center">Reported Diet Plans</h4><br>
            @if (count($reportedDietPlans) == 0)
                <p>No reported diet plans.</p>
            @endif
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($reportedDietPlans as $diet_plan)
                        <div class="col">
                            <div class="card card-margin dp-card">
                                <br>
                                <div class="card-body pt-0">
                                    <div class="widget-49">
                                        <div class="widget-49-title-wrapper">
                                            <div class="widget-49-date-primary">
                                                <span
                                                    class="widget-49-date-day">{{ $diet_plan->diet_plan_duration }}</span>
                                                <span class="widget-49-date-month">days</span>
                                            </div>
                                            <div class="widget-49-meeting-info">
                                                <span style="font-weight: bold" class="widget-49-pro-title"
                                                    style="text-transform: uppercase">{{ $diet_plan->diet_plan_weight_goal }}
                                                    KG
                                                    &nbsp; {{ $diet_plan->diet_plan_type }}</span>
                                                <p><span style="font-weight: bold"
                                                        class="widget-49-meeting-time">Shared
                                                        by </span>
                                                    <a href="{{ route('view/dietitian/profile', ['id' => $diet_plan->dietitian_id, 'accessedBy' => 'admin']) }}"
                                                        style="color: #3d251e">{{ $diet_plan->dietitian_username }}</a>
                                                </p>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <div>
                                                <a target="_blank"
                                                    href="{{ route('display_diet_plans_pdf', $diet_plan->diet_plan_id) }}""
                                                    class="card-text card-text-color name-text">VIEW</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('approveDietPlan', $diet_plan->diet_plan_id) }}"><i
                                                        class="bi bi-check-circle-fill text-success me-2 fs-3"></i></a>
                                                <a href="{{ url('delDietPlan', $diet_plan->diet_plan_id) }}"><i
                                                        class="bi bi-x-circle-fill text-danger fs-3"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- CHEFS SECTION --}}
        <div id="chefs" style="padding: 10px 10% 10px 10%">
            <h4 class="fw-bold text-center">Chefs</h4><br>
            <div class="make-hscroll">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Picture</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Likes</th>
                            <th scope="col">Email</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @foreach ($chefs as $chef)
                            <tr>
                                <th class="align-items-center" scope="row">{{ $count++ }}</th>
                                <td><img src="{{ url('/images/chef_profile_images/' . $chef->chef_profile_pic) }}"
                                        height="60px" width="60px" style="object-fit: cover;" alt="Profile Pic">
                                </td>
                                <td><a style="text-decoration: none; color:black" href="{{ route('view/chef/profile', ['id' => $chef->chef_id, 'accessedBy'=>"admin"]) }}">
                                    {{ $chef->chef_full_name }}</a></td>
                                <td>{{ $chef->chef_username }}</td>
                                <td>{{ $chef->chef_likes }}</td>
                                <td>{{ $chef->chef_email }}</td>
                                <td> <a href="{{ url('delete/chef', $chef->chef_id) }}" class="delete-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-trash" style="color: #ba0e02"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                            <path fill-rule="evenodd"
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                        </svg>
                                    </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DIETITIANS SECTION --}}
        <div id="dietitians" style="padding: 10px 10% 10px 10%">
            <h4 class="fw-bold text-center">Dietitians</h4><br>
            <div class="make-hscroll">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Picture</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Likes</th>
                            <th scope="col">WhatsApp</th>
                            <th scope="col">Email</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @foreach ($dietitians as $dietitian)
                            <tr>
                                <th class="align-items-center" scope="row">{{ $count++ }}</th>
                                <td><img src="{{ url('/images/dietitian_profile_images/' . $dietitian->dietitian_profile_pic) }}"
                                        height="60px" width="60px" style="object-fit: cover;" alt="Profile Pic">
                                </td>
                                <td><a style="text-decoration: none; color:black" href="{{ route('view/dietitian/profile', ['id'=>$dietitian->dietitian_id, 'accessedBy'=>"admin"]) }}">
                                    {{ $dietitian->dietitian_full_name }}</a></td>
                                <td>{{ $dietitian->dietitian_username }}</td>
                                <td>{{ $dietitian->dietitian_likes }}</td>
                                <td>{{ $dietitian->dietitian_phone_number }}</td>
                                <td>{{ $dietitian->dietitian_email }}</td>
                                <td> <a href="{{ url('delete/dietitian', $dietitian->dietitian_id) }}"
                                        class="delete-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-trash" style="color: #ba0e02"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                            <path fill-rule="evenodd"
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                        </svg>
                                    </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <script>
            // To hide and show sidebar
            var menu_btn = document.querySelector("#menu-btn");
            var sidebar = document.querySelector("#sidebar");
            var container = document.querySelector(".my-container");
            menu_btn.addEventListener("click", () => {
                sidebar.classList.toggle("active-nav");
                container.classList.toggle("active-cont");
            });

            // To display section according to the option selected
            $(document).ready(function() {
                        var activeTab = "{{ session('activeTab') }}";

                        // show home page by default on page load
                        $("#home").show();
                        $("#dietitians-req").hide();
                        $("#reported-recipes").hide();
                        $("#reported-diet-plans").hide();
                        $("#chefs").hide(); $("#dietitians").hide();

                            if (activeTab == "dttn-approved" || activeTab == "dttn-disapproved") {
                                $("#home").hide();
                                $("#dietitians-req").show();
                                $("#reported-recipes").hide();
                                $("#reported-diet-plans").hide()
                                $("#chefs").hide();
                                $("#dietitians").hide();
                                $(".mx-2:contains('Dietitians Requests')").addClass("active");
                                $(".mx-2:contains('Reported Recipes')").removeClass("active");
                                $(".mx-2:contains('Reported Diet Plans')").removeClass("active");
                                $(".mx-2:contains('Chefs')").removeClass("active");
                                $(".mx-2:contains('Dietitians')").removeClass("active");
                            }

                            if (activeTab == "recipe-approved" || activeTab == "recipe-deleted") {
                                $("#home").hide();
                                $("#dietitians-req").hide();
                                $("#reported-recipes").show();
                                $("#reported-diet-plans").hide()
                                $("#chefs").hide();
                                $("#dietitians").hide();
                                $(".mx-2:contains('Dietitians Requests')").removeClass("active");
                                $(".mx-2:contains('Reported Recipes')").addClass("active");
                                $(".mx-2:contains('Reported Diet Plans')").removeClass("active");
                                $(".mx-2:contains('Chefs')").removeClass("active");
                                $(".mx-2:contains('Dietitians')").removeClass("active");
                            }

                            if (activeTab == "diet-plan-approved" || activeTab == "diet-plan-deleted") {
                                $("#home").hide();
                                $("#dietitians-req").hide();
                                $("#reported-recipes").hide();
                                $("#reported-diet-plans").show()
                                $("#chefs").hide();
                                $("#dietitians").hide();
                                $(".mx-2:contains('Dietitians Requests')").removeClass("active");
                                $(".mx-2:contains('Reported Recipes')").removeClass("active");
                                $(".mx-2:contains('Reported Diet Plans')").addClass("active");
                                $(".mx-2:contains('Chefs')").removeClass("active");
                                $(".mx-2:contains('Dietitians')").removeClass("active");
                            }

                            if (activeTab == "chef-deleted") {
                                $("#home").hide();
                                $("#dietitians-req").hide();
                                $("#reported-recipes").hide();
                                $("#reported-diet-plans").hide()
                                $("#chefs").show();
                                $("#dietitians").hide();
                                $(".mx-2:contains('Dietitians Requests')").removeClass("active");
                                $(".mx-2:contains('Reported Recipes')").removeClass("active");
                                $(".mx-2:contains('Reported Diet Plans')").removeClass("active");
                                $(".mx-2:contains('Chefs')").addClass("active");
                                $(".mx-2:contains('Dietitians')").removeClass("active");
                            }

                            if (activeTab == "dietitian-deleted") {
                                $("#home").hide();
                                $("#dietitians-req").hide();
                                $("#reported-recipes").hide();
                                $("#reported-diet-plans").hide()
                                $("#chefs").hide();
                                $("#dietitians").show();
                                $(".mx-2:contains('Dietitians Requests')").removeClass("active");
                                $(".mx-2:contains('Reported Recipes')").removeClass("active");
                                $(".mx-2:contains('Reported Diet Plans')").removeClass("active");
                                $(".mx-2:contains('Chefs')").removeClass("active");
                                $(".mx-2:contains('Dietitians')").addClass("active");
                            }

                            $(".mx-2").click(function() {
                                // show corresponding div based on clicked link
                                if ($(this).text() == "Home") {
                                    $("#home").show();
                                    $("#dietitians-req").hide();
                                    $("#reported-recipes").hide();
                                    $("#reported-diet-plans").hide();
                                    $("#chefs").hide();
                                    $("#dietitians").hide();
                                } else if ($(this).text() == "Dietitians Requests") {
                                    $("#home").hide();
                                    $("#dietitians-req").show();
                                    $("#reported-recipes").hide();
                                    $("#reported-diet-plans").hide();
                                    $("#chefs").hide();
                                    $("#dietitians").hide();
                                } else if ($(this).text() == "Reported Recipes") {
                                    $("#home").hide();
                                    $("#dietitians-req").hide();
                                    $("#reported-recipes").show();
                                    $("#reported-diet-plans").hide();
                                    $("#chefs").hide();
                                    $("#dietitians").hide();
                                } else if ($(this).text() == "Reported Diet Plans") {
                                    $("#home").hide();
                                    $("#dietitians-req").hide();
                                    $("#reported-recipes").hide();
                                    $("#reported-diet-plans").show();
                                    $("#chefs").hide();
                                    $("#dietitians").hide();
                                } else if ($(this).text() == "Chefs") {
                                    $("#home").hide();
                                    $("#dietitians-req").hide();
                                    $("#reported-recipes").hide();
                                    $("#reported-diet-plans").hide();
                                    $("#chefs").show();
                                    $("#dietitians").hide();
                                } else if ($(this).text() == "Dietitians") {
                                    $("#home").hide();
                                    $("#dietitians-req").hide();
                                    $("#reported-recipes").hide();
                                    $("#reported-diet-plans").hide();
                                    $("#chefs").hide();
                                    $("#dietitians").show();
                                }
                            });
                        });

                    $(document).ready(function() {
                        $("img").click(function() {
                            this.requestFullscreen()
                        })
                    });

                    function clickDietitianReq() {
                        document.getElementById("click-dttn-req").click();
                    }

                    function clickReportedRecipes() {
                        document.getElementById("click-reported-recipes").click();
                    }

                    function clickReportedDietPlans() {
                        document.getElementById("click-reported-diet-plans").click();
                    }
        </script>

</body>

</html>
