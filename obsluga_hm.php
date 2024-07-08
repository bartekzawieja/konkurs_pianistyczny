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
	$echo7 = false;


	$resultNierozp= pg_query($con, "SELECT z.id, z.data_zgloszenia, u.imie, u.nazwisko FROM zgloszenia z INNER JOIN uzytkownicy u ON u.id = z.artysta_id WHERE z.id NOT IN (SELECT h.zgloszenie_id FROM harmonogram h WHERE h.zgloszenie_id IS NOT NULL) ORDER BY z.data_zgloszenia");

	if($resultNierozp) {
		$numrowsNierozp = pg_numrows($resultNierozp);
	} else {
		$numrowsNierozp = 0;
	}

	//dla każdego zebrać dane poza utworami
	$resultObsluga1= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							  WHERE u_h.numer_obslugi = 1
							  ORDER BY h.kolejnosc ASC");
	
	if($resultObsluga1) {
		$numrowsObsluga1 = pg_numrows($resultObsluga1);
	} else {
		$numrowsObsluga1 = 0;
	}


	$resultArtysta1= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN zgloszenia z ON u.id = z.artysta_id
							  	INNER JOIN harmonogram h ON z.id = h.zgloszenie_id");
	

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{	


		if ( $_POST['button_id'] === 'generuj_harmonogram' ) {

			$szukaj2 = pg_query($con, "SELECT * FROM uzytkownicy_harmonogram WHERE numer_obslugi = 2");

			$licz2 = pg_numrows($szukaj2);

			$szukaj1 = pg_query($con, "SELECT * FROM uzytkownicy_harmonogram WHERE numer_obslugi = 1");

			$licz1 = pg_numrows($szukaj1);

			if($szukaj2 && $szukaj1 && pg_numrows($szukaj2) ==  pg_numrows($szukaj1) ) {

				pg_query($con, "UPDATE uzytkownicy SET flagaKoniec = 1 WHERE id = {$user_data['id']}");
				header("Location: obsluga_mn.php");
				die;

			}
			header("Location: obsluga_hm.php");
			die;
			

		}

		//coś jest dodane:
		$dany_id = $_POST['dany_id'];
		$dany_wybor = $_POST['dany_wybor'];

		//<option value="1">dodaj do harmonogarmu konkursu zgłoszenie o wskazanym id</option>;
		//<option value="2">usuń z harmonogarmu konkursu występ o wskazanym id</option>;
	    //<option value="3">dołącz do zespołu oceniającego występ o wskazanym id</option>;
		//<option value="4">opóść zespół oceniający występ o wskazanym id</option>;
		
		if(!empty($dany_id) && is_numeric($dany_id )) {
			
			if($dany_wybor == '1') {
				
				$wynik1 = pg_query($con, "SELECT z.id FROM zgloszenia z WHERE z.id NOT IN (SELECT h.zgloszenie_id FROM harmonogram h WHERE h.zgloszenie_id IS NOT NULL) AND z.id = '$dany_id' LIMIT 1 ");
				
				/////////////////////
				
				if($wynik1) {
					pg_query($con, "INSERT INTO harmonogram (zgloszenie_id) VALUES ('$dany_id')");

					$resultNowyHid = pg_query($con, "SELECT * FROM harmonogram ORDER BY id DESC LIMIT 1");

					$nowyHid = pg_fetch_assoc($resultNowyHid, 0);

					pg_query($con, "INSERT INTO uzytkownicy_harmonogram (uzytkownik_id, harmonogram_id, numer_obslugi) VALUES ({$user_data['id']}, {$nowyHid['id']}, 1)");

					header("Location: obsluga_hm.php");
					die;
					

				} else {
					$echo1 = false;
					$echo2 = false;
					$echo3 = false;
					$echo4 = false;
					$echo5 = false;
					$echo6 = false;
					$echo7 = true;

				}
				

			} else {

				$wynik2 = pg_query($con, "SELECT * FROM harmonogram h WHERE h.id = '$dany_id' LIMIT 1");

				if($wynik2 && pg_numrows($wynik2) > 0) {
					//echo "WYBÓR 2";
					if($dany_wybor == '2') {

						pg_query($con, "DELETE FROM uzytkownicy_harmonogram u_h WHERE u_h.harmonogram_id = '$dany_id' ");

						pg_query($con, "DELETE FROM harmonogram h WHERE h.id = '$dany_id' ");

						header("Location: obsluga_hm.php");
						die;

					}
					
					
					if($dany_wybor == '3') {
						//echo "WYBÓR 3";
						$wynik21 = pg_query($con, "SELECT * FROM uzytkownicy_harmonogram WHERE harmonogram_id = '$dany_id' AND uzytkownik_id = {$user_data['id']} AND numer_obslugi = 2 LIMIT 1 ");

						if($wynik21 && pg_numrows($wynik21) == 0) {

							$wynik211 = pg_query($con, "SELECT * FROM uzytkownicy_harmonogram WHERE harmonogram_id = '$dany_id' AND uzytkownik_id = {$user_data['id']} AND numer_obslugi = 1 LIMIT 1 ");

							if($wynik211 && pg_numrows($wynik211) == 0) {

								pg_query($con, "INSERT INTO uzytkownicy_harmonogram (uzytkownik_id, harmonogram_id, numer_obslugi) VALUES ({$user_data['id']}, '$dany_id', 2)");

								header("Location: obsluga_hm.php");
								die;

							} else {
								$echo1 = false;
								$echo2 = false;
								$echo3 = false;
								$echo4 = false;
								$echo5 = false;
								$echo6 = true;
								$echo7 = false;
							}

						} else {
							$echo1 = false;
							$echo2 = false;
							$echo3 = false;
							$echo4 = false;
							$echo5 = true;
							$echo6 = false;
							$echo7 = false;
						}
			
					}
					
					
					if($dany_wybor == '4') {
						//echo "WYBÓR 4";

						$wynik22 = pg_query($con, "SELECT * FROM uzytkownicy_harmonogram WHERE uzytkownik_id = {$user_data['id']} AND harmonogram_id = '$dany_id' AND numer_obslugi = 2 LIMIT 1 ");

						if($wynik22 && pg_numrows($wynik22) > 0) {

							pg_query($con, "DELETE FROM uzytkownicy_harmonogram WHERE uzytkownik_id = {$user_data['id']} AND harmonogram_id = '$dany_id' AND numer_obslugi = 2");

							header("Location: obsluga_hm.php");
							die;

						} else {
							$echo1 = false;
							$echo2 = false;
							$echo3 = false;
							$echo4 = true;
							$echo5 = false;
							$echo6 = false;
							$echo7 = false;
						}
					}
					


				} else {
					$echo1 = false;
					$echo2 = false;
					$echo3 = true;
					$echo4 = false;
					$echo5 = false;
					$echo6 = false;
					$echo7 = false;

				}
			//////////////////////////
			}

			
		} else{
			
			if($dany_wybor == '1') {
				$echo1 = false;
				$echo2 = true;
				$echo3 = false;
				$echo4 = false;
				$echo5 = false;
				$echo6 = false;
				$echo7 = false;
				
			} else {
				$echo1 = true;
				$echo2 = false;
				$echo3 = false;
				$echo4 = false;
				$echo5 = false;
				$echo6 = false;
				$echo7 = false;
				
			}

			
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
		flex-direction: column;
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
		background-color: rgba(30, 100, 90, 1);
		border: 1px solid white;
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

	#button5{

		position:fixed;
		bottom: 20px;
		right: 20px;
		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgrey;
		border: none;
	}

	#buttonM{

		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgreen;
		border: none;
	}



	#smallBox1{
		display: flex;
		margin: 10px;
		//height: 200px;
		background-color: rgba(30, 100, 90, 0.9);
		padding: 10px;
		color: white;

	}

	#smallBox2{
		display: flex;
		margin: 10px;
		//height: 200px;
		background-color: rgba(30, 100, 90, 0.9);
		padding: 10px;
		color: white;

	}

	#smallBox3{
		display: flex;
		margin: 10px;
		background-color: rgba(100, 100, 90, 0.8);
		padding: 10px;
		color: white;

	}

	#boxPlus {
		display: flex;
  		flex-direction: row;
  		align-items: center;
  		gap: 7px;
	}

	#echoo{
		position: fixed;
		bottom: 10px;
		left: 10px;
		background-color: rgba(0, 0, 0, 0);
		padding: 0px;

	}

	select{
	  border:none;
	  padding: 10px 20px;
	  border-radius:5px;
	}

	select:focus{
	  outline:none;
	}


	table tr{
            font-size: 12px;
            font-weight: 550;
        }



	</style>

