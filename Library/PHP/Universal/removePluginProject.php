<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	//Include bundle
	include "functions.php";
	include "dataBase.php";

	$getPluginSlug = secure_input($_POST["pluginSlug"], false);

	//Connect to the Database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Plugin_Store_ID FROM ".$sender."_Plugins_Relations WHERE Plugin_Slug='$getPluginSlug' AND Plugin_Author='$sender'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_store_id = $row["Plugin_Store_ID"];
		}
	}

	$sql = "DELETE FROM ".$sender."_Plugins_Relations WHERE Plugin_Slug='$getPluginSlug' AND Plugin_Author='$sender'";
	$conn->query($sql);

	if ( $plugin_store_id != -1 ) {
		//Get plugin folks
		$sql = "SELECT Folks FROM Plugin_Store WHERE ID=$plugin_store_id";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ( !empty($row["Folks"]) ) { $plugin_folks = explode(",", $row["Folks"]); }
			}
		}

		//Delete from store
		$sql = "DELETE FROM Plugin_Store WHERE ID=$plugin_store_id";
		$conn->query($sql);	

		if ( !empty($plugin_folks) ) {
			if ( is_array($plugin_folks) ) {
				foreach ($plugin_folks as $folk) {
					$table_ = $folk ."_Plugins_Relations";
					$sql = "DELETE FROM $table_ WHERE Plugin_Store_ID=$plugin_store_id";
					$conn->query($sql);
				}
			} elseif ( !is_array($plugin_folks) ) {
				$table_ = $plugin_folks ."_Plugins_Relations";
				$sql = "DELETE FROM $table_ WHERE Plugin_Store_ID=$plugin_store_id";
				$conn->query($sql);
			}
		}
	}

	//Close the connection
	$conn->close();

	//Remove from HDD
	remove_dir("../../Authors/$sender/Plugins/$getPluginSlug");

	echo "READY";
?>