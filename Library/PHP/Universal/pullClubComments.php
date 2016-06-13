<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$postId = $_POST["postId"];
	$mobile = $_POST["isMobile"];

	$responce = "";

	//Include bundle
	include "dataBase.php";

	//Connect to the Database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$table_name = $getClubTable."_Story_Comments_".$getClubId;
	$sql = "SELECT ID FROM $table_name";
	$pick = $conn->query($sql);
	if ($pick->num_rows <= 0) {
		$sql = "CREATE TABLE $table_name (
			ID int NOT NULL AUTO_INCREMENT, 
			Post_Id INT, 
			Post_Author LONGTEXT, 
			Post_Content LONGTEXT,
			PRIMARY KEY (ID))";
		$conn->query($sql);
	} else { //Table exists :O
		$sql = "SELECT ID, Post_Id, Post_Author, Post_Content FROM $table_name WHERE Post_Id=$postId ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$commentId = $row["ID"];
				$commentAuthor = $row["Post_Author"];
				$commentText = nl2br($row["Post_Content"]);

				$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
				$url = NULL;

				$splitComment = explode("<br />", $commentText);
				$commentText = "";
				foreach ($splitComment as $line) {
					if(preg_match($reg_exUrl, $line, $url)) {
						if (!strpos($line, "<img") && !strpos($line, "<a")) {
							$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
						}
					}

					$splitLine = explode(" ", $line);
					$line = ""; // Clear the line

					foreach ($splitLine as $part) {
						if (
							(substr($part, 0, 1) == "#" && !strpos($part, "# ")) 
							|| (strpos($part, "#") && !strpos($part, "# "))
							&& !strpos($part, "&quot;#")
						) {
							if (substr_count($part, "#") > 1) {
								$splitTags = explode("#", $part);
								if (empty($splitTags[0])) { array_shift($splitTags); } // Convert and clean the array
								elseif (!empty($splitTags[0])) { $splitTags[0] = "#".$splitTags[0]; }

								$part = ""; // Clear the part with multiple hashtags

								foreach ($splitTags as $tag) {
									if (substr($tag, 0, 1) == "#") { $part .= str_replace("#", "", $tag); } // If this is not a tag, just add it to the post content.
									else { $part .= "<a href='https://www.google.com/search?q=$tag' target='_blank'>#$tag</a>"; }
								}
							} elseif (substr_count($part, "#") == 1) { 
								$getTag = end(explode("#", $part)); 
								$part = str_replace("#$getTag", "<a href='https://www.google.bg/search?q=$getTag' target='_blank'>#$getTag</a>", $part);
							}
						}
						
						$line .= " ".$part; // Append the converted content to the line
					}

					$commentText .= $line;
				}


				$buildDeleteButton = "";
				$setSmallerWidth = "";
				if ( $sender == $commentAuthor ) { 
					$buildDeleteButton = "";
					$buildDeleteAction = "";

					if ( $mobile == 0 ) { 
						$setSmallerWidth = "style='width: calc(100% - 65px);'"; 
						$buildDeleteButton = "<button type='button' class='iconic delete' title='Remove comment' onclick='removeClubComment($commentId, $mobile);'>&#xf00d;</button>";
					}
					elseif ( $mobile == 1 ) { 
						$setSmallerWidth = "style='width: calc(100% - 100px);'"; 
						$buildDeleteAction = "onclick='removeClubComment($commentId, $mobile);'";
					}
				}
				
				$responce .= "
					<div id='$commentId' class='comment-container-row' $buildDeleteAction>
						<a href='openBloger.php?$commentAuthor'>
							<div id='picture-holder'></div>
						</a>
						$buildDeleteButton
						<p id='comment-text' $setSmallerWidth>$commentText</p>
						<script>loadCommentAuthor($commentId, \"$commentAuthor\", $mobile);</script>
					</div>
				";
			}
		}
	}

	//Close the connection
	$conn->close();

	//Retun responce
	echo $responce;
?>