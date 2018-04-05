<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" sizes="16x16" href="{{ asset('assets/app/img/favicon.png') }}" />
	<title>404</title>

	<!-- inject:css -->
	<link rel="stylesheet" href="{{ asset('assets/app/css/kotakarsip-lp.min.css') }}">
	<!-- endinject -->
</head>
<body class="not-found">
	<nav class="navbar navbar-expand-lg navbar-light" style="border-bottom: 1px solid #eee">
		<a class="navbar-brand m-auto" href="{{ URL::previous() }}">
			<div class="row align-items-center">
				<img src="{{ asset('assets/app/img/logo.png') }}" alt="Logo KotakArsip">
				<h3>KotakArsip</h3>
			</div>
		</a>
	</nav>

	<div class="main">
		<div class="form-wrapper">
			<div class="bullet nb-1"></div>
			<div class="bullet nb-2"></div>
			<div class="container col-lg-4 col-md-5 col-sm-8 text-center">
				<div class="row align-items-center">
					<div class="col-12">
						<h4 style="font-size: 120px; color: #122b53">404</h4>
						<p>Halaman Tidak Ditemukan</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="globalfooter" class="small">
		<div class="container">
			<div class="gf-footer">
				<div class="row">
					<div class="col-md-12 text-center">
						© copyright 2018 KotakArsip
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>