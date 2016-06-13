<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}

	$getContent = htmlentities(trim($_POST['reportedData']));
	$getContent = str_replace("'", "`", $getContent);
	
	//Build and send mail
	$mail = "vtm.sunrise@gmail.com";
	$subject = "Profile report";
	mail($mail, $subject, $getContent);

	include "../Universal/dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Creatе new TABLE
	$sql = "CREATE TABLE worldReports (ID int NOT NULL AUTO_INCREMENT, SUBJECT LONGTEXT, REPORT LONGTEXT, PRIMARY KEY (ID))";
	if ($conn->query($sql) === TRUE) {}

	$getContent = "Sender: $sender
	Report:
	$getContent
	";

	$sql = "INSERT INTO worldReports (SUBJECT, REPORT) VALUES ('$subject', '$getContent')";
	$conn->query($sql);

	//Close the connection
	$conn->close();
	
	header('Location: openSettings.php');

	die();
?>