<?php
	session_start();
	$admin = $_SESSION["admin"];
	if ( !isset($admin) ) { die(); }

	$plugin_id = $_POST["pluginID"];
	$plugin_slug = $_POST["pluginSlug"];
	$plugin_author = $_POST["pluginAuthor"];

	include "../PHP/Universal/functions.php";

	include "../PHP/Universal/dataBase.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE Plugin_Store SET Plugin_Store_State=1 WHERE ID=$plugin_id";
	$conn->query($sql);

	$table_ = $plugin_author ."_Plugins_Relations";
	$sql = "UPDATE $table_ SET Plugin_Store_ID=$plugin_id WHERE Plugin_Slug='$plugin_slug' AND Plugin_Author='$plugin_author'";
	$conn->query($sql);

	//Send notification
	send_custom_mail("Blogy Admin", $plugin_author, "plugin_accepted", "Your Blogy plugin was accepted", "Congratulations!<br>Your plugin has been accepted to the Plugin Store.<br>Cheers!");

	//Close the connection
	$conn->close();

	//Return response
	echo "READY";
?>