<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$plugin_id = $_POST["pluginID"];

	//Connect to data base
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$table_ = $sender ."_Plugins_Relations";
	$sql = "SELECT Plugin_State FROM $table_ WHERE ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_state = $row["Plugin_State"];
		}
	}

	if ( $plugin_state == 0 ) { //Deactivated
		$sql = "UPDATE $table_ SET Plugin_State=1 WHERE ID=$plugin_id";
		$response = "activated";
	} elseif ( $plugin_state == 1 ) { //Activated
		$sql = "UPDATE $table_ SET Plugin_State=0 WHERE ID=$plugin_id";
		$response = "deactivated";
	}

	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>