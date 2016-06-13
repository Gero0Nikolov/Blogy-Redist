<?php // There will be all needed functions for Blogy
	$ori_path = "/home/blogycoo/public_html/";


	//Function: Get client IP address
	if ( !function_exists("get_client_ip") ) {
	 	function get_client_ip() {
		    $ipaddress = '';
		    if (getenv('HTTP_CLIENT_IP'))
		        $ipaddress = getenv('HTTP_CLIENT_IP');
		    else if(getenv('HTTP_X_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		    else if(getenv('HTTP_X_FORWARDED'))
		        $ipaddress = getenv('HTTP_X_FORWARDED');
		    else if(getenv('HTTP_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_FORWARDED_FOR');
		    else if(getenv('HTTP_FORWARDED'))
		       $ipaddress = getenv('HTTP_FORWARDED');
		    else if(getenv('REMOTE_ADDR'))
		        $ipaddress = getenv('REMOTE_ADDR');
		    else
		        $ipaddress = 'UNKNOWN';
		    return $ipaddress;
		}
	}


	//Check if valid image link
	if ( !function_exists("isValidImage") ) {
		function isValidImage($url) {
			$url_template = "http://".$_SERVER[HTTP_HOST]."/";
			$url = str_replace("../../../", $url_template, $url);

			if ( !empty( $url ) ) { if ( filter_var( $url, FILTER_VALIDATE_URL ) ) { $url_headers = get_headers($url, 1); } }

		    if( isset( $url_headers['Content-Type'] ) ) {

		        $type=strtolower($url_headers['Content-Type']);

		        $valid_image_type=array();
		        $valid_image_type['image/png']='';
		        $valid_image_type['image/jpg']='';
		        $valid_image_type['image/jpeg']='';
		        $valid_image_type['image/jpe']='';
		        $valid_image_type['image/gif']='';
		        $valid_image_type['image/tif']='';
		        $valid_image_type['image/tiff']='';
		        $valid_image_type['image/svg']='';
		        $valid_image_type['image/ico']='';
		        $valid_image_type['image/icon']='';
		        $valid_image_type['image/x-icon']='';

		        if(isset($valid_image_type[$type])){
		            $flag = 1;
		        } else {
		        	if ( strpos( $url, ".jpg" ) ) { $flag = 1; }
		    		else { $flag = 0; }
		        }
		    } else {
		    	if ( strpos( $url, ".jpg" ) ) { $flag = 1; }
		    	else { $flag = 0; }
		    }

		    return $flag;
		}
	}


	//Story content parser
	if ( !function_exists("parse_story_content") ) {
		function parse_story_content( $content, $post_id ) {
			$response = "";

			if ( !empty( $content ) ) {
				$content = nl2br( $content );
				$content = parseShortcodes( $content, $post_id );
				$response = str_replace( "`", "'", str_replace( "&35;", "#", $content ) );
			}

			return $response;
		}
	}


	//Shortcodes parser
	if ( !function_exists("parseShortcodes") ) {
		function parseShortcodes($postContent, $post_id) {
			$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			$url = NULL;

			//BOLD shortcode
			$postContent = str_replace("[b]", "<content style='font-weight: bold;'>", $postContent);
			$postContent = str_replace("[/b]", "</content>", $postContent);

			//ITALIC shortcode
			$postContent = str_replace("[i]", "<content style='font-style: italic;'>", $postContent);
			$postContent = str_replace("[/i]", "</content>", $postContent);

			//UNDERLINE shortcode
			$postContent = str_replace("[u]", "<content style='text-decoration: underline;'>", $postContent);
			$postContent = str_replace("[/u]", "</content>", $postContent);

			//SCRATCH shortcode
			$postContent = str_replace("[scratch]", "<content style='text-decoration: line-through;'>", $postContent);
			$postContent = str_replace("[/scratch]", "</content>", $postContent);

			//OVERLINE shortcode
			$postContent = str_replace("[o]", "<content style='text-decoration: overline;'>", $postContent);
			$postContent = str_replace("[/o]", "</content>", $postContent);

			//LINE-SEPARATOR shortcode
			$postContent = str_replace("[separator]", "<content style='display: inline-block; width: 100%; height: 3px; margin: 5px auto; background-color: LightGray;'></content>", $postContent);

			//COLOR shortcode
			$isColorFound = 1;
			while ( $isColorFound == 1 ):
				if ( strpos($postContent, "color=&quot;") ) {
					$getColor = explode("&quot;]", explode("[/color]", explode("color=&quot;", $postContent)[1])[0])[0];

					$convertColorToRGB = hex2rgb($getColor);

					//Replace the COLOR shortcode with the proper content
					$postContent = str_replace("[color=&quot;".$getColor."&quot;]", "<content style='color: ".$convertColorToRGB.";'>", $postContent);
					$postContent = str_replace("[/color]", "</content>", $postContent);
				
					$isColorFound = 1;
				} else {
					$isColorFound = 0;
				}
			endwhile;

			//MARK shortcode
			$isMarkFound = 1;
			while ( $isMarkFound == 1 ):
				if ( strpos($postContent, "mark=&quot;") ) {
					$getColor = explode("&quot;]", explode("[/mark]", explode("mark=&quot;", $postContent)[1])[0])[0];

					$convertColorToRGB = "rgb(". implode( ",", hex2rgb($getColor) ) .")";

					//Replace the COLOR shortcode with the proper content
					$postContent = str_replace("[mark=&quot;".$getColor."&quot;]", "<content style='background: ".$convertColorToRGB.";'>", $postContent);
					$postContent = str_replace("[/mark]", "</content>", $postContent);
					
					$isMarkFound = 1;
				} else {
					$isMarkFound = 0;
				}
			endwhile;

			//REPOSTED shortcode
			if ( strpos($postContent, "reposted]") ) {
				$getOriginalAuthor = explode("[/reposted]", explode("reposted]", $postContent)[1])[0];
				$getOriginalAuthor = str_replace("[/", "", $getOriginalAuthor);

				//Get author nickname
				include "dataBase.php"; //Connect to the database
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				$sql = "SELECT Nickname FROM WorldBloggers WHERE Author_UID='".$getOriginalAuthor."'";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						$getNickname = $row["Nickname"];
					}
				}

				//Close the connection
				$conn->close();

				//Check if the viewer is logged in
				$reposted_src = "http://". $_SERVER[ "HTTP_HOST" ];
				if ( strpos( $_SERVER[ "HTTP_REFERER" ], "logedIn.php" ) || strpos( $_SERVER[ "HTTP_REFERER" ], "openBloger.php" ) ) { $reposted_src = "openBloger.php"; }

				//Build the link
				$buildLink = "<content class='reposted-label'>Author <a href='".$reposted_src."?".$getOriginalAuthor."' data-ajax='false'>@".$getNickname."</a>";

				//Replace the shortcode with the UI link
				$postContent = str_replace("[reposted]".$getOriginalAuthor."[/reposted]", $buildLink, $postContent);
			}

			//MEDIA shortcode
			$isMediaFound = 1;
			while ( $isMediaFound == 1 ):
				if ( strpos($postContent, "media]") ) {
					$get_shared_url = str_replace( "[/", "", explode("[/media]", explode("media]", $postContent)[1])[0] );

					$build_url = build_url( $get_shared_url, "content_build" );
					$url_to_markup = $build_url[0];
					$is_video = $build_url[1];

					if ( empty( $is_video ) ) {
						$finish_markup = "
							<a href='$get_shared_url' data-lightbox='meta-media-$post_id' class='meta-media' data-ajax='false'>$url_to_markup</a>
						";
					} elseif ( $is_video == 1 ) {
						$finish_markup = $url_to_markup;
					}
 
					//Replace the MEDIA shortcode with the proper content
					$postContent = str_replace("[media]".$get_shared_url."[/media]", $finish_markup, $postContent);

					$isMediaFound = 1;
				} else {
					$isMediaFound = 0;
				}
			endwhile;

			//MASCOT shortcode
			$isMascotFound = 1;
			while ( $isMascotFound == 1 ) :
				if ( strpos($postContent, "mascot=&quot;") ) {
					$get_mascot_type = explode( "&quot;]", explode("mascot=&quot;", $postContent)[1] )[0];
					$get_mascot_url = give_mascot( $get_mascot_type );

					//Put the Mascot
					$postContent = str_replace("[mascot=&quot;".$get_mascot_type."&quot;]", "<img src=".$get_mascot_url." class='mascot' alt='Broken image.' />", $postContent);

					$isMascotFound = 1;
				} else {
					$isMascotFound = 0;
				}
			endwhile;

			//HASH Tag shortcode
			$content_to_lines = explode( "<br />", $postContent );
			$postContent = "";
			foreach ( $content_to_lines as $line ) {
				//Replace links with real links
				if( preg_match( $reg_exUrl, $line, $url ) ) {
					if ( !strpos($line, "img src") && !strpos($line, "a href") && !strpos($line, "iframe src") && !strpos($line, "div style") ) {
						$line = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $line);
					}
				}

				$isTagFound = 1;

				//Make the hashtags clickable links
				while ( $isTagFound == 1 ) {
					if ( preg_match( "/#./", $line ) > 0 ) {
						$shortcode_ = explode( " ", explode( "#", $line )[1] )[0];
						$line = str_replace( "#$shortcode_", "<a href='https://www.google.bg/search?q=$shortcode_' target='_blank'>&35;$shortcode_</a>", $line );
					} else { $isTagFound = 0; }
				}
				$postContent .= " ". $line ."<br />";
			}
		
			return $postContent;
		}
	}


	//Hex to RGB
	if ( !function_exists("hex2rgb") ) {
		function hex2rgb($hex) {
		   $hex = str_replace("#", "", $hex);

		   if(strlen($hex) == 3) {
		      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		   } else {
		      $r = hexdec(substr($hex,0,2));
		      $g = hexdec(substr($hex,2,2));
		      $b = hexdec(substr($hex,4,2));
		   }

		   $rgb = array($r, $g, $b);
		   //return implode(",", $rgb); // returns the rgb values separated by commas
		   return $rgb; // returns an array with the rgb values
		}
	}


	//Send club invintation notification
	if ( !function_exists("send_club_notification") ) {
		function send_club_notification($sender, $clubTable, $clubId, $bloggerId, $conn, $type) {
			$date = date("d.M.Y");

			if ($type == "club_invitation") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#invitation for club', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "club admin promotion") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#club admin promotion', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "accepted club invitation") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#accepted club invitation', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "declined club invitation") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#declined club invitation', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "promoted as admin") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#promoted as admin', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "wants to join the club") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#wants to join the club', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "approved you to join the club") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#approved you to join the club', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "declined you to join the club") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#declined you to join the club', '".$date."')";
				$conn->query($sql);
			} elseif ($type == "delete club request") {
				$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$clubTable."=".$clubId."#delete club request', '".$date."')";
				$conn->query($sql);
			}
		}
	}


	//Send custom notification
	if ( !function_exists("send_notification") ) {
		function send_notification($sender, $bloggerId, $message) {
			//Connect to the database
			include "dataBase.php";
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$message = htmlentities($message);
			$date = date("d.M.Y");
			$sql = "INSERT INTO pushTable$bloggerId (MEMBER, MESSAGE, DATE) VALUES ('".$sender."', '".$message."', '$date')";
			$conn->query($sql);
			
			//Close the connection
			$conn->close();
		}
	}


	//Send e-mail
	if ( !function_exists("send_custom_mail") ) {
		function send_custom_mail($sender, $bloggerId, $type, $subject, $content) {
			//Connect to the Database
			include "dataBase.php";

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT Author_EMAIL, Nickname FROM WorldBloggers WHERE Author_UID='".$bloggerId."'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$bloggerMail = $row["Author_EMAIL"];
					$bloggerNick = $row["Nickname"];
				}
			}

			if ( $sender != "Blogy Admin" ) {
				$sql = "SELECT Nickname FROM WorldBloggers WHERE Author_UID='".$sender."'";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						$senderNick = $row["Nickname"];
					}
				}
			}

			//Close the connection
			$conn->close();

			if ($type == "club_invitation") {
				$subject = "Club Invintation";
				$content = "Hello there @".$bloggerNick.", your friend @".$senderNick." invited you to join a club.<br>Come and check it in Blogy !";
			} elseif ($type == "club_promotion") {
				$subject = "Club Promotion";
				$content = "Hello there @".$bloggerNick.", admin @".$senderNick." has promoted a member from your club as an admin.<br>Come and check it in Blogy !";
			}

			//Build the message
			$emailBody = '
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
						<title>Blogy #Mail</title>
						<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
					</head>
					<body style="margin: 0; padding: 0;">
						<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border: 1px solid #333333;">
							<tr>
								<td style="background-color: #333333; text-align: center; vertical-align: middle; color: #ffffff; width: 100%; height: 150px">
									<h1 style="font-family: Arial, sans-serif; font-size: 32px; font-weight: lighter; margin-bottom: 5px;">Blogy</h1>
									<h2 style="font-family: Arial, sans-serif; font-size: 24px; font-weight: lighter; margin-top: 5px;">#Mail</h2>
								</td>
							</tr>
							<tr>
								<td style="font-family: Arial, sans-serif; font-size: 16px; font-weight: lighter; color: #000000; text-align: left; padding: 10px 10px; background: #F7F7F7;">
									'.$content.'

									<div style="display: block; text-align: center; margin: 40px 0px 20px 0px;">
										<a href="http://'.$_SERVER[HTTP_HOST].'" style="text-decoration: none; background: #3366CC; color: #ffffff; border-radius: 3px; font-family: Arial, sans-serif; font-size: 16px; font-weight: lighter; padding: 10px 45px">Log in</a>
									</div>
								</td>
							</tr>
							<tr>
								<td style="font-family: Arial, sans-serif; font-size: 16px; font-weight: lighter; color: #ffffff; text-align: center; padding: 10px 10px; background: #333333">
									&copy; 2016 Blogy
								</td>
							</tr>
						</table>
					</body>
				</html>
			';

			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			mail($bloggerMail, $subject, $emailBody, $headers);

			/*require "../PHPMailer/PHPMailerAutoload.php";

			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'mail.zoho.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'blogy@blogy.co';                 // SMTP username
			$mail->Password = 'bruteforce';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to

			$mail->setFrom('noreply@blogy.co', 'Mailer');
			$mail->addAddress($bloggerMail, '');     // Add a recipient

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $subject;
			$mail->Body    = $emailBody;

			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    echo 'Message has been sent';
			}*/
		}
	}

	
	//Split to excerpt if lenght is too big
	if ( !function_exists("splitToExcerpt") ) {
		function splitToExcerpt( $input, $containerId ) {
			$countLenght = strlen($input);

			if ( $countLenght > 380 ) {
				$explodeInputToChunks = explode(" ", $input);

				$countChunks = 0;
				while ( $countChunks < 55 ) {
					$visiblePart .= " ".$explodeInputToChunks[$countChunks];
					$countChunks++;
				}
				$visiblePart .= "<content id='extern-dots'>...</content>";

				$countChunks = 0;
				while ( $countChunks < count($explodeInputToChunks) ) {
					if ($countChunks >= 55) {
						$hiddenPart .= " ".$explodeInputToChunks[$countChunks];
					}
					$countChunks++;
				}

				$input = $visiblePart."<button onclick='openExcerpt(".$containerId.", 1);' id='open-extern' class='read-more-button font-opensans-regular'>Read more</button><span id='extern-".$containerId."' class='extern extern-hidden'>".$hiddenPart."</span>";
			}
		
			return $input;
		}
	}


	//Block access function
	if ( !function_exists("blockAccess") ) {
		function blockAccess() {
			echo "
				<html>
					<head>
						<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
						<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
						<title>Oops :(</title>
						<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
						<link href= '../../fonts.css' rel='stylesheet' type='text/css'>
					</head>
					<body>
						<div class='error-message'>
							<h1>Oops.. It seem that we don't find this author. :(</h1>
							<a href='http://".$_SERVER[HTTP_HOST]."'>Log in</a>
						</div>
					</body>
				</html>
			";

			die();
		}
	}


	//Brute Decrypt function PHP
	if ( !function_exists("bruteDecrypt") ) {
		function bruteDecrypt($text_to_decrypt) {
			$parseText = explode("#", $text_to_decrypt);

			$getKey = $parseText[0]; // Key locker

			$decryptedStack = array();

			$countLoops = 1;
			while ( $countLoops < count($parseText) ) {
				$decryptedSymbol = chr( ($parseText[$countLoops] / 69) - $getKey );
				array_push($decryptedStack, $decryptedSymbol);
				$countLoops++;
			}

			$decryptedStack = implode($decryptedStack);

			return $decryptedStack;
		}
	}


	//Secure input data
	if ( !function_exists("secure_input") ) {
		function secure_input($input_data = "", $strip_tags = true) {
			if ( !empty($input_data) ) {
				$input_data = trim($input_data);
				if ( $strip_tags == true ) { $input_data = strip_tags($input_data); }
				$input_data = htmlentities($input_data);
				$input_data = str_replace("'", "&#39;", $input_data);
			}

			return $input_data;
		}
	}


	//Remove directory recursively
	if ( !function_exists("remove_dir") ) {
		function remove_dir($path = "") {
		 	$files = glob($path . '/*');
			foreach ($files as $file) {
				is_dir($file) ? remove_dir($file) : unlink($file);
			}
			rmdir($path);

			if ( file_exists($path) ) { $result = 0; }
			elseif ( !file_exists($path) ) { $result = -1; }
		 	
		 	return $result;
		}
	}


	//Remove file
	if ( !function_exists("remove_file") ) {
		function remove_file($path = "") {
			if ( unlink($path) ) {
				$result = 0;
			} else {
				$result = -1;
			}

			return $result;
		}
	}


	//Move file
	if ( !function_exists("moveFileTo") ) {
		function moveFileTo($target = NULL, $destination = NULL) {
			$result = -1;

			if ( !empty($target) && !empty($destination) ) {
				copy($target, $destination);
				if ( $target != $destination ) { unlink($target); }
				$result = 0;
			}

			return $result;
		}
	}


	//Move folder
	if ( !function_exists("moveFolderTo") ) {
		function moveFolderTo($target = NULL, $destination = NULL) {
			$result = -1;

			if ( !empty($target) && !empty($destination) ) {
				$get_target_name = end( explode("/", $target) );
				$destination .= "/".$get_target_name;

				if ( !file_exists($destination) && is_dir($target) ) {
					mkdir($destination);
				}

			 	$dir = opendir($target); 
			    
			    if ( !file_exists($destination) ) { mkdir($destination); }
			    
			    while( false !== ( $file = readdir($dir) ) ) { 
			        if ( ( $file != '.' ) && ( $file != '..' ) ) { 
			            if ( is_dir($target . '/' . $file) ) { 
			                moveFolderTo($target . '/' . $file, $destination); 
			            } else { 
			                copy($target . '/' . $file, $destination . '/' . $file); 
			            } 
			        } 
			    } 
			    closedir($dir);

			    if ( $target != $destination) { remove_dir($target); }
			
			    $result = 0;
			}

			return $result;
		}
	}


	//Extract zip
	if ( !function_exists("extract_zip") ) {
		function extract_zip($target_ = "", $destination_ = "") {
			$result = -1;

			if ( !empty($target_) && !empty($destination_) ) {
				$zip = new ZipArchive;
				$try_ = $zip->open($target_);
				if ( $try_ === true ) {
					$zip->extractTo($destination_);
					$zip->close();

					$result = 0;
				} else { $result = -1; }
			} else {
				$result = -1;
			}

			return $result;
		} 
	}


	//Create zip
	if ( !function_exists("create_zip") ) {
		function create_zip($target = "", $destination = "") {
			$result = 0;

			if ( !empty($target) && !empty($destination) ) {
				// Get real path for our folder
				$rootPath = realpath($target);

				// Initialize archive object
				$zip = new ZipArchive();
				$zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);

				// Create recursive directory iterator
				/** @var SplFileInfo[] $files */
				$files = new RecursiveIteratorIterator(
				    new RecursiveDirectoryIterator($rootPath),
				    RecursiveIteratorIterator::LEAVES_ONLY
				);

				foreach ($files as $name => $file) {

				    // Skip directories (they would be added automatically)
				    if ( !$file->isDir() ) {
				        // Get real and relative path for current file
				        $filePath = $file->getRealPath();
				        $relativePath = substr($filePath, strlen($rootPath) + 1);

				        // Add current file to archive
				        $zip->addFile($filePath, $relativePath);
				    }

				}

				// Zip archive will be created only after closing object
				$zip->close();
			} else {
				$result = -1;
			}

			return $result;
		}
	}


	//List folder tree
	if ( !function_exists("list_folder_tree") ) {
		function list_folder_tree($dir, $plugin_slug = "") {
			$ffs = scandir($dir);
		    echo '<ol>';
		    foreach( $ffs as $ff ) {
		        if( $ff != '.' && $ff != '..' ) {
		            if ( !is_dir( $dir .'/'. $ff ) ) { echo "<li><a href='openBoard.php?$plugin_slug%$dir/$ff' target='_blank'>". $ff ."</a></li>"; }
		            if ( is_dir( $dir .'/'. $ff ) ) {
		            	echo "<li>". $ff ."</li>";
		            	list_folder_tree($dir .'/'. $ff, $plugin_slug);
		            }
		        }
		    }
		    echo '</ol>';
		}
	}


	//Strip functions
	if ( !function_exists("strip_functions") ) {
		function strip_functions($target_ = "", $replace_message = "") {
			if ( empty($replace_message) ) { $replace_message = "---> Should be replace with bHandler function <---"; }

			if ( !empty($target_) ) {
				$target_ = str_replace("readfile(", "/* readfile $replace_message */", $target_);
				$target_ = str_replace("readfile (", "/* readfile $replace_message */", $target_);
				$target_ = str_replace("fopen(", "/* fopen $replace_message */", $target_);
				$target_ = str_replace("fopen (", "/* fopen $replace_message */", $target_);
				$target_ = str_replace("file(", "/* file $replace_message */", $target_);
				$target_ = str_replace("file (", "/* file $replace_message */", $target_);
				$target_ = str_replace("file_get_contents(", "/* file_get_contents $replace_message */", $target_);
				$target_ = str_replace("file_get_contents (", "/* file_get_contents $replace_message */", $target_);
				$target_ = str_replace("chmod(", "/* chmod $replace_message */", $target_);
				$target_ = str_replace("chmod (", "/* chmod $replace_message */", $target_);
				$target_ = str_replace("chown(", "/* chown $replace_message */", $target_);
				$target_ = str_replace("chown (", "/* chown $replace_message */", $target_);
				$target_ = str_replace("clearstatcache(", "/* clearstatcache $replace_message */", $target_);
				$target_ = str_replace("clearstatcache (", "/* clearstatcache $replace_message */", $target_);
				$target_ = str_replace("copy(", "/* copy $replace_message */", $target_);
				$target_ = str_replace("copy (", "/* copy $replace_message */", $target_);
				$target_ = str_replace("delete(", "/* delete $replace_message */", $target_);
				$target_ = str_replace("delete (", "/* delete $replace_message */", $target_);
				$target_ = str_replace("lchgrp(", "/* lchgrp $replace_message */", $target_);
				$target_ = str_replace("lchgrp (", "/* lchgrp $replace_message */", $target_);
				$target_ = str_replace("lchown(", "/* lchown $replace_message */", $target_);
				$target_ = str_replace("lchown (", "/* lchown $replace_message */", $target_);
				$target_ = str_replace("link(", "/* link $replace_message */", $target_);
				$target_ = str_replace("link (", "/* link $replace_message */", $target_);
				$target_ = str_replace("mkdir(", "/* mkdir $replace_message */", $target_);
				$target_ = str_replace("mkdir (", "/* mkdir $replace_message */", $target_);
				$target_ = str_replace("rename(", "/* rename $replace_message */", $target_);
				$target_ = str_replace("rename (", "/* rename $replace_message */", $target_);
				$target_ = str_replace("rmdir(", "/* rmdir $replace_message */", $target_);
				$target_ = str_replace("rmdir (", "/* rmdir $replace_message */", $target_);
				$target_ = str_replace("symlink(", "/* rmdir $replace_message */", $target_);
				$target_ = str_replace("symlink (", "/* rmdir $replace_message */", $target_);
				$target_ = str_replace("tempnam(", "/* tempnam $replace_message */", $target_);
				$target_ = str_replace("tempnam (", "/* tempnam $replace_message */", $target_);
				$target_ = str_replace("tmpfile(", "/* tmpfile $replace_message */", $target_);
				$target_ = str_replace("tmpfile (", "/* tmpfile $replace_message */", $target_);
				$target_ = str_replace("touch(", "/* touch $replace_message */", $target_);
				$target_ = str_replace("touch (", "/* touch $replace_message */", $target_);
				$target_ = str_replace("umask(", "/* umask $replace_message */", $target_);
				$target_ = str_replace("umask (", "/* umask $replace_message */", $target_);
				$target_ = str_replace("unlink(", "/* unlink $replace_message */", $target_);
				$target_ = str_replace("unlink (", "/* unlink $replace_message */", $target_);
				$target_ = str_replace("mysql_connect(", "/* mysql_connect $replace_message*/", $target_);
				$target_ = str_replace("mysql_connect (", "/* mysql_connect $replace_message*/", $target_);
				$target_ = str_replace("mysqli_connect(", "/* mysqli_connect $replace_message*/", $target_);
				$target_ = str_replace("mysqli_connect (", "/* mysqli_connect $replace_message*/", $target_);
			}

			return $target_;
		}
	}

	//Build URL to markup
	if ( !function_exists("build_url") ) {
		function build_url($link, $build_for = "") {
			$parseUrl = parse_url($link);
			if ($parseUrl['host'] == 'www.youtube.com' || $parseUrl['host'] == 'm.youtube.com' || $parseUrl['host'] == 'youtu.be') {
				$query = $parseUrl['query'];
				$queryParse = explode("=", $query);
				
				if ($parseUrl['host'] == 'youtu.be') {
					$queryParse = $parseUrl['path'];
					$src = "https://www.youtube.com/embed/$queryParse";
				}
				else
				if ($parseUrl['host'] == 'm.youtube.com') {
					$src = "https://www.youtube.com/embed/$queryParse[1]";
				} else {
					$src = "https://".$parseUrl['host']."/embed/$queryParse[1]";
				}
				
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
				$isVideo = "1";
			}
			else 
			if ($parseUrl['host'] == 'vimeo.com') {
				$query = end(explode("/", $parseUrl['path']));
				$cmd = "<iframe src='//player.vimeo.com/video/$query' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
				$isVideo = "2";
			}
			else
			if ($parseUrl['host'] == 'vbox7.com') {
				$query = end(explode("/", $parseUrl['path']));
				$videoId = end(explode(":", $query));
				if (strpos($videoId, "&")) { $videoId = explode("&", $videoId)[0]; }

				$cmd = "<iframe src='http://vbox7.com/emb/external.php?vid=".$videoId."' frameborder='0' allowfullscreen></iframe>";
			}
			else
			if ($parseUrl['host'] == 'videopress.com') {
				$query = end(explode("/", $parseUrl['path']));
				$cmd = "
					<iframe src='https://videopress.com/embed/".$query."' frameborder='0' allowfullscreen></iframe>
					<script src='https://videopress.com/videopress-iframe.js'></script>
				";
			}
			else
			if ($parseUrl['host'] == 'vine.co') {
				$query = end(explode("/", $parseUrl['path']));
				$cmd = "
					<iframe src='https://vine.co/v/".$query."/embed/simple' frameborder='0'></iframe>
					<script src='https://platform.vine.co/static/scripts/embed.js'></script>
				";
			}
			else
			if ($parseUrl['host'] == 'www.dailymotion.com') {
				$query = $parseUrl['path'];
				$src = "//www.dailymotion.com/embed/$query";
				$cmd = "<iframe src='$src' frameborder='0' allowfullscreen></iframe>";
				//$isVideo = 1;
			}
			else
			if ($parseUrl['host'] == 'www.metacafe.com') {
				$query = $parseUrl['path'];
				$queryParse = explode("/", $query);
				$src = "http://www.metacafe.com/embed/$queryParse[2]/";
				$cmd = "<iframe src='$src' allowFullScreen frameborder=0></iframe>";
				//$isVideo = 1;
			}
			else {
				$get_link_type = strtolower(end(explode(".", $link)));

				if (
					strpos(".".$get_link_type, "png") ||
					strpos(".".$get_link_type, "jpg") ||
					strpos(".".$get_link_type, "jpeg") ||
					strpos(".".$get_link_type, "jpe") ||
					strpos(".".$get_link_type, "gif") ||
					strpos(".".$get_link_type, "tif") ||
					strpos(".".$get_link_type, "tiff") ||
					strpos(".".$get_link_type, "svg") ||
					strpos(".".$get_link_type, "ico") ||
					strpos(".".$get_link_type, "icon") ||
					strpos(".".$get_link_type, "x-icon")
 				) {
					if ( $build_for == "" ) {
						$cmd = "<div style='background-image: url($link); background-size: cover; background-position: 50%;' class='featured-image'><div class='inline-hover'></div></div>";
 					} elseif ( $build_for == "content_build" ) {
 						$cmd = "<img src='$link' class='inline-image' alt='Bad image link' />";
 					}
 				} elseif( isValidImage($link) ) {
 					if ( $build_for == "" ) {
						$cmd = "<div style='background-image: url($link); background-size: cover; background-position: 50%;' class='featured-image'><div class='inline-hover'></div></div>";
 					} elseif ( $build_for == "content_build" ) {
 						$cmd = "<img src='$link' class='inline-image' alt='Bad image link' />";
 					}
 				} elseif (
 					$get_link_type == "mp4" ||
 					$get_link_type == "ogg" ||
 					$get_link_type == "webm" ||
 					$get_link_type == "mov"
 				) {
 					$cmd =  "
 						<video src='".$link."' loop controls>
							Your browser does not support HTML5 video.
						</video>
 					";

 					$isVideo = 1;
 				} else {
					$cmd = "<h2>Unsupported player :(</h2>";
				}
			}

			//Build responce
			$responce = array($cmd, $isVideo);

			//Return builded markup
			return $responce;
		}
	}

	//Strip Words
	if ( !function_exists("strip_words") ) {
		function strip_words( $content, $words = 25, $after_text = "..." ) {
			if ( !empty( $content ) ) {
				$content_rebuild = array();
				$split_content = explode( " ", $content );
				$smaller_flag = false;

				if ( count( $split_content ) < $words ) { $words = count( $split_content ); $smaller_flag = true; }

				$word_counter = 0;
				while ( $word_counter < $words ) {
					$split_content[ $word_counter ] = preg_replace( "/\[.\]/i", "", $split_content[ $word_counter ]);
					$split_content[ $word_counter ] = preg_replace( "/\[\/.\]/i", "", $split_content[ $word_counter ]);
					if ( !empty( $split_content[ $word_counter ] ) ) {
						array_push( $content_rebuild, $split_content[ $word_counter ] );
					}
					$word_counter += 1;
				}
				$response = implode( " ", $content_rebuild ) ;

				if ( $smaller_flag == false ) { $response .= $after_text; }
 			} else {
				$response = "";
			}

			return $response;
		}
	}


	/* SESSION FUNTIONS  */
	if ( !function_exists("get_session") ) {
		function get_session( $arg = "" ) {
			session_start();

			if ( $arg == "user_id" ) { $return_ = $_SESSION['sender']; }
			elseif ( $arg == "user_first_name" ) { $return_ = $_SESSION['senderFN']; }
			elseif ( $arg == "user_last_name" ) { $return_ = $_SESSION['senderLN']; }
			elseif ( $arg == "user_profile_picture" ) { $return_ = $_SESSION['senderImg']; }
			elseif ( $arg == "user_social_profile" ) { $return_ = $_SESSION['senderHref']; }
			elseif ( empty($arg) ) { $return_ = $_SESSION; }

			return $return_;
		}
	}

	if ( !function_exists("set_session_variable") ) {
		function set_session_variable( $var_, $val_ ) {
			session_start();
			$_SESSION[$var_] = $val_;

			if ( !empty( $_SESSION[$var_] ) && $_SESSION[$var_] == $val_ ) { return 1; }
			else { return 0; }
		}
	}

	if ( !function_exists("unset_session_variable") ) {
		function unset_session_variable( $var_ ) {
			session_start();
			unset( $_SESSION[$var_] );

			if ( empty( $SESSION[$var_] ) && !isset( $_SESSION[$var_] ) ) { return 1; }
			else { return 0; } 
		}
	}

	if ( !function_exists("is_there_session_variable") ) {
		function is_there_session_variable( $var_ ) {
			if ( !empty( $_SESSION[$var_] ) && isset( $_SESSION[$var_] ) ) { return 1; }
			else { return 0; }
		}
	}


	/* PATH FUNCTIONS */
	if ( !function_exists("path_to_plugin") ) {
		function path_to_plugin( $plugin_author, $plugin_slug, $is_ajax = false ) {
			$path_ = "";
			if ( !empty( $plugin_slug ) && !empty( $plugin_author ) ) {
				if ( $is_ajax == false ) {
					$path_ = "{$GLOBALS["ori_path"]}/Library/Authors/$plugin_author/Plugins/$plugin_slug";
				} elseif ( $is_ajax == true ) {
					$path_ = "/Library/Authors/$plugin_author/Plugins/$plugin_slug";
				}
			} else {
				$path_ = 0;
			}

			return $path_;
		}
	}


	/* STORIES FUNCTIONS */
	if ( !function_exists("get_stories") ) {
		function get_stories( $pieces_ = 5, $order_by = "ID", $order_ = "DESC", $build_banner = true, $build_shortcodes = true, $calc_likes = true ) {
			session_start();
			$user_id = $_SESSION["sender"];

			//Connect to the Database
			include "dataBase.php";

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$order_by = strtolower( $order_by );
			if ( $order_by == "id" ) { $order_by = "ID"; }
			elseif ( $order_by == "publish_date_time" ) { $order_by = "DATETIME"; }
			elseif ( $order_by == "story_title" ) { $order_by = "STORYTITLE"; }
			elseif ( $order_by == "likes" ) { $order_by = "LIKES"; }

			$order_ = strtoupper( $order_ );

			$set_limit = "";
			if ( $pieces_ > 0 ) { $set_limit = "LIMIT $pieces_"; }

			$stories_holder = array();
			$single_story = array();

			$table_ = "stack". $user_id;
			$sql = "SELECT ID, DATETIME, STORYTITLE, STORYLINK, STORYCONTENT, LIKES FROM $table_ ORDER BY $order_by $order_ $set_limit";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$single_story["ID"] = $row["ID"];
					$single_story["post_publish_date_time"] = $row["DATETIME"];
					$single_story["post_title"] = str_replace( "6996", " ", $row["STORYTITLE"] );
					$single_story["post_banner"] = $row["STORYLINK"];
					$single_story["post_content"] = $row["STORYCONTENT"];
					$single_story["post_likes"] = $row["LIKES"];

					if ( $build_banner == true ) { $single_story["post_banner"] = build_url( $single_story["post_banner"] ); }
					if ( $build_shortcodes == true ) { $single_story["post_content"] = parseShortcodes( $single_story["post_content"] ); }
					if ( $calc_likes == true ) {
						if ( !empty( $single_story["post_likes"] ) ) {
							$single_story["post_likes"] = count( $single_story["post_likes"] );
						} else {
							$single_story["post_likes"] = 0;
						}
					} 

					$single_story_object = (object) $single_story;

					array_push($stories_holder, $single_story_object);
				}
			}

			//Close the connection
			$conn->close();

			if ( !empty( $stories_holder ) ) { return $stories_holder; }
			else { return 0; }
		}
	}

	if ( !function_exists("get_story") ) {
		function get_story( $post_id = -1, $build_banner = true, $build_shortcodes = true, $calc_likes = true ) {
			session_start();
			$user_id = $_SESSION["sender"];

			//Connect to the Database
			include "dataBase.php";

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$single_story = array();

			$table_ = "stack". $user_id;
			$sql = "SELECT ID, DATETIME, STORYTITLE, STORYLINK, STORYCONTENT, LIKES FROM $table_ WHERE ID=$post_id";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$single_story["ID"] = $row["ID"];
					$single_story["post_publish_date_time"] = $row["DATETIME"];
					$single_story["post_title"] = str_replace( "6996", " ", $row["STORYTITLE"] );
					$single_story["post_banner"] = $row["STORYLINK"];
					$single_story["post_content"] = $row["STORYCONTENT"];
					$single_story["post_likes"] = $row["LIKES"];

					if ( $build_banner == true ) { $single_story["post_banner"] = build_url( $single_story["post_banner"] ); }
					if ( $build_shortcodes == true ) { $single_story["post_content"] = parseShortcodes( $single_story["post_content"] ); }
					if ( $calc_likes == true ) {
						if ( !empty( $single_story["post_likes"] ) ) {
							$single_story["post_likes"] = count( $single_story["post_likes"] );
						} else {
							$single_story["post_likes"] = 0;
						}
					}
				
					$single_story = (object) $single_story;
				}
			}

			//Close the connection
			$conn->close();

			if ( !empty( $single_story ) ) { return $single_story; }
			else { return 0; }
		}
	}


	/* ALBUM OPTIONS */
	if ( !function_exists("get_album") ) {
		function get_album() {
			session_start();
			$user_id = $_SESSION["sender"];

			//Connect to the Database
			include "dataBase.php";

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$single_image = array();
			$album_holder = array();

			$sql = "SELECT ID, ALBUM FROM albumOf$user_id ORDER BY ID";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$single_image = array();

					$single_image["ID"] = $row["ID"];
					$single_image["name"] = $row["ALBUM"];
					$single_image["path"] = "{$GLOBALS["ori_path"]}/Library/Authors/$user_id/Album/". $row["ALBUM"];

					$single_image = (object) $single_image;
					array_push($album_holder, $single_image);
				}
			}

			//Close the connection
			$conn->close();

			if ( !empty($album_holder) ) { return $album_holder; }
			else { return 0; }
		}
	}


	/* WORLD BLOGGERS */
	if ( !function_exists("get_bloggers") ) {
		function get_bloggers( $limit_ = 5, $order_ = "ID", $order_by = "DESC" ) {
			$is_core_insight = 0;

			//Connect to the Database
			include "dataBase.php";

			$plugin_author = explode("/", explode("Authors/", $_SERVER['REQUEST_URI'])[1] )[0];
			$plugin_slug = explode("/", explode("Plugins/", $_SERVER['REQUEST_URI'])[1] )[0];

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT Core_Insight FROM Plugin_Store WHERE Plugin_Slug='$plugin_slug' AND plugin_author='$plugin_author'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$is_core_insight = $row["Core_Insight"];
				}
			}

			if ( $is_core_insight == 0 ) { 
				//Close the check connection
				$conn->close();
				die("Plugin with slug: <b>$plugin_slug</b> from author: <b>$plugin_author</b> is not a core Plugin!"); 
			}

			$authors_ = array();

			$sql = "SELECT ID, Author_UID FROM WorldBloggers WHERE BAN=0 ORDER BY $order_ $order_by LIMIT $limit_";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {			
					$author_id = $row["ID"];
					$author_uid = $row["Author_UID"];

					$author_container = array(
							'user_id' => $author_id,
							'user_uid' => $author_uid
						);
					$author_container = (object) $author_container;

					array_push( $authors_, $author_container );
				}
			}

			//Close the connection
			$conn->close();

			//Return response
			return $authors_;
		}
	}

	if ( !function_exists("build_blogger_by_uid") ) {
		function build_blogger_by_uid( $blogger_uid = "" ) {
			
			if ( !empty( $blogger_uid ) ) {
				$path_to_config = "{$GLOBALS["ori_path"]}/Library/Authors/$blogger_uid/config.txt";

				$line_ = 0;
				$open_config = fopen( $path_to_config, "r" ) or die( "Failed to open user meta." );
				while ( !feof( $open_config ) ) {
					$get_meta = trim( fgets( $open_config ) );
					if ( !empty( $get_meta ) ) {
						if ( $line_ == 0 ) { $blogger_profile_image = $get_meta; }
						elseif ( $line_ == 1 ) { $blogger_social_profile = $get_meta; }
						elseif ( $line_ == 3 ) { $blogger_first_name = $get_meta; }
						elseif ( $line_ == 4 ) { $blogger_last_name = $get_meta; break; }
					}

					$line_ += 1;
				}
				fclose( $open_config );
			
				$user_meta = array(
						'user_profile_picture' => $blogger_profile_image,
						'user_social_profile' => $blogger_social_profile,
						'user_first_name' => $blogger_first_name,
						'user_last_name' => $blogger_last_name
					);

				return $user_meta;
			}
		
		}
	}

	if ( !function_exists("open_blogger_story") ) {
		function open_blogger_story( $blogger_uid ) {

			if ( strpos( $_SERVER["HTTP_REFERER"], "Mobile" ) ) {
				$link_ = "http://".$_SERVER[HTTP_HOST]."/Library/PHP/Mobile/openBloger.php?".$blogger_uid;
			} else {
				$link_ = "http://".$_SERVER[HTTP_HOST]."/Library/PHP/openBloger.php?".$blogger_uid;
			}

			return $link_;

		}
	}


	/* FRIENDS & FAMILY FUNCTIONS */
	if ( !function_exists("get_friends") ) {
		function get_friends() {
			session_start();
			$user_id = $_SESSION["sender"];
			$path_ = "{$GLOBALS["ori_path"]}/Library/Authors/$user_id/Following.txt";
			$friends_ = array();

			$open_friends = fopen( $path_, "r" );
			while ( !feof($open_friends) ) {
				$get_id = trim( fgets($open_friends) );
				if ( !empty( $get_id ) ) { array_push($friends_, $get_id); }
			}  
			fclose($open_friends);

			if ( empty( $friends_ ) ) { $friends_ = 0; }

			return $friends_;
		}
	}

	if ( !function_exists("get_ohana") ) {
		function get_ohana() {
			session_start();
			$user_id = $_SESSION["sender"];
			$path_ = "{$GLOBALS["ori_path"]}/Library/Authors/$user_id/Ohana.txt";
			$ohana_ = array();

			$open_ohana = fopen( $path_, "r");
			while ( !feof($open_ohana) ) {
				$get_id = trim( fgets($open_ohana) );
				if ( !empty( $get_id ) ) { array_push($ohana_, $get_id); }
			}
			fclose($open_ohana);

			if ( empty( $ohana_ ) ) { $ohana_ = 0; }

			return $ohana_;
		}
	}

	if ( !function_exists("get_hobbies") ) {
		function get_hobbies( $user_id = "" ) {
			if ( empty( $user_id ) ) {
				session_start();
				$user_id = $_SESSION["sender"];
			}

			$hobbies_ = array();

			//Connect to the Database
			include "dataBase.php";
			
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT Hobbies FROM WorldBloggers WHERE Author_UID='$user_id'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$hobbies_ = explode( ",", $row["Hobbies"] );
				}
			}

			//Close the connection
			$conn->close();

			return $hobbies_;
		}
	}


	/* PAGE FUNCTION */
	if ( !function_exists("is_own_story") ) {
		function is_own_story() {
			$get_url = $_SERVER['HTTP_REFERER'];
			if ( strpos($get_url, "logedIn") ) { return 1; }
			else { return 0; }
		}
	}

	if ( !function_exists("is_visited_story") ) {
		function is_visited_story() {
			$get_url = $_SERVER['HTTP_REFERER'];
			if ( strpos($get_url, "openBloger") ) { return 1; }
			else { return 0; }
		}
	}

	if ( !function_exists("is_desktop") ) {
		function is_desktop() {
			$get_url = $_SERVER['HTTP_REFERER'];
			if ( !strpos($get_url, "Mobile") ) { return 1; }
			else { return 0; }
		}
	}

	if ( !function_exists("is_mobile") ) {
		function is_mobile() {
			$get_url = $_SERVER['HTTP_REFERER'];
			if ( strpos($get_url, "Mobile") ) { return 1; }
			else { return 0; }
		}
	}

	if ( !function_exists("redirect_to") ) {
		function redirect_to( $url, $method, $plugin_slug = "", $plugin_author = "", $new_window = false ) {
			$method = strtolower( $method );


			if ( $method == "javascript" || $method == "js" || $method == "front" ) {
				if ( !empty( $plugin_slug ) && !empty( $plugin_author ) ) {
					$catch_page = $url;
					$url = "/Library/Authors/$plugin_author/Plugins/$plugin_slug/$catch_page";
				}

				if ( $new_window == false ) { echo " <script>window.location='$url';</script> "; }
				elseif ( $new_window == true ) { echo "<script>window.open('$url');</script>"; }

				die();
			} elseif ( $method == "php" || $method == "back" ) {
				if ( !empty( $plugin_slug ) && !empty( $plugin_author ) ) {
					$catch_page = $url;
					$url = "/home/blogycoo/public_html/Library/Authors/$plugin_author/Plugins/$plugin_slug/$catch_page";
				}

				if ( !headers_sent() && $new_window == false ) { header("Location: $url"); }
				else { 
					if ( $new_window == false ) { echo " <script>window.location='$url';</script> "; }
					elseif ( $new_window == true ) { echo " <script>window.open('$url');</script> "; }
				}

				die();				
			}
		}
	}

	if ( !function_exists("give_mascot") ) {
		function give_mascot( $mascot_ = "wat" ) {
			if ( $mascot_ == "wat" ) {
				$mascot_url = "http://".$_SERVER["HTTP_HOST"]."/Library/images/Stickers/Bun/dafack.png";
			} elseif ( $mascot_ == "tongue" ) {
				$mascot_url = "http://".$_SERVER["HTTP_HOST"]."/Library/images/Stickers/Bun/vat.png";
			} elseif ( $mascot_ == "love" ) {
				$mascot_url = "http://".$_SERVER["HTTP_HOST"]."/Library/images/Stickers/Bun/love.png";
			} elseif ( $mascot_ == "confused" ) {
				$mascot_url = "http://".$_SERVER["HTTP_HOST"]."/Library/images/Stickers/Bun/confused.png";
			} elseif ( $mascot_ == "wave" ) {
				$mascot_url = "http://".$_SERVER["HTTP_HOST"]."/Library/images/Stickers/Bun/wave.png";
			} else {
				$mascot_url = "";
			}

			return $mascot_url;
		}
	} 


	/* PLUGIN AUTHORIZATIONS */
	if ( !function_exists("is_core_insight") ) {
		function is_core_insight() {
			include "dataBase.php";

			$is_core_insight = 0;

			$plugin_author = explode("/", explode("Authors/", $_SERVER['REQUEST_URI'])[1] )[0];
			$plugin_slug = explode("/", explode("Plugins/", $_SERVER['REQUEST_URI'])[1] )[0];

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT Core_Insight FROM Plugin_Store WHERE Plugin_Slug='$plugin_slug' AND plugin_author='$plugin_author'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$is_core_insight = $row["Core_Insight"];
				}
			}

			//Close the check connection
			$conn->close();

			return $is_core_insight;
		}
	} 


	/* BODY FUNCTIONS */
	if ( !function_exists("load_header") ) {
		function load_header( $is_mobile = 0 ) {
			if ( $is_mobile == 0 ) { 
				$path_ = "{$GLOBALS["ori_path"]}/Library/PHP/";
			} elseif ( $is_mobile == 1 ) { 
				$path_ = "{$GLOBALS["ori_path"]}/Library/PHP/Mobile/";
			}
			include $path_ ."header.php"; 
		}
	}

	if ( !function_exists("prepend_to") ) {
		function prepend_to( $prepend_container_id = "", $prepend_content = "" ) {
			if ( !empty( $prepend_container_id ) ) {
				$prepend_content = str_replace("\n", " ", $prepend_content);

				echo "
				<script>
				if ( $( '$prepend_container_id' ).length ) {
					$( '$prepend_container_id' ).prepend( \"$prepend_content\" );
				} else {
					console.log( 'There is no element with ID: $prepend_container_id' );
				}
				</script>
				";
			}
		}
	}

	if ( !function_exists("append_to") ) {
		function append_to( $append_container_id = "", $append_content = "" ) {
			if ( !empty( $append_container_id ) ) {
				$append_content = str_replace("\n", " ", $append_content);

				echo "
				<script>
				if ( $( '$append_container_id' ).length ) {
					$( '$append_container_id' ).append( \"$append_content\" );
				} else {
					console.log( 'There is no element with ID: $append_container_id' );
				}
				</script>
				";
			}
		}
	}

	/*if ( !function_exists("load_menu") ) {
		function load_menu( $is_mobile = 0 ) {
			if ( $is_mobile == 0 ) { 
				$path_ = "{$GLOBALS["ori_path"]}/Library/PHP/";
				include $path_ ."loadMenu.php"; 
				include $path_ ."loadSuggestedBlogers.php";
			} elseif ( $is_mobile == 1 ) { 
				$path_ = "{$GLOBALS["ori_path"]}/Library/PHP/Mobile/";
				include $path_ ."loadMenu.php"; 
			}
		}
	}*/


	/* DATABASE FUNCTIONS */

	//Open Connection
	if ( !function_exists("connect_to_database") ) {
		function connect_to_database() {
			include "dataBase.php";

			$is_core_insight = 0;

			$plugin_author = explode("/", explode("Authors/", $_SERVER['REQUEST_URI'])[1] )[0];
			$plugin_slug = explode("/", explode("Plugins/", $_SERVER['REQUEST_URI'])[1] )[0];

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT Core_Insight FROM Plugin_Store WHERE Plugin_Slug='$plugin_slug' AND plugin_author='$plugin_author'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$is_core_insight = $row["Core_Insight"];
				}
			}

			//Close the check connection
			$conn->close();

			if ( $is_core_insight == 0 ) { return 0; }
			else {
				$conn_var =  mysqli_connect($servername, $username, $password, $dbname);
				if ($conn_var->connect_error) {
					die("Connection failed: " . $conn_var->connect_error);
				}

				return $conn_var;
			}
		}
	}

	//Close opened connection
	if ( !function_exists("close_database_connection") ) {
		function close_database_connection( $conn_var ) {
			$conn_var->close();
		}
	}


	//Create Table
	if ( !function_exists("create_table") ) {
		function create_table( $table_name, $args = array() ) {
			if ( !empty( $table_name ) && !empty( $args ) ) {
				$conn = connect_to_database();

				if ( $conn != 0 ) { 
					$sql = "CREATE TABLE $table_name (ID int NOT NULL AUTO_INCREMENT,";
					foreach ( $args as $column_name => $columne_type ) {
						$sql .= "$column_name $columne_type,";
					}
					$sql .= "PRIMARY KEY(ID))";

					$conn->query($sql);
				} else {
					die("Fatal: This plugin is not Core Insight!");
				}
			}
		}
	}


	//Create Plugin File
	if ( !function_exists("create_doc") ) {
		function create_doc( $path, $content = "", $permisions = 0755, $user_id = "" ) {
			$plugin_author = explode("/", explode("Authors/", $_SERVER['REQUEST_URI'])[1] )[0];
			$plugin_slug = explode("/", explode("Plugins/", $_SERVER['REQUEST_URI'])[1] )[0];

			if ( empty( $user_id ) ) { 
				session_start();
				$user_id = $_SESSION["sender"];
			}

			if ( !file_exists( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author ) ) {
				mkdir( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author, 0751 );

				if ( !file_exists( $ori_path ."Library/Authors/$user_id/Plugins/Plugins_Files/$plugin_slug-$plugin_author/index.php" ) ) {
					$navigator_ = fopen( $GLOBALS["ori_path"] ."Library/Authors/$user_id/Plugins/Plugins_Files/$plugin_slug-$plugin_author/index.php", "w" );
					fwrite($navigator_, "<?php header('Location: http://".$_SERVER["HTTP_HOST"]."'); ?>");
					fclose( $navigator_ );
				}
			}
			
			$file_ = fopen( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author ."/". $path, "w" );
			fwrite( $file_, $content );
			fclose( $file_ );

			chmod( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author ."/". $path, $permisions );
		}
	}

	//Delete Plugin File
	if ( !function_exists("delete_doc") ) {
		function delete_doc( $path, $user_id = "" ) {
			$plugin_author = explode("/", explode("Authors/", $_SERVER['REQUEST_URI'])[1] )[0];
			$plugin_slug = explode("/", explode("Plugins/", $_SERVER['REQUEST_URI'])[1] )[0];

			if ( empty( $user_id ) ) { 
				session_start();
				$user_id = $_SESSION["sender"];
			}

			$is_deleted = unlink( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author ."/". $path );
		
			return $is_deleted;
		}
	}

	//Write to Plugin File
	if ( !function_exists("write_to_doc") ) {
		function write_to_doc( $path, $content, $rewrite = true, $user_id = "" ) {
			$plugin_author = explode("/", explode("Authors/", $_SERVER['REQUEST_URI'])[1] )[0];
			$plugin_slug = explode("/", explode("Plugins/", $_SERVER['REQUEST_URI'])[1] )[0];

			if ( empty( $user_id ) ) { 
				session_start();
				$user_id = $_SESSION["sender"];
			}

			if ( !file_exists( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author ) ) {
				mkdir( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author, 0751 );

				if ( !file_exists( $ori_path ."Library/Authors/$user_id/Plugins/Plugins_Files/$plugin_slug-$plugin_author/index.php" ) ) {
					$navigator_ = fopen( $GLOBALS["ori_path"] ."Library/Authors/$user_id/Plugins/Plugins_Files/$plugin_slug-$plugin_author/index.php", "w" );
					fwrite($navigator_, "<?php header('Location: http://".$_SERVER["HTTP_HOST"]."'); ?>");
					fclose( $navigator_ );
				}
			}

			if ( $rewrite == true ) { $mode_ = "w"; }
			elseif ( $rewrite == false ) { $mode_ = "a"; }

			$file_ = fopen( $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author ."/". $path, $mode_ );
			fwrite( $file_, $content );
			fclose( $file_ );
		}
	}

	//Read from Plugin File
	if ( !function_exists("read_from_doc") ) {
		function read_from_doc( $path, $lines_ = -1, $read_from = 0, $user_id = "" ) {
			$plugin_author = explode("/", explode("Authors/", $_SERVER['REQUEST_URI'])[1] )[0];
			$plugin_slug = explode("/", explode("Plugins/", $_SERVER['REQUEST_URI'])[1] )[0];

			if ( empty( $user_id ) ) { 
				session_start();
				$user_id = $_SESSION["sender"];
			}

			$start_ = 0; // Starts to read from file when $start_ is equal to $read_from
			$full_path = $GLOBALS["ori_path"] ."Library/Authors/". $user_id ."/Plugins/Plugins_Files/". $plugin_slug ."-". $plugin_author ."/". $path;

			if ( $lines_ > 0 || $lines_ == -1 ) { 
				$file_content = "";
				if ( file_exists( $full_path ) ) {
					$file_ = fopen( $full_path , "r" );
					if ( $lines_ == -1 ) { // Read from the whole file
						while ( !feof( $file_ ) ) {
							if ( $start_ == $read_from ) { $file_content .= trim( fgets( $file_ ) ); }
							else { fgets( $file_ ); $start_ += 1; }
						}
					} else { // Read the file while the lines are not equal to argument
						$line_counter = 0;
						while ( $line_counter < $lines_ ) {
							if ( $start_ == $read_from ) { $file_content .= trim( fgets( $file_ ) ); $line_counter += 1; }
							else { fgets( $file_ ); $start_ += 1; }
						}
					}
					fclose( $file_ );
				} else {
					$file_content = NULL;
				}
			} else {
				$file_content = NULL;
			}
		
			return $file_content;
		}
	}

	//Styles include functions
	if ( !function_exists("get_desktop_styles") ) {
		function get_desktop_styles() {
			return "http://". $_SERVER["HTTP_HOST"] ."/style.css";
		}
	}

	if ( !function_exists("get_mobile_styles") ) {
		function get_mobile_styles() {
			return "http://". $_SERVER["HTTP_HOST"] ."/Library/Mobile/CSS/style.css";
		}
	}

	if ( !function_exists("get_fonts") ) {
		function get_fonts() {
			return "http://". $_SERVER["HTTP_HOST"] ."/fonts.css";
		}
	}

	//Scripts include functions
	if ( !function_exists("get_scripts") ) {
		function get_scripts() {
			echo "
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
			<script src='http://". $_SERVER["HTTP_HOST"] ."/java.js'></script>
			";
		}
	}
?>