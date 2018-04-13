<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="https://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>2 Langkah Verifikasi</title>
</head>
<body style="-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; box-sizing: border-box; color: #343434; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; min-width: 100%; padding: 0; text-align: left; width: 100% !important;">
	<div style="text-align: center">
		<img src="http://app.kotakarsip.com/assets/app/img/email/head.png" alt="Logo KotakArsip" />
		<div style="max-width: 450px; width: 100%; padding: 20px; margin: auto">
			<h2 style="color: #999; font-weight: normal;">Yth. <b style="color: #0070ff">{{ $company }}</b>,</h2>
			<p style="color: #999; line-height: 24px; font-weight: normal">Berikut adalah tagihan pembayaran Anda</p>
			<br />
			<div class="row">
				<div style="width: 45%; float: left; text-align: left; font-size: 12px; line-height: 1.6">
					<div class="account text-left">
						<div class="item" style="margin-bottom: 20px;"><img src="http://app.kotakarsip.com/assets/app/img/bank/BCA_logo.png" alt="Bank BCA Logo" width="100px"></div>
						<div class="item"><b>PT. Docotel Teknologi Celebes</b></div>
						<div class="item">No. Rekening 7890863200</div>
						<div class="item">---</div>
						<div class="item">Jl. Hertasning I No. 30. Tidung, Rappocini</div>
						<div class="item">Kota Makassar, Sulawesi Selatan</div>
						<div class="item">---</div>
						<div class="item">(0411) 4678985<br><small>Senin-Jum'at 09:00 - 17:00 WITA</small></div>
					</div>
				</div>
				<div style="width: 45%; float: right; text-align: right; font-size: 14px; line-height: 1.6">
					<div class="account text-right">
						<div class="item" style="margin-top: 50px;"><b>Total Tagihan</b></div>
						<div class="item">Harga Paket: Rp. {{ number_format($base_price,0,",",".") }}</div>
						<div class="item">PPN (10%): Rp. {{ number_format($base_price * 0.10,0,",",".") }}</div>
						<div class="item">Kode Unik Pembayaran: Rp. {{ substr($price, -3) }}</div>
						<div class="item total-price">
							<hr>
							<h2>Rp. {{ number_format($price,0,",",".") }}</h2>
						</div>
					</div>
				</div>
				<div style="clear: both"></div>
				<div class="item">
					<br>
					<br>
					<small>*Kode unik digunakan untuk mempermudah proses pengecekan pembayaran</small>
				</div>
			</div>
			<br />
			<p style="color: #999; line-height: 24px; font-weight: normal">Dapatkan kemudahan memanajemen arsip pada perusahaan Anda di KotakArsip.com</p>
			<img src="http://app.kotakarsip.com/assets/app/img/email/thanks.png" alt="Logo KotakArsip" />
		</div>
	</div>
</body>
</html>