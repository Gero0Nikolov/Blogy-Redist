<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	$getSearch = htmlentities(str_replace(" ", "", $_POST["bloggerID"]));
	$responce = array();

	//Connect to the dataBase
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Select all bloggers with name like the search
	$sql = "SELECT Author_UID FROM WorldBloggers WHERE Nickname LIKE '$getSearch%'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push($responce, $row["Author_UID"]);
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