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
	$echo3 = false;
	$echo4 = false;
	$echo5 = false;
	$echo6 = false;


	$resultT= pg_query($con, "SELECT k.nazwisko, k.imie, u.tytul FROM utwory u INNER JOIN kompozytorzy k ON k.id = u.kompozytor_id GROUP BY k.nazwisko, k.imie, u.tytul ORDER BY k.nazwisko");
	$numrows = pg_numrows($resultT);


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{	
		//coś jest dodane:
		$dany_u1 = $_POST['dany_u1'];
		$dany_u2 = $_POST['dany_u2'];
		$dany_u3 = $_POST['dany_u3'];

		//var_dump($_POST);
		
		if(!empty($dany_u1) && !empty($dany_u2) && !empty($dany_u3) && ($dany_u1 != $dany_u2) && ($dany_u1 != $dany_u3) && ($dany_u2 != $dany_u3))
		{
				//bierzemy rekordy utworów:
				$result1 = pg_query($con, "SELECT * FROM utwory WHERE tytul = '$dany_u1' LIMIT 1");
				$result2 = pg_query($con, "SELECT * FROM utwory WHERE tytul = '$dany_u2' LIMIT 1");
				$result3 = pg_query($con, "SELECT * FROM utwory WHERE tytul = '$dany_u3' LIMIT 1");
				
				//sprawdzamy, czy pobranie rekordów utoworów wykonało się poprawnie:
				if($result1 && $result2 && $result3)
				{
					
					//bierzemy rekordy danego artysty w zgłoszeniach:
					$result0 = pg_query($con, "SELECT * FROM zgloszenia WHERE artysta_id = {$user_data['id']} LIMIT 1");
					
					//jeżeli pobranie wykonało się poprawnie i nie ma takich rekordów to można dodać jego zgłoszenie:
					if($result0 && pg_numrows($result0) < 1)
					{
							
							//sprawdzamy, czy pobrane rekordy utworów istnieją:
							if($result1 && pg_numrows($result1) > 0 && $result2 && pg_numrows($result2) > 0 && $result3 && pg_numrows($result3) > 0)
							{
								
					
								//dodajemy paczkę utworów do zgłoszenia_utwory:
								$u1_data = pg_fetch_assoc($result1);
								$u2_data = pg_fetch_assoc($result2);
								$u3_data = pg_fetch_assoc($result3);

									if ($u1_data['kompozytor_id'] != $u2_data['kompozytor_id'] &&
									$u1_data['kompozytor_id'] != $u3_data['kompozytor_id'] &&
									$u2_data['kompozytor_id'] != $u3_data['kompozytor_id'] ) { 

									//dodajemy artystę do zgłoszeń:
									pg_query($con, "INSERT INTO zgloszenia(artysta_id, data_zgloszenia) VALUES ({$user_data['id']}, current_timestamp)");

									$result4 = pg_query($con, "SELECT * FROM zgloszenia ORDER BY id DESC LIMIT 1");

									$zgl_data = pg_fetch_assoc($result4);

									pg_query($con, "INSERT INTO zgloszenia_utwory(zgloszenie_id, utwor_id, numer_utworu) VALUES ({$zgl_data['id']}, {$u1_data['id']}, 1)");

									pg_query($con, "INSERT INTO zgloszenia_utwory(zgloszenie_id, utwor_id, numer_utworu) VALUES ({$zgl_data['id']}, {$u2_data['id']}, 2)");

									pg_query($con, "INSERT INTO zgloszenia_utwory(zgloszenie_id, utwor_id, numer_utworu) VALUES ({$zgl_data['id']}, {$u3_data['id']}, 3)");

									$echo1 = false;
									$echo2 = false;
									$echo3 = false;
									$echo4 = false;
									$echo5 = false;
									$echo6 = true;

								} else {
									$echo1 = false;
									$echo2 = false;
									$echo3 = false;
									$echo4 = false;
									$echo5 = true;
									$echo6 = false;
								}
							

							} else
							{
							
								$echo1 = false;
								$echo2 = false;
								$echo3 = false;
								$echo4 = true;
								$echo5 = false;
								$echo6 = false;
				
							}
							

					}else
					{
						$echo1 = false;
						$echo2 = false;
						$echo3 = true;
						$echo4 = false;
						$echo5 = false;
						$echo6 = false;
						
					}
				
				}else
				{
					$echo1 = false;
					$echo2 = true;
					$echo3 = false;
					$echo4 = false;
					$echo5 = false;
					$echo6 = false;
					
				}
				
		
		}else
		{
			$echo1 = true;
			$echo2 = false;
			$echo3 = false;
			$echo4 = false;
			$echo5 = false;
			$echo6 = false;
			
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
		display: flex;
		flex-direction: row;
  		justify-content: center;
  		align-items: center;
		height: 100vh;
		margin: 0px;
		padding: 0;
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
		color: white;
		background-color: rgba(30, 100, 90, 1);
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
		margin: 30px;
		background-color: rgba(30, 100, 90, 0.9);
		padding: 10px;
		color: white;

	}

	#echoo{
		position: fixed;
		bottom: 10px;
		left: 10px;
		background-color: rgba(0, 0, 0, 0);
		padding: 0px;

	}

	table tr{
            font-size: 20px;
            font-weight: 550;
        }


	</style>

