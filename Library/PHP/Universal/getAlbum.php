<?php
	session_start();
	$sender = $_SESSION['sender'];

	//Include bundle
	include "functions.php";

	$getId = $_POST["lastId"];
	$isMobile = $_POST["isMobile"];

	//Connect to dataBase
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Check if user has clubs
	$sql = "SELECT ID FROM ".$sender."_Clubs LIMIT 1";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) { $addClubLogoButton = 1; }

	//Get content
	$isTrue = 0;
	while ($isTrue == 0 && $getId > 1) {
		$sql = "SELECT ID, ALBUM FROM albumOf$sender WHERE ID='$getId'";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ($row['ALBUM'] != "SPACE") {
					$picture = $row['ALBUM'];
					$src = "http://".$_SERVER[HTTP_HOST]."/Library/Authors/$sender/Album/".$picture;

					if ($isMobile == 1) {

						if ( isValidImage($src) ) {
							$build = "
								<div id='imgContainer' class='$getId' style='background-image:url(\"$src\")' onclick='showAlbumOptionsM(\"$picture\", \"$sender\", \"$getId\")'>
									<form id='picture$picture' method='post' style='display: none'>
										<input id='pictureId' name='pictureId' value='$picture'>
									</form>
								</div>
							";
						} else {
							$build = "
								<div id='imgContainer' class='$getId'>
									<video id='$picture' class='img' src='$src' preload='none'>
									</video>
								</div>
							";
						}
					} elseif ($isMobile == 0) {

						if ( isValidImage($src) ) {
							if ( $addClubLogoButton == 1 ) { $changeClubLogoButton = "<button type='button' class='split' onclick='loadClubSelector(0, \"change-logo\", \"$picture\")'>Set as a club logo</button>"; }

							$build = "
								<div id='imgContainer' class='$getId'>
									<div id='$picture' class='img' style='background: url(\"$src\"); background-size: cover; background-position: 50%;' alt='Bad image link :('>
										<div id='imgOptions'>
											<a href='$src' data-lightbox='roadtrip'>
												<button type='button' class='split'>View</button>
											</a>
											<button type='button' class='split' onclick='showContainerPost(\"$picture\", \"$sender\")'>Make a story</button>
											<button type='button' class='split' onclick='showContainerFriends(\"$picture\")'>Send to a friend</button>
											<button type='button' class='split' onclick='setAsProfilePic(\"$picture\")'>Set as a profile pic.</button>
											".$changeClubLogoButton."
											<button type='button' onclick='deleteObjectFromAlbum(\"$picture\", \"$getId\")' class='remove-button-hover'>Delete</button>
										</div>
									</div>
								</div>
							";
						} else {
							$build = "
								<div id='imgContainer' class='$getId'>
									<video id='$picture' class='img' src='$src' onclick='playVideo(\"$picture\", \"$getId\", \"$src\", \"$sender\");' title='Play video'>
									</video>
								</div>
							";
						}
					}

					$isTrue = 1;
				}
			}

			break;
		} else {
			$getId--;
		}
	}

	if ($getId > 1) {
		$getId--;
		$build .= "~1~$getId";
	} else {
		$build .= "~0";
	}

	//Close connection
	$conn->close();

	//Return response
	echo $build;
?>