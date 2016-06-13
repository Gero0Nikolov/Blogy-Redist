<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
		die();
	}

	$clubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Table name
	$table_name = $sender."_Membershiped_Clubs";

	//Check if the table exists
	$sql = "SELECT ID FROM $table_name";
	$pick = $conn->query($sql);
	if ($pick->num_rows <= 0) { // The table don't exists
		$responce = "-1~NONE";
	} else { // The table exists :O
		
		if ( $clubId == -1 ) {
			$sql = "SELECT ID FROM ".$table_name." ORDER BY DESC LIMIT 1";
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$clubId = $row["ID"];
				}
			}
		}

		if ($clubId > 0) {
			$isFound = 0;
			while ($isFound == 0) {
				$sql = "SELECT ID, Club_Table, Club_Id FROM ".$table_name." WHERE ID=$clubId";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						$membershipedClubTable = $row["Club_Table"];
						$membershipedClubId = $row["Club_Id"];
					}
					$isFound = 1;
				} else {
					$clubId--;

					if ($clubId <= 0) {
						break;
					}
				}
			}
		}

		$sql = "SELECT Club_Name, Club_Color, Club_Logo, Administrators FROM ".$membershipedClubTable." WHERE ID=".$membershipedClubId;
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$clubName = $row["Club_Name"];
				$clubColor = $row["Club_Color"];
				$clubLogo = $row["Club_Logo"];
				$clubAdministrators = explode(",", $row["Administrators"]);

				$build_container = 1;
			}
		}

		if ( strpos( $_SERVER["HTTP_REFERER"], "Mobile" ) && !strpos( $clubLogo, "blogy.co/Library/" ) ) { $clubLogo = "../". $clubLogo; }

		$buildContainer = "NONE";
		if ( isset($build_container) ) {

			$admin_badge = "";
			if ( in_array($sender, $clubAdministrators) ) { $admin_badge = "<span class='club-badge'>#admin</span>"; }

			if ($clubLogo != "") {
				$buildContainer = "
					<a href='previewClub.php?".$membershipedClubTable."=".$membershipedClubId."'>
						<div class='club-container ".$clubColor."'>
							<div class='club-logo' style='background-image: url(".$clubLogo."); background-size: cover; background-position: 50%;'></div>
							<h1 class='club-name'>".$clubName."</h1>
							".$admin_badge."
						</div>
					</a>
				";
			} else {
				$buildContainer = "
					<a href='previewClub.php?".$membershipedClubTable."_Clubs=".$membershipedClubId."'>
						<div class='club-container ".$clubColor."'>
							<div class='club-logo' style='background-color: #fff;'></div>
							<h1 class='club-name'>".$clubName."</h1>
							".$admin_badge."
						</div>
					</a>
				";
			}

		}

		//Increment down for the next club
		$clubId--;

		$responce = $clubId."~".$buildContainer;
	}

	//Close the connection
	$conn->close();

	//Return responce
	echo $responce;
?>