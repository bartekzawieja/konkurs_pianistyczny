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
		$dany_email = $_POST['dany_email'];
		$dane_haslo = $_POST['dane_haslo'];

		if(!empty($dany_email) && !empty($dane_haslo) && strlen($dane_haslo) >7 && (strpos($dany_email, "@") !== false) && (strpos($dany_email, ".") !== false))
		{
			//read from database
			$query = "SELECT * FROM uzytkownicy WHERE email = '$dany_email' LIMIT 1";
			$result = pg_query($con, $query);

			if($result)
			{
				if($result && pg_numrows($result) > 0)
				{

					$user_data = pg_fetch_assoc($result);
					
					if($user_data['haslo'] === $dane_haslo)
					{
						$_SESSION['user_id'] = $user_data['id'];

						header("Location: index.php");
					}
				}
			}
			
			$echo1 = true;
		}else
		{
			$echo1 = true;
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>

	<style type="text/css">

body {

	font-family: garamond, sans-serif;
}

	#bigBox {
		display: flex;
		flex-direction: row;
  		justify-content: center;
  		align-items: center;
		height: 100vh;
		margin: 0px;
		padding: 0;
	}

	#background {

	  	background-image: url("obraz_logowanie.jpg");
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


	#smallBox1{
		display: flex;
		margin: 30px;
		background-color: rgba(100, 100, 90, 0.8);
		padding: 40px;

	}

	#smallBox2{
		display: flex;
		margin: 30px;
		background-color: rgba(100, 100, 90, 0.8);
		padding: 10px;
		border: 2px solid black;

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


		<div id="smallBox1">
			
			<form method="post">
				<div style="font-size: 30px;margin: 10px;color: white;">Logowanie:</div>

				<input type="text" id="text" maxlength="70" placeholder="Adres e-mail" name="dany_email"><br><br>
				<input type="password" id="text" maxlength="50" placeholder="Hasło" name="dane_haslo"><br><br>

				<input id="button1" type="submit" value="Zaloguj"><br><br>
				<a href="signup.php" style="color: black">Nie jesteś jeszcze zarejestrowany? Rejestracja </a><br><br>

			</form>
		</div>


		<div id="smallBox2">
				<ul>
  					<li><p style="font-size: 25px">  Data: 10.07.2023 </p></li>
  					<li><p style="font-size: 25px">  Miejsce: Filharmonia Narodowa, ul. Jasna 5, Warszawa </p></li>
  				</ul>	
		</div>


		<div id = "echoo">
		<?php
		if($echo1) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "zły adres email lub hasło!" . '</span>';}
		?>
		</div>



	</div>
	</div>
</body>
</html>