<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Activation</title>
</head>
<body>
	<?php 
		// For Windows
		$string = exec('getmac');
		$mac = substr($string, 0, 17);

		// For Mac
		ob_start();
		system("ifconfig en1 | awk '/ether/{print $2}'");
		$mac = substr(ob_get_contents(), 0, 17);
		ob_clean();

		echo $mac;
	 ?>
	<form action="{{ route('activation_store') }}" method="post">
		{{ csrf_field() }}
		<label for="">Serial Number</label>
		<input type="text" name="serial">
		<button>Save</button>
	</form>
</body>
</html>