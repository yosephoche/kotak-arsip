<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>2 Langkah Verifikasi</title>

	<link rel="icon" sizes="16x16" href="{{ asset('assets/app/img/favicon.png') }}" />
	<link rel="stylesheet" href="{{ asset('assets/app/css/kotakarsip-lp.min.css') }}">
	<style>
		p {
			font-size: 14px;
			margin-bottom: 20px;
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
				<a class="nav-link btn-primary" href="{{ route('logout') }}">
					Login
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
						<h3>2 Langkah Verifikasi</h3>
						<p>Kami telah mengirimkan kode unik ke email Anda. Masukkan 4 digit kode unik otentikasi Anda</p>
						<form action="{{ route('twostepauth_update') }}" method="post">
							{{ csrf_field() }}
							@if ( Session::has('error') )
								<div class="alert-top alert alert-danger text-center">Kode yang Anda masukkan salah</div>
							@endif
							<div class="input-item mb-4">
								<input type="text" name="code" value="{{ old('code') }}" class="form-control" placeholder="4 kode unik" autocomplete="off">
							</div>

							<div class="display-inline">
								<button class="daftar">Konfirmasi</button>
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
				<div class="text-center">
					<small>© copyright 2018 KotakArsip</small>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip-lp.min.js') }}"></script>
</body>
</html>