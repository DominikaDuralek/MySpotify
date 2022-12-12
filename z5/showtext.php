<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="fonts/fontawesome/fontawesome/css/all.css">
	<style>
	</style>
</head>
<BODY>
	<b>TEKST: </b><br>
	<?php
	$dbhost="";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	if (!$connection)
	{
	echo " MySQL Connection error." . PHP_EOL;
	echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Error: " . mysqli_connect_error() . PHP_EOL;
	exit;
	}
	$connection->query("SET NAMES 'utf8'");
	
	$lyrics = $_GET['lyrics'];
	
	echo $lyrics;
	
	mysqli_close($connection);
	?>
	
	<br><br><a href='myspotify.php'>Poprzednia strona</a><br><br>
</BODY>
</HTML>