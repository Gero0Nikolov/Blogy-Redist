<?php
	session_start();
	$sender = $_SESSION["sender"];

	$responce = "";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, Club_Name, Club_Color, Club_Logo FROM ".$sender."_Clubs ORDER BY ID DESC";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getID = $row["ID"];
			$getName = $row["Club_Name"];
			$getColor = $row["Club_Color"];
			$getLogo = $row["Club_Logo"];

			if ($getLogo != "") {
				$responce .= "
					<a href='previewClub.php?".$sender."_Clubs=".$getID."' class='list-row ".$getColor."'>
						<div style='background-image: url(".$getLogo."); background-size: cover; background-position: 50%;' class='club-sidebar-logo'></div>
						<span class='club-name'>".$getName."</span>
					</a>
				";
			} else {
				$responce .= "
					<a href='previewClub.php?".$sender."_Clubs=".$getID."' class='list-row ".$getColor."'>
						<div style='background-color: #fff;' class='club-sidebar-logo'></div>
						<span class='club-name'>".$getName."</span>
					</a>
				";
			}
		}
	} else {
		$responce = "<h2>You don't have any clubs, yet.</h2>";
	}

	//Close the connection
	$conn->close();

	//Return responce
	echo $responce;
?>