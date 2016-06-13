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

	$sql = "UPDATE Plugin_Store SET Plugin_Store_State=0 WHERE ID=$plugin_id";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return response
	echo "READY";
?>