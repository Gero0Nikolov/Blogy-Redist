<?php
	include "../Universal/dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Create the table: WorldBloggers
	$sql = "CREATE TABLE WorldBloggers (ID int NOT NULL AUTO_INCREMENT, Author_EMAIL LONGTEXT, Author_UID LONGTEXT, Author_PASS LONGTEXT, PRIMARY KEY (ID))";
	$conn->query($sql);

	//Collect users from Info.csv
	$fd = fopen("../../Authors/Info.csv", "r") or die("Fatal error: File not found.");
	while( !feof($fd) ) {
		$collect = fgetcsv($fd);

		//Insert into the database
		$sql = "INSERT INTO WorldBloggers (Author_EMAIL, Author_UID, Author_PASS) VALUES ('".$collect[0]."', '".$collect[1]."', '".$collect[2]."')";
		$conn->query($sql);
	}
	fclose($fd);

	//Close the connection
	$conn->close();
?>