<?php 
	session_start();
	$admin = $_SESSION["admin"];
	if ( !isset($admin) ) { die(); }

	$plugin_id = $_POST["pluginID"];
	$plugin_author = $_POST["pluginAuthor"];

	include "../PHP/Universal/functions.php";

	include "../PHP/Universal/dataBase.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "DELETE FROM Plugin_Store WHERE ID=$plugin_id";
	$conn->query($sql);

	//Send mail
	send_custom_mail("Blogy Admin", $plugin_author, "plugin_declined", "Your plugin was not accepted", "Your plugin was declined from the store because it don't matches some of the policies.<br>Check the policies here http://dev.blogy.co and submit it once again.<br>Cheers!");

	//Close the connection
	$conn->close();

	//Return response
	echo "READY";
?>