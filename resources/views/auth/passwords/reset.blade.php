<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Login Kotakarsip</title>

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
		.main {
			min-height: calc(100vh - 100px);
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
				<a class="nav-link btn-primary" href="{{ route('register') }}">
					Daftar
				</a>
			</li>
		</ul>
	</nav>

	<div class="main">
		<div class="form-wrapper">
			<div class="bullet nb-1"></div>
			<div class="bullet nb-2"></div>
			<div class="container col-lg-4 col-md-5 col-sm-8">
				<div class="row">
					<div class="col-md-12">
						<h3>Ganti Kata Sandi</h3>
						<form method="POST" action="{{ route('password.request') }}">
							{{ csrf_field() }}
							<input type="hidden" name="token" value="{{ $token }}">
							
							@if (session('status'))
								<div class="alert alert-success">
									{{ session('status') }}
								</div>
							@endif

							<div class="input-item mb-4">
								<input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="Alamat Email" required autofocus>

								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>

							<div class="input-item mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
								<input id="password" type="password" class="form-control" name="password" placeholder="Kata Sandi Baru" required>

								@if ($errors->has('password'))
									<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
							</div>

							<div class="input-item mb-4{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required>

								@if ($errors->has('password_confirmation'))
									<span class="help-block">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
								@endif
							</div>

							<div class="display-inline">
								<button class="daftar">Ganti Kata Sandi</button>
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
					<div class="left">
						<small>© copyright 2018 KotakArsip</small>
					</div>
					<div class="right">
						<ul class="row justify-content-between">
							<li>Ikuti kami</li>
							<li><a href="#"><img src="" alt=""><span class="fab fa-facebook-square"></span></a></li>
							<li><a href="#"><img src="" alt=""><span class="fab fa-instagram"></span></a></li>
							<li><a href="#"><img src="" alt=""><span class="fab fa-linkedin"></span></a></li>
							<li><a href="#"><img src="" alt=""><span class="fab fa-youtube"></span></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip-lp.min.js') }}"></script>
</body>
</html>
