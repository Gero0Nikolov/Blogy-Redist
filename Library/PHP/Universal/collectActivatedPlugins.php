<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$visitor = $_POST["visitor"];
	$blogger_ = $_POST["blogger"];
	$response = "";
	$storage_ = array();

	//Connect to data base
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ( $visitor == 0 ) { $table_ = $sender ."_Plugins_Relations"; }
	elseif ( $visitor == 1 ) { $table_ = $blogger_ ."_Plugins_Relations"; } 

	$sql = "SELECT * FROM $table_ WHERE Plugin_State=1";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$pluginID = $row["ID"];
			$pluginName = $row["Plugin_Name"];
			$pluginSlug = $row["Plugin_Slug"];
			$pluginPath = $row["Plugin_Address"];
			$pluginStoreID = $row["Plugin_Store_ID"];
			$build_stack = $pluginID ."~". $pluginName ."~". $pluginSlug ."~". $pluginPath ."~". $pluginStoreID;
			array_push($storage_, $build_stack);
		}
	}

	//Close the connection
	$conn->close();

	//Convert and prepare for response
	if ( !empty($storage_) ) { $response = implode(",", $storage_); }

	//Return response
	echo $response;
?>