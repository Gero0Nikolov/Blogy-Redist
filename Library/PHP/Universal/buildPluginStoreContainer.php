<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$plugin_id = $_POST["pluginID"];
	$mobile = $_POST["mobile"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Plugin_Name, Plugin_Slug, Plugin_Path, Plugin_Author, Folks, Likers, Haters, Core_Insight FROM Plugin_Store WHERE ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_name = $row["Plugin_Name"];
			$plugin_slug = $row["Plugin_Slug"];
			$plugin_url_path = $row["Plugin_Path"];
			$plugin_ori_path = str_replace("http://blogy.co/", "/home/blogycoo/public_html/", $plugin_url_path);
			$plugin_author = $row["Plugin_Author"];
			$plugin_core_insight = $row["Core_Insight"];

			if ( !empty($row["Folks"]) ) { $plugin_folks = explode(",", $row["Folks"]); }
			else { $plugin_folks = array(); }
			if ( !empty($row["Likers"]) ) { $plugin_likers = explode(",", $row["Likers"]); }
			else { $plugin_likers = array(); }
			if ( !empty($row["Haters"]) ) { $plugin_haters = explode(",", $row["Haters"]); }
			else { $plugin_haters = array(); }
		}
	}

	//Close the connection
	$conn->close();

	//Do mathematics if !empty
	$count_folks = 0;
	$count_likes = 0;
	$count_hates = 0;
	if ( !empty($plugin_folks) ) { $count_folks = count( $plugin_folks ); }
	if ( !empty($plugin_likers) ) { $count_likes = count( $plugin_likers ); }
	if ( !empty($plugin_haters) ) { $count_hates = count( $plugin_haters ); }

	//Set tags
	if ( $plugin_core_insight == 1 ) { $core_tag = "<div class='core tag'><span>&bull;</span>Core</div>"; }

	//Get meta
	$open_meta = fopen($plugin_ori_path ."/meta-description.txt", "r");
	$read_meta = fread($open_meta, filesize($plugin_ori_path ."/meta-description.txt"));
	fclose($open_meta);

	$meta_icon = trim( explode(";", explode("~icon:", $read_meta)[1] )[0] );
	$meta_license = trim( explode(";", explode("~license:", $read_meta)[1] )[0] );
	$meta_description = nl2br( trim( explode(";", explode("~description:", $read_meta)[1] )[0] ) );

	$likes_text = "Like";
	$hates_text = "Hate";
	$hooks_text = "Hook";

	if ( !in_array($sender, $plugin_likers) && !in_array($sender, $plugin_haters) ) { $like_button = "<button id='like-button' title='Like plugin' onclick='likeUnlikePlugin($plugin_id, $mobile);'><span class='iconic'>&#xf164;</span>$count_likes $likes_text</button>"; }
	elseif ( in_array($sender, $plugin_likers) ) { $like_button = "<button id='unlike-button' class='liked' title='Unlike plugin' onclick='likeUnlikePlugin($plugin_id, $mobile);'><span class='iconic'>&#xf164;</span>$count_likes $likes_text</button>"; }
	if ( in_array($sender, $plugin_haters) ) { $like_button = "<button id='like-button' class='inactive-like'><span class='iconic'>&#xf164;</span>$count_likes $likes_text</button>"; }

	if ( !in_array($sender, $plugin_haters) && !in_array($sender, $plugin_likers) ) { $hate_button = "<button id='hate-button' title='Hate plugin' onclick='hateUnhatePlugin($plugin_id, $mobile);'><span class='iconic'>&#xf165;</span>$count_hates $hates_text</button>"; }
	elseif ( in_array($sender, $plugin_haters) ) { $hate_button = "<button id='hate-button' class='hated' title='Unhate plugin' onclick='hateUnhatePlugin($plugin_id, $mobile);'><span class='iconic'>&#xf165;</span>$count_hates $hates_text</button>"; }
	if ( in_array($sender, $plugin_likers) ) { $hate_button = "<button id='hate-button' class='inactive-hate'><span class='iconic'>&#xf165;</span>$count_hates $hates_text</button>"; }


	if ( !in_array($sender, $plugin_folks) && $sender != $plugin_author ) { $attach_button = "<button id='install-button' title='Install plugin' onclick='installPlugin($plugin_id, $mobile);'><span class='iconic'>&#xf0c1;</span>$count_folks $hooks_text</button>"; }
	elseif ( in_array($sender, $plugin_folks) ) { $attach_button = "<button id='uninstall-button' class='installed' title='Uninstall plugin' onclick='uninstallPlugin($plugin_id, $mobile);'><span class='iconic'>&#xf127;</span>$count_folks $hooks_text</button>"; }
	if ( $sender == $plugin_author ) { $attach_button = "<button id='install-button' class='inactive-install'><span class='iconic'>&#xf0c1;</span>$count_folks $hooks_text</button>"; }

	if ( !empty( $like_button ) && !empty( $hate_button ) ) { $left_bull = "<span class='bullet'>&bull;</span>"; }
	if ( !empty( $hate_button ) && !empty( $attach_button ) ) { $right_bull = "<span class='bullet'>&bull;</span>"; }

	$response = "
		<div class='plugin-icon' style='background-image: url($meta_icon); background-size: cover; background-position: 50%;'></div>
		<div class='plugin-meta'>
			<h1 class='title'>$plugin_name</h1>
			<h2 class='license'>License: $meta_license</h2>
			<p class='description'>$meta_description</p>
			<h3 class='author'>By: <a href='openBloger.php?$plugin_author'>$plugin_author</a></h3>
			<div class='tags'>
				$core_tag
			</div>
		</div>
		<div class='plugin-options'>
			$like_button
			$left_bull
			$hate_button
			$right_bull
			$attach_button
		</div>
	";

	//Return response
	echo $response; 
?>