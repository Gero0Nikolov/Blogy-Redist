<?php 
	include "../PHP/Universal/dataBase.php"; 
	
	$buildedPlaces = "";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load places
		$sql = "SELECT ID, PLACEID, APPROVED FROM worldPlaces ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if (!isset($_POST["allPlaces"])) {
					if ($row['APPROVED'] == 0) {
						$placeIdNum = $row['ID'];
						$placeId = $row['PLACEID'];
						$build = "
							<div id='row-container' class='$placeIdNum'>
								<button type='button' class='select-button unselected' onclick='selectPlace(\"$placeIdNum\");' title='Select place'></button>
								<button type='button' class='placeholder' onclick='previewPlace(\"$placeIdNum\");' title='Preview place'>
									$placeId
								</button>
								<div id='controls-container'>
									<button type='button' class='option-button' onclick='addPlace(\"$placeIdNum\");' title='Approve place'>
										Add
									</button>
									<button type='button' class='option-button delete-button' onclick='deletePlace(\"$placeIdNum\");' title='Delete place'>
										Delete
									</button>
								</div>
							</div>
						";
						
						$buildedPlaces .= $build;
					} 
				} else {
					$placeIdNum = $row['ID'];
					$placeId = $row['PLACEID'];
					$build = "
						<div id='row-container' class='$placeIdNum'>
							<button type='button' class='select-button unselected' onclick='selectPlace(\"$placeIdNum\");' title='Select place'></button>
							<button type='button' class='placeholder' onclick='previewPlace(\"$placeIdNum\");' title='Preview place'>
								$placeId - ".$row['APPROVED']."
							</button>
							<div id='controls-container'>
								<button type='button' class='option-button delete-button' onclick='deletePlace(\"$placeIdNum\");' title='Delete place'>
									Delete
								</button>
							</div>
						</div>
					";
					
					$buildedPlaces .= $build;
				}
			}
		}
	}
	$conn->close();

	//Return result
	echo $buildedPlaces;
?>