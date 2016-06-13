<?php 
	include "header.php"; 

	$getURL = $_SERVER["REQUEST_URI"];

	$catchOwnerId = explode("=", end(explode("?", $getURL)))[0];
	$catchClubId = explode("=", end(explode("?", $getURL)))[1];
	$catchParameters = explode("?", $getURL)[1];

	$isMobile = 0;

	if ( strpos($getURL, "&") ) {
		$openStoryBoard = explode("&", $getURL)[1];

		//Clear ID argument
		$catchClubId = explode("&", $catchClubId)[0];
	}

	$isFound = 0;
	$isMember = 0;

	if ($catchOwnerId != "" && $catchClubId != "") {
		//Connect to the Database
		include "Universal/dataBase.php";

		//Connect to the database
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT ID, Club_Name, Club_Slug, Club_Color, Club_Logo, Authors, Comments, Like_Button, Administrators, Members, Requesters FROM ".$catchOwnerId." WHERE ID=".$catchClubId;
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getClubName = $row["Club_Name"];
				$getClubSlug = $row["Club_Slug"];
				$getClubColor = $row["Club_Color"];
				$getClubLogo = $row["Club_Logo"];
				$getClubAuthors = $row["Authors"];
				$getClubComments = $row["Comments"];
				$getClubLikeButton = $row["Like_Button"]; 
				$getAdministrators = $row["Administrators"];
				$getClubMembers = explode(",", $row["Members"]);
				$getClubRequests = explode(",", $row["Requesters"]);

				$_SESSION["club_comments"] = $getClubComments;
				$_SESSION["club_likes"] = $getClubLikeButton;
			}

			$isFound = 1; // The club is found
		}

		$clubOwner = explode("_", $catchOwnerId)[0];
		$_SESSION["club_administrators"] = explode(",", $getAdministrators);

		//Check is array
		if ( !is_array($getClubMembers) ) { $getClubMembers = array(); }
		if ( !is_array($getClubRequests) ) { $getClubRequests = array(); }

		$sql = "SELECT Club_Name, Club_Type, Owner_ID FROM worldClubs WHERE Club_Slug='".$getClubSlug."' AND Owner_ID='".$clubOwner."'";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getClubType = $row["Club_Type"];
			}
		}

		//Close the connection
		$conn->close();

		if ( $sender == $clubOwner || in_array($sender, explode(",", $getAdministrators)) ) { $isAdmin = 1; }
	
		if ( in_array($sender, $getClubMembers) ) { $isMember = 1; }

	} else {
		$getClubName = "Ooops..";
	}
?>
<title><?php echo $getClubName; ?></title>

<?php // Inline scripts
    if ( !empty( $openStoryBoard ) ) {
	    $catch_argument = $openStoryBoard;
		if ( strpos( $catch_argument, "pen_s" ) ) {
			$story_id = explode( "=", $catch_argument )[1];
			?>
			<script>
				$( document ).ready(function(){ openComments( "<?php echo $story_id; ?>", 0, 0 ); });
			</script>
			<?php
		}
	}
?>

