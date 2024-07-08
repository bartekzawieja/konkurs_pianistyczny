<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

	session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

	$echo1 = false;

	//dla każdego zebrać dane poza utworami
	
	$resultOcena= pg_query($con, "SELECT h.id, h.zgloszenie_id
							       FROM harmonogram h
							  	   INNER JOIN uzytkownicy_harmonogram u_h ON h.id = u_h.harmonogram_id
							       WHERE u_h.uzytkownik_id = {$user_data['id']} AND u_h.numer_obslugi = 1 AND h.ocena1 IS NULL OR
							       u_h.uzytkownik_id = {$user_data['id']} AND u_h.numer_obslugi = 2 AND h.ocena2 IS NULL
							       ORDER BY h.kolejnosc ASC");
	
	if($resultOcena) {
		$numrowsOcena = pg_numrows($resultOcena);
	} else {
		$numrowsOcena = 0;
	}
	

	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{	
		//coś jest dodane:
		$dany_id = $_POST['dany_id'];
		$dana_ocena = $_POST['dana_ocena'];

		
		if(!empty($dany_id) && is_numeric($dany_id )) {

			
			//czy dany występ jest w tych, których oceniającym 1 lub oceniajacym 2 jest uzytkownik
			$idCheckOg = pg_query($con, "SELECT h.id
							       	FROM harmonogram h
							  	    INNER JOIN uzytkownicy_harmonogram u_h ON h.id = u_h.harmonogram_id
							        WHERE
							        (u_h.uzytkownik_id = {$user_data['id']} AND u_h.numer_obslugi = 1 AND h.ocena1 IS NULL AND h.id = $dany_id)
							        OR
							        (u_h.uzytkownik_id = {$user_data['id']} AND u_h.numer_obslugi = 2 AND h.ocena2 IS NULL AND h.id = $dany_id)
							        LIMIT 1");
				

				if($idCheckOg && pg_numrows($idCheckOg) > 0) {
					
					//czy dany występ jest w tych, których oceniającym 1 jest uzytkownik
					$idCheck1 = pg_query($con, "SELECT *
							       	  FROM harmonogram h
							  	      INNER JOIN uzytkownicy_harmonogram u_h ON h.id = u_h.harmonogram_id
							          WHERE
							          u_h.uzytkownik_id = {$user_data['id']} AND u_h.numer_obslugi = 1 AND h.ocena1 IS NULL AND h.id = $dany_id
							          LIMIT 1");



					if($dana_ocena == 1) {
						
						if($idCheck1 && pg_numrows($idCheck1) > 0) {
							$gdzieOcena1 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena1 = 1 WHERE harmonogram.id = {$gdzieOcena1['id']}");
						} else {
							$gdzieOcena2 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena2 = 1 WHERE harmonogram.id = {$gdzieOcena2['id']}");
						}
						
				
					}

					if($dana_ocena == 2) {
						
						if($idCheck1 && pg_numrows($idCheck1) > 0) {
							$gdzieOcena1 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena1 = 2 WHERE harmonogram.id = {$gdzieOcena1['id']}");
						} else {
							$gdzieOcena2 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena2 = 2 WHERE harmonogram.id = {$gdzieOcena2['id']}");
						}
						
				
					}

					if($dana_ocena == 3) {
						
						if($idCheck1 && pg_numrows($idCheck1) > 0) {
							$gdzieOcena1 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena1 = 3 WHERE harmonogram.id = {$gdzieOcena1['id']}");
						} else {
							$gdzieOcena2 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena2 = 3 WHERE harmonogram.id = {$gdzieOcena2['id']}");
						}
						
					}

					if($dana_ocena == 4) {
						
						if($idCheck1 && pg_numrows($idCheck1) > 0) {
							$gdzieOcena1 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena1 = 4 WHERE harmonogram.id = {$gdzieOcena1['id']}");
						} else {
							$gdzieOcena2 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena2 = 4 WHERE harmonogram.id = {$gdzieOcena2['id']}");
						}
						
					}

					if($dana_ocena == 5) {
						
						if($idCheck1 && pg_numrows($idCheck1) > 0) {
							$gdzieOcena1 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena1 = 5 WHERE harmonogram.id = {$gdzieOcena1['id']}");
						} else {
							$gdzieOcena2 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena2 = 5 WHERE harmonogram.id = {$gdzieOcena2['id']}");
						}
						
					}

					if($dana_ocena == 6) {
						
						if($idCheck1 && pg_numrows($idCheck1) > 0) {
							$gdzieOcena1 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena1 = 6 WHERE harmonogram.id = {$gdzieOcena1['id']}");

						} else {
							$gdzieOcena2 = pg_fetch_array($idCheckOg, 0);
							pg_query($con, "UPDATE harmonogram SET ocena2 = 6 WHERE harmonogram.id = {$gdzieOcena2['id']}");
						}
						
					}

					header("Location: obsluga_oc.php");
					die;
					

				} else {
					$echo1 = true;
				}
		

	} else {
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
		background-color: rgba(139, 0, 0, 1);
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

	#buttonM{

		padding: 10px;
		width: 200px;
		color: black;
		background-color: lightgreen;
		border: none;
	}



	#smallBox1{
		display: flex;
		margin: 20px;
		background-color: rgba(139, 0, 0, 0.9);
		padding: 10px;
		color: white;

	}

	#smallBox3{
		display: flex;
		margin: 20px;
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
            font-size: 15px;
            font-weight: 550;
        }


	</style>

<body>
	
	<div id="background">
	<div id="bigBox">


		<div id="smallBox3">
		<form method="post" >

		<div style="font-size: 30px; margin: 10px;color: white;">Oceń występ </div>

		<div id = "boxPlus">

		<input type="text" id="text" style="width: 30px" maxlength="15" placeholder="id" name="dany_id"><br><br>

		<select name="dana_ocena" id="dana_ocena">
		  <option value="1">1</option>
		  <option value="2">2</option>
		  <option value="3">3</option>
		  <option value="4">4</option>
		  <option value="5">5</option>
		  <option value="6">6</option>
		</select>

		<input id="button1" type="submit" value="oceń"><br><br>

		</div>
		</form>
		</div>
	

		<div id="smallBox1">
			<form method="post">
				<div style="font-size: 25px; margin: 10px;color: white;">Występy oczekujące na ocenę: </div>
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

				 for($ri = 0; $ri < $numrowsOcena; $ri++) {
				 	
				 	echo "<tr>\n";
				 	
				 	$rowOcena = pg_fetch_array($resultOcena, $ri);

				 	$resultArtysta= pg_query($con, "SELECT
								u.imie, u.nazwisko
							  FROM uzytkownicy u
							  	INNER JOIN zgloszenia z ON u.id = z.artysta_id
							  	INNER JOIN harmonogram h ON z.id = h.zgloszenie_id
							  	WHERE h.zgloszenie_id = {$rowOcena['zgloszenie_id']}");

				 	$rowArtysta = pg_fetch_array($resultArtysta, 0);
					
					$resultUtwor1= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowOcena['zgloszenie_id']} AND z_u.numer_utworu = 1 LIMIT 1");

					$resultUtwor2= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowOcena['zgloszenie_id']} AND z_u.numer_utworu = 2 LIMIT 1");

					$resultUtwor3= pg_query($con, "SELECT ut.tytul FROM utwory ut INNER JOIN zgloszenia_utwory z_u ON ut.id = z_u.utwor_id
							  	WHERE z_u.zgloszenie_id = {$rowOcena['zgloszenie_id']} AND z_u.numer_utworu = 3 LIMIT 1");
					
					$rowUtwor1 = pg_fetch_array($resultUtwor1, 0);
					$rowUtwor2 = pg_fetch_array($resultUtwor2, 0);
					$rowUtwor3 = pg_fetch_array($resultUtwor3, 0);
					
				 	echo " <td>" . number_format($ri + 1) . "</td>\n";
				 	echo " <td>" . $rowOcena['id'] . "</td>\n";
				 	echo " <td>" . $rowArtysta['imie'] . "   " . $rowArtysta['nazwisko'] . "</td>\n";
					echo " <td>" . $rowUtwor1['tytul'] . "</td>\n";
					echo " <td>" . $rowUtwor2['tytul'] . "</td>\n";
					echo " <td>" . $rowUtwor3['tytul'] . "</td>\n";
					
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
		if($echo1) {echo '<span style="color: white; font-size: 20px; font-weight: bold"> ' . "Proszę wskazać id z listy występów!" . '</span>';}
		?>
	</div>

	</div>
	</div>
</body>
</html>