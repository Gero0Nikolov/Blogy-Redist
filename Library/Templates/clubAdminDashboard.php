<!-- DASHBOARD -->
<div id='club-dashboard' class='full-width-container'>
	<div id='dashboard-body'>
		<div id='left-column'>
			<h1 id='members-calc-container' class='column-header-small center-text'><!-- MEMBERS --></h1>
		</div>
		<div id='middle-column'>
			<h1 id='visits-calc-container' class='column-header-small center-text'><!-- VISITS --></h1>
		</div>
		<div id='right-column'>
			<h1 id='stories-calc-container' class='column-header-small center-text'><!-- STORIES --></h1>
		</div>

		<div id='control-panel'>
			<form id='general-settings' class='row blue-border-top' method='POST'>
				<h1 class='row-header'>General Settings</h1>
				<label for='club-name'>Club Name :</label>
				<input id='club-name' name='clubName' class='wide-fat' placeholder='Club name goes here..' value='<?php echo $getClubName; ?>'>
				<label for='club-slug'>Club Slug :</label>
				<input id='club-slug' class='wide-fat' name='clubSlug' readonly value='<?php echo $getClubSlug; ?>'>
				
				<label for='club-type'>Club Type :</label>
				<?php //Check current Club type and select the correct one
					if ( $getClubType == "SECRET" ) { $checkSecretType = "selected"; }
					else
					if ( $getClubType == "CLOSED" ) { $checkClosedType = "selected"; }
					else
					if ( $getClubType == "PUBLIC" ) { $checkPublicType = "selected"; }
				?>
				<select id='club-type' class='wide-fat' name='clubType'>
					<option value="SECRET" <?php echo $checkSecretType; ?>>Secret</option>
					<option value="CLOSED" <?php echo $checkClosedType; ?>>Closed</option>
					<option value="PUBLIC" <?php echo $checkPublicType; ?>>Public</option>
				</select>

				<label for='club-authors'>Who can write posts :</label>
				<?php //Check authors options
					if ( $getClubAuthors == "0" ) { $checkAdministratorsOnly = "selected"; }
					else
					if ( $getClubAuthors == "1" ) { $checkAllMembers = "selected"; }
				?>
				<select id='club-authors' class='wide-fat' name='clubAuthors'>
					<option value="0" <?php echo $checkAdministratorsOnly; ?>>Administrators only</option>
					<option value="1" <?php echo $checkAllMembers; ?>>All members</option>
				</select>
				<div style='display: none'>
					<input id='club-owner' name='clubOwner' value='<?php echo $clubOwner."_Clubs"; ?>'>
					<input id='club-id' name='clubID' value='<?php echo $catchClubId; ?>'>
				</div>

				<label for='club-comments'>Comments :</label>
				<?php //Check comments options
					if ( $getClubComments == "0" ) { $checkDisableComments = "selected"; }
					else
					if ( $getClubComments == "1" ) { $checkEnableComments = "selected"; }
				?>
				<select id='club-comments' class='wide-fat' name='clubComments'>
					<option value="0" <?php echo $checkDisableComments; ?>>Disable</option>
					<option value="1" <?php echo $checkEnableComments; ?>>Enable</option>
				</select>

				<label for='club-like-button'>Like button :</label>
				<?php //Check like button options 
					if ( $getClubLikeButton == "0" ) { $checkDisableLikeButton = "selected"; }
					else
					if ( $getClubLikeButton == "1" ) { $checkEnableLikeButton = "selected"; }
				?>
				<select id='club-like-button' class='wide-fat' name='clubLikeButton'>
					<option value="0" <?php echo $checkDisableLikeButton; ?>>Disable</option>
					<option value="1" <?php echo $checkEnableLikeButton; ?>>Enable</option>
				</select>
				</select>
				<!-- UPDATE BUTTON -->
				<button type="button" id="update-button" class="submit-button mt-15" onclick="updateClubMeta(<?php echo $isMobile; ?>);">Update</button>
			</form>
			<form id='visualize-settings' class='row yellow-border-top' method='POST' enctype='multipart/form-data'>
				<h1 class='row-header'>Visual Settings</h1>
				<button type='button' class='inline-option-button wide-fat mt-10' onclick="$('#logo-container-dashboard').click();">Change club logo</button>
				<button type='button' class='inline-option-button wide-fat mt-10' onclick="loadColorPickerClubs(<?php echo $isMobile; ?>);">Change club color</button>
				<input type='file' style='display: none;' id='logo-container-dashboard' name='fileToUpload' onchange="uploadLogo('#logo-container-dashboard');">
				<div style='display: none'>
					<input id='club-owner' name='clubOwner' value='<?php echo $clubOwner."_Clubs"; ?>'>
					<input id='club-id' name='clubID' value='<?php echo $catchClubId; ?>'>
					<input type='text' id='is-club' name='isClub' value="1">
				</div>
			</form>
			<div id='remove-club-container' class='row red-border-top'>
				<h1 class='row-header'>Delete club & Club story</h1>
				<button type='button' class='inline-remove-button wide-fat mt-10' onclick='promptDeleteClubConfirmation(<?php echo $isMobile; ?>);'>Delete permanently</button>
			</div>
			<div id='members-container' class='row green-border-top'>
				<h1 class='row-header'>Administrators</h1>
				<div id='administrators-container' class='mt-10'>
					<!-- LOAD ADMINISTRATORS FROM JS FUNCTION IN THE FOOTER -->
				</div>
				<div class='row-splitter'></div>
				<h1 class='row-header'>Requests</h1>
				<div id='requests-container' class='mt-10'>
					<!-- LOAD REQUESTS FROM JS FUNCTION IN THE FOOTER -->
				</div>
				<div class='row-splitter'></div>
				<h1 class='row-header'>Members</h1>
				<button type='button' class='row-option-button' onclick="loadSearchAndInviteEngine(<?php echo $isMobile; ?>);">Invite</button>
				<div id='club-members-container' class='mt-10'>
					<!-- LOAD MEMBERS FROM JS FUNCTION THE FOOTER -->
				</div>
			</div>
	</div>
</div>