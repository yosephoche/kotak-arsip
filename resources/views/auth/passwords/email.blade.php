<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Kotakarsip</title>

	@include('app.layouts.partial.style')

</head>

<body class="page-login">
	<div id="app">
		<nav class="ka-nav ka-nav-detail">
			<ul class="left-side">
				<a href="/">
					<li class="brand">
						<img src="{{ asset('assets/app/img/logo.png') }}" class="logo" alt="Logo KotakArsip"> &nbsp;&nbsp;<b class="view-tablet-only">KOTAK<span>ARSIP</span></b>
					</li>
				</a>
			</ul>
			<ul class="right-side">
				<li>
					<a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
				</li>
			</ul>
		</nav>

		<div class="ka-body">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-3 view-tablet-only">
						<img src="{{ asset('assets/app/img/login.png') }}" alt="" width="98%" style="margin-top: 60px">
					</div>
					<div class="col-md-4">
						<h1>Lupa Kata Sandi?</h1>
						
						<form method="POST" action="{{ route('password.email') }}">
							{{ csrf_field() }}

							@if (session('status'))
								<div class="alert alert-success">
									{{ session('status') }}
								</div>
							@endif
							<div class="form-group">
								<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Alamat Email" required>

								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>
						
							<div class="form-group">
								<hr>
								<button class="btn btn-primary">Atur Ulang Kata Sandi</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	 @include('app.layouts.partial.script')

</body>

</html>