</head>
<body style="overflow-x: hidden;">
	<?php
		include "loadMenu.php";
		include "loadSuggestedBlogers.php";
		
		if ($catchOwnerId == "" || $catchClubId == "" || $isFound == 0) {
			$crashed = 1;
	?>
		<h1 id="error-message">Ooops.. It seems that we don't find this club :-(</h1>
	<?php } else { ?>
	<?php if ( $isMember == 1 || ($isMember == 0 && $getClubType != "SECRET") ) { ?>

	<!-- CLUB CONTAINER -->
	<div id="club-container">
		<!-- HEADER -->
		<div id="club-header" class="<?php echo $getClubColor; ?>">
			<div class="club-logo-name-container">
				<div class="full-width-container">
					<div class="club-logo" style="background-image: url(<?php echo $getClubLogo; ?>); background-size: cover; background-position: 50%;"></div>
					<h1 class="club-logo-name"><?php echo $getClubName; ?></h1>
				</div>
			</div>

			<!-- LOAD PROPER MENUS -->
			<?php 
				if ( isset($isAdmin) ) {
					if ( !isset($openStoryBoard) || empty($openStoryBoard) ) { include "../Templates/clubAdminMenuDashboard.php"; }
					else { include "../Templates/clubAdminMenuStoryboard.php"; }
			?>
		</div> <!-- CLOSE HEADER ADMIN -->
			<?php
					if ( !isset($openStoryBoard) || empty($openStoryBoard) ) { include "../Templates/clubAdminDashboard.php"; }
					else { include "../Templates/clubStoryBoard.php"; }
				} else {
					if ( $isMember == 1 ) {
						include "../Templates/clubUserMenu.php";
					}

					if ( $getClubType == "CLOSED" && $isMember == 0):
						if ( !in_array($sender, $getClubRequests) ):
			?>
					</div> <!-- CLOSE HEADER VISITOR -->
					<div class="club-invitation-dialog">
						<h1>It seems you are not a member of this club !</h1>
						<button class='just-button' onclick="sendJoinClubRequest('location.reload', 0);">Join !</button>
					</div>
			<?php
						elseif ( in_array($sender, $getClubRequests) ) :
			?>
					</div> <!-- CLOSE HEADER VISITOR -->
					<div class="club-invitation-dialog">
						<h1>You've already sent your request. Would you like to cancel ?</h1>
						<button class='decline-button' onclick="cancelJoinClubRequest('location.reload', 0);">Cancel</button>
					</div>
			<?php
						endif;
					elseif ( $getClubType == "PUBLIC" && $isMember == 0  ) :
						if ( !in_array($sender, $getClubRequests) ) :
			?>

					<div id='club-header-menu' class='full-width-container'>
						<div id='club-header-settings-mainmenu' class='menu-container right-aligned white-menu right-margin-25'>
							<button class='club-header-button' onclick="sendJoinClubRequest('location.reload', 0);"><span>&#xf0a5;</span>Join us!</button>
						</div>
					</div>

					<?php elseif ( in_array($sender, $getClubRequests) ) : ?>

					<div id='club-header-menu' class='full-width-container'>
						<div id='club-header-settings-mainmenu' class='menu-container right-aligned white-menu right-margin-25'>
							<button class='club-header-button' onclick="cancelJoinClubRequest('location.reload', 0);"><span>&#xf00d;</span>Cancel</button>
						</div>
					</div>

					<?php endif; ?>
				</div> <!-- CLOSE HEADER VISITOR -->
			<?php endif; ?>
				</div> <!-- CLOSE HEADER VISITOR -->
			<?php include "../Templates/clubStoryBoard.php"; ?>
			<?php
				}
			?>

		<!-- CHECK IF CLUB HAS LOGO -->
		<?php 
			if ( $getClubLogo == "" ) {
		?>
				<div id="editorContainer" style="display: block;">
					<form id="editor-fields" method="POST" enctype="multipart/form-data">
						<h1>Your club needs an unique logo first :-)</h1>
						<button type='button' class='inline-option-button wide-fat mt-d-5 border-bottom-radius' onclick='$("#logo-container-popup").click();'>Choose logo</button>
						<input type='file' id='logo-container-popup' name='fileToUpload' style='display: none;' onchange='uploadLogo("#logo-container-popup");'>
						<input type='text' id='club-owner' name='clubOwner' style='display: none;' value='<?php echo $catchOwnerId; ?>'>
						<input type='text' id='club-id' name='clubID' style="display: none;" value='<?php echo $catchClubId; ?>'>
						<input type='text' id='is-club' name='isClub' style="display: none;" value="1">
					</form>
				</div>
		<?php } ?>

		<!-- BODY -->
		<div id="club-body">
		</div>

		<!-- FOOTER -->
		<?php
			if ( isset($isAdmin) ) {
				include "../Templates/clubAdminFooter.php";
			}

			if ( ( isset($openStoryBoard) && !empty($openStoryBoard) ) || !isset($isAdmin) ) {
				include "../Templates/clubStoryBoardFooter.php";
			}
		?> 
	</div>

	<?php 
	} else { // Close the check for the club 
		if ( $getClubType == "SECRET" ) :
	?>
		<h1 id="error-message">Ooops.. You are not supposed to be there!</h1>
	<?php
		endif;
	}
	?>
<?php
}
?>
</body>
</html>