<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }
	
	$getSearch = htmlentities(str_replace(" ", "", $_POST["pluginID"]));
	$response_plugins = array();

	//Connect to the dataBase
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Select all bloggers with name like the search
	$sql = "SELECT Plugin_Name, Plugin_Slug, Plugin_Author, Plugin_Store_State FROM Plugin_Store WHERE Plugin_Slug LIKE '$getSearch%' AND Plugin_Store_State=1";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push($response_plugins, $row["Plugin_Name"] ."~". $row["Plugin_Slug"] ."~". $row["Plugin_Author"]);
		}
	} else {
		$response_plugins = "NF:(";
	}

	//Close the connection
	$conn->close();

	//Bind results
	if ( is_array($response_plugins) ) { $response_plugins = implode(",", $response_plugins); }

	//Return responce
	echo $response_plugins;
?>