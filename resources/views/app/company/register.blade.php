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
		.company-name {
			background: #EDF5FF url('{{ asset('assets/app/img/company.png') }}') no-repeat left bottom/200px;
		}
		.company-name input {
			border: 2px solid #e2edfb !important;
		}
		.company-join input {
			border: 2px solid #EDF5FF !important;
		}
		.side {
			margin-top: 28px;
			min-height: calc(100vh - 150px);
		}
		.side h1 {
			font-weight: bold;
			margin-bottom: 20px;
		}
		.side p {
			font-size: 16px;
			margin-bottom: 20px;
			line-height: 1.8;
		}
		.side input {
			border: 2px;
			padding: 10px 15px;
			font-size: 16px;
		}
		.gf-footer {
			margin-top: 0 !important;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light">
		<a class="navbar-brand m-auto" href="https://www.kotakarsip.com/">
			<div class="row align-items-center">
				<img src="{{ asset('assets/app/img/logo.png') }}" alt="Logo KotakArsip">
				<h3>KotakArsip</h3>
			</div>
		</a>
	</nav>

	<div class="main">
		<div class="container-fluid">
			<div class="row">	
				<div class="col-md-6 side company-name d-flex align-items-center">
					<div class="row">
						<div class="col-10 col-md-8 m-auto">
							<form action="{{ route('company_store') }}" method="post">
								{{ csrf_field() }}
								<input type="hidden" name="id_user" value="{{ Auth::user()->_id }}">
								<h1>Daftarkan Perusahaan</h1>
								<p>Perusahaan Anda ingin menggunakan layanan KotakArsip? Silahkan masukkan nama perusahaan Anda!</p>
								<div class="form-group">
									<input type="text" id="company_name" name="company_name" class="form-control" placeholder="Nama Perusahaan">
								</div>
								<button class="btn btn-primary">Lanjutkan &nbsp;<i class="fa fa-angle-double-right"></i></button>
							</form>
						</div>
					</div>
				</div>

				<div class="col-md-6 side company-join d-flex align-items-center">
					<div class="row">
						<div class="col-10 col-md-8 m-auto">
							<form action="{{ route('company_store') }}" method="post">
								{{ csrf_field() }}
								<input type="hidden" name="id_user" value="{{ Auth::user()->_id }}">
								<br>
								<br>
								<h1>Gabung ke Perusahaan?</h1>
								<p>Masukkan kode perusahaan Anda!</p>
								<div class="form-group">
									<input type="text" id="company_code" name="company_code" class="form-control" placeholder="Kode Perusahaan">
								</div>
								<button class="btn btn-primary">Gabung &nbsp;<i class="fa fa-angle-double-right"></i></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="globalfooter" class="small">
		<div class="gf-footer">
			<div class="row justify-content-between">
				<div class="col-12 text-center">
					<small>© copyright 2018 KotakArsip</small>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip-lp.min.js') }}"></script>
</body>
</html>