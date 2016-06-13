<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("../../../index.php");
	}

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Owner, Members, Invited FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubOwner = $row["Owner"];
			$getMembers = explode(",", $row["Members"]);
			$getInvited = explode(",", $row["Invited"]);
		}
	}

	//Close the connetion
	$conn->close();

	//Check the arrays
	if ( !is_array($getMembers) ) { $getMembers = array(); }
	if ( !is_array($getInvited) ) { $getInvited = array(); }

	//Collect friends
	$loadStack = fopen("../../Authors/$sender/Following.txt", "r") or die("Unable to load stack.");
	$stack = array();
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if ($line != ""
			&& $line != $getClubOwner
			&& !in_array($line, $getMembers)
			&& !in_array($line, $getInvited) ) array_push($stack, $line);
	}
	fclose($loadStack);

	//Build for search
	$configStack = array();
	foreach ($stack as $friend) {
		$pickUpCount = 0;
		$parseUser = fopen("../../Authors/$friend/config.txt", "r") or die("Unable to start parsing.");
		while (!feof($parseUser)) {
			$pickUpLine = trim(fgets($parseUser));
			if ($pickUpCount == 0) {
				$friendImg = $pickUpLine;
			}
			else
			if ($pickUpCount == 1) {
				$friendHref = $pickUpLine;
			}
			else
			if ($pickUpCount == 3) {
				$friendFN = $pickUpLine;
			}
			else
			if ($pickUpCount == 4) {
				$friendLN = $pickUpLine;
				break;
			}
			$pickUpCount++;
		}
		fclose($parseUser);

		array_push($configStack, "$friendFN#$friendLN#$friend#$friendImg#$friendHref");
	}

	$bindFriends = implode(",", $configStack);

	//Return responce
	echo $bindFriends;
?>