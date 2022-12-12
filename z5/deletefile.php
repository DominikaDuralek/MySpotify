<?php
	//usuwanie pliku
	session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	$ids = $_GET['ids']; //id piosenki do usuniecia
	$playlistname = $_SESSION['playlist'];
	
	$link = mysqli_connect(); // połączenie z BD
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
	
	//pobranie id biezacej playlisty
	$idpl_query = mysqli_query($link, "SELECT idpl FROM playlistname WHERE name='$playlistname'") or die ("DB error: $dbname");
	$idpl_array = mysqli_fetch_array($idpl_query);
	$idpl = $idpl_array[0];
	
	$sql = "DELETE FROM playlistdatabase WHERE idpl='$idpl' AND ids='$ids' LIMIT 1"; //usuniecie pliku z bazy
	
	mysqli_query($link, $sql);
	mysqli_close($link);

	header('Location: myspotify.php');	
?>