<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getNewLogo = $_POST["pictureId"];
	$isMobile = $_POST["mobile"];
	$responce = "";

	//Connect to the Database
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//Check if user has clubs
	$sql = "SELECT ID FROM ".$sender."_Clubs LIMIT 1";
	$pick = $conn->query($sql);
	if ($pick->num_rows <= 0) { $responce = "<h1 class='error-message'>It seems that you don't have clubs :(</h1>"; }
	else {
		$sql = "SELECT ID, Club_Name, Club_Logo FROM ".$sender."_Clubs ORDER BY ID";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$responce .= "
					<button type='button' class='inline-button' onclick='setNewClubLogo(".$isMobile.", \"".$sender."_Clubs\", ".$row["ID"].", \"".$getNewLogo."\");'>
						<div style='background-image: url(".$row["Club_Logo"]."); background-size: cover; background-position: 50%;' class='img'></div>
						".$row["Club_Name"]."
					</button>
				";
			}
		}
	}

	//Close the connection
	$conn->close();

	//Return responce
	echo $responce;
?>