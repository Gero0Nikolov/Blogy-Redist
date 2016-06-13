function tryLog() {
	getMail = document.getElementById('mail').value.toLowerCase().trim();
	getPass = bruteEncrypt(document.getElementById('password').value);
	document.getElementById('mail').value = getMail;

	cleanContainer();

	if (getMail == "") {
		alert( "Enter your e-mail first!" );
	}
	else
	if (getPass == "") {
		alert( "Enter your password first!" );
	} 
	else {
		if (!isValidEmailAddress(getMail)) {
			alert( "Enter valid e-mail please!" );
		} else {
			//Remove the onclick function
			document.getElementById("login-button").onclick = "";

			build = "<div id='popup-holder'><h3 class='load-sign'>Logging...</h3></div>";
			$( "body" ).append(build);

			setTimeout(function(){ $( "#popup-holder" ).addClass( "fade-in" ); }, 150);

			var requestType;
			if (window.XMLHttpRequest) {
				requestType = new XMLHttpRequest();
			} else {
				requestType = new ActiveXObject("Microsoft.XMLHTTP");
			}
			requestType.open("POST", "Library/PHP/checkLog.php", true);
			requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			requestType.send("mail="+getMail+"&password="+getPass);

			requestType.onreadystatechange = function() {
				flag = 0;

				if (requestType.readyState == 4 && requestType.status == 200) {
					//Append onclick function
					document.getElementById("login-button").onclick = function(){ tryLog() };

					$( "#popup-holder" ).remove();

		 			var storeResponce = requestType.responseText;
					
					if (storeResponce == "BMAIL" || storeResponce == "BPASS") {
						alert( "Your e-mail or password is wrong." );
					}
					else
					if (storeResponce == "BANNED") {
						alert( "Your profile is temporary banned!" );
					}
					else
					if (storeResponce == "NOT_ACTIVATED") {
						alert( "Your profile is NOT ACTIVATED!\nVisit your e-mail address to activate it." );
					}
					else
					if (storeResponce == "LMOBILE") {
						flag = 1;
					}
					else
					if (storeResponce == "LDESKTOP") {
						flag = 2;
					}
					else
					if (storeResponce == "SACMOBILE") {
						flag = 3;
					}
					else
					if (storeResponce == "SACDESKTOP") {
						flag = 4;
					}

					document.cookie = "mail=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
					document.cookie = "password=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

					if (flag == 1 || flag == 3) {
						if (flag == 3) { document.cookie = "set_save_code=1;domain=."+window.location.host+";path=/"; }
						window.location = "Library/Errors/stuck.php";
					}
					else
					if (flag == 2 || flag ==4) {
						if (flag == 4) { document.cookie = "set_save_code=1;domain=."+window.location.host+";path=/"; }
						window.location = "Library/PHP/logedIn.php";
					}
				}
			}
		}
	}
}

function cleanContainer() {
	$('#message-container-main').children("#container-message").children("p").remove();
	$('#message-container-main').children("#container-message").children(".option").remove();
	$('#message-container-main').fadeOut('fast');
}

function cleanInput() {
	document.getElementById("mail").value = "";
	document.getElementById("password").value = "";
}

function sendPasswordAndAuth() {
	getMail = document.getElementById("email_uid").value.trim();

	flag = 0;

	if ( getMail == "" || getMail == undefined ) { flag = 1; alert("Enter your e-mail first."); }

	if ( flag == 0 ) {
		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		requestType.open("POST", "Library/PHP/Universal/sendForgottenPassAndAuth.php", true);

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("mail="+getMail);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				if ( requestType.responseText == "READY" ) {
					alert( "You should receive an e-mail with your information.\r\n Check your spam box as well!" );
				}
			}
		}
	}
}