<body>
	
	<div id="background">
	<div id="bigBox">


		<div id="smallBox3">
		<form method="post" >

		<div id = "boxPlus">

		<input type="text" id="text" style="width: 30px" maxlength="15" placeholder="id" name="dany_id"><br><br>

		<select name="dany_wybor" id="dany_wybor">
		  <option value="1">dodaj do harmonogarmu konkursu zgłoszenie o wskazanym id</option>
		  <option value="2">usuń z harmonogarmu konkursu wykonanie o wskazanym id</option>
		  <option value="3">dołącz do zespołu oceniającego wykonanie o wskazanym id</option>
		  <option value="4">opóść zespół oceniający wykonanie o wskazanym id</option>
		</select>

		<input id="button1" type="submit" value="dodaj/usuń"><br><br>

		</div>
		</form>
		</div>

		<div style="font-size: 15px; color: white;">*Żeby możliwe było wygenerowanie harmonogramu, muszą być przynajmniej 3 zaakceptowane zgłoszenia i każde ze zgłoszeń nich musi mieć pełen zespoł oceniający (obu obsługujących)</div>

		
		<div id="smallBox2">
			<form method="post">
				<div style="font-size: 25px; margin: 10px;color: white;">Zgłoszenia nierozpatrzone:</div>
				<table border="1" align=center cellspacing="2" cellpadding="8">
				
				<?php
				echo "<tr>\n";
				echo " <td>" . "   " . "</td>\n";
				echo " <td>" . "id zgłoszenia" . "</td>\n";
				echo " <td>" . "data zgłoszenia" . "</td>\n";
				echo " <td>" . "artysta" . "</td>\n";
				echo " <td>" . "tytuł pierwszego utworu" . "</td>\n";
				echo " <td>" . "tytuł drugiego utworu" . "</td>\n";
				echo " <td>" . "tytuł trzeciego utworu" . "</td>\n";
				
			 for($rj = 0; $rj < $numrowsNierozp; $rj++) {
			 	echo "<tr>\n";

			 	$rowNierozp = pg_fetch_array($resultNierozp, $rj);

			 	$resultUtworNie1= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id WHERE z_u.zgloszenie_id = {$rowNierozp['id']} AND z_u.numer_utworu = 1 LIMIT 1");

				$resultUtworNie2= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id WHERE z_u.zgloszenie_id = {$rowNierozp['id']} AND z_u.numer_utworu = 2 LIMIT 1");
				
				$resultUtworNie3= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id WHERE z_u.zgloszenie_id = {$rowNierozp['id']} AND z_u.numer_utworu = 3 LIMIT 1");	

				$rowUtworNie1 = pg_fetch_array($resultUtworNie1, 0);
				$rowUtworNie2 = pg_fetch_array($resultUtworNie2, 0);
				$rowUtworNie3 = pg_fetch_array($resultUtworNie3, 0);
				echo " <td>" . number_format($rj + 1) . "</td>\n";
				echo " <td>" . $rowNierozp['id'] . "</td>\n";
				echo " <td>" . $rowNierozp['data_zgloszenia'] . "</td>\n";
				echo " <td>" . $rowNierozp['imie'] . "   " . $rowNierozp['nazwisko'] . "</td>\n";
				echo " <td>" . $rowUtworNie1['tytul'] . "</td>\n";
				echo " <td>" . $rowUtworNie2['tytul'] . "</td>\n";
				echo " <td>" . $rowUtworNie3['tytul'] . "</td>\n";
				echo "<tr>\n";
				}
				 
				?>
				</table>
			</form>
		</div>
	

		<div id="smallBox1">
			<form method="post">
				<div style="font-size: 25px; margin: 10px;color: white;">Zgłoszenia (występy) przygotowanie do uwzględnienia w harmonogramie i oceny:</div>
				<table border="1" align=center cellspacing="2" cellpadding="8">
				<?php

				 // Przechodzimy po wierszach wyniku.
				echo "<tr>\n";
				echo " <td>" . "   " . "</td>\n";
				echo " <td>" . "id wykonania" . "</td>\n";
				echo " <td>" . "artysta" . "</td>\n";
				echo " <td>" . "tytuł pierwszego utworu" . "</td>\n";
				echo " <td>" . "tytuł drugiego utworu" . "</td>\n";
				echo " <td>" . "tytuł trzeciego utworu" . "</td>\n";
				echo " <td>" . "pierwszy obsługujący" . "</td>\n";
				echo " <td>" . "drugi obslugujący" . "</td>\n";
				
				 for($ri = 0; $ri < $numrowsObsluga1; $ri++) {
				 	
				 	echo "<tr>\n";
				 	
				 	$rowObsluga1 = pg_fetch_array($resultObsluga1, $ri);

				 	$resultArtysta1= pg_query($con, "SELECT
								u.imie, u.nazwisko
							  FROM uzytkownicy u
							  	INNER JOIN zgloszenia z ON u.id = z.artysta_id
							  	INNER JOIN harmonogram h ON z.id = h.zgloszenie_id
							  	WHERE h.zgloszenie_id = {$rowObsluga1['zgloszenie_id']}");

				 	$rowArtysta1 = pg_fetch_array($resultArtysta1, 0);

				 		
				 		$resultObsluga2= pg_query($con, "SELECT
								u.imie, u.nazwisko, h.id, h.zgloszenie_id
							  FROM uzytkownicy u
							  	INNER JOIN uzytkownicy_harmonogram u_h ON u.id = u_h.uzytkownik_id
							  	INNER JOIN harmonogram h ON h.id = u_h.harmonogram_id
							   WHERE  h.id = {$rowObsluga1['id']} AND  u_h.numer_obslugi = 2 
							  LIMIT 1");
						
					 	if($resultObsluga2 && pg_numrows($resultObsluga2) > 0 ) {

					 	$rowObsluga2 = pg_fetch_array($resultObsluga2, 0);
					 	$flagaPustaObsluga2 = 0;

						 } else {
						 	$flagaPustaObsluga2 = 1;
						 }
					
					$resultUtwor1= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowObsluga1['zgloszenie_id']} AND z_u.numer_utworu = 1 LIMIT 1");

					$resultUtwor2= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowObsluga1['zgloszenie_id']} AND z_u.numer_utworu = 2 LIMIT 1");

					$resultUtwor3= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowObsluga1['zgloszenie_id']} AND z_u.numer_utworu = 3 LIMIT 1");
					
					$rowUtwor1 = pg_fetch_array($resultUtwor1, 0);
					$rowUtwor2 = pg_fetch_array($resultUtwor2, 0);
					$rowUtwor3 = pg_fetch_array($resultUtwor3, 0);
					
				 	echo " <td>" . number_format($ri + 1) . "</td>\n";
				 	echo " <td>" . $rowObsluga1['id'] . "</td>\n";
				 	echo " <td>" . $rowArtysta1['imie'] . "   " . $rowArtysta1['nazwisko'] . "</td>\n";
					echo " <td>" . $rowUtwor1['tytul'] . "</td>\n";
					echo " <td>" . $rowUtwor2['tytul'] . "</td>\n";
					echo " <td>" . $rowUtwor3['tytul'] . "</td>\n";
					echo " <td>" . $rowObsluga1['imie'] . "   " . $rowObsluga1['nazwisko'] . "</td>\n";
					
					if ($flagaPustaObsluga2 == 1) {
						echo " <td>" . "brak drugiego członka obsługi" . "</td>\n";	
					} else {
						echo " <td>" . $rowObsluga2['imie'] . "   " . $rowObsluga2['nazwisko'] . "</td>\n";	
					}
					
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


	<form method="POST">
		<button name="button_id" type="submit" value="generuj_harmonogram">Wszystkie przygotowane zgłoszenia są gotowe - wygeneruj charmonogram!</button>
	</form>


	<div id = "echoo">
		<?php
		if($echo1) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Proszę wskazać występ!" . '</span>';}
		if($echo2) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Proszę wskazać zgłoszenie!" . '</span>';}
		if($echo3) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Proszę wskazać id z listy występów!" . '</span>';}
		if($echo4) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Nie należysz do zespołu oceniającego ten występ!" . '</span>';}
		if($echo5) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Ten zespół jest już pełen!" . '</span>';}
		if($echo6) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Już należysz do tego zespołu!" . '</span>';}
		if($echo6) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Proszę wskazać id z listy zgłoszeń!" . '</span>';}
		?>
	</div>

	</div>
	</div>
</body>
</html>