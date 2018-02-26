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
				<img src="{{ asset('assets/app/img/logo.png') }}" class="logo" alt="Logo KotakArsip"> &nbsp;&nbsp;<b>KOTAK<span>ARSIP</span></b>
			</div>
		</nav>

		<div class="ka-body" style="padding-top: 50px">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-4 col-md-4 text-center">
						<img src="{{ asset('assets/app/img/company.png') }}" alt="" width="80%" style="margin-top: 60px">
					</div>
					<div class="col-md-12 text-center">
						<h1>Selamat, Perusahaan Anda berhasil terdaftar</h1>
						<p>Berikut kode perusahaan Anda</p>
						<h1 class="no-margin"><a href="" class="color-primary">{{ $company->code }}</a></h1>
						<br>
						<a href="{{ route('storage_register') }}" class="btn btn-primary">Lanjutkan &nbsp;&nbsp;<i class="fa fa-angle-double-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>

</body>

</html>