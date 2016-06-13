<?php
	session_start();
	$sender = $_SESSION['sender'];
	if ( !isset($sender) ) { die(); }

	$plugin_slug = $_POST["pluginSlug"];

	//Connect to the database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get plugin info
	$table_user_relations = $sender ."_Plugins_Relations";
	$sql = "SELECT Plugin_Name, Plugin_Slug, Plugin_Address, Plugin_Author FROM $table_user_relations WHERE Plugin_Slug='$plugin_slug' AND Plugin_Author='$sender'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_name = $row["Plugin_Name"];
			$plugin_address = $row["Plugin_Address"];
			$plugin_author = $row["Plugin_Author"];
		}
	}

	//Send to plugin store
		//Create Plugin_Store table if not exists
	$sql = "CREATE TABLE Plugin_Store (
		ID int NOT NULL AUTO_INCREMENT,
		Plugin_Name LONGTEXT,
		Plugin_Slug LONGTEXT,
		Plugin_Path LONGTEXT,
		Plugin_Author LONGTEXT,
		Plugin_Store_State INT,
		Folks LONGTEXT,
		Likers LONGTEXT,
		Haters LONGTEXT,
		Featured INT,
		Core_Insight INT,
		PRIMARY KEY (ID))";
	$conn->query($sql);

	//Check if the plugin is already sent for review
	$sql = "SELECT Plugin_Slug, Plugin_Author FROM Plugin_Store WHERE Plugin_Slug='$plugin_slug' AND Plugin_Author='$sender'";
	$pick = $conn->query($sql);
	if ($pick->num_rows <= 0) {
		//Insert this plugin
		$sql = "INSERT INTO Plugin_Store (Plugin_Name, Plugin_Slug, Plugin_Path, Plugin_Author, Plugin_Store_State, Folks, Likers, Haters, Featured, Core_Insight) VALUES ('$plugin_name', '$plugin_slug', '$plugin_address', '$plugin_author', 0, NULL, NULL, NULL, 0, 0)";
		$conn->query($sql);

		//Send mail to the admin
		$subject = "New plugin for review in Blogy";
		$content = "Hello Admin.\nThere is a new plugin that has been submited by @$sender.\nLog in to the admin and check it as soon as possible.\nCheers!\nhttp://admin.blogy.co";
		$mail = "vtm.sunrise@gmail.com";
		mail($mail, $subject, $content);

		$response = "READY";
	} else {
		$response = "You already have sent this plugin for a review!";
	}

	//Close connection
	$conn->close();

	//Return response
	echo $response;
?>