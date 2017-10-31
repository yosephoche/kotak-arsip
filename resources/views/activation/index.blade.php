<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Activation</title>
</head>
<body>
	<?php 
		$string=exec('getmac');
        $mac=substr($string, 0, 17);
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