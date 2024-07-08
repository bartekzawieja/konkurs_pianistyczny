<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


	session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

	$iterator = 0;

    ////////////////////////////////////////////////////////////
	
	$harm = pg_query($con, "SELECT * FROM uzytkownicy WHERE flagaKoniec = 1 LIMIT 1");

	if($harm && pg_numrows($harm) > 0) {

		$resultHarm= pg_query($con, "SELECT * FROM harmonogram");
		$iterator = pg_numrows($resultHarm);
	}
	
    /////////////////////////////////////////////////////////////
  
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
  		justify-content: left;
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

		position:center;
		margin: 10px;
		padding: 18px;
		width: 400px;
		color: white;
		background-color: rgba(30, 100, 90, 1);
		border: none;
		font-size: 15px;
	}

	#button2{

		position:center;
		margin: 10px;
		padding: 18px;
		width: 400px;
		color: white;
		background-color: rgba(139, 0, 0, 1);
		border: none;
		font-size: 15px;
	}


	#button3{

		position:center;
		margin: 10px;
		padding: 18px;
		width: 400px;
		color: white;
		background-color: rgba(0, 0, 139, 1);
		border: none;
		font-size: 15px;
	}


	#button4{

		position:fixed;
		top: 47px;
		right: 20px;
		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgrey;
		border: none;
	}

	#button5{

		position:fixed;
		top: 20px;
		right: 20px;
		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgrey;
		border: none;
	}


	#smallBox1{
		display: grid;
		height: 500px;
		margin: 30px;
		background-color: rgba(100, 100, 90, 0.8);
		padding: 40px;

	}

	#smallBox2{
		display: grid;
		height: 85vh;
		margin: 30px;
		background-color: rgba(100, 100, 90, 0.8);
		padding: 40px;
		color: white;
		border: 2px solid white;

	}

	table tr{
            font-size: 12px;
            font-weight: 550;
        }


	</style>

