<?php
	session_start();
	$admin = $_SESSION["admin"];
	if ( !isset($admin) ) { die(); }

	$plugin_id = $_POST["pluginID"];

	include "../PHP/Universal/dataBase.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Folks FROM Plugin_Store WHERE ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			if ( !empty($row["Folks"]) ) { $plugin_folks = explode(",", $row["Folks"]); }
		}
	}

	$sql = "DELETE FROM Plugin_Store WHERE ID=$plugin_id";
	$conn->query($sql);	

	if ( !empty($plugin_folks) ) {
		if ( is_array($plugin_folks) ) {
			foreach ($plugin_folks as $folk) {
				$table_ = $folk ."_Plugins_Relations";
				$sql = "DELETE FROM $table_ WHERE Plugin_Store_ID=$plugin_id";
				$conn->query($sql);
			}
		} elseif ( !is_array($plugin_folks) ) {
			$table_ = $plugin_folks ."_Plugins_Relations";
			$sql = "DELETE FROM $table_ WHERE Plugin_Store_ID=$plugin_id";
			$conn->query($sql);
		}
	}

	//Close the connection
	$conn->close();

	//Return response
	echo "READY";
?>