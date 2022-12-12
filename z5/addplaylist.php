<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}

session_start();
$user = $_SESSION['username']; //zalogowany uzytkownik

$dbhost="";
$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if (!$connection)
{
echo " MySQL Connection error." . PHP_EOL;
echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
echo "Error: " . mysqli_connect_error() . PHP_EOL;
exit;
}

//id usera
$user_id_query = mysqli_query($connection, "SELECT idu FROM user WHERE login='$user'");
$user_id_array = mysqli_fetch_array($user_id_query);
$user_id = $user_id_array[0];

$datetime = date('Y-m-d H:i:s');

$name = $_POST['name'];
$public = $_POST['public'];

if($public == 1){
	$public_value = 1;
}else{
	$public_value = 0;
}

$matching_name = mysqli_query($connection, "SELECT * FROM playlistname WHERE name='$name'"); // wiersza, w którym login=login z formularza
$matching_name_rekord = mysqli_fetch_array($matching_name); // wiersza z BD, struktura zmiennej jak w BD

if(!$matching_name_rekord){ //jezeli identyczny plik jeszcze nie istnieje
	$query = mysqli_query($connection, "INSERT INTO playlistname (idu, name, public, datetime) 
	VALUES ('$user_id', '$name', '$public_value', '$datetime');") or die ("DB error: $dbname");
}
mysqli_close($connection);

header('Location: myspotify.php');	
?>
