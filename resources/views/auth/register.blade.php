<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Daftar Kotakarsip</title>

	<link rel="icon" sizes="16x16" href="{{ asset('assets/app/img/favicon.png') }}" />
	<link rel="stylesheet" href="{{ asset('assets/app/css/kotakarsip-lp.min.css') }}">

	<style>
		input {
			-webkit-appearance: none; -moz-appearance: none; appearance: none;
		}
		.help-block {
			font-size: 12px;
			display: block;
			margin-top: 10px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light">
		<a class="navbar-brand" href="{{ route('login') }}">
			<div class="row align-items-center">
				<img src="{{ asset('assets/app/img/logo.png') }}" alt="Logo KotakArsip">
				<h3>KotakArsip</h3>
			</div>
		</a>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link btn-primary" href="{{ route('login') }}">
					Login
				</a>
			</li>
		</ul>
	</nav>

	<div class="main">
		<div class="form-wrapper">
			<div class="bullet nb-1"></div>
			<div class="bullet nb-2"></div>
			<div class="container col-lg-4 col-md-5 col-sm-7">
				<div class="row">
					<div class="col-md-12">
						<h3>Daftar</h3>
						<form method="POST" action="{{ route('register') }}">
							{{ csrf_field() }}
							<input type="hidden" name="email_status" value="pending">

							<div class="input-item mb-4">
								<input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nama Lengkap" required>
								@if ($errors->has('name'))
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
							</div>

							<div class="input-item mb-4">
								<input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Alamat Email" required>
								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>

							<div class="mb-4 password">
								<div class="input-group">
									<input type="password" name="password" class="form-control" placeholder="Kata Sandi" aria-label="Recipient's username" aria-describedby="basic-addon2">
									<div class="input-group-append">
										<button class="btn" type="button"><span class="fas fa-eye"></span></button>
									</div>
								</div>
								@if ($errors->has('password'))
									<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
							</div>

							<div class="input-item mb-4">
								<div id="capcha_ka"></div>
							</div>

							<div class="display-inline">
								<button class="daftar">Daftar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="globalfooter" class="small">
		<div class="container">
			<div class="gf-footer">
				<div class="row justify-content-between">
					<div class="col-12 text-center">
						<small>© copyright 2018 KotakArsip</small>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip-lp.min.js') }}"></script>
	<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=id"
		async defer>
	</script>

	<script type="text/javascript">
		var onloadCallback = function() {
			grecaptcha.render('capcha_ka', {
				'sitekey' : '6LdaQU0UAAAAAK8qaDGRfvvY1SNlA3lNds_3T0Tj',
				'theme' : 'light'
			});
		};
		$('form').submit(function(e) {
			if (grecaptcha.getResponse() == ""){
				e.preventDefault();
				alert("Anda belum mencentang tombol capcha!");
			}
		});
	</script>
</body>
</html>