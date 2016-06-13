<?php 
	//Get core variables
	$mail = $_POST["mail"];

	//Include bundle
	include "functions.php";
	include "dataBase.php";

	//Connect to the Database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Author_UID, Save_Code FROM WorldBloggers WHERE Author_EMAIL='".$mail."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getBloggerId = $row["Author_UID"];
			$getAuth = $row["Save_Code"];
		}
	}

	//Close the connection
	$conn->close();

	//Parse blogger config
	$line_count = 0;
	$open_config = fopen("../../Authors/$getBloggerId/config.txt", "r") or die("Fatal.");
	while ( !feof( $open_config ) ) {
		$get_config = trim( fgets( $open_config ) );
		if ( $line_count == 5 ) {
			$getPass = $get_config;
			break;
		}
		$line_count += 1;
	}
	fclose( $open_config );

	//Setup mail
	$subject = "Forgotten password and authentication code";
	$content = "
		Your password and authentication code.<br><br>
		Password: <b>".$getPass."</b><br>
		Authentication code: <b>".$getAuth."</b>
	";

	//Send mail
	send_custom_mail("Blogy Admin", $getBloggerId, "", $subject, $content);

	//Setup responce
	$responce = "READY";

	//Return responce
	echo $responce;
?>