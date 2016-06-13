<?php
	//Connect to data base
	include "dataBase.php";
	
	$buildedPlaces = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get ID
	$getId = $_POST["placeId"];
	if ($getId == -1) {
		$sql = "SELECT ID FROM worldPlaces ORDER BY ID DESC LIMIT 1";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getId = $row["ID"];
			}
		}
	}

	$isFound = 0;
	$isMobile = $_POST["mobile"];

	//Get place
	while ( $isFound == 0 && $getId > 0) {
		$sql = "SELECT ID, PLACEID, APPROVED FROM worldPlaces WHERE ID=$getId";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ($row['APPROVED'] == 1) {
					$placeIdNum = $row['ID'];
					$placeId = $row['PLACEID'];

					if ($isMobile == 0) {
						$build = "
							<div id='place'>
								<button type='button' class='placeName' onclick='previewWorldPlace(\"$placeIdNum\", \"$sender\", 0)'>
									$placeId
								</button>
							</div>
						";
					}
					else
					if ($isMobile == 1) {
						$build = "
							<div id='place'>
								<button type='button' class='placeName' onclick='previewWorldPlace(\"$placeIdNum\", \"$sender\", 1)'>
									$placeId
								</button>
							</div>
						";
					}
				}
			}

			$isFound = 1;
		} else {
			$getId--;
		}
	}

	//Close the connection
	$conn->close();

	//Increment down for the next place
	$getId--;

	//Return responce
	echo $getId."~".$build;
?>