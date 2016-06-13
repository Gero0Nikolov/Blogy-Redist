<?php
	session_start();
	$sender = $_SESSION["sender"];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$authorId = $_POST["authorId"];

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
	
	$getBuild = "
		<a id='$authorId' href='openBloger.php?$authorId'>
			<div style='background: url(\"$authorImg\"); background-size: cover; background-position: 50%;' class='img'></div>
			$authorFN $authorLN
		</a>
	";	

	echo $getBuild;
?>