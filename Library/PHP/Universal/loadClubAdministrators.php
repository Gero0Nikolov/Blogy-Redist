<?php
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, Owner, Administrators, Promoted FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubAdministrators = explode(",", $row["Administrators"]);
			$getClubPromotions = explode(",", $row["Promoted"]);
		}
	}

	//Check if array
	if ( !is_array($getClubAdministrators) && empty($getClubAdministrators) ) { $getClubAdministrators = array(); }
	if ( !is_array($getClubPromotions) && empty($getClubPromotions) ) { $getClubPromotions = array(); }

	//Build members as ADMINS and PROMOTIONS
	$setStorage = array();
	foreach ($getClubAdministrators as $administrator) {
		if ( !empty($administrator) ) {
			array_push($setStorage, $administrator."~ADMIN");
		}
	}

	foreach ($getClubPromotions as $promotion) {
		if ( !empty($promotion) ) {
			array_push($setStorage, $promotion."~PROMOTION");
		}
	}

	//Close the connection
	$conn->close();

	//Build responce
	$responce = implode(",", $setStorage);

	//Return responce
	echo $responce;
?>