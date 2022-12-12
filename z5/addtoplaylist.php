<?php
	session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: logowanie.php');
		exit();
	}

	$dbhost="";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	if (!$connection)
	{
	echo " MySQL Connection error." . PHP_EOL;
	echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Error: " . mysqli_connect_error() . PHP_EOL;
	exit;
	}

	$idpl = $_POST['addtoplaylist'];
	$ids = $_POST['ids'];
	
	$sql = "INSERT INTO playlistdatabase (idpl, ids) 
	VALUES ('$idpl', '$ids')";
	mysqli_query($connection, $sql);
	mysqli_close($connection);
	
	header('Location: myspotify.php');	
?>