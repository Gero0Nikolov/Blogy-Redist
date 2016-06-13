<?php 
	$user_id = $_POST["userID"];

	$server_path = "/home/blogycoo/public_html/";

	$build_ = "";
	if ( !empty( $user_id ) ) {
		$lines_count = 0;
		$author = fopen( $server_path."Library/Authors/$user_id/config.txt", "r" ) or die("Unable to open author.");
		while ( !feof( $author ) ) {
			$line = fgets( $author );
			if ( $lines_count == 0 ) {
				$user_img = trim( $line );
			}
			if ( $lines_count == 1 ) {
				$user_social_profile = trim( $line );
			}
			else
			if ( $lines_count == 3 ) {
				$user_first_name = trim( $line );
			}
			else
			if ( $lines_count == 4 ) {
				$user_last_name = trim( $line );
				break;
			}
			$lines_count++;
		}
		fclose($author);
		
		$build_ = "
			<a id='$user_id' class='follower' href='openBloger.php?$user_id' data-ajax='false'>
				<div style='background: url(\"$user_img\"); background-size: cover; background-position: center;' class='profile-picture'></div>
				$user_first_name $user_last_name
			</a>
		";
	}

	//Return response
	echo $build_;
?>