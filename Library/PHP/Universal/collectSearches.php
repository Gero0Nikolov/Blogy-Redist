<?php
	session_start();
	$sender = $_SESSION['sender'];

	//Collect friends
	$loadStack = fopen("../../Authors/$sender/Following.txt", "r") or die("Unable to load stack.");
	$stack = array();
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if ($line != "") array_push($stack, $line);
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