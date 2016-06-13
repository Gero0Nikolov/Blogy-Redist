<?php
	//Connect to Database
	include "../Universal/dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Collect the information
	$sql = "SELECT Author_UID FROM WorldBloggers ORDER BY ID";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$tableName = "pushTable".$row["Author_UID"];
			
			//Add CHECKED value to all pushTables 
			$sql = "ALTER TABLE $tableName ADD CHECKED LONGTEXT";
			$conn->query($sql);
		}
	}

	//Close the connection
	$conn->close();
?>