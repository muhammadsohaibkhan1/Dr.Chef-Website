<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0/css/bootstrap.min.css">

    {{-- Lato Font CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin_login.css') }}" media="all">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="images/favicon.png">

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form method="POST" action="admin_login" class="d-flex flex-column align-items-center login-form" enctype="multipart/form-data">
                    @csrf
                    <h3 class="text-center mb-4">Admin Login</h3>
                    <div class="mb-3">
                        <label for="email">Email</label><br>
                        <input type="text" class="input-field" id="email" name="admin_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label><br>
                        <input type="password" class="input-field" id="password" name="admin_password" required>
                    </div>
                    @if (session('invalid-alert'))
                        <p class="text-danger" style="width: 260px; text-align:center">
                            {{ session('invalid-alert') }}
                        </p>
                    @endif
                    <button type="submit" class="btn btn-login mt-3">Login</button>
                </form>
            </div>

        </div>
    </div>

</body>

</html>
