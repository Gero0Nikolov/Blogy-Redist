<?php
	session_start();
	$sender = $_SESSION["sender"];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$authorId = $_POST["authorId"];
	$container = $_POST["container"];
	$mobile = $_POST["mobile"];
	$memberType = $_POST["memberType"];

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
	
	$pendingSign = "";
	if ($memberType == "INVITED") { $pendingSign = "<span class='iconic roll-over' title='Pending for confirmation'>&#xf017;</span>"; }

	$promoteButton = "";
	if ($memberType == "MEMBER") { $promoteButton = "<button type='button' class='just-button' title='Promote member as administrator' onclick='promoteClubMember(\"".$authorId."\", ".$mobile.")'>Promote</button>"; }

	$removeButton = "<button type='button' class='delete-button' onclick='removeClubMember(\"".$authorId."\", ".$mobile.");'>Remove</button>";

	$getBuild = "
		<div id='placeholder' class='column w-65p vam'>
			<a href='openBloger.php?".$authorId."'>
				<div style='background: url(\"".$authorImg."\"); background-size: cover; background-position: 50%;' class='img'></div>
				".$authorFN." ".$authorLN."
			</a>
		</div>
		<div id='options-container' class='column w-30p vam'>
			".$pendingSign."
			".$promoteButton."
			".$removeButton."
		</div>
	";	

	echo $getBuild;
?>