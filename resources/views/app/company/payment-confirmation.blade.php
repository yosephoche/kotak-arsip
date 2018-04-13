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
		.account .item {
			margin-bottom: 5px;
		}
		.account .item img {
			margin-bottom: 15px;
		}
		.total-price .plus {
			position: absolute;
			margin-top: -31px;
			display: inline-block;
			font-weight: normal;
			background-color: #fff;
			padding-left: 10px;
		}
		.total-price hr {
			border-color: #000;
		}
		.total-price h2 {
			font-size: 20px;
			font-family: 'Century Gothic Bold';
		}
		@media (max-width: 767px) {
			.account {
				text-align: center !important;
			}
			.total-price .plus {
				right: 15px;
			}
			.title li {
				display: none;
			}
			.title li:nth-child(2) {
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
								<li><span style="color: #ccc; font-family: 'Century Gothic'; font-size: 18px">Pilih Paket &nbsp;<i class="fa fa-angle-right"></i></span></li>
								<li>Konfirmasi Pembayaran</li>
								<li><span style="color: #ccc; font-family: 'Century Gothic'; font-size: 18px"><i class="fa fa-angle-right"></i> &nbsp;Registrasi Berhasil</span></li>
							</ul>
						</h1>
						<br>
						<hr>
						<div class="row">
							@if ($invoice->status == 'unpaid')
								<div class="col-md-8 col-md-offset-2">
									<div class="alert alert-success">Terima Kasih, kami akan memeriksa pembayaran Anda dan segera mengaktifkan akun Anda.</div>
									<br>
								</div>
							@endif
							<div class="col-md-offset-2 col-md-4 col-sm-6">
								<div class="account text-left">
									<div class="item"><img src="{{ asset('assets/app/img/bank/BCA_logo.png') }}" alt="Bank BCA Logo" width="100px"></div>
									<div class="item"><b>PT. Docotel Teknologi Celebes</b></div>
									<div class="item">No. Rekening 7890863200</div>
									<div class="item">---</div>
									<div class="item">Jl. Hertasning I No. 30. Tidung, Rappocini</div>
									<div class="item">Kota Makassar, Sulawesi Selatan</div>
									<div class="item">---</div>
									<div class="item">(0411) 4678985<br><small>Senin-Jum'at 09:00 - 17:00 WITA</small></div>
								</div>
							</div>
							<div class="col-sm-6 col-md-4">
								<div class="account text-right">
									<div class="item"><b>Total Tagihan</b></div>
									<div class="item">Harga Paket: Rp. {{ number_format($invoice->base_price,0,",",".") }}</div>
									<div class="item">PPN (10%): Rp. {{ number_format($invoice->base_price * 0.10,0,",",".") }}</div>
									<div class="item">Kode Unik Pembayaran: Rp. {{ substr($invoice->price, -3) }}</div>
									<div class="item total-price">
										<hr>
										<b class="plus">+</b>
										<h2>Rp. {{ number_format($invoice->price,0,",",".") }}</h2>
									</div>
									<div class="item">
										<br>
										<small>*Kode unik digunakan untuk mempermudah proses pengecekan pembayaran</small>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								@if ($invoice->status == null)
									<hr>
									Pastikan Anda telah mentransfer uang sebelum mengeksekusi tombol<br><br>
									<form action="{{ route('company_invoice_store') }}" method="post">
										{{ csrf_field() }}
										<input type="hidden" name="id" value="{{ $invoice->_id }}">
										<button class="btn btn-primary">Cek Pembayaran</button>
									</form>
								@endif
								<br>
								<br>
								<br>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>

</body>

</html>