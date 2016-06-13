<?php 
	//Include bundle
	include "functions.php";
	
	//Connect to the database
	include "dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$user_ids = array();

	//Get all users that haven't activated their profiles
	$sql = "SELECT Author_UID FROM WorldBloggers WHERE (Acti_Key != '0' OR Acti_Key IS NOT NULL)";
	$pick = $conn->query($sql);

	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push( $user_ids, $row[ "Author_UID" ] );
		}
	}

	//Clear all users that haven't activated their profiles
	$sql = "DELETE FROM WorldBloggers WHERE (Acti_Key != '0' OR Acti_Key IS NOT NULL)";
	$conn->query( $sql );

	//Delete all push tables & user dirs
	foreach ( $user_ids as $user ) {
		$sql = "DROP TABLE pushTable$user";
		$conn->query( $sql );
		remove_dir( "../../Authors/$user" );
	}

	//Close the connection
	$conn->close();
?>