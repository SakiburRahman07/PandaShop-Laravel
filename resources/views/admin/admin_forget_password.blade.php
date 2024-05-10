<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('adminbackend/assets/images/favicon-32x32.png') }}" type="image/png" />

    <!-- CSS Dependencies -->
    <link href="{{ asset('adminbackend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('adminbackend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('adminbackend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('adminbackend/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('adminbackend/assets/js/pace.min.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('adminbackend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminbackend/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('adminbackend/assets/css/icons.css') }}" rel="stylesheet">
</head>

<body class="bg-forgot">

    <!-- Forgot Password Form -->
    <form action="{{ route('admin.forgot') }}" method="post">
        @csrf

        <div class="wrapper">
            <div class="authentication-forgot d-flex align-items-center justify-content-center">
                <div class="card forgot-box">
                    <div class="card-body">
                        <div class="p-4 rounded border">
                            <div class="text-center">
                                <img src="{{ asset('adminbackend/assets/images/icons/forgot-2.png') }}" width="120" alt="Forgot Password" />
                            </div>
                            <h4 class="mt-5 font-weight-bold">Forgot Password?</h4>
                            <p class="text-muted">Enter your registered email ID to reset your password.</p>

                            <!-- Display success and not found messages -->
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @elseif (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Email input -->
                            <div class="my-4">
                                <label class="form-label">Email ID</label>
                                <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="example@user.com" value="{{ old('email') }}" required />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Send</button>
                                <a href="{{ route('admin.login') }}" class="btn btn-light btn-lg">
                                    <i class='bx bx-arrow-back me-1'></i> Back to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</body>

</html>
