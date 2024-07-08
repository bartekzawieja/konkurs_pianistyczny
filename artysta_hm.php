<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


	session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

	//dla każdego zebrać dane poza utworami
	$resultH1= pg_query($con, "SELECT
								z.id, u.imie, u.nazwisko, (h.ocena1 + h.ocena2) AS ocena
							  FROM uzytkownicy u
							  	INNER JOIN zgloszenia z ON u.id = z.artysta_id
							  	INNER JOIN harmonogram h ON z.id = h.zgloszenie_id
							  ORDER BY h.kolejnosc ASC");
	$numrows = pg_numrows($resultH1);
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



	#smallBox{
		display: flex;
		margin: 30px;
		background-color: rgba(139, 0, 0, 0.9);
		padding: 10px;
		color: white;

	}

	table tr{
            font-size: 20px;
            font-weight: 550;
        }


	</style>

<body>
	
	<div id="background">
	<div id="bigBox">


		<div id="smallBox">
			<form method="post">
				<div style="font-size: 25px; margin: 10px;color: white;">Oceny występów</div>
				<table border="1" align=center cellspacing="2" cellpadding="8">
				<?php

				 // Przechodzimy po wierszach wyniku.
				echo "<tr>\n";
				echo " <td>" . "   " . "</td>\n";
				echo " <td>" . "artysta" . "</td>\n";
				echo " <td>" . "tytuł pierwszego utworu" . "</td>\n";
				echo " <td>" . "tytuł drugiego utworu" . "</td>\n";
				echo " <td>" . "tytuł trzeciego utworu" . "</td>\n";
				echo " <td>" . "ocena sumaryczna" . "</td>\n";

				 for($ri = 0; $ri < $numrows; $ri++) {
				 	echo "<tr>\n";
				 	$row = pg_fetch_array($resultH1, $ri);


				 	//dla każdego zebrać po 3 utwory - wyjdą 3 wiersze, potem wyrywam z każdego wiersza wartość i wstawiam do poszczególnych kolumn w pętli:
					$resultUtwor1= pg_query($con, "SELECT ut.tytul
							  FROM utwory ut
							  	INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$row['id']} AND z_u.numer_utworu = 1 LIMIT 1");
					$resultUtwor2= pg_query($con, "SELECT ut.tytul
							  FROM utwory ut
							  	INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$row['id']} AND z_u.numer_utworu = 2 LIMIT 1");
					$resultUtwor3= pg_query($con, "SELECT ut.tytul
							  FROM utwory ut
							  	INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$row['id']} AND z_u.numer_utworu = 3 LIMIT 1");
					$row1 = pg_fetch_array($resultUtwor1, 0);
					$row2 = pg_fetch_array($resultUtwor2, 0);
					$row3 = pg_fetch_array($resultUtwor3, 0);

				 	echo " <td>" . number_format($ri + 1) . "</td>\n";
				 	echo " <td>" . $row['imie'] . "   " . $row['nazwisko'] . "</td>\n";
					echo " <td>" . $row1['tytul'] . "</td>\n";
					echo " <td>" . $row2['tytul'] . "</td>\n";
					echo " <td>" . $row3['tytul'] . "</td>\n";
					echo " <td>" . $row['ocena'] . "</td>\n";
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

	</div>
	</div>
</body>
</html>