<?php
	include "../PHP/Universal/dataBase.php"; 
	
	$buildedButtons = "";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load users
		$sql = "SELECT ID, ADMIN FROM grantedPermissions ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$authorRow = $row["ID"];
				$authorId = $row["ADMIN"];

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

				$build = "
					<div id='row-container' class='$authorRow'>
						<button type='button' class='placeholder' onclick='window.open(\"http://".$_SERVER[HTTP_HOST]."?$authorId\");'>
							<div style='background-image:url($authorImg); background-size: cover; background-position: 50%;' class='img'></div>
							$authorFN $authorLN
						</button>
						<div id='controls-container'>
							<button type='button' class='option-button delete-button' onclick='deleteAdmin(\"$authorRow\");' title='Delete admin'>
								Delete
							</button>
						</div>
					</div>
				";

				$buildedButtons .= $build;
			}
		}
	}

	//Close the connection
	$conn->close();

	echo $buildedButtons;
?>