<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$plugins_class = $_POST["pluginsClass"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ( $plugins_class == "featured" ) {
		$store_ids_ = array();

		$sql = "SELECT ID FROM Plugin_Store WHERE Featured=1 AND Plugin_Store_State=1 ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($store_ids_, $row["ID"]);
			}
		}

		$response = implode(",", $store_ids_);
	} elseif ( $plugins_class == "popular" ) {
		$store_ids_ = array();

		$sql = "SELECT ID FROM Plugin_Store WHERE Plugin_Store_State=1 ORDER BY Folks DESC, Likers DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($store_ids_, $row["ID"]);
			}
		}

		$response = implode(",", $store_ids_);
	} elseif ( $plugins_class == "all" ) {
		$store_ids_ = array();

		$sql = "SELECT ID FROM Plugin_Store WHERE Plugin_Store_State=1 ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($store_ids_, $row["ID"]);
			}
		}

		$response = implode(",", $store_ids_);
	} else {
		$store_ids_ = array();

		$sql = "SELECT ID FROM Plugin_Store WHERE Plugin_Slug LIKE '%$plugins_class%' AND Plugin_Store_State=1 ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($store_ids_, $row["ID"]);
			}
		}

		$response = implode(",", $store_ids_);
	}

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>