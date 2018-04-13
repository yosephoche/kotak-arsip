<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Pilih Paket Kotakarsip</title>

	@include('app.layouts.partial.style')

	<style>
		.select-cabinet h2 {
			font-family: 'Century Gothic Bold';
			font-size: 60px;
			color: #126fff;
			margin-top: 0;
		}
		.select-cabinet h2 small {
			color: #ccc;
			font-size: 20px;
		}
		.btn-primary {
			padding: 15px 22px;
		}
		.select-cabinet label.active {
			border: 2px solid #0070ff;
			padding: 19px;
		}
		@media (max-width: 767px) {
			.title li {
				display: none;
			}
			.title li:first-child {
				display: unset;
			}
		}
	</style>
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
					<div class="col-md-12 text-center">
						<h1>
							<ul class="list-unstyled list-inline title">
								<li>Pilih Paket</li>
								<li><span style="color: #ccc; font-family: 'Century Gothic'; font-size: 18px"><i class="fa fa-angle-right"></i> &nbsp;Konfirmasi Pembayaran</span></li>
								<li><span style="color: #ccc; font-family: 'Century Gothic'; font-size: 18px"><i class="fa fa-angle-right"></i> &nbsp;Registrasi Berhasil</span></li>
							</ul>
						</h1>
						<br>
						<br>
						<p>Berikut beberapa pilihan paket yang dapat Anda pilih.</p>
						<br>
						<form action="{{ route('company_select_package_store') }}" method="post">
                            {{ csrf_field() }}

							<input type="radio" name="package" class="hide" id="paket-1" value="paket-1 30 300000 month" checked>
							<input type="radio" name="package" class="hide" id="paket-2" value="paket-2 50 400000 month">
							<input type="radio" name="package" class="hide" id="paket-3" value="paket-3 75 500000 month">
							<input type="radio" name="package" class="hide" id="paket-4" value="paket-4 100 600000 month">

							<input type="radio" name="package" class="hide" id="paket-1-year" value="paket-1 30 3300000 year">
							<input type="radio" name="package" class="hide" id="paket-2-year" value="paket-2 50 4400000 year">
							<input type="radio" name="package" class="hide" id="paket-3-year" value="paket-3 75 5500000 year">
							<input type="radio" name="package" class="hide" id="paket-4-year" value="paket-4 100 6600000 year">

							<div class="row select-cabinet">
								<!-- Nav tabs -->
								<ul class="nav nav-tabs" role="tablist" style="margin: auto;display: inline-block;">
									<li role="presentation" class="active"><a href="#month" aria-controls="month" role="tab" data-toggle="tab"><b>Bulanan</b></a></li>
									<li role="presentation"><a href="#year" aria-controls="year" role="tab" data-toggle="tab"><b>Tahunan</b></a></li>
								</ul>

								<div class="tab-content">
									<div id="month" role="tabpanel" class="tab-pane active">
										<div class="col-md-3 text-center">
											<label for="paket-1" class="text-center active">
												<h2>30<small>GB</small></h2>
												<p>Rp. 300.000/bulan</p>
											</label>
										</div>

										<div class="col-md-3 text-center">
											<label for="paket-2" class="text-center">
												<h2>50<small>GB</small></h2>
												<p>Rp. 400.000/bulan</p>
											</label>
										</div>

										<div class="col-md-3 text-center">
											<label for="paket-3" class="text-center">
												<h2>75<small>GB</small></h2>
												<p>Rp. 500.000/bulan</p>
											</label>
										</div>

										<div class="col-md-3 text-center">
											<label for="paket-4" class="text-center">
												<h2>100<small>GB</small></h2>
												<p>Rp. 600.000/bulan</p>
											</label>
										</div>
									</div>

									<div id="year" role="tabpanel" class="tab-pane">
										<div class="col-md-3 text-center">
											<label for="paket-1-year" class="text-center">
												<h2>30<small>GB</small></h2>
												<p>Rp. 3.300.000/tahun<br><small style="color: #0070ff">Diskon 10%</small></p>
											</label>
										</div>

										<div class="col-md-3 text-center">
											<label for="paket-2-year" class="text-center">
												<h2>50<small>GB</small></h2>
												<p>Rp. 4.400.000/tahun<br><small style="color: #0070ff">Diskon 10%</small></p>
											</label>
										</div>

										<div class="col-md-3 text-center">
											<label for="paket-3-year" class="text-center">
												<h2>75<small>GB</small></h2>
												<p>Rp. 5.500.000/tahun<br><small style="color: #0070ff">Diskon 10%</small></p>
											</label>
										</div>

										<div class="col-md-3 text-center">
											<label for="paket-4-year" class="text-center">
												<h2>100<small>GB</small></h2>
												<p>Rp. 6.600.000/tahun<br><small style="color: #0070ff">Diskon 10%</small></p>
											</label>
										</div>
									</div>
								</div>

								<div class="col-md-12 text-center">
									<br>
									<br>
									<button class="btn btn-primary">Lanjutkan &nbsp;<i class="fa fa-angle-double-right"></i></button>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip.min.js') }}"></script>
	<script>
		$('.select-cabinet label').click(function() {
			var id = $(this).attr('for');

			$('input[type="radio"]').removeAttr('checked');
			$('input#' + id).attr('checked', 'checked');

			$('label').removeClass('active');
			$('label[for="' + id + '"]').addClass('active');
		});
	</script>

</body>

</html>