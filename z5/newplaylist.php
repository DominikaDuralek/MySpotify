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
		i{
			color: black;
			font-size: 24px;
		}
		
		input[type=button], input[type=submit]{
			background-color: CornflowerBlue;
			border-color: DodgerBlue;
			color: white;
		}
		
		input[type=button], input[type=submit]:hover{
			background-color: RoyalBlue;
			border-color: DodgerBlue;
			color: white;
		}
	</style>
</head>
<BODY>
	<b>NOWA PLAYLISTA</b><br>
	<br><form action="addplaylist.php" enctype="multipart/form-data" method="post">
		Name: <br><input type="text" name="name" maxlength="20" size="20">
		<input type="hidden" name="public" value="0" />
		&ensp;&ensp;Publiczna: <input type="checkbox" name="public" value="1"><br>	
		<br><input type="submit" value="Send" />
		</form><br>
		
	<br><a href ='myspotify.php'>Poprzednia strona</a><br />
</BODY>
</HTML>