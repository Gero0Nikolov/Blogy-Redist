<?php
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "CREATE TABLE Partnerships (ID int NOT NULL AUTO_INCREMENT, PARTNERID LONGTEXT, PARTNERLINK LONGTEXT, PARTNERLOGO LONGTEXT, DONATION LONG, VIP LONGTEXT, PRIMARY KEY (ID))";
	if ($conn->query($sql) === TRUE) {
		$responce = "TBC";
	} else {
		$partners = array();

		$sql = "SELECT ID, PARTNERID, PARTNERLINK, PARTNERLOGO, DONATION, VIP FROM Partnerships ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				//$getDonation = $row['DONATION'];
				$getPartnerName = $row['PARTNERID'];
				$getPartnerLink = $row['PARTNERLINK'];
				$getPartnerLogo = $row['PARTNERLOGO'];
				$getStatus = strtoupper($row['VIP']);

				$build = $getPartnerName."|".$getPartnerLink."|".$getPartnerLogo;

				if ($getStatus == "FALSE") array_push($partners, $build);
				else
				if ($getStatus == "TRUE") array_unshift($partners, $build);
			}
		}

		if (count($partners) == 1) { $responce = $partners[0]; }
		else { $responce = implode(",", $partners); }
	}

	echo $responce;
?>