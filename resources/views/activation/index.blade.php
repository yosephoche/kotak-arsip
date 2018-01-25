<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Aktivasi KotakArsip</title>
	<style>
		* {
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
			font-size: 14px;
		}
		.container {
			max-width: 800px;
			padding: 20px;
			width: 100%;
			margin: auto;
			text-align: center;
		}
		h1 {
			font-size: 30px;
			margin-bottom: 50px;
		}
		img {
			margin-bottom: 30px;
		}
		label {
			display: block;
		}
		input {
			padding: 5px;
			border-radius: 3px;
			border: 1px solid #ccc;
			max-width: 300px;
			width: 100%;
			margin-top: 10px;
			margin-bottom: 10px;
		}
		button {
			background-color: #0074F6;
			color: #fff;
			border: none;
			outline: none;
			padding: 10px 20px;
		}
	</style>
</head>
<body>
	<?php
		// For Mac
		// ob_start();
		// system("ifconfig en1 | awk '/ether/{print $2}'");
		// $mac = substr(ob_get_contents(), 0, 17);
		// ob_clean();

		// For Ubuntu
		ob_start();
		system("ifconfig -a | grep -Po 'HWaddr \K.*$'");
		$mac = substr(ob_get_contents(), 0, 17);
		ob_clean();

		// For Windows
		// $string = exec('getmac');
		// $mac = substr($string, 0, 17);

		echo $mac;
	 ?>
	 <div class="container">
	 	<h1>Aktivasi KotakArsip</h1>
	 	<img src="{{ asset('assets/app/img/company.png') }}" alt="">
		<form action="{{ route('activation_store') }}" method="post">
			{{ csrf_field() }}
			<label for="">Masukkan No.Serial</label>
			<input type="text" name="serial"><br>
			<button>Aktivasi</button>
		</form>
	 </div>
</body>
</html>
