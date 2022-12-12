<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//strona po zalogowaniu uzytkownika
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="fonts/fontawesome/fontawesome/css/all.css">
	<style>
	</style>
</head>
<BODY>
	<script type = "text/javascript">
	window.onload = function() {
		if(!window.location.hash) {
			window.location = window.location + '#loaded';
			window.location.reload();
		}
	}
	</script>

	Zalogowano - 
	
	<?php
		error_reporting(0);
		
		$link = mysqli_connect(); // połączenie z BD
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
		
		echo $_SESSION['username']; //informacja o tym kto jest zalogowany
		echo "<br>-----------------------------------";
		date_default_timezone_set('Europe/Warsaw');

		$username = $_SESSION['username'];
		
		//informacja o ostatniej probie wlamania sie na konto
		$breakins = mysqli_query($link, "SELECT * FROM break_ins WHERE username='$username' ORDER BY datetime DESC LIMIT 1"); // wiersze, w którym login=login z formularza
		foreach ($breakins as $row) {
			if($row['datetime'] != ""){
				echo "<br><p style='color: red';>Ostatnia próba włamania:<br>DATA: " . $row['datetime'] . "<br>IP: " . $row['ip'] . "</p>";			
			}
		}
		
		mysqli_close($link);
	?>
		
	<br><a href ='myspotify.php'>MySpotify</a>
	<br><a href ='logout.php'>Wyloguj</a><br />
</BODY>
</HTML>