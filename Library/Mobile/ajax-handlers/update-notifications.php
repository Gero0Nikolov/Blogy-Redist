<?php 
	session_start();
	$sender = $_SESSION[ "sender" ];
	if ( !isset( $sender ) ) { die(); }

	//Server path
	$server_path = "/home/blogycoo/public_html/";
	
	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {}

	$sql = "UPDATE pushTable$sender SET CHECKED='TRUE' WHERE CHECKED IS NULL";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return response
	echo "READY";
?>