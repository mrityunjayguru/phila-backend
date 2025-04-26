<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Track Bus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content=""/>
    <meta name="msapplication-tap-highlight" content="no">
	<link rel="stylesheet" href="{{asset('authAssets/main.d810cf0ae7f39f28f336.css')}}">
	<link rel="stylesheet" href="{{asset('authAssets/custom.css')}}" />
	<script>var token = '{{ csrf_token() }}'; </script>
	<style>
	.trckbus h4.login-title {color: #1e1c1d;}
	h4.login-title label{color: #CD001C;}
	</style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="trckbus">
                <div class="row h-100 m-0">
                    <div class="col-12 col-md-4 p-0">
                        <div class="text-center auth-logo">
							<img src="{{asset('default/Track-Bus.svg')}}">
						</div>
                    </div>
                    <div class="d-flex bg-white justify-content-center align-items-center col-12 col-md-8 p-0">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div class="app-logo"></div>
							<h4 class="mb-0 login-title">
                                <span>ENTER THE <label>CODE</label> TO TRACK YOUR BUS</span>
                            </h4>
                            <span class="title-line"></span>
                            <div>
                                <form method="POST" action="javascript:void(0);" onsubmit="ticketValidate()">
									<div class="form-row ai-signin">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <input id="code" placeholder="Enter here" type="text" class="form-control" required>
												<div class="validation-div" id="val-code"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="trck-btn">
                                            <button class="btn-theme btn btn-primary btn-lg btn-theme">TRACK</button>
                                        </div>
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
	<script src="{{asset('authAssets/custom.js')}}"></script>
	<!-- Sweetalert -->
	<script src="{{ asset('/authAssets/sweetalert/sweetalert2.js') }}"></script>
	
	<script>
		function ticketValidate() {
			var data = new FormData();
			data.append('code', $('#code').val());
			var response = runAjax("{{route('ajax.code.validate')}}", data);
			if(response.status == '200'){
				window.location.href = "{{url('track')}}/" + $('#code').val();
			} else if (response.status == '422') {
				$('.validation-div').text('');
				$.each(response.error, function(index, value) {
					$('#val-' + index).text(value);
				});

			} else if (response.status == '201') {
				swal.fire({title: response.message,type: 'error'});
			}
			
		}
	</script>
</body>
</html>