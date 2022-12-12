<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//strona odtwarzacza
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

//pobranie nazwy uzytkownika oraz idu
$user = $_SESSION['username']; //zalogowany uzytkownik
$user_id_query = mysqli_query($connection, "SELECT idu FROM user WHERE login='$user'");
$user_id_array = mysqli_fetch_array($user_id_query);
$user_id = $user_id_array[0];

//jezeli jeszcze nie wybrano playlisty:
if(!isset($_POST['playlist'])){ $_POST['playlist'] = 'brak'; }
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
	<b>User - <?=$user?></b><br>
	
	<br><form action="addfile.php" enctype="multipart/form-data" method="post">
			Wyślij wybrany plik: <input type="file" name="uploaded_file"/><br>
			Tytuł utworu: <br><input type="text" name="title" maxlength="20" size="20"><br>
			Wykonawca: <br><input type="text" name="musician" maxlength="20" size="20"><br>
			<!--Tekst utworu: <br><input type="text" name="lyrics" maxlength="2000" size="20"><br>-->
			Tekst utworu: <br><input type="text" name="lyrics" maxlength="5000" size="20"><br>
			
			</textarea><br>
			
			<label for="music_type">Gatunek:</label><br>
			<select id="music_type" name="music_type">
			  <?php 
			  //wyszukanie dostepnych gatunkow
			  $music_type_query = mysqli_query($connection, "SELECT * FROM musictype") or die ("DB error: $dbname");
			  while ($row = mysqli_fetch_array ($music_type_query))
			  {
				$music_type = $row[1]; //nazwy uzytkownikow
				?>
				<option value=<?=$row[0]?>><?=$music_type?></option>
			  <?php
			  }
			  ?>
			</select><br>
			
			
			<br><input type="submit" value="Send" />
		</form><br>

	<a href ='logout.php'>Wyloguj</a><br />
<br>----------------------------------------------------------------
<br>UTWORY<br>

<form action="myspotify.php" method="post">
<label for="playlist">Wybierz platlistę:</label>
<select id="playlist" name="playlist">
<option value="brak" hidden="hidden"></option>
<?php 
	//pobranie nazw playlist
	$playlistname_query = mysqli_query($connection, "SELECT * FROM playlistname") or die ("DB error: $dbname");
	while ($row = mysqli_fetch_array ($playlistname_query))
	{
		$playlist = $row[2]; //nazwy playlist
		if($row[3] == 1 || $row[1] == $user_id){
		?>
		<option value=<?=$playlist?>><?=$playlist?></option>
	<?php
		}
	}
	?>
	<option value="brak">(brak)</option>
</select>
<input type="submit" value="Pokaż" />
</form>

Nowa playlista: <a href ='newplaylist.php'><i class="fa-solid fa-square-plus" style='font-size:24px;color:black;'></i></a><br><br>

