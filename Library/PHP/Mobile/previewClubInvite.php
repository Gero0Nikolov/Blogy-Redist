<?php 
	include "header.php"; 

	$getURL = $_SERVER[REQUEST_URI];

	$catchOwnerId = explode("=", end(explode("?", $getURL)))[0];
	$catchClubId = explode("=", end(explode("?", $getURL)))[1];

	$isFound = 0;

	if ($catchOwnerId != "" && $catchClubId != "") {
		//Connect to the Database
		include "../Universal/dataBase.php";

		//Connect to the database
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT ID, Club_Name, Club_Slug, Club_Color, Club_Logo, Members, Invited FROM ".$catchOwnerId." WHERE ID=".$catchClubId;
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getClubName = $row["Club_Name"];
				$getClubSlug = $row["Club_Slug"];
				$getClubColor = $row["Club_Color"];
				$getClubLogo = $row["Club_Logo"];
				$getClubMembers = explode(",", $row["Members"]);
				$getClubInvites = explode(",", $row["Invited"]);
			}

			$isFound = 1; // The club is found
		}

		$clubOwner = explode("_", $catchOwnerId)[0];

		$sql = "SELECT Club_Name, Club_Type, Owner_ID FROM worldClubs WHERE Club_Slug='".$getClubSlug."' AND Owner_ID='".$clubOwner."'";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getClubType = $row["Club_Type"];
			}
		}

		//Close the connection
		$conn->close();
	} else {
		$getClubName = "Ooops..";
	}
?>
<title><?php echo $getClubName; ?></title>
</head>
<body style="overflow-x: hidden;">
	<?php
		include "loadMenu.php";
		
		if ($catchOwnerId == "" || $catchClubId == "" || $isFound == 0) {
			$crashed = 1;
	?>
			<h1 id="error-message">Ooops.. It seems that we don't find this club :-(</h1>
	<?php 
		} else { 
			if ( !in_array($sender, $getClubInvites) && $getClubType == "SECRET" ) {
	?>
				<h1 id="error-message">Ooops.. You are not supposed to be there!</h1>
	<?php
			} elseif ( !in_array($sender, $getClubInvites) && ( $getClubType == "CLOSED" || $getClubType == "PUBLIC") ) {
	?>
				<script type="text/javascript">window.location='previewClub.php?<?php echo $getURL; ?>';</script>
	<?php
			} elseif ( in_array($sender, $getClubInvites) ) {
	?>
				<div class="club-invitation-dialog mt-175">
					<h1>
						Would you like to join <?php echo $getClubName; ?>
						<div class="img" style="background-image: url(<?php echo $getClubLogo; ?>); background-size: cover; background-position: 50%;"></div>
						?
					</h1>
					<button class='agree-button' onclick="acceptClubInvitation(1);">Join!</button>
					<button class='decline-button' onclick='declineClubInvitation(1);'>Decline</button>
				</div>
	<?php
			}

			if( in_array($sender, $getClubMembers) ) { echo "<script>window.location='previewClub.php?".$catchOwnerId."=".$catchClubId."'</script>"; }
	?>

	<?php } ?>
</body>
</html>