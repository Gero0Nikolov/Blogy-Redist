<?php
	$clubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Table name
	$table_name = $sender."_Clubs";

	//Check if the table exists
	$sql = "SELECT ID FROM worldClubs";
	$pick = $conn->query($sql);
	if ($pick->num_rows <= 0) { // The table don't exists
		$responce = "-1~NONE";
	} else { // The table exists :O
		if ( $clubId == -1 ) {
			$sql = "SELECT ID FROM worldClubs ORDER BY ID DESC LIMIT 1";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$clubId = $row["ID"];
				}
			}
		}

		if ( $clubId != -1 ) {
			$isGood = 0;
			while ( $isGood == 0 ) {
				$sql = "SELECT ID, Club_Slug, Club_Type, Owner_ID FROM worldClubs WHERE ID=".$clubId;
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {				
					while ($row = $pick->fetch_assoc()) {
						$clubId = $row["ID"];
						$clubType = $row["Club_Type"];
						$clubSlug = $row["Club_Slug"];
						$clubOwner = $row["Owner_ID"];
					}
					
					if ( $clubType == "SECRET" ) {
						$clubId -= 1;
					} elseif ( $clubType != "SECRET" ) {
						$isGood = 1;
					}
				} else {
					$clubId -= 1;
				}
				
				if ( $clubId <= 0 ) { break; }
			}
		}

		if ($clubId >= 0 && $clubType != "SECRET") {
			$table_name = $clubOwner."_Clubs";
			$sql = "SELECT ID, Club_Name, Club_Color, Club_Logo FROM $table_name WHERE Club_Slug='".$clubSlug."'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$clubInlineId = $row["ID"];
					$clubName = $row["Club_Name"];
					$clubColor = $row["Club_Color"];
					$clubLogo = $row["Club_Logo"];
				}
				$build_container = 1;
			}
			
			//Get the nickname of the owner
			$sql = "SELECT Nickname FROM WorldBloggers WHERE Author_UID='".$clubOwner."'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$clubOwnerNickname = $row["Nickname"];
				}
			}
		}

		if ( strpos( $_SERVER["HTTP_REFERER"], "Mobile" ) && !strpos( $clubLogo, "blogy.co/Library/" ) ) { $clubLogo = "../". $clubLogo; }

		$nicknameContainer = "";
		if ( isset($clubOwnerNickname) && !empty($clubOwnerNickname) ) {
			$nicknameContainer = "<span class='club-badge'>@".$clubOwnerNickname."</span>";
		}

		if ( isset($build_container) ) {
			if ($clubLogo != "") {
				$buildContainer = "
					<a href='previewClub.php?".$clubOwner."_Clubs=".$clubInlineId."'>
						<div class='club-container ".$clubColor."'>
							<div class='club-logo' style='background-image: url(".$clubLogo."); background-size: cover; background-position: 50%;'></div>
							<h1 class='club-name'>".$clubName."</h1>
							$nicknameContainer
						</div>
					</a>
				";
			} else {
				$buildContainer = "
					<a href='previewClub.php?".$clubOwner."_Clubs=".$clubInlineId."'>
						<div class='club-container ".$clubColor."'>
							<div class='club-logo' style='background-color: #fff;'></div>
							<h1 class='club-name'>".$clubName."</h1>
							$nicknameContainer
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