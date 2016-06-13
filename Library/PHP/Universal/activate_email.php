<?php
	$activation_code = explode( "?", $_SERVER["REQUEST_URI"] )[1];

	//Connect to the database
	include "dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE WorldBloggers SET Acti_Key=NULL WHERE Acti_Key='$activation_code'";
	$conn->query( $sql );

	//Close the connection
	$conn->close();

	//Return to Blogy
	header( "Location: http://".$_SERVER["HTTP_HOST"] );
?>