<body>
	
	<div id="background">
	<div id="bigBox">


		<div id="smallBox1">
			
			<form method="post">
				<div style="font-size: 40px;margin: 10px;color: white;">Wyślij swoje zgłoszenie</div>

				<input type="text" id="text" maxlength="100" placeholder="Tytuł utwóru 1 (innego niż dwa pozostałe)" name="dany_u1"><br><br>
				<input type="text" id="text" maxlength="100" placeholder="Tytuł utwóru 2 (innego niż dwa pozostałe)" name="dany_u2"><br><br>
				<input type="text" id="text" maxlength="100" placeholder="Tytuł utwóru 3 (innego niż dwa pozostałe)" name="dany_u3"><br><br>

				<input id="button1" type="submit" value="Wyślij"><br><br>
				<div style="font-size: 15px;margin: 10px;color: white;">*Każdy z wybranych utworów musi mieć innego kompozytora.</div>
				<div style="font-size: 15px;margin: 10px;color: white;">*Można wysłać tylko jedno zgłoszenie.</div>

			</form>
		</div>

		<div id="smallBox2">
			<form method="post">
				<div style="font-size: 25px; margin: 10px;color: white;">Dostępne utwory</div>
				<table border="1" align=center cellspacing="2" cellpadding="8">
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

	<form action = "artysta_mn.php">
		<input type="submit" id="button4" value = "Powrót do menu" />
	</form>

	<div id = "echoo">
		<?php
		if($echo1) {echo '<span style="color: black; font-size: 20px; font-weight: bold"> ' . "Proszę wprowdzić 3 różne utwory!" . '</span>';}
		if($echo2) {echo '<span style="color: black; font-size: 20px; font-weight: bold"> ' . "Proszę wprowdzić poprawne tytuły wszystkich 3 utworów!" . '</span>';}
		if($echo3) {echo '<span style="color: black; font-size: 20px; font-weight: bold"> ' . "Każdy artysta może wysłać tylko jedno zgłoszenie!" . '</span>';}
		if($echo4) {echo '<span style="color: black; font-size: 20px; font-weight: bold"> ' . "Proszę wprowdzić tytuły utworów, które istnieją w bazie!" . '</span>';}
		if($echo5) {echo '<span style="color: black; font-size: 20px; font-weight: bold"> ' . "Każdy z wybranych utworów musi mieć innego kompozytora!" . '</span>';}
		if($echo6) {echo '<span style="color: black; font-size: 20px; font-weight: bold"> ' . "Przyjęto zgłoszenie!" . '</span>';}
		?>
	</div>

	</div>
	</div>
</body>
</html>