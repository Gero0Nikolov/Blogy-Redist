<?php
	include "../PHP/Universal/dataBase.php"; 
	
	$buildedButtons = "";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load users
		$sql = "SELECT ID, PARTNERID, PARTNERLINK, PARTNERLOGO, DONATION, VIP FROM Partnerships ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$partnerRow = $row["ID"];
				$partnerId = $row["PARTNERID"];
				$partnerLink = $row["PARTNERLINK"];
				$partnerLogo = $row["PARTNERLOGO"];
				$partnerDonation = $row["DONATION"];
				$isVip = $row["VIP"];

				if (strtolower($isVip) == "true") { $checked = "checked";}
				else
				if (strtolower($isVip) == "false") { $checked = ""; }

				$build = "
					<div id='row-container' class='$partnerRow'>
						<button type='button' class='placeholder' onclick='previewPartner(\"$partnerLink\");' title='Preview partner'>
							<div style='background-image:url($partnerLogo); background-size: cover; background-position: 50%;' class='img'></div>
							$partnerId
						</button>
						<div id='controls-container'>
							<button type='button' class='option-button' onclick='editPartner(\"$partnerRow\");' title='Edit partner'>
								Edit
							</button>
							<button type='button' class='option-button delete-button' onclick='deletePartner(\"$partnerRow\");' title='Delete partner'>
								Delete
							</button>
						</div>
						<form id='editor-container' method='post'>
							<label for='partnerId'>Partner ID (Name)</label>
							<input type='text' id='partnerId' name='partnerId' value='$partnerId' onclick='this.select();'>
							<label for='partnerLink'>Partner's web-page (Link)</label>
							<input type='text' id='partnerLink' name='partnerLink' value='$partnerLink' onclick='this.select();'>
							<label for='partnerLogo'>Partner's logo (Link)</label>
							<input type='text' id='partnerLogo' name='partnerLogo' value='$partnerLogo' onclick='this.select();'>
							<label for='partnerStatus'>Is V.I.P ?</label>
							<input type='checkbox' name='partnerStatus' id='partnerStatus' class='checkBox' value='TRUE' $checked>
							<input style='display: none;' name='partnerRow' value='$partnerRow'>
							<button type='button' onclick='saveChangesPartner(\"$partnerRow\");'>Save</button>
						</form>
					</div>
				";

				if ($isVip == "TRUE") {
					$buildedButtons = $build.$buildedButtons;
				} else {
					$buildedButtons .= $build;
				}
			}
		}
	}

	//Close the connection
	$conn->close();

	echo $buildedButtons;
?>