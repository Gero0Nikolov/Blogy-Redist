<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }
	
	$getSearch = htmlentities(str_replace(" ", "", $_POST["clubID"]));
	$responce = array();

	//Connect to the dataBase
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Select all bloggers with name like the search
	$sql = "SELECT Club_Slug, Owner_ID FROM worldClubs WHERE Club_Slug LIKE '$getSearch%' AND ( Club_Type='PUBLIC' OR Club_Type='CLOSED' )";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push($responce, $row["Club_Slug"] ."~". $row["Owner_ID"]);
		}
	} else {
		$responce = "NF:(";
	}

	//Close the connection
	$conn->close();

	//Bind results
	if ( is_array($responce) ) { $responce = implode(",", $responce); }

	//Return responce
	echo $responce;
?>