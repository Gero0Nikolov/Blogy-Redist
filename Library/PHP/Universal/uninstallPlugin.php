<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }
	$senderFN = $_SESSION['senderFN'];
	$senderLN = $_SESSION['senderLN'];

	$plugin_id = $_POST["pluginID"];

	//Include bundle
	include "functions.php";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get plugin store information
	$sql = "SELECT Folks FROM Plugin_Store WHERE ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			if ( !empty($row["Folks"]) ) { $plugin_folks = explode(",", $row["Folks"]); }
			else { $plugin_folks = array(); }
		}
	}

	//Revise folks
	$revise_folks = array();
	foreach ($plugin_folks as $folk) {
		if ( $folk != $sender && !empty( $folk ) ) {
			array_push($revise_folks, $folk);
		}
	}
	if ( !empty($revise_folks) ) { $plugin_folks = implode(",", $revise_folks); }
	else { $plugin_folks = NULL; }

	//Update plugin folks
	$sql = "UPDATE Plugin_Store SET Folks='$plugin_folks' WHERE ID=$plugin_id";
	$conn->query($sql);

	//User plugin relations
	$table_name = $sender ."_Plugins_Relations";

	//Get plugin information from the user relations
	$sql = "SELECT Plugin_Slug, Plugin_Author FROM $table_name WHERE Plugin_Store_ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_slug = $row["Plugin_Slug"];
			$plugin_author = $row["Plugin_Author"];
		}
	}

	//Remove from user relations
	$sql = "DELETE FROM $table_name WHERE Plugin_Store_ID=$plugin_id";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Delete the Plugin files folder from the Plugin_Files folder
	if ( file_exists( "../../Authors/$sender/Plugins/Plugins_Files/$plugin_slug-$plugin_author" ) ) {
		remove_dir( "../../Authors/$sender/Plugins/Plugins_Files/$plugin_slug-$plugin_author" );
	}

	//Return response
	echo "READY";
?>