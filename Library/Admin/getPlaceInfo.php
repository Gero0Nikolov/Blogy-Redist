<?php
	header ("Content-Type:text/xml");

	$getId = $_COOKIE['previewIndex'];

	//Connect to data base
	include "../PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load place
		$sql = "SELECT ID, PLACEID, PLACECORDS, PLACESTORY, TAGGED FROM worldPlaces WHERE ID=$getId";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$placeTitle = $row['PLACEID'];
				$placeCords = $row['PLACECORDS'];
				$placeStory = nl2br(trim($row['PLACESTORY']));
				$taggedFriends = $row['TAGGED'];
				$likers = $row['LIKERS'];
			}
		}
	}
	$conn->close();

	//Build XML
	$XMLContent = "
		~$getId$
		~$placeTitle$
		~$placeCords$
		~$placeStory$
		~$taggedFriends$
		~$likers$
	";

	echo $XMLContent;
?>