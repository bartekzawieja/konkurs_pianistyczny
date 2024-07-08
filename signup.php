<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();

	include("connection.php");
	include("functions.php");

	$echo1 = false;

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//coś jest dodane:
		$dane_imie = $_POST['dane_imie'];
		$dane_nazwisko = $_POST['dane_nazwisko'];
		$dany_email = $_POST['dany_email'];
		$dane_haslo = $_POST['dane_haslo'];
		
		if(!empty($dane_imie) && !empty($dane_nazwisko) && !empty($dany_email) && !empty($dane_haslo) && strlen($dane_haslo) >7 && !is_numeric($dane_imie) && !is_numeric($dane_nazwisko) && (strpos($dany_email, "@") !== false) && (strpos($dany_email, ".") !== false))
		{
			//dodać do bazy danych:
			pg_query("INSERT INTO uzytkownicy(imie, nazwisko, email, haslo, rola) values ('$dane_imie', '$dane_nazwisko', '$dany_email', '$dane_haslo', 'artysta')");

			header("Location: login.php");
			die;
			
		}else
		{
			$echo1 = true;
		}
		
	}
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Signup</title>
</head>

	<style type="text/css">

body {

	font-family: garamond, sans-serif;
}

	#bigBox {
		display: grid;
		height: 100vh;
		margin: 0px;
	}

	#background {

	  	background-image: url("obraz_rejestracja1.jpg");
	  	background-size: cover;
	  	background-repeat: no-repeat;

	}

	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#button1{

		padding: 5px;
		width: 100px;
		color: black;
		background-color: lightgreen;
		border: none;
		font-size: 15px;
	}

	#button2{

		position:fixed;
		bottom: 20px;
		right: 20px;
		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgrey;
		border: none;
	}

	#smallBox{
		place-self: center;
		background-color: rgba(100, 100, 90, 0.8);
		width: 500px;
		padding: 50px;

	}

	#echoo{
		position: fixed;
		bottom: 10px;
		left: 10px;
		background-color: rgba(0, 0, 0, 0);
		padding: 0px;

	}


	</style>

<body>
	
	<div id="background">
	<div id="bigBox">

		<div id="smallBox">
			
			<form method="post">
				<div style="font-size: 30px;margin: 10px;color: white;">Rejestracja artysty:</div>

				<input type="text" id="text" maxlength="50" placeholder="Imię" name="dane_imie"><br><br>
				<input type="text" id="text" maxlength="50" placeholder="Nazwisko" name="dane_nazwisko"><br><br>
				<input type="text" id="text" maxlength="70" placeholder="Adres e-mail" name="dany_email"><br><br>
				<input type="password" id="text" maxlength="50" placeholder="Hasło (minimum 8 znaków)" name="dane_haslo"><br><br>

				<input id="button1" type="submit" value="Zarejestruj"><br><br>
				<a href="login.php" style="color: black" >Jesteś już zarejestrowany? Logowanie </a><br><br>

			</form>
		</div>
	<form action = "signupob.php">
		<input type="submit" id="button2" value = "Rejestracja członków obsługi" />
	</form>

	<div id = "echoo">
		<?php
		if($echo1) {echo '<span style="color: black; font-size: 20px; font-weight: bold"> ' . "Proszę wprowadzić prawidłowe dane rejestracji!" . '</span>';}
		?>
	</div>

	</div>
	</div>
</body>
</html>