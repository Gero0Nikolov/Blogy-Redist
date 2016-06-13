<?php
	include "../PHP/Universal/dataBase.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$set_state_ = $_POST["allPlugins"];
	$buildedPlugins = "";

	$select_where_not_activated = "";
	if ( $set_state_ == 0 ) { $select_where_not_activated = "WHERE Plugin_Store_State=0"; }

	$sql = "SELECT ID, Plugin_Name, Plugin_Slug, Plugin_Path, Plugin_Author, Plugin_Store_State, Featured FROM Plugin_Store $select_where_not_activated ORDER BY ID DESC";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_store_id = $row["ID"];
			$plugin_name = $row["Plugin_Name"];
			$plugin_slug = $row["Plugin_Slug"];
			$plugin_url_path = $row["Plugin_Path"];
			$plugin_ori_path = str_replace("/blogy/", "", str_replace("http://blogy.co", "/home/blogycoo/public_html/", $plugin_url_path));
			$plugin_author = $row["Plugin_Author"];
			$plugin_state = $row["Plugin_Store_State"];
			$plugin_featured = $row["Featured"];

			//Get plugin meta
			$open_meta = fopen($plugin_ori_path ."/meta-description.txt", "r");
			$read_meta = fread($open_meta, filesize($plugin_ori_path ."/meta-description.txt"));
			fclose($open_meta);

			//Parse meta
			$plugin_icon = trim( explode(";", explode("~icon:", $read_meta)[1] )[0] );
			$plugin_license = trim( explode(";", explode("~license:", $read_meta)[1] )[0] );
			$plugin_description = nl2br( trim( explode(";", explode("~description:", $read_meta)[1] )[0] ) );
	

			if ( $set_state_ == 1 ) {
				if ( $plugin_featured == 0 ) { $feature_button_ = "<button id='feature' class='option-button' onclick='featurePlugin($plugin_store_id, 0);'>Feature</button>"; }
				elseif ( $plugin_featured == 1 ) { $feature_button_ = "<button id='unfeature' class='option-button delete-button' onclick='unfeaturePlugin($plugin_store_id, 0);'>Unfeature</button>"; }

				if ( $plugin_state == 0 ) { $change_state_ = "<button id='enable' class='option-button' onclick='activatePlugin($plugin_store_id, 0);'>Enable</button>"; }
				elseif ( $plugin_state == 1 ) { $change_state_ = "<button id='disable' class='option-button delete-button' onclick='disablePlugin($plugin_store_id, 0);'>Disable</button>"; }

				$controls_ = "
					<div id='controls-container' class='plugin-$plugin_store_id'>
						<button id='download-button' class='option-button' onclick='downloadPluginProject(\"$plugin_ori_path\", \"$plugin_slug\", \"$plugin_author\", 0);'>
							Download
						</button>
						$feature_button_
						$change_state_
						<button id='decline' class='option-button delete-button' onclick='deletePluginFromStore($plugin_store_id, 0);'>
							Delete
						</button>
					</div>
				";
			} elseif ( $set_state_ == 0 ) {
				$controls_ = "
					<div id='controls-container'>
						<button id='download-button' class='option-button' onclick='downloadPluginProject(\"$plugin_ori_path\", \"$plugin_slug\", \"$plugin_author\", 0);'>
							Download
						</button>
						<button id='approve' class='option-button' onclick='addRequestedPlugin($plugin_store_id, \"$plugin_slug\", \"$plugin_author\", 0);'>
							Add
						</button>
						<button id='decline' class='option-button delete-button' onclick='declineRequestedPlugin($plugin_store_id, \"$plugin_author\", 0);'>
							Delete
						</button>
					</div>
				";
			}
 
			$container_ = "
				<div id='row-container' class='plugin-container plugin-$plugin_store_id'>
					<div id='options'>
						<button id='plugin-title' onclick='openPluginDescription(\".plugin-$plugin_store_id>#description\");'>
							<div id='plugin-logo' style='background-image: url($plugin_icon); background-size: cover; background-position: 50%;'></div>
							$plugin_name
						</button>
						$controls_
					</div>
					<div id='description'>
						<p id='license'>License: $plugin_license</p>
						<p id='author'>By: <a href='http://blogy.co?$plugin_author' target='_blank'>$plugin_author</a></p>
						<p id='text'>$plugin_description</p>
					</div>
				</div>
			";

			$buildedPlugins .= $container_;
		}
	}

	$conn->close();

	//Return result
	echo $buildedPlugins;
?>