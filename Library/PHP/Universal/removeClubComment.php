<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$commentId = $_POST["commentId"];

	$tableName = $getClubTable."_Story_Comments_".$getClubId;

	//Include bundle
	include "dataBase.php";

	//Connect to the Database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "DELETE FROM $tableName WHERE ID=$commentId";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Set responce
	$responce = "READY";

	//Return responce
	echo $responce;
?>