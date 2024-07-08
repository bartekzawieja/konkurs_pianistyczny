<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

	session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

	$echo1 = false;
	$echo2 = false;


	$resultT= pg_query($con, "SELECT k.nazwisko, k.imie, u.tytul FROM utwory u INNER JOIN kompozytorzy k ON k.id = u.kompozytor_id GROUP BY k.nazwisko, k.imie, u.tytul ORDER BY k.nazwisko");
	$numrows = pg_numrows($resultT);


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{	
		//coś jest dodane:
		$dany_tytul = $_POST['dany_tytul'];
		$dane_imie = $_POST['dane_imie'];
		$dane_nazwisko = $_POST['dane_nazwisko'];
		
		if(!empty($dany_tytul) && !empty($dane_imie) && !empty($dane_nazwisko))
		{
				//bierzemy rekordy utworów:
				$result1 = pg_query($con, "SELECT u.id, k.id FROM utwory u, kompozytorzy k WHERE u.tytul = '$dany_tytul' LIMIT 1");
				
				//sprawdzamy, czy pobranie rekordów utoworów wykonało się poprawnie:
				if($result1 && pg_numrows($result1) < 1)
				{
					$resultKomp = pg_query($con, "SELECT * FROM kompozytorzy WHERE imie = '$dane_imie' AND nazwisko = '$dane_nazwisko' LIMIT 1");
					
					if($resultKomp && pg_numrows($resultKomp) > 0) {
						$stary_kompozytor = pg_fetch_assoc($resultKomp);

						pg_query($con, "INSERT INTO utwory(kompozytor_id, obsluga_id, tytul) VALUES ({$stary_kompozytor['id']}, {$user_data['id']}, '$dany_tytul')");

					} else {

						pg_query($con, "INSERT INTO kompozytorzy(imie, nazwisko) VALUES ('$dane_imie', '$dane_nazwisko')");

						$result2 = pg_query($con, "SELECT * FROM kompozytorzy ORDER BY id DESC LIMIT 1");

						$nowe_id_kopozytora = pg_fetch_assoc($result2);

						pg_query($con, "INSERT INTO utwory(kompozytor_id, obsluga_id, tytul) VALUES ({$nowe_id_kopozytora['id']}, {$user_data['id']}, '$dany_tytul')");

					}
	
					header("Location: obsluga_utw.php");
					die;
									

				}else
				{
						$echo1 = false;
						$echo2 = true;
				}
				
		
		}else
		{
			$echo1 = true;
			$echo2 = false;
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

table tr{
            font-size: 20px;
            font-weight: 550;
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

	  	background-image: url("obraz_rejestracjaOb.jpg");
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
		color: white;
		background-color: rgba(0, 0, 139, 1);
		border: none;
		font-size: 15px;
	}

	#button2{

		position:fixed;
		top: 47px;
		right: 20px;
		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgrey;
		border: none;
	}

	#button3{

		position:fixed;
		top: 20px;
		right: 20px;
		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgrey;
		border: none;
	}

	#button4{

		position:fixed;
		top: 20px;
		left: 20px;
		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgrey;
		border: none;
	}


	#smallBox1{
		display: flex;
		margin: 30px;
		background-color: rgba(100, 100, 90, 0.8);
		padding: 40px;

	}

	#smallBox2{
		display: flex;
		color: white;
		margin: 30px;
		background-color: rgba(0, 0, 139, 0.9);
		padding: 10px;

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
				<div style="font-size: 40px;margin: 10px;color: white;">Dodaj utwór</div>

				<input type="text" id="text" maxlength="100" placeholder="Tytuł utwóru" name="dany_tytul"><br><br>
				<input type="text" id="text" maxlength="100" placeholder="Imię autora" name="dane_imie"><br><br>
				<input type="text" id="text" maxlength="100" placeholder="Nazwisko autora" name="dane_nazwisko"><br><br>

				<input id="button1" type="submit" value="Dodaj"><br><br>

				<div style="font-size: 15px;margin: 10px;color: white;">*Nie można dodać dwóch utworów o dokładnie tym samym tytule.</div>

			</form>
		</div>

		<div id="smallBox2">
			<form method="post" color: white>
				<div style="font-size: 25px; margin: 10px;color: white;">Dostępne utwory</div>
				<table border="1" align=center cellspacing="2" cellpadding="8" color: white>
				<?php

				 // Przechodzimy po wierszach wyniku.
				echo "<tr>\n";
				echo " <td>" . "tytuł utworu" . "</td>\n";
				echo " <td>" . "autor" . "</td>\n";

				 for($ri = 0; $ri < $numrows; $ri++) {
				 	echo "<tr>\n";
				 	$row = pg_fetch_array($resultT, $ri);
				 	echo " <td>" . $row['tytul'] . "</td>\n";
					echo " <td>" . $row['imie'] . "   " . $row['nazwisko'] . "</td>\n";
					echo "<tr>\n";
				 	}
				?>
				</table>
			</form>
		</div>


	<form action = "logout.php">
		<input type="submit" id="button3" value = "Wyloguj użytkownika" />
	</form>

	<form action = "logout.php">
		<input type="submit" id="button2" value = "<?php echo $user_data['imie']; ?> <?php echo $user_data['nazwisko']; ?>" />
	</form>

	<form action = "obsluga_mn.php">
		<input type="submit" id="button4" value = "Powrót do menu" />
	</form>

	<div id = "echoo">
		<?php
		if($echo1) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Proszę wprowdzić wszystkie 3 informacje!" . '</span>';}
		if($echo2) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Utwór o tym tytule jest już w bazie!" . '</span>';}
		?>
	</div>

	</div>
	</div>
</body>
</html>