<?php
//wszystkie piosenki z playlisty
if(isset($_POST['playlist']) && $_POST['playlist'] == 'brak'){
	
	$song_query = mysqli_query($connection, "SELECT * FROM song") or die ("DB error: $dbname");
	foreach ($song_query as $row) {
		$ids = $row['ids'];
		$title = $row['title'];
		$musician = $row['musician'];
		$datetime = $row['datetime'];
		$filename = $row['filename'];
		$lyrics = $row['lyrics'];
		$idmt = $row['idmt'];
		//znalezienie gatunku piosenki
		$type_query = mysqli_query($connection, "SELECT * FROM musictype WHERE idmt='$idmt'") or die ("DB error: $dbname");
		while ($row = mysqli_fetch_array ($type_query))
		{
			$type = $row[1];
		}
		
		?>
		------------------------------<i class="fa-solid fa-music" style='font-size:24px;'></i>------------------------------<br><br>
		<div id="song" style="background-color: #d6edff;width:25%;padding:5px;">
		<?php
		
		echo "<b>" . $title . "</b> - " . $musician . " - " . $type . " (added: " . $datetime . ")<br><br><audio controls style='height:25px;width:300px;border-radius:0;background-color:#D3D3D3;'><source src='$filename' type='audio/mpeg'></audio><br><br>";
		//dodawanie piosenki do playlisty
		?>
		<form action="addtoplaylist.php" method="post">
		<input type="hidden" name="ids" value=<?=$ids?> />
		<label for="addtoplaylist">Dodaj do playlisty:</label>
		<select id="addtoplaylist" name="addtoplaylist">
		<?php 
			$playlistname_query = mysqli_query($connection, "SELECT * FROM playlistname") or die ("DB error: $dbname");
			while ($row = mysqli_fetch_array ($playlistname_query))
			{
				$playlist = $row[2]; //nazwy playlist
				if($row[1] == $user_id){
				?>
				<option value=<?=$row[0]?>><?=$playlist?></option>
			<?php
				}
			}
			?>
		</select>
		<input type="submit" value="+" />
		</form>
		
		<a href="showtext.php?lyrics=<?=$lyrics?>">Pokaż tekst</a><br>
	
		</div>
		<?php
	}
}else if(isset($_POST['playlist'])){
	//nazwa biezacej playlisty
	echo "<b>" . $_POST['playlist'] . ":</b><br>";
	$_SESSION['playlist'] = $_POST['playlist']; 
	$playlistname = $_POST['playlist'];
	
	//pobranie id biezacej playlisty
	$playlist_id_query = mysqli_query($connection, "SELECT * FROM playlistname WHERE name='$playlistname'") or die ("DB error: $dbname");
	$playlist_id_array = mysqli_fetch_array($playlist_id_query);
	$playlist_id = $playlist_id_array[0];
	
	//znalezienie rekordow pldb biezacej playlisty
	$pldb_query = mysqli_query($connection, "SELECT * FROM playlistdatabase WHERE idpl='$playlist_id'") or die ("DB error: $dbname");
	while ($pldb = mysqli_fetch_array ($pldb_query))
	{
		$ids = $pldb[2];
		
		$song_query = mysqli_query($connection, "SELECT * FROM song WHERE ids='$ids'") or die ("DB error: $dbname");
		foreach ($song_query as $row) {
			$ids = $row['ids'];
			$title = $row['title'];
			$musician = $row['musician'];
			$datetime = $row['datetime'];
			$filename = $row['filename'];
			$lyrics = $row['lyrics'];
			$idmt = $row['idmt'];
			//znalezienie gatunku piosenki
			$type_query = mysqli_query($connection, "SELECT * FROM musictype WHERE idmt='$idmt'") or die ("DB error: $dbname");
			while ($row = mysqli_fetch_array ($type_query))
			{
				$type = $row[1];
			}
			
			?>
			------------------------------<i class="fa-solid fa-music" style='font-size:24px;'></i>------------------------------<br><br>
			<div id="song" style="background-color: #d6edff;width:25%;padding:5px;">
			<?php
			echo "<b>" . $title . "</b> - " . $musician . " - " . $type . " (added: " . $datetime . ")<br><br><audio controls style='height:25px;width:300px;border-radius:0;background-color:#D3D3D3;'><source src='$filename' type='audio/mpeg'></audio><br><br>";
			
			//usuwanie piosenki z playlisty
			$playlist_query = mysqli_query($connection, "SELECT * FROM playlistname WHERE name='$playlistname' LIMIT 1") or die ("DB error: $dbname");
			
			$user_id_query = mysqli_query($connection, "SELECT idu FROM user WHERE login='$user'");
			$user_id_array = mysqli_fetch_array($user_id_query);
			$user_id = $user_id_array[0];
			
			$can_modify = 0;
			while ($row = mysqli_fetch_array ($playlist_query))
			{
				if($row[1] == $user_id){$can_modify = 1;}
			}
			if($can_modify == 1){
				echo "<a href ='deletefile.php?ids=$ids'>Usun z playlisty</a><br>";
			}
			
			//dodawanie piosenki do playlisty
			?>
			<form action="addtoplaylist.php" method="post">
			<input type="hidden" name="ids" value=<?=$ids?> />
			<label for="addtoplaylist">Dodaj do playlisty:</label>
			<select id="addtoplaylist" name="addtoplaylist">
			<?php 
				$playlistname_query = mysqli_query($connection, "SELECT * FROM playlistname") or die ("DB error: $dbname");
				while ($row = mysqli_fetch_array ($playlistname_query))
				{
					$playlist = $row[2]; //nazwy playlist
					if($row[1] == $user_id){
					?>
					<option value=<?=$row[0]?>><?=$playlist?></option>
				<?php
					}
				}
				?>
			</select>
			<input type="submit" value="+" />
			</form>
			
			<a href="showtext.php?lyrics=<?=$lyrics?>">Pokaż tekst</a><br>
			
			</div>

			<?php
		}
	}
}

mysqli_close($connection);
?>

</BODY>
</HTML>