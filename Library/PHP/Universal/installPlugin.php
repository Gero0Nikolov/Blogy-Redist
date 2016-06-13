<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }
	$senderFN = $_SESSION['senderFN'];
	$senderLN = $_SESSION['senderLN'];

	$plugin_id = $_POST["pluginID"];

	//Include bundle
	include "functions.php";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get plugin information
	$sql = "SELECT Plugin_Name, Plugin_Slug, Plugin_Path, Plugin_Author, Folks FROM Plugin_Store WHERE ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_name = $row["Plugin_Name"];
			$plugin_slug = $row["Plugin_Slug"];
			$plugin_path = $row["Plugin_Path"];
			$plugin_author = $row["Plugin_Author"];
			if ( !empty($row["Folks"]) ) { $plugin_folks = explode(",", $row["Folks"]); }
		}
	}

	//Create the plugin relations of the user
	$table_name = $sender ."_Plugins_Relations";
	$sql = "CREATE TABLE $table_name (
		ID int NOT NULL AUTO_INCREMENT, 
		Plugin_Name LONGTEXT,
		Plugin_Slug LONGTEXT,
		Plugin_Address LONGTEXT, 
		Plugin_Store_ID INT,
		Plugin_State INT,
		Plugin_Author LONGTEXT,
		PRIMARY KEY (ID))";
	$conn->query($sql);

	//Add plugin to the user relations
	$table_relations = $sender ."_Plugins_Relations";
	$sql = "INSERT INTO $table_relations (Plugin_Name, Plugin_Slug, Plugin_Address, Plugin_Store_ID, Plugin_State, Plugin_Author) VALUES ('$plugin_name', '$plugin_slug', '$plugin_path', '$plugin_id', 1, '$plugin_author')";
	$conn->query($sql);

	//Update plugin folks
	if ( !empty($plugin_folks) ) {
		array_push($plugin_folks, $sender);
		$plugin_folks = implode(",", $plugin_folks);
	} else {
		$plugin_folks = $sender;
	}

	$sql = "UPDATE Plugin_Store SET Folks='$plugin_folks' WHERE ID=$plugin_id";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Create Plugins folder of the user
	if ( !file_exists( "../../Authors/$sender/Plugins" ) ) {
		mkdir( "../../Authors/$sender/Plugins", 0751 );
	}

	//Create auto navigation file in the Plugins folder
	if ( !file_exists( "../../Authors/$sender/Plugins/index.php" ) ) {
		$navigator_ = fopen( "../../Authors/$sender/Plugins/index.php", "w" );
		fwrite($navigator_, "<?php header('Location: http://".$_SERVER["HTTP_HOST"]."'); ?>");
		fclose( $navigator_ );
	}

	//Create the Plugin_Files folder in the Plugins folder
	if ( !file_exists( "../../Authors/$sender/Plugins/Plugins_Files" ) ) {
		mkdir( "../../Authors/$sender/Plugins/Plugins_Files", 0751 );
	}

	//Create auto navigation file in the Plugins_Files folder
	if ( !file_exists( "../../Authors/$sender/Plugins/Plugins_Files/index.php" ) ) {
		$navigator_ = fopen( "../../Authors/$sender/Plugins/Plugins_Files/index.php", "w" );
		fwrite($navigator_, "<?php header('Location: http://".$_SERVER["HTTP_HOST"]."'); ?>");
		fclose( $navigator_ );
	}

	//Create the Plugin files folder of the plugin in the Plugins_Files folder
	if ( !file_exists( "../../Authors/$sender/Plugins/Plugins_Files/$plugin_slug-$plugin_author" ) ) {
		mkdir( "../../Authors/$sender/Plugins/Plugins_Files/$plugin_slug-$plugin_author", 0751 );
	}

	//Create auto navigation file in the Plugin files folder of the plugin in the Plugins_Files folder
	if ( !file_exists( "../../Authors/$sender/Plugins/Plugins_Files/$plugin_slug-$plugin_author/index.php" ) ) {
		$navigator_ = fopen( "../../Authors/$sender/Plugins/Plugins_Files/$plugin_slug-$plugin_author/index.php", "w" );
		fwrite($navigator_, "<?php header('Location: http://".$_SERVER["HTTP_HOST"]."'); ?>");
		fclose( $navigator_ );
	}

	$notification_message = "just installed your $plugin_name plugin";
	$mail_subject = "New plugin user - Folk";
	$mail_message = "Hello there!<br><a href='http://blogy.co?$sender'>@$senderFN$senderLN</a> installed your $plugin_name to his plugins.";

	send_notification($sender, $plugin_author, $notification_message);
	send_custom_mail($sender, $plugin_author, "plugin_notification", $mail_subject, $mail_message);

	//Return response
	echo "READY";
?>