<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$doLine = 0;
	$config = fopen("../Authors/$sender/config.txt", "r") or die("Unable to open this path.");
	while (! feof($config)) {
		$line = fgets($config);

		if ($doLine == 0) {
			$profilePic = $line;
		}
		else
		if ($doLine == 1) {
			$profileHref = trim($line);
		}
		else
		if ($doLine == 2) {
			$fullName = trim($line);
		}
		else
		if ($doLine == 3) {
			$profileFirst = trim($line);
		}
		else
		if ($doLine == 4) {
			$profileLast = trim($line);
		}
		else
		if ($doLine == 5) {
			$pass = $line;
		}
		else
		if ($doLine == 6) {
			$eMail = trim($line);
		}
		else
		if ($doLine == 7) {
			$notifyOnPost = trim($line);
		}
		else
		if ($doLine == 8) {
			$notifyOnMessage = trim($line);
		}

		$doLine++;
	}
	fclose($config);
	
	//Connect to the Database
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Save_Code, Hobbies, Birthdate FROM WorldBloggers WHERE Author_UID='".$fullName."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$auth_code= $row["Save_Code"];
			$hobbies = $row["Hobbies"];
			$birthdate = $row["Birthdate"];
		}
	}

	//Close the connection
	$conn->close();

	if ($profileHref == "NULL" || empty($profileHref)) {
		$profileHref = "";
	}
	
echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's panel</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

		<script type='text/javascript' src='../../java.js'></script>
		<script type = 'text/javascript'> 
			function start() {				
				var profilePic = document.getElementById('profilePic').value.trim();
				var profileHref = document.getElementById('profileHref').value.trim();
				var fName = document.getElementById('fName').value.trim();
				var lName = document.getElementById('lName').value.trim();
				var pass = document.getElementById('pass').value.trim();
				var auth_code = document.getElementById('auth_code').value.trim();
				
				var flag = 0;
			
				if (profilePic == '') {
					document.getElementById('profilePic').value = 'https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-512.png';
					profilePic = document.getElementById('profilePic').value;
				}
				else 
				if (profileHref == '' || profileHref == 'None') {
					document.getElementById('profileHref').value = 'NULL';
				}
				else
				if (fName == '') {
					alert('You are supposed to have First name.');
					flag = 1;
				}
				else
				if (lName == '') {
					alert('And you also need and Last name.');
					flag = 1;
				}
				else
				if (pass == '') {
					alert('Yes, you need password !');
					flag = 1;
				}
				else
				if (auth_code == '') {
					alert('Enter your authentication code.');
					flag = 1;
				}
				else {
					flag = 0;
				}

				var letters = /^[a-zA-Z]+$/;
				if (fName.match(letters) && lName.match(letters)) {
					//alert(0);
				} else {
					flag = 1;
					alert('You have to enter your correct names first.');
				}
				
				if (flag == 0) { 
					document.getElementById('fName').value = capitalizeFirstLetter(fName);
					document.getElementById('lName').value = capitalizeFirstLetter(lName);
					document.getElementById('pass').value = bruteEncrypt(pass);

					document.forms['controlPanel'].submit();
				}
			}

			function removeHobby( hobbyID ) {
				current_hobbies = $( '#hobbies' ).val().split( ',' );

				indexOfHobby = current_hobbies.indexOf( hobbyID );
				if ( indexOfHobby > -1 ) {
					current_hobbies.splice( indexOfHobby, 1 );
					$( '#hobbies' ).val( current_hobbies.toString() );
					$( '#'+hobbyID ).remove();
				}
			}

			function addHobby( e ) {
				if ( e.keyCode == 13 ) {
					newHobby = $( '#add-hobby' ).val().trim().toLowerCase();
					current_hobbies = $( '#hobbies' ).val().split( ',' );
					
					hobbies = [];
					if ( newHobby.indexOf( ',' ) > -1 ) {
						hobbies = newHobby.split( ',' );
					}

					if ( hobbies.length > 0 ) {
						for ( i = 0; i < hobbies.length; i++ ) {
							hobby = hobbies[i].trim();
							if ( hobby !== undefined && hobby != '' ) {
								if ( current_hobbies.indexOf( hobby ) < 0 ) {
									";
									?>
									build_ = "<span id='"+hobby+"' class='hobby' onclick='removeHobby(\""+hobby+"\");'>"+hobby+"<i>&bull;</i></span>";
									<?php echo "
									$( '.hobbies-container .list' ).append( build_ );
									$( '#add-hobby' ).val( '' );

									current_hobbies.push( hobby );
									$( '#hobbies' ).val( current_hobbies.toString() );
								} else {
									alert( 'You already have this hobby: '+ hobbies[i] );
								}
							} else { alert( 'What is your hobby ?' ); }
						}
					} else {
						if ( newHobby !== undefined && newHobby != '' ) {
							if ( current_hobbies.indexOf( newHobby ) < 0 ) {
								";
								?>
								build_ = "<span id='"+newHobby+"' class='hobby' onclick='removeHobby(\""+newHobby+"\");'>"+newHobby+"<i>&bull;</i></span>";
								<?php echo "
								$( '.hobbies-container .list' ).append( build_ );
								$( '#add-hobby' ).val( '' );
								
								$( '#'+ newHobby ).on('click', function() { removeHobby( newHobby ); } );

								current_hobbies.push( newHobby );
								$( '#hobbies' ).val( current_hobbies.toString() );
							} else {
								alert( 'You already have this hobby: '+ newHobby );
							}
						} else { alert( 'What is your hobby?' ); }
					}
				}
			}
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "
		<div id='body'>
			<form id='controlPanel' action='../PHP/configBuild.php' method='post'>
				<h1>Profile picture</h1>
				<input type='text' value='$profilePic' id='profilePic' name='profilePic' onclick='this.select()'></input>
				<br>
				<h1>Social profile</h1>
				<input type='text' value='$profileHref' id='profileHref' name='profileHref' onclick='this.select()'></input>
				<br>
				<h1>First name</h1>
				<input type='text' value='$profileFirst' id='fName' name='fName' pattern='[A-Za-z]'></input>
				<br>
				<h1>Last name</h1>
				<input type='text' value='$profileLast' id='lName' name='lName' pattern='[A-Za-z]'></input>
				<br>
				<h1>Password</h1>
				<input type='password' value='$pass' id='pass' name='pass'></input>
				<br>
				<h1>Authentication code</h1>
				<input type='password' value='$auth_code' id='auth_code' name='authCode'>
				<h1>Birthdate</h1>
				<input type='text' value='$birthdate' onfocus='(this.type=\"date\")' onblur='(this.type=\"text\")' id='birth-date' name='birthdate' placeholder='When is your birthday ?'>
				<h1>Hobbies</h1>
				<div class='hobbies-container'>
					<div class='list'>
				";

				$hobbies_ = explode( ",", $hobbies );
				foreach ( $hobbies_ as $hobby ) {
					echo "<span id='$hobby' class='hobby' onclick='removeHobby(\"$hobby\");'>$hobby<i>&bull;</i></span>";
				}

				echo "
					</div>
					<input type='text' id='add-hobby' onkeydown='addHobby(event);'>
				</div>
				<div style='display: none;'>
					<input type='text' value='$fullName' name='sender'>
					<input type='text' value='$notifyOnPost' name='notifyOnPost'>
					<input type='text' value='$notifyOnMessage' name='notifyOnMessage'>
					<input type='text' value='$hobbies' id='hobbies' name='hobbies'>
				</div>
				<br>
				<div id='marginBottomFirefox'>
					<a href='#' onclick='start()'>Save</a>
				</div>
			</form>
		</div>
	</body>
";
?>