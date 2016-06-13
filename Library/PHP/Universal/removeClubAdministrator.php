<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
		die();
	}

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$getAdminId = $_POST["adminId"];

	$clubOwner = explode("_", $getClubTable)[0];
	
	if ( $sender == $clubOwner && $sender == $getAdminId ) {
		$responce = "CDUSO";
	} elseif ( $clubOwner == $getAdminId ) {
		$responce = "CDO";
	} else {

		//Connect to the database
		include "dataBase.php";

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		//Collect administrators
		$sql = "SELECT Administrators FROM ".$getClubTable." WHERE ID=".$getClubId;
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getAdministrators = explode(",", $row["Administrators"]);
			}
		}

		$reviseAdministrators = array();
		foreach ($getAdministrators as $adminId) {
			if ( $adminId != $getAdminId ) {
				array_push($reviseAdministrators, $adminId);
			}
		}

		$reviseAdministrators = implode(",", $reviseAdministrators);

		//Update administrators
		$sql = "UPDATE ".$getClubTable." SET Administrators='".$reviseAdministrators."' WHERE ID=".$getClubId;
		$conn->query($sql);

		//Close the connection
		$conn->close();

		//Return responce
		$responce = "READY";
	}

	//Return responce
	echo $responce;
?>