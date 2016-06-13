<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
		die();
	}

	$url_template = "http://".$_SERVER[HTTP_HOST]."/Library/Authors/".$sender."/Album/";

	$getTableId = $_POST["tableId"];
	$getClubId = $_POST["clubId"];
	$getNewLogo = $_POST["pictureId"];

	//Connect to the Database
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE ".$getTableId." SET Club_Logo='".$url_template.$getNewLogo."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>