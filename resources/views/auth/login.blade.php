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
		.alert-info-demo p {
			font-size: 12px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light">
		<a class="navbar-brand" href="https://www.kotakarsip.com/">
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
			<div class="container col-lg-4 col-md-5 col-sm-7">
				<div class="row">
					<div class="col-md-12">
						<h3>Login</h3>
						<form action="{{ route('login') }}" method="POST">
							{{ csrf_field() }}
							@if ( $errors->has('email') or $errors->has('password') )
								<div class="alert-top alert alert-danger text-center">Email atau kata sandi salah</div>
							@endif

							@if (isset($_GET['demo']))
								<div class="alert-top alert alert-info alert-info-demo">
									<p>Untuk mencoba akun demo, gunakan akun dibawah ini:</p>
									Email: <b>demo@kotakarsip.com</b><br>
									Password: <b>demokotakarsip</b>
								</div>
							@endif
							<div class="input-item mb-4">
								<input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Alamat Email">
							</div>
							<div class="input-item mb-4">
								<input type="password" name="password" class="form-control" placeholder="Kata Sandi">
							</div>
							<div class="form-group mb-4">
								<div class="checkbox">
									<input type="checkbox" id="rememberme" name="remember" {{ old('remember') ? 'checked' : '' }}>
									<label for="rememberme"><div class="choice-checkbox"></div> Tetap Masuk</label>
								</div>
							</div>
							<div class="display-inline">
								<button class="daftar">Masuk</button>
								<a href="{{ route('password.request') }}" class="forgot">Lupa kata sandi?</a>
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
</body>
</html>