<body>
	
	<div id="background">
	<div id="bigBox">


		<div id="smallBox1">
			<div style="font-size: 60px;margin: 10px;color: white;">Menu</div>

			<form action = "obsluga_utw.php">
				<input type="submit" id="button3" value = "1) Rozszerzenie bazy kompozytorów i utworów" />
			</form>

			<form action = "obsluga_hm.php">
				<input type="submit" id="button1" value = "3) Obsługa harmonogramu konkursu" />
			</form>

			<form action = "obsluga_oc.php">
				<input type="submit" id="button2" value = "5) Ewaluacja występów konkursowych" />
			</form>

		</div>

		<div id="smallBox2">
			<form method="post">
				<div style="font-size: 25px; margin: 10px;color: white;">4) Harmonogram konkursu:</div>
				<table border="1" align=center cellspacing="2" cellpadding="8">
				<?php

				 // Przechodzimy po wierszach wyniku.
				echo "<tr>\n";
				echo " <td>" . "kolejność wykonań" . "</td>\n";
				echo " <td>" . "artysta" . "</td>\n";
				echo " <td>" . "tytuł utworu" . "</td>\n";
				echo " <td>" . "pierwszy oceniający" . "</td>\n";
				echo " <td>" . "drugi oceniający" . "</td>\n";
				
				 for($ri = 0; $ri < ($iterator); $ri++) { //utwory 1

				 	echo "<tr>\n";

				 	$resultArtysta= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN zgloszenia z ON u.id = z.artysta_id
							  	INNER JOIN harmonogram h ON z.id = h.zgloszenie_id");

				 	$rowArtysta = pg_fetch_array($resultArtysta, $ri);
					

					$resultUtwor1= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowArtysta['zgloszenie_id']} AND z_u.numer_utworu = 1 LIMIT 1");
					
					$rowUtwor1 = pg_fetch_array($resultUtwor1, 0);


				 	$resultObsluga1= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							  WHERE u_h.numer_obslugi = 1 AND h.zgloszenie_id = {$rowArtysta['zgloszenie_id']}");
				 	
				 	$rowObsluga1 = pg_fetch_array($resultObsluga1, 0);


				 	$resultObsluga2= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							  WHERE u_h.numer_obslugi = 2 AND h.zgloszenie_id = {$rowArtysta['zgloszenie_id']}");
				 	
				 	$rowObsluga2 = pg_fetch_array($resultObsluga2, 0);

					echo "<tr>\n";

				 	echo " <td>" . number_format($ri + 1) . "</td>\n";
				 	echo " <td>" . $rowArtysta['imie'] . "   " . $rowArtysta['nazwisko'] . "</td>\n";
					echo " <td>" . $rowUtwor1['tytul'] . "</td>\n";
					echo " <td>" . $rowObsluga1['imie'] . "   " . $rowObsluga1['nazwisko'] . "</td>\n";
					echo " <td>" . $rowObsluga2['imie'] . "   " . $rowObsluga2['nazwisko'] . "</td>\n";
					
					echo "<tr>\n";
				}	


				for($ri = 0; $ri < ($iterator); $ri++) { //utwory 2

				 	echo "<tr>\n";

				 	$resultArtysta= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN zgloszenia z ON u.id = z.artysta_id
							  	INNER JOIN harmonogram h ON z.id = h.zgloszenie_id");

				 	$rowArtysta = pg_fetch_array($resultArtysta, $ri);
					

					$resultUtwor2= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowArtysta['zgloszenie_id']} AND z_u.numer_utworu = 2 LIMIT 1");
					
					$rowUtwor2 = pg_fetch_array($resultUtwor2, 0);


				 	$resultObsluga1= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							  WHERE u_h.numer_obslugi = 1 AND h.zgloszenie_id = {$rowArtysta['zgloszenie_id']}");
				 	
				 	$rowObsluga1 = pg_fetch_array($resultObsluga1, 0);


				 	$resultObsluga2= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							  WHERE u_h.numer_obslugi = 2 AND h.zgloszenie_id = {$rowArtysta['zgloszenie_id']}");
				 	
				 	$rowObsluga2 = pg_fetch_array($resultObsluga2, 0);

					echo "<tr>\n";

				 	echo " <td>" . number_format($iterator + $ri + 1) . "</td>\n";
				 	echo " <td>" . $rowArtysta['imie'] . "   " . $rowArtysta['nazwisko'] . "</td>\n";
					echo " <td>" . $rowUtwor2['tytul'] . "</td>\n";
					echo " <td>" . $rowObsluga1['imie'] . "   " . $rowObsluga1['nazwisko'] . "</td>\n";
					echo " <td>" . $rowObsluga2['imie'] . "   " . $rowObsluga2['nazwisko'] . "</td>\n";
					
					echo "<tr>\n";
				}	

				for($ri = 0; $ri < ($iterator); $ri++) { //utwory 3

				 	echo "<tr>\n";

				 	$resultArtysta= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN zgloszenia z ON u.id = z.artysta_id
							  	INNER JOIN harmonogram h ON z.id = h.zgloszenie_id");

				 	$rowArtysta = pg_fetch_array($resultArtysta, $ri);
					

					$resultUtwor3= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowArtysta['zgloszenie_id']} AND z_u.numer_utworu = 3 LIMIT 1");
					
					$rowUtwor3 = pg_fetch_array($resultUtwor3, 0);


				 	$resultObsluga1= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							  WHERE u_h.numer_obslugi = 1 AND h.zgloszenie_id = {$rowArtysta['zgloszenie_id']}");
				 	
				 	$rowObsluga1 = pg_fetch_array($resultObsluga1, 0);


				 	$resultObsluga2= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							  WHERE u_h.numer_obslugi = 2 AND h.zgloszenie_id = {$rowArtysta['zgloszenie_id']}");
				 	
				 	$rowObsluga2 = pg_fetch_array($resultObsluga2, 0);

					echo "<tr>\n";

				 	echo " <td>" . number_format(2*$iterator + $ri + 1) . "</td>\n";
				 	echo " <td>" . $rowArtysta['imie'] . "   " . $rowArtysta['nazwisko'] . "</td>\n";
					echo " <td>" . $rowUtwor3['tytul'] . "</td>\n";
					echo " <td>" . $rowObsluga1['imie'] . "   " . $rowObsluga1['nazwisko'] . "</td>\n";
					echo " <td>" . $rowObsluga2['imie'] . "   " . $rowObsluga2['nazwisko'] . "</td>\n";
					
					echo "<tr>\n";
				}	


				?>
				</table>

				<?php if($iterator == 0) {echo "*Harmonogram nie został jeszcze stworzony.";} ?>

			</form>
		</div>


	<form action = "logout.php">
		<input type="submit" id="button5" value = "Wyloguj użytkownika" />
	</form>

	<form action = "logout.php">
		<input type="submit" id="button4" value = "<?php echo $user_data['imie']; ?> <?php echo $user_data['nazwisko']; ?>" />
	</form>

	</div>
	</div>

</body>
</html>