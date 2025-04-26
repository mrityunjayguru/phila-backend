<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Recover Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content=""/>
    <meta name="msapplication-tap-highlight" content="no">
	<link rel="stylesheet" href="{{asset('authAssets/main.d810cf0ae7f39f28f336.css')}}">
	<link rel="stylesheet" href="{{asset('authAssets/custom.css')}}" />
	<script>var token = '{{ csrf_token() }}'; </script>
	<style>.slick-slider .slide-img-bg{opacity: 0.9;}</style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="loginbox">
                <div class="row h-100 m-0">
                    <div class="col-12 col-md-4 p-0">
                        <div class="text-center auth-logo">
							<img src="@if(Settings::get('logo')){{ asset(Settings::get('logo')) }} @endif">
						</div>
						<!--<div class="slider-light">
							<div class="slick-slider">
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center" tabindex="-1">
                                        <div class="slide-img-bg" style="background-image: url('{{asset('themeAssets/images/auth/login-slide-2.jpg')}}');"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center" tabindex="-2">
                                        <div class="slide-img-bg" style="background-image: url('{{asset('themeAssets/images/auth/login-slide-2.jpg')}}');"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center" tabindex="-3">
                                        <div class="slide-img-bg" style="background-image: url('{{asset('themeAssets/images/auth/login-slide-3.jpg')}}');"></div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="d-flex bg-white justify-content-center align-items-center col-12 col-md-8 p-0">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div class="app-logo"></div>
							<h4 class="mb-0 login-title">
                                <span>Recover Your Password.</span>
                            </h4>
                            <!--<h6 class="mt-3">No account? <a href="{{ url('register') }}" class="text-theme text-primary">Sign up now</a></h6>-->
                            <span class="title-line"></span>
                            <div>
                                <form id="recover-password" method="POST" action="javascript:void(0);" onsubmit="recoverPassword()">
									<div class="form-row ai-signin">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <input id="exampleEmail" placeholder="Enter Email Address" type="email" class="form-control" required>
												<div class="validation-div" id="val-email"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group">
                                        <button class="btn-theme btn btn-primary btn-lg btn-theme">Get a Code</button>
                                    </div>
									<div class="position-relative form-group">
                                        <a href="{{route('login')}}" class="text-theme">Back to login page</a>
                                    </div>
                                </form>
								<form id="resrt-password" style="display:none;" method="POST" action="javascript:void(0);" onsubmit="resetPassword()">
									<div class="form-row ai-signin">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <input id="exampleOTP" placeholder="Enter OTP" type="number" class="form-control" required>
												<div class="validation-div" id="val-exampleOTP"></div>
                                            </div>
											<div class="position-relative form-group">
                                                <input id="examplePassword" placeholder="Enter New Password" type="password" class="form-control" required>
												<div class="validation-div" id="val-examplePassword"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group">
                                        <button class="btn-theme btn btn-primary btn-lg btn-theme">Reset Now</button>
                                    </div>
									<div class="position-relative form-group">
                                        <a class="text-theme" href="javascript:void(0);" onclick="recoverPassword()" >Resend Code</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script src="{{asset('authAssets/main.d810cf0ae7f39f28f336.js') }}"></script>
	<script src="{{asset('authAssets/jquery.min.js')}}"></script>
	<!-- Sweetalert -->
	<script src="{{ asset('/authAssets/sweetalert/sweetalert2.js') }}"></script>
	<script>
		var lgnurl 	= '{{route("login")}}';
		var rcrpswd  = '{{route("ajax.recover.password")}}';
	</script>
    <script src="{{asset('authAssets/custom.js')}}"></script>
</body>
</html>