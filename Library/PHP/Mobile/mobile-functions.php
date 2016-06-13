<?php
	
	function convertMessage($message) {
		if (strpos($message, "type*")) {
			$getLinked = explode("^", $message)[1];

			if (explode("^", $message)[0] != NULL) $message = explode("^", $message)[0];
			else $message = "";
		}

		if (isset($getLinked)) {
			if (!empty($getLinked)) {
				$flag = 1;
				//echo "<script>alert(1);</script>";

				$parseLinked = explode("~", $getLinked);
				if (in_array("nl2br", $parseLinked)) {
					$message .= "<br />";
					$flag = 2;
				}

				if (explode("*", $parseLinked[0])[1] == "img") {
					$src = explode("*", $parseLinked[$flag])[1];
					$message .= "
						<a href='$src' data-lightbox='roadtrip'>
							<img src='$src' alt='Bad image link' />
						</a>
					";
				}
			}
		}

		//$message = html_entity_decode($message);
		$message = str_replace("&lt;br /&gt;", "<br />", $message);
		
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		if (!strpos($message, "Bad image link")) {
			$url = NULL;
			if(preg_match($reg_exUrl, $message, $url)) {
				$message = preg_replace($reg_exUrl, "<a href='$url[0]' target='_blank'>$url[0]</a>", $message);
			}
		}
		
		//:D
		$message = str_replace(":D", "<img src='../../images/Emoji/lol.png' class='emoji'/>", $message);
		//:P
		$message = str_replace(":P", "<img src='../../images/Emoji/tongue.png' class='emoji'/>", $message);
		$message = str_replace(":p", "<img src='../../images/Emoji/tongue.png' class='emoji'/>", $message);
		//<3
		$message = str_replace("&lt;3", "<img src='../../images/Emoji/RH.png' class='emoji'/>", $message);
		//:O
		$message = str_replace(":O", "<img src='../../images/Emoji/ooh.png' class='emoji'/>", $message);
		//:)
		$message = str_replace(":)", "<img src='../../images/Emoji/smile.png' class='emoji'/>", $message);
		//;)
		$message = str_replace(";)", "<img src='../../images/Emoji/wink.png' class='emoji'/>", $message);
		//:(
		$message = str_replace(":(", "<img src='../../images/Emoji/sad.png' class='emoji'/>", $message);
		//;'(
		$message = str_replace(":'(", "<img src='../../images/Emoji/cry.png' class='emoji'/>", $message);
		$message = str_replace(";(", "<img src='../../images/Emoji/cry.png' class='emoji'/>", $message);
		//:*
		$message = str_replace(":*", "<img src='../../images/Emoji/kiss.png' class='emoji'/>", $message);
		//0.0
		$message = str_replace("0.0", "<img src='../../images/Emoji/wat.png' class='emoji'/>", $message);
		$message = str_replace("O.O", "<img src='../../images/Emoji/wat.png' class='emoji'/>", $message);
		$message = str_replace("{49}", "<img src='../../images/Emoji/wat.png' class='emoji'/>", $message);
		//Inlove
		$message = str_replace("{2369}", "<img src='../../images/Emoji/inlove.png' class='emoji'/>", $message);
		//Scare
		$message = str_replace(":|", "<img src='../../images/Emoji/scare.png' class='emoji'/>", $message);
		$message = str_replace("{666}", "<img src='../../images/Emoji/scare.png' class='emoji'/>", $message);
		//MyBad - Oops
		$message = str_replace("{118}", "<img src='../../images/Emoji/mybad.png' class='emoji'/>", $message);
		//Meh
		$message = str_replace("{999}", "<img src='../../images/Emoji/meh.png' class='emoji'/>", $message);
		//Much Cry
		$message = str_replace("{7428}", "<img src='../../images/Emoji/muchCry.png' class='emoji'/>", $message);
		//LoLo
		$message = str_replace("{1010}", "<img src='../../images/Emoji/lolo.png' class='emoji'/>", $message);
		//Calm
		$message = str_replace(":3", "<img src='../../images/Emoji/calm.png' class='emoji'/>", $message);
		//Sexy
		$message = str_replace("{1619}", "<img src='../../images/Emoji/sexy.png' class='emoji'/>", $message);
		//Angry
		$message = str_replace(":@", "<img src='../../images/Emoji/angry.png' class='emoji'/>", $message);
		//Hearts
		$message = str_replace("{23}", "<img src='../../images/Emoji/RH.png' class='emoji'/>", $message);
		$message = str_replace("{45}", "<img src='../../images/Emoji/BH.png' class='emoji'/>", $message);
		$message = str_replace("{0103}", "<img src='../../images/Emoji/GH.png' class='emoji'/>", $message);

		if (strpos($message, "src#")) {
			$src = explode('#', $message)[1];
			$message = "
				<a href='../$src' data-lightbox='roadtrip'>
					<img src='../$src' alt='Bad image link :(' />
				</a>
			";
		}

		return $message;
	}

	function isValidImage($urlPath) {
		$url_headers = get_headers($urlPath, 1);
		if (isset($url_headers['Content-Type'])) {
			error_reporting(E_ERROR | E_PARSE);
			$type = strtolower($url_headers['Content-Type']);
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
			if (isset($valid_image_type[$type])){
				return true; // Its an image
			}
			return false;// Its an URL
		}
	}

?>