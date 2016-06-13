<?php
	$getFN = trim(ucfirst(strtolower($_POST["fName"])));
	$getLN = trim(ucfirst(strtolower($_POST["lName"])));
	$getLL = triM($_POST["logoLink"]);
	$getSL = trim($_POST["socLink"]);

	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "INSERT INTO Partnerships (PARTNERID, PARTNERLINK, PARTNERLOGO, DONATION, VIP) VALUES ('$getFN $getLN', '$getSL', '$getLL', '0.99', 'FALSE')";
	$conn->query($sql);

	header("Location: ../../../SignIn.html");
?>