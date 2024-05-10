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
	<title>Reset Password</title>
</head>

<body>
	<!-- wrapper -->
	<div class="wrapper">
		<div class="authentication-reset-password d-flex align-items-center justify-content-center">
			<div class="row">
				<div class="col-12 col-lg-10 mx-auto">

				<form action="{{ route('admin.reset.post', ['token' => $token]) }}" method="post">
                @csrf
                <div class="card">
						<div class="row g-0">
							<div class="col-lg-5 border-end">
								<div class="card-body">
									<div class="p-5">
										<div class="text-start">
											<img src="{{asset('adminbackend/assets/images/logo-img.png')}}" width="180" alt="">
										</div>
										<h4 class="mt-5 font-weight-bold">Genrate New Password</h4>
										<p class="text-muted">We received your reset password request. Please enter your new password!</p>
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
										<div class="mb-3 mt-5">

											<label class="form-label">New Password</label>
											<input type="text" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Enter new password" />
										</div>
										<div class="mb-3">
											<label class="form-label">Confirm Password</label>
											<input type="text" id="confirm_password" name="confirm_password" class="form-control form-control-lg @error('confirm_password') is-invalid @enderror" placeholder="Confirm password" />
										</div>
										<div class="d-grid gap-2">
											<button type="submit" class="btn btn-primary">Change Password</button> <a href="{{ route('admin.login') }}" class="btn btn-light"><i class='bx bx-arrow-back mr-1'></i>Back to Login</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-7">
								<img src="{{asset('adminbackend/assets/images/login-images/forgot-password-frent-img.jpg')}}" class="card-img login-img h-100" alt="...">
							</div>
						</div>
					</div>
                </form>
				</div>
			</div>
		</div>
	</div>
	<!-- end wrapper -->
</body>

</html>