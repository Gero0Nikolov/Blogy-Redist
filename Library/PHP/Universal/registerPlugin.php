<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	//Include bundle
	include "functions.php";

	$getPluginName = secure_input($_POST["pluginName"], false);
	$getPluginSlug = strtolower(str_replace(" ", "_", $getPluginName));
	$url_set = "http://".$_SERVER["HTTP_HOST"]."/Library";

	if ( !file_exists("../../Authors/$sender/Plugins") ) {
		mkdir("../../Authors/$sender/Plugins", 0751, true);
		
		$buildIndex = fopen("../../Authors/$sender/Plugins/index.php", "w");
		fwrite($buildIndex, "<?php header('Location: http://".$_SERVER["HTTP_HOST"]."'); ?>");
		fclose($buildIndex);
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

	if ( !file_exists("../../Authors/$sender/Plugins/$getPluginSlug") ) {
		mkdir("../../Authors/$sender/Plugins/$getPluginSlug", 0751, true);
		
		//Create security index
		$buildIndex = fopen("../../Authors/$sender/Plugins/$getPluginSlug/index.php", "w");
		fwrite($buildIndex, "<?php echo 'Silence is golden!'; ?>");
		fclose($buildIndex);

		//Build plugin body
		$buildPlugin = fopen("../../Authors/$sender/Plugins/$getPluginSlug/$getPluginSlug.php", "w");
		$pluginStart = "<?php\n\$include_path='/home/blogycoo/public_html/Library/PHP/Universal/functions.php';\ninclude \$include_path; //Include functions bundle\n\n/* Here your plugin body goes! */\n?>";
		fwrite($buildPlugin, $pluginStart);
		fclose($buildPlugin);

		//Build plugin default description
		$pluginDescription = "~call-on: click;\n~icon: $url_set/images/default-plugin-ico.png;\n~license: Free;\n~description: Plugin description goes here.;";
		$buildDescription = fopen("../../Authors/$sender/Plugins/$getPluginSlug/meta-description.txt", "w");
		fwrite($buildDescription, $pluginDescription);
		fclose($buildDescription);

		$table_name = $sender."_Plugins_Relations";

		//Include bundle
		include "dataBase.php";

		//Connect to the Database
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		//Create the plugin relations of the user
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

		//Inset this plugin to the relations
		$sql = "INSERT INTO $table_name (Plugin_Name, Plugin_Slug, Plugin_Address, Plugin_Store_ID, Plugin_State, Plugin_Author) VALUES ('$getPluginName', '$getPluginSlug', '$url_set/Authors/$sender/Plugins/$getPluginSlug', -1, 1, '$sender')";
		$conn->query($sql);

		//Close the connection
		$conn->close();

		$responce = "READY";
	} else {
		$responce = "PAE"; // Plugin Already Excists
	}

	//Return responce
	echo $responce;
?>