<?php
	session_start();
	$sender = $_SESSION["sender"];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$authorId = explode("~", $_POST["authorId"])[0];
	$authorType = explode("~", $_POST["authorId"])[1];
	$container = $_POST["container"];
	$mobile = $_POST["mobile"];
	$clubOwner = $_POST["clubOwner"];

	$lines_count = 0;
	$author = fopen("../../Authors/$authorId/config.txt", "r") or die("Unable to open author.");
	while (!feof($author)) {
		$line = fgets($author);
		if ($lines_count == 0) {
			$authorImg = trim($line);
		}
		if ($lines_count == 1) {
			$authorHref = trim($line);
		}
		else
		if ($lines_count == 3) {
			$authorFN = trim($line);
		}
		else
		if ($lines_count == 4) {
			$authorLN = trim($line);
			break;
		}
		$lines_count++;
	}
	fclose($author);
	
	$removeButton = "";
	if ($authorId == $clubOwner) {
		$getBuild = "
			<div id='placeholder' class='column w-65p vam'>
				<a href='openBloger.php?".$authorId."'>
					<div style='background: url(\"$authorImg\"); background-size: cover; background-position: 50%;' class='img'></div>
					$authorFN $authorLN
				</a>
			</div>
			<div id='options-container' class='column w-30p vam'>
				<span class='iconic roll-over' title='Owner of the club!'>&#xf006;</span>
			</div>
		";	
	} elseif ($authorId != $clubOwner && $authorType == "ADMIN") { 
		$removeButton = "<button type='button' class='delete-button' onclick='removeClubAdministrator(\"".$authorId."\", \"".$container."\", ".$mobile.");'>Remove</button>"; 
		
		$getBuild = "
			<div id='placeholder' class='column w-65p vam'>
				<a href='openBloger.php?".$authorId."'>
					<div style='background: url(\"$authorImg\"); background-size: cover; background-position: 50%;' class='img'></div>
					$authorFN $authorLN
				</a>
			</div>
			<div id='options-container' class='column w-30p vam'>
				".$removeButton."
			</div>
		";	
	} elseif ($authorId != $clubOwner && $authorType == "PROMOTION") {
		if ( $sender == $clubOwner ) {
			$removeButton = "<button type='button' class='delete-button' onclick='removeClubPromotion(\"".$authorId."\", ".$mobile.");'>Decline</button>"; 
			$approveButton = "<button type='button' class='just-button' onclick='acceptClubPromotion(\"".$authorId."\", ".$mobile.");'>Accept</button>";

			$getBuild = "
				<div id='placeholder' class='column w-65p vam'>
					<a href='openBloger.php?".$authorId."'>
						<div style='background: url(\"$authorImg\"); background-size: cover; background-position: 50%;' class='img'></div>
						$authorFN $authorLN
					</a>
				</div>
				<div id='options-container' class='column w-30p vam'>
					".$approveButton."
					".$removeButton."
				</div>
			";	
		} elseif ( $sender != $clubOwner ) {
			$pendingLabel = "<span class='iconic roll-over' title='Pending for confirmation'>&#xf017;</span>";

			$getBuild = "
				<div id='placeholder' class='column w-65p vam'>
					<a href='openBloger.php?".$authorId."'>
						<div style='background: url(\"$authorImg\"); background-size: cover; background-position: 50%;' class='img'></div>
						$authorFN $authorLN
					</a>
				</div>
				<div id='options-container' class='column w-30p vam'>
					".$pendingLabel."
				</div>
			";	
		}
	}

	

	echo "$getBuild";
?>