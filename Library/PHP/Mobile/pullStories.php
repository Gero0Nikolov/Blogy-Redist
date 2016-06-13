<?php
	$sender = $_POST['sender'];
	$getId = $_POST['getId'];
	$getCmd = $_POST['buildFor'];

	if ($getCmd == 0 && !isset($sender)) {
		session_start();
		$sender = $_SESSION['sender'];
	}
	else
	if ($getCmd == 1) {
		$sender = $_POST['sender'];
	}
	else
	if ($getCmd == 5) {
		$getClubTable = $_POST["clubTable"];
		$getClubId = $_POST["clubId"];

		session_start();
		$sender = $_SESSION['sender'];
	}

	/*else
	if ($getCmd == 2) {
		$authorInfo = explode("#", $_COOKIE['authorInfo']);
		$sender = $authorInfo[0];
	}*/

	include "../Universal/functions.php";
	include "loadStories.php";
	
	//Connect to data base
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Check if ID = -1 -- Begining author table
	if ( $getId == -1 && !isset($_COOKIE["buildWorldStories"])) {
		if ( $getCmd == 5 ) {
			$table_name = $getClubTable."_Story_".$getClubId;
			$sql = "SELECT ID FROM $table_name ORDER BY ID DESC LIMIT 1";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getId = $row["ID"];
				}
			} else {
				$getId = 1;
			}
		} else {
			$sql = "SELECT ID FROM stack$sender ORDER BY ID DESC LIMIT 1";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getId = $row["ID"];
				}
			} else {
				$getId = 1;
			}
		}
	} 
	elseif ( $getId == -1 && isset($_COOKIE["buildWorldStories"]) ) {
		$sql = "SELECT ID FROM worldStories ORDER BY ID DESC LIMIT 1";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getId = $row["ID"];
			}
		} else {
			$getId = 0;
		}
	}


	$isFound = 0;

	//Build the story
	if (!isset($_COOKIE["buildWorldStories"])) {

		if ( $getCmd == 5 ) {
			while ($isFound == 0 && $getId > 0) {
				$sql = "SELECT Story_Title, Story_Link, Story_Content, Likers, Author FROM ".$getClubTable."_Story_".$getClubId." WHERE ID=$getId";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						$getTitle = $row["Story_Title"];
						$getLink = $row["Story_Link"];
						$getContent = $row["Story_Content"];
						$getLikes = $row["Likers"];
						$getAuthor = $row["Author"];

						$lineCount = 0;
						$pullInfo = fopen("../../Authors/$getAuthor/config.txt", "r") or die("Fatal: Could not load.");
						while (!feof($pullInfo)) {
							$line = trim(fgets($pullInfo));
							if ($line != "") {
								if ($lineCount == 0) {
									$authorImg = $line;
								}
								else
								if ($lineCount == 1) {
									$authorHref = $line;
								}
								else
								if ($lineCount == 3) {
									$authorFN = $line;
								}
								else
								if ($lineCount == 4) {
									$authorLN = $line;
									break;
								}
							}
							$lineCount++;
						}
						fclose($pullInfo);
					}

					$authorInfo = array("$getAuthor", "$authorImg", "$authorHref", "$authorFN", "$authorLN");	
					$isFound = 1;
				} else {
					$getId--;
				}
			}
		} else {
			while ($isFound == 0 && $getId > 0) {
				$sql = "SELECT STORYTITLE, STORYLINK, STORYCONTENT, LIKES FROM stack$sender WHERE ID=$getId";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						$getTitle = $row["STORYTITLE"];
						$getLink = $row["STORYLINK"];
						$getContent = $row["STORYCONTENT"];
						$getLikes = $row["LIKES"];
					
						$isFound = 1;
					}
				} else {
					$getId--;
				}
			}
		}

	} else {
		while ($isFound == 0 && $getId > 0) {
			$sql = "SELECT AuthorTitle, LINK, POST FROM worldStories WHERE ID=$getId";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getAuthorTitle = $row["AuthorTitle"];
					$getLink = $row["LINK"];
					$getContent = $row["POST"];

					$getTitle = explode("$", $getAuthorTitle)[2];

					$oldAuthor = $postAuthor;
					$postAuthor = explode("$", $getAuthorTitle)[0];
					if ($postAuthor != $oldAuthor) {
						$lineCount = 0;
						$pullInfo = fopen("../../Authors/$postAuthor/config.txt", "r") or die("Fatal: Could not load.");
						while (!feof($pullInfo)) {
							$line = trim(fgets($pullInfo));
							if ($line != "") {
								if ($lineCount == 0) {
									$authorImg = $line;
								}
								else
								if ($lineCount == 1) {
									$authorHref = $line;
								}
								else
								if ($lineCount == 3) {
									$authorFN = $line;
								}
								else
								if ($lineCount == 4) {
									$authorLN = $line;
									break;
								}
							}
							$lineCount++;
						}
						fclose($pullInfo);
					}

					$authorInfo = array("$postAuthor", "$authorImg", "$authorHref", "$authorFN", "$authorLN");	
					$isFound = 1;
				}
			} else {
				$getId--;
			}
		}
	}
	
	//Close the connection
	$conn->close();

	setcookie("buildWorldStories", "", time() - 3600);

	//Increment down for next ID
	$currentId = $getId;
	$getId -= 1;
		
	if ($getId >= 0) {
		if ($getCmd == 0) echo $getId."~".parseContent($getTitle, $getLink, $getContent, $getLikes, 0, "0", $currentId, $sender);
		else
		if ($getCmd == 1 || $getCmd == 3) echo $getId."~".parseContent($getTitle, $getLink, $getContent, $getLikes, 0, "1", $currentId, $sender);
		else
		if ($getCmd == 2) echo $getId."~".parseContent($getTitle, $getLink, $getContent, "NULL", $authorInfo, "2", $currentId, $sender);
		else
		if ($getCmd == 4) echo $getId."~".parseContent($getTitle, $getLink, $getContent, $getLikes, 0, "3", $currentId, $sender);
		else
		if ($getCmd == 5) echo $getId."~".parseContent($getTitle, $getLink, $getContent, $getLikes, $authorInfo, "5", $currentId, $sender);
	}
?>