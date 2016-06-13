<?php
	//Connect to the Database
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Add BAN column
	$sql = "ALTER TABLE WorldBloggers ADD BAN INT";
	$conn->query($sql);
	//Add TUTO column
	$sql = "ALTER TABLE WorldBloggers ADD TUTO INT";
	$conn->query($sql);
	//Add NICK COLUMN
	$sql = "ALTER TABLE WorldBloggers ADD Nickname LONGTEXT";
	$conn->query($sql);
	
	//Catch and write nicknames of all authors
	$sql = "SELECT Author_UID FROM WorldBloggers ORDER BY ID ASC";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$authorID = $row['Author_UID'];
			
			//Parse author
			$lines_count = 0;
			$parser = fopen("../../Authors/$authorID/config.txt", "r") or die("Unable to open author.");
			while ( !feof($parser) ) {
				$line = fgets($parser);
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
			fclose($parser);

			//Build author nickname
			$nickname = $authorFN.$authorLN;

			//Push the changes into the Database
			$sql = "INSERT INTO WorldBloggers (BAN, TUTO, Nickname) VALUES (0, 1, '$nickname') WHERE Author_UID='$authorID'";
			$conn->query($sql);
		}
	}

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>