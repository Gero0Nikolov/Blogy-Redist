<?php
	$authorId = $_POST["authorId"];
	$isBanned = $_POST["banned"];
	$isOnline = $_POST["online"];

	$lines_count = 0;
	$author = fopen("../Authors/$authorId/config.txt", "r") or die("Unable to open author.");
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

	if ($isBanned == 0) { 
		$ban_button = "
			<button type='button' class='option-button delete-button ban-button' onclick='banBlogger(\"$authorId\");' title='Ban Blogger'>
				Ban
			</button>
		";
	} elseif ($isBanned == 1) {
		$ban_button = "
			<button type='button' class='option-button delete-button ban-button' onclick='unbanBlogger(\"$authorId\");' title='Unban Blogger'>
				Unban
			</button>
		";
	}

	if ($isOnline == 0) { $unload_button = ""; }
	else
	if ($isOnline == 1) {
		$unload_button = "
			<button type='button' class='option-button unload-button' onclick='unloadBlogger(\"$authorId\");' title='Set the Blogger to offline'>
				Unload
			</button>
		";
	}
	
	$getBuild = "
		<div id='row-container' class='$authorId'>
			<a href='http://".$_SERVER[HTTP_HOST]."?".$authorId."' target='_blank' class='placeholder'>
				<div style='background: url(\"$authorImg\"); background-size: cover; background-position: 50%;' class='img'></div>
				$authorFN $authorLN
			</a>
			<div id='controls-container'>
				".$unload_button."
				".$ban_button."
			</div>
		</div>
	";	

	echo $getBuild;
?>