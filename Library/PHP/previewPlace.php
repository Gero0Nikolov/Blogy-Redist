<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];

	$getId = $_COOKIE['placeId'];
	if (!isset($getId)) {
		echo "<script>window.history.back();</script>";
		die();
	}

	$foundedFlag = 0;

	//Connect to data base
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load place
		$sql = "SELECT ID, PLACEID, PLACECORDS, PLACESTORY, TAGGED FROM placesOf$sender ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$placeIdNum = $row['ID'];
				if ($placeIdNum == $getId) {
					$placeTitle = $row['PLACEID'];
					$placeCords = $row['PLACECORDS'];
					$placeStory = $row['PLACESTORY'];
					$taggedFriends = $row['TAGGED'];
					$foundedFlag = 1;
					break;
				}
			}
		}
	}

	if ($taggedFriends != "NONE") {
		$taggedFriends = explode(",", $taggedFriends);
	}
$placeStory = trim($placeStory);
$placeStory = nl2br($placeStory);

	//Get friends
	$friendsStack = array();
	$pullFriends = fopen("../Authors/$sender/Following.txt", "r") or die("Fatal: Unable to get friends.");
	while (!feof($pullFriends)) {
		$line = trim(fgets($pullFriends));
		if ($line != "") {
			array_push($friendsStack, $line);
		}
	}
	fclose($pullFriends);

echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
";
	
	if ($foundedFlag == 1) echo "<title>$placeTitle</title>";
	else echo "<title>Oops :-(</title>";

echo "
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>

			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='../../java.js'></script>

			<script src='http://maps.google.com/maps/api/js?sensor=false'></script>
			<script type='text/javascript'>
				var flag = 0;

				var sendTo = [];
				function sharePlaceObject(userId, id) {
					if (sendTo.indexOf(userId) > -1) {
						document.getElementById(\"friend\"+id).style.color = 'black';
						var index = sendTo.indexOf(userId);
						sendTo.splice(index, 1);
					} else {
						document.getElementById(\"friend\"+id).style.color = '#0088cc';
						sendTo.push(userId);
					}
					
					if (sendTo.length > 0) {
						document.getElementById('sendButton').style.visibility= 'visible';
					} else {
						document.getElementById('sendButton').style.visibility= 'hidden';
					}
				}
				
				function sendPlace() {
					var shareWith = sendTo.toString();
					document.cookie = 'shareWith='+shareWith;
					window.location = 'sendPlace.php';
				}
			</script>
		</head>
";

	if ($foundedFlag == 1) {
		echo "
			<body onload='showOnMap(".explode("#", $placeCords)[0].", ".explode("#", $placeCords)[1].", \"mapContainer\")'>
		";
	} else {
		echo "
			<body>
		";
	}

	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';

	if ($foundedFlag == 1) {
echo "
			<div id='storeFriends'>
				<button class='sendButton' id='sendButton' onclick='sendPlace()'>Send place</button>
				<button class='hideButton' onclick='hideContainerFriends()'></button>
				<h1>Choose friends :</h1>
				<div id='chooseToSend'>
";
	
	$friendId = 0;
	foreach ($friendsStack as $friend) {
		$pickUpCount = 0;
		$parseUser = fopen("../Authors/$friend/config.txt", "r") or die("Unable to start parsing.");
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
		
		$friendId++;
		$build = "
			<button type='button' id='friend$friendId' onclick='sharePlaceObject(\"$friend\", \"$friendId\")'>
				<img src='$friendImg' />
				$friendFN $friendLN
			</button>
		";
		
		echo $build;
	}

echo "
				</div>
			</div>
			<div id='previewPlaceMain'>
				<div id='menuContainer'>
					<div class='left'>
						<button type='button' onclick='$(\"#options\").fadeToggle(\"fast\")'><img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-arrow-down-512.png' /></button>
						<div id='options'>
							<button type='button' class='split' title='Share this place with your friends' onclick='$(\"#storeFriends\").fadeIn(\"fast\")'>Share with friends</button>
							<button type='button' class='split' title='Share this place with all Bloggers' onclick='sharePlace(\"$placeIdNum\")'>Share in places</button>
							<button type='button' title='Delete this place from youр map' onclick='deletePlace(\"$placeIdNum\")'>Delete place</button>
						</div>
					</div>
					<div class='right'>
						<button type='button' title='Resize map' onclick='resizeToggle(\"mapContainer\")'><img id='resizeButton' src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-arrow-up-512.png' /></button>
";
	
	if ($taggedFriends != "NONE") {
		echo "
			<button type='button' title='Tagged friends' onclick='$(\"#taggedFriends\").fadeToggle(\"fast\")'><img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-people-512.png' /></button>
			<div id='taggedFriends'>
		";

			foreach ($taggedFriends as $friend) {
				if ($friend != $sender) {
					$pickUpCount = 0;
					$parseUser = fopen("../Authors/$friend/config.txt", "r") or die("Unable to start parsing.");
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

					$build = "
						<button type='button' onclick='window.location=\"openBloger.php?$friend\"'>
							<img src='$friendImg' />
							$friendFN $friendLN
						</button>
					";
					
					echo "$build";
				}
			}
		
		echo "
			</div>
		";
	}

echo "
					</div>
				</div>
				<div id='informationContainer'>
					<h1>$placeTitle</h1>
					<p>
						$placeStory
					</p>
					<div id='mapContainer'>
					</div>
				</div>
			</div>
";
	} else {
		echo "<h2 id='error-message'>Oops, it seems that you have deleted this place.</h2>";
	}

echo "
		</body>
	</html>	
";
?>