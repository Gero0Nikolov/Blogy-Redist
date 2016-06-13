<?php
	session_start();
	$sender = $_SESSION["sender"];
	if (!isset($sender)) { die(); }

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
	
	$acceptButton = "<button type='button' class='just-button' title='Accept blogger' onclick='acceptClubRequest(\"".$authorId."\", ".$mobile.")'>Accept</button>";
	$removeButton = "<button type='button' class='delete-button' onclick='declineClubRequest(\"".$authorId."\", ".$mobile.");'>Decline</button>";

	$getBuild = "
		<div id='placeholder' class='column w-65p vam'>
			<a href='openBloger.php?".$authorId."'>
				<div style='background: url(\"".$authorImg."\"); background-size: cover; background-position: 50%;' class='img'></div>
				".$authorFN." ".$authorLN."
			</a>
		</div>
		<div id='options-container' class='column w-30p vam'>
			".$acceptButton."
			".$removeButton."
		</div>
	";	

	echo $getBuild;
?>