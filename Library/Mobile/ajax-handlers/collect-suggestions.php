<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	$server_path = "/home/blogycoo/public_html/";

	$following_ = array();
	if ( file_exists( $server_path."Library/Authors/$sender/Following.txt") ) {
		$following_list = fopen( $server_path."Library/Authors/$sender/Following.txt", "r" ) or die( "Fatal: Could not start opening." );
		while ( !feof( $following_list ) ) {
			$line = trim( fgets( $following_list ) );
			if ( $line != "" ) {
				array_push( $following_, $line );
			}
		}
		fclose( $following_list );
	}

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";
	
	$blocked_persons = array();
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query( $sql );
		if ( $pick->num_rows > 0 ) {
			while ( $row = $pick->fetch_assoc() ) {
				array_push( $blocked_persons, $row['BLOCKEDID']) ;
			}
		}
	}
	
	$current_user_hobbies = "";
	$build = "";
	$suggestions_ = array();

	//Get hobbies of the current user
	$sql = "SELECT Hobbies FROM WorldBloggers WHERE Author_UID='$sender'";
	$pick = $conn->query($sql);

	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$current_user_hobbies = explode( ",", $row["Hobbies"] );
		}
	}

	//Get similar hobbied persons
	foreach ( $current_user_hobbies as $hobby ) {
		if ( !empty( $hobby ) ) {
			$sql = "SELECT Author_UID
					FROM WorldBloggers
					WHERE Hobbies LIKE  '%".$hobby."%'
					AND Author_UID !=  '".$sender."'";
			$pick = $conn->query($sql);

			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$author_uid = $row["Author_UID"];

					$sql = "SELECT BLOCKEDID FROM blockList$author_uid WHERE BLOCKEDID='$sender'";
					$pick_inline = $conn->query($sql);
					if ( $pick_inline->num_rows <= 0  && !in_array( $author_uid, $blocked_persons ) && !in_array( $author_uid, $following_ ) ) {
						array_push( $suggestions_, $author_uid );
					}

					$suggestions_ = array_unique( $suggestions_ );
				}
			}
		}
	}

	if ( !empty( $suggestions_ ) ) {
		foreach ( $suggestions_ as $author_uid ) {
			if ( !empty( $author_uid ) ) {
				$lineCount = 0;
				if ( $author_uid != NULL) {
					$user_meta = fopen( $server_path."Library/Authors/$author_uid/config.txt", "r" ) or die( "Fatal: Could not load." );
					while ( !feof( $user_meta ) ) {
						$line = trim( fgets( $user_meta ));
						if ( $line != "" ) {
							if ( $lineCount == 0 ) {
								$profile_picture = $line;
							}
							else
							if ( $lineCount == 1 ) {
								$social_link = $line;
							}
							else
							if ( $lineCount == 3 ) {
								$user_first_name = $line;
							}
							else
							if ( $lineCount == 4 ) {
								$user_last_name = $line;
								break;
							}
						}

						$lineCount += 1;
					}
					fclose( $user_meta );
									
					//Build and print
					$build .= "
						<a class='suggestion-controller' href='openBloger.php?$author_uid' data-ajax='false'>
							<div style='background: url(\"$profile_picture\"); background-size: cover; background-position: 50%;' class='profile-picture'></div>
							$user_first_name $user_last_name
						</a>
					";
				}		
			}
		}
	} else {
		$build = "<h2 class='sidebar-message'>There are no new suggestion for you :-(</h2>";
	}
	
	$conn->close(); //Close SQL Connection

	//Return response
	$response = $build;
	echo $response;
?>