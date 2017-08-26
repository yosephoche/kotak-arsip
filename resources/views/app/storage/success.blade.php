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
			<div class="brand brand-center">
				<img src="{{ asset('assets/app/img/logo.svg') }}" class="logo" alt="Logo KotakArsip"> &nbsp;&nbsp;<b>KOTAK<span>ARSIP</span></b>
			</div>
		</nav>

		<div class="ka-body" style="padding-top: 50px">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-4 col-md-4 text-center">
						<img src="{{ asset('assets/app/img/icons/filling_cabinet.svg') }}" alt="" width="60%" style="margin-top: 60px">
					</div>
					<div class="col-md-12 text-center">
						<h1>Penyimpanan Arsip berhasil ditambahkan</h1>
						<p>Apakah Anda masih memiliki media penyimpanan lainnya? <a href="{{ route('storage_register') }}">Klik disini</a> jika masih ada</p>
						<br>
						<a href="{{ route('dashboard') }}" class="btn btn-primary">Lanjutkan &nbsp;&nbsp;<i class="fa fa-angle-double-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>

</body>

</html>