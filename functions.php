<?php

function check_login($con)
{

	if(isset($_SESSION['user_id']))
	{

		$id = $_SESSION['user_id'];
		$query = "SELECT * FROM uzytkownicy WHERE id = $id limit 1";

		$result = pg_query($con,$query);
		if($result && pg_numrows($result) > 0)
		{

			$user_data = pg_fetch_assoc($result);
			return $user_data;
		}
	}

	header("Location: login.php");
	die;

}