<?php
	$getRow = $_POST["partnerRow"];
	$getId = $_POST["partnerId"];
	$getURL = $_POST["partnerLink"];
	$getLogo = $_POST["partnerLogo"];
	$getStatus = strtoupper($_POST["partnerStatus"]);

	if (!isset($_POST["partnerStatus"]) || $_POST["partnerStatus"] == "") {
		$getStatus = "FALSE";
	}

	//Connect to data base
	include "../PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "UPDATE Partnerships SET PARTNERID='$getId', PARTNERLINK='$getURL', PARTNERLOGO='$getLogo', VIP='$getStatus' WHERE ID=$getRow";
		$conn->query($sql);
	}

	//Close the connection
	$conn->close();

	header("Location: previewPartners.php");
?>