<?php 
	session_start();
	$sender = $_SESSION[ "sender" ];
	if ( !isset( $sender ) ) { die(); }

	$offset_ = $_POST[ "imagesOffset" ];

	$response = "";

	//Server path
	$server_path = "/home/blogycoo/public_html/";

	//Include bundle
	include $server_path."Library/PHP/Universal/functions.php";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, ALBUM, SPACE FROM albumOf$sender ORDER BY ID DESC LIMIT 3 OFFSET $offset_";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		$element_counter = 1;
		while ($row = $pick->fetch_assoc()) {
			if ( $row[ "ALBUM" ] != "SPACE" ) {
				$element_id = $row[ "ID" ];
				$element_ = $row[ "ALBUM" ];
				$src = "http://". $_SERVER[ "HTTP_HOST" ] ."/Library/Authors/$sender/Album/". $element_;

				if ( isValidImage( $src ) ) {
					if ( $element_counter < 3 ) {
						if ( $element_counter == 1 ) { $container_position = "left"; }
						elseif ( $element_counter == 2 ) { $container_position = "right"; }

						$build = "
							<div id='element-$element_id' class='album-element medium-element $container_position' style='background-image: url(\"$src\"); background-size: cover; background-position: center;' onclick='openAlbumController( \"element-$element_id\" );'>
							</div>
						";
					} elseif ( $element_counter == 3 ) {
						$build = "
							<div id='element-$element_id' class='album-element large-element' style='background-image: url(\"$src\"); background-size: cover; background-position: center;' onclick='openAlbumController( \"element-$element_id\" );'>
							</div>
						";
					}
				} else {
					if ( $element_counter < 3 ) {
						if ( $element_counter == 1 ) { $container_position = "left"; }
						elseif ( $element_counter == 2 ) { $container_position = "right"; }

						$build = "
							<video id='element-$element_id' class='album-element medium-element $container_position' src='$src' preload='none' controls='false' onclick='openAlbumController( \"element-$element_id\" );'>
							</video>
						";
					} elseif ( $element_counter == 3 ) {
						$build = "
							<video id='element-$element_id' class='album-element large-element' src='$src' preload='none' controls='false' onclick='openAlbumController( \"element-$element_id\" );'>
							</video>
						";
					}
				}

				$element_counter += 1;
				if ( $element_counter > 3 ) { $element_counter = 1; }

				//Append build to the global response
				$response .= $build;
			}
		}
	}

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>