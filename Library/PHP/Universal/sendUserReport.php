<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header("Location: ../../../SignIn.html");
	}

	$getReported = $_COOKIE['reportedId'];
	$getReport = $_COOKIE['reportContainer'];

	$getReport = htmlentities($getReport);
	$getReport = str_replace("'", "`", $getReport);

	$build = "
		User report,
		Sender: $sender,
		Reported: $getReported,
		Report:
		$getReport
	";

	mail("vtm.sunrise@gmail.com", "User report", $build);

	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Creatе new TABLE
	$sql = "CREATE TABLE worldReports (ID int NOT NULL AUTO_INCREMENT, SUBJECT LONGTEXT, REPORT LONGTEXT, PRIMARY KEY (ID))";
	if ($conn->query($sql) === TRUE) {}

	$subject = "Reported user";
	$getContent = "Sender: $sender
	Reported: $getReported
	Report: 
	$getReport
	";

	$sql = "INSERT INTO worldReports (SUBJECT, REPORT) VALUES ('$subject', '$getContent')";
	$conn->query($sql);

	//Close connection
	$conn->close();

	echo "READY";
?>