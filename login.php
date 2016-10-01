<?php
session_start();
if(!empty($_SESSION['auth']) || @$_SESSION['auth'] === true) header("Location: index.php");
$msg = '';
if(!empty($_GET)){
	if($_GET['err'] == 1){
		$msg = '<div class="alert alert-danger" role="alert">Maaf, username atau password anda tidak cocok.</div>';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Log In | Polresta Malang</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
		<div id="header">
		<img class="logo-tribrata" src="img/Logo Tribrata.png" />
		<h1>Bantuan Polisi</br>Malang Kota</h1>
		<img class="logo-polres" src="img/Logo Polres Kota Malang.png" />
		</div>
		<?php echo $msg ?>
		<form method="POST" action="index.php">
			<input type="text" name="username" id="username" class="form-control" placeholder="Username">
			<input type="password" name="password" id="password" class="form-control" placeholder="Password">
			<button id="btn-help-reply" class="btn btn-primary">Masuk</button>
		</form>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
