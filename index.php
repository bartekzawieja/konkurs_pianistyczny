<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);


	if($user_data['rola'] === 'artysta') 
	{
		header("Location: artysta_mn.php");
		die;
	}else
	{
		header("Location: obsluga_mn.php");
		die;
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Moja strona!</title>
</head>
<body>

	<a href="logout.php">Logout</a>
	<h1>Tto jest index</h1>

	<br>
	Witaj, <?php echo $user_data['user_name']; ?>
</body>
</html>