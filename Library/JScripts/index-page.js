function checkKey(e, cmd) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if(code == 13) { //Enter keycode
		if (cmd == 0) {
			var text = document.getElementById('mail').value.toLowerCase();
			var pass = document.getElementById('password').value;
			document.getElementById('mail').value = document.getElementById('mail').value.toLowerCase();
			
			if (text == "")
			{
				alert("Enter your e-mail please.");
			}
			else
			if (pass == "")
			{
				alert("Enter your password please.");
			}
			else
			{
				if (!isValidEmailAddress(text)) {
					alert("Enter valid e-mail address please.");
				}
				else {
					tryLog();
				}
			}
		}
		else
		if (cmd == 1) {
			regLoad();
		}
		else
		if (cmd == 2) {
			notEmpty();
		}
	}
}

function checkInput(e, id) {
	if (e.keyCode == 32) {
		var object = document.getElementById(id);
		alert("Spaces are not allowed !");
		object.value = object.value.substring(0, object.value.length - 1);
		if (object.value.length == 1) {
			object.value = "";
		}
	}
}

function regLoad() {
	if (document.getElementById('honey-pot').value != "") {
		window.location = window.location.href;
	} else {
		fName = document.getElementById('fName').value.trim();
		lName = document.getElementById('lName').value.trim();
		email = document.getElementById('mail-registration').value.trim();
		pass1 = document.getElementById('pass1').value.trim();
		
		fName = document.getElementById('fName').value.toLowerCase();
		fName = document.getElementById('fName').value.charAt(0).toUpperCase() + document.getElementById('fName').value.substring(1);
		lName = document.getElementById('lName').value.toLowerCase();
		lName = document.getElementById('lName').value.charAt(0).toUpperCase() + document.getElementById('lName').value.substring(1);
		email = document.getElementById('mail-registration').value.toLowerCase();
		
		flag = 0;
		
		if (fName == "")
		{
			alert("Enter your first name please.");
			flag = 1;
		}
		
		if (lName == "")
		{
			alert("Enter your last name please.");
			flag = 1;
		}
		
		if (email == "")
		{
			alert("Enter your e-mail please.");
			flag = 1;
		}

		if (pass1 == "")
		{
			alert("Choose your password.");
			flag = 1;
		}

		var letters = /^[a-zA-Z]+$/;
		if (fName.match(letters) && lName.match(letters)) {
			//OK.
		} else {
			alert("Enter your real name please.");
			flag = 1;
		}

		if (flag == 0) {
			if (!isValidEmailAddress(document.getElementById('mail-registration').value)) {
				alert("Enter valid e-mail address !");
			} else {
				//Check if IP is blocked
				var requestType;
				if (window.XMLHttpRequest) {
					requestType = new XMLHttpRequest();
				} else {
					requestType = new ActiveXObject("Microsoft.XMLHTTP");
				}
				requestType.open("GET", "Library/PHP/Universal/checkIP.php", true);
				requestType.send();

				requestType.onreadystatechange=function() {
				 	if (requestType.readyState==4 && requestType.status==200) {
				 		isBanned = requestType.responseText;

				 		if (isBanned == "TRUE") { alert("You already have registered an account today.\nTry again tomorrow :-)"); }
				 		else
				 		if (isBanned == "FALSE") { document.forms['register'].submit(); }
				 	}
				}
			}
		}
	}
}

function notEmpty() {
	mail = document.getElementById("email_uid").value;
	auth_code = document.getElementById("save_code").value;
	
	flag = 0;

	if (mail == "") {
		alert("Enter your email first.");
		flag = 1;
	}

	if (auth_code == "") {
		alert("Enter your authentication code first.");
		flag = 1;
	}

	if (flag == 0) {
		document.getElementById("passwordReset").action = 'Library/PHP/checkLog.php';
		document.forms["passwordReset"].submit();
	}
}

function sendLetter() {
	var message = "Finish the subject with the job you want to do.%0DAnd then tell us what do you preffer to do in your free time.%0DAlso tells us why do you preffer to do this and why you are the best !";
	window.open('mailto:vtm.sunrise@gmail.com?subject=I want to&body='+message);
}

function setBackground() {
	getRandom = Math.floor((Math.random() * 4) + 1);
	src = "Library/images/bgs/"+getRandom.toString()+".JPG";
	document.body.style.background = "black url('"+src+"') no-repeat fixed center";
	document.body.style.backgroundSize = "cover";
}

function Entry() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "Library/PHP/Universal/addView.php", true);
	requestType.send();

	requestType.onreadystatechange=function() {
	 	if (requestType.readyState==4 && requestType.status==200) {
	 		//alert(requestType.responseText);

	 		if (requestType.responseText == "LOGIN-MOBILE") {
	 			window.location = "Library/Errors/stuck.php";
	 		}
	 		else
	 		if (requestType.responseText == "LOGIN-DESKTOP") {
	 			window.location = "Library/PHP/logedIn.php";
	 		}
	 		else
	 		if (requestType.responseText == "DONT-LOG") {
	 			getURL = window.location.href;
				getArgument = getURL.split("?")[1];

				isFollowCommand = 0;
				isActivateCommand = 0;
				isPost = 0; 

				if ( getArgument !== undefined && getArgument.indexOf("p_id") > -1 ) { isPost = 1; }
				else if ( getArgument !== undefined && getArgument.indexOf("f=") > -1 ) { isFollowCommand = 1; }
				else if ( getArgument !== undefined && getArgument.indexOf("activate") > -1 ) { console.log( "activate" ); isActivateCommand = 1; activationCode = getArgument.split( "=" )[1]; } 
				else { getName = getArgument; }

				if ( isPost == 0 ) {
					if ( isFollowCommand == 0 ) {
						if ( isActivateCommand == 0 ) { 
		 					if ( getName ) {
								if (getName == "Admin_view") { window.location = "Library/Admin"; }
								else { window.location = "Library/PHP/previewAuthor.php?"+getName; }
							}
						} else if ( isActivateCommand == 1 ) { window.location = "Library/PHP/Universal/activate_email.php?"+activationCode; }
					} else if ( isFollowCommand == 1 ) {
						if(typeof(Storage) !== "undefined") {
							userID = getArgument.split( "f=" )[1];
							localStorage.followUpAuthor = userID;
						}
					}
				} 
				else
				if ( isPost == 1 ) {
					window.location = "Library/PHP/previewSharedPost.php?"+getArgument;
				}
	 		}
	 	}
	}
}

function showHelloMessage(arg) {
	message = "";

	if (arg == 0) { message = "Hello night owl :-)"; }
	else
	if (arg == 1) { message = "Good morning :-)"; }
	else
	if (arg == 2) { message = "Good afternoon :-)"; }

	//alert(message);

	build = "\
		<div class='helloMessage'>\
			<h1>"+message+"</h1>\
		</div>\
	";

	$("#blur-container").append(build);
	$(".helloMessage").fadeIn("fast");

	setTimeout(function(){ clearMessage(); }, "1000");
}

function clearMessage() {
	$(".helloMessage").fadeOut("fast");
	setTimeout(function(){ $(".helloMessage").remove(); loadBody(); }, "150");
}

function loadBody() {
	$("#blur-container").fadeIn("fast");
	/*$("#additional").fadeIn("fast");
	$("#nav-bar").fadeIn("fast");*/
}

function isMobile() { 
	if( navigator.userAgent.match(/Android/i)
		|| navigator.userAgent.match(/webOS/i)
		|| navigator.userAgent.match(/iPhone/i)
		|| navigator.userAgent.match(/iPad/i)
		|| navigator.userAgent.match(/iPod/i)
		|| navigator.userAgent.match(/BlackBerry/i)
		|| navigator.userAgent.match(/Windows Phone/i)
	) {
		return true;
	}
	else {
		return false;
	}
}

function hidePopup() {
	$( "#popup-holder" ).removeClass( "fade-in" );
	setTimeout(function(){ $( "#popup-holder" ).remove(); }, 250 );
}

// On page load events
$( document ).ready(function(){
	//Load partners
	loadPartnerships();

	var boxTitleTimeout = setTimeout(function(){
		$( "#login-title" ).addClass( "fade-in" );
		setTimeout(function(){ $( "#login-title" ).removeClass( "fade-in" ); }, 1000);
	}, 150);

	setTimeout(function(){
		$( "#background" ).addClass( "fade-in" );
	}, 1000);

	$( "#control-board .controll-button" ).on("click", function(){
		$( "#control-board .active-box" ).removeClass( "active-box" );
		$( "#control-board .active" ).removeClass( "active" );

		$( this ).addClass( "active" );
		boxID = $( this ).attr( "id" ).split( "-controller" )[0];
		$( "#"+boxID ).addClass( "active-box" );

		clearTimeout( boxTitleTimeout );
		boxTitleTimeout = setTimeout(function(){
			$( "#"+boxID+"-title" ).addClass( "fade-in" );
			setTimeout(function(){ $( "#"+boxID+"-title" ).removeClass( "fade-in" ); }, 1000);
		}, 150);
	});

	// Control box swipe left / right
	$( ".controll-box" ).on("swiperight", function(){
		prevSectionID = parseInt( $( this ).attr( "section-id" ) ) - 1;

		if ( prevSectionID == 0 ) { prevSectionID = 6; }

		boxID = $( this ).attr( "id" );

		//Hide current box
		$( this ).removeClass( "active-box" );
		$( "#control-board #"+boxID+"-controller" ).removeClass( "active" );

		//Show next box
		$( "[section-id='"+prevSectionID+"']" ).addClass( "active-box" );
		prevBoxID = $( "[section-id='"+prevSectionID+"']" ).attr( "id" );
		$( "#control-board #"+prevBoxID+"-controller" ).addClass( "active" );

		clearTimeout( boxTitleTimeout );
		boxTitleTimeout = setTimeout(function(){
			$( "#"+prevBoxID+"-title" ).addClass( "fade-in" );
			setTimeout(function(){ $( "#"+prevBoxID+"-title" ).removeClass( "fade-in" ); }, 1000);
		}, 150);
	});

	$( ".controll-box" ).on("swipeleft", function(){
		nextSectionID = parseInt( $( this ).attr( "section-id" ) ) + 1;

		if ( nextSectionID == 7 ) { nextSectionID = 1; }

		boxID = $( this ).attr( "id" );

		//Hide current box
		$( this ).removeClass( "active-box" );
		$( "#control-board #"+boxID+"-controller" ).removeClass( "active" );

		//Show next box
		$( "[section-id='"+nextSectionID+"']" ).addClass( "active-box" );
		nextBoxID = $( "[section-id='"+nextSectionID+"']" ).attr( "id" );
		$( "#control-board #"+nextBoxID+"-controller" ).addClass( "active" );

		clearTimeout( boxTitleTimeout );
		boxTitleTimeout = setTimeout(function(){
			$( "#"+nextBoxID+"-title" ).addClass( "fade-in" );
			setTimeout(function(){ $( "#"+nextBoxID+"-title" ).removeClass( "fade-in" ); }, 1000);
		}, 150);
	});

	//Open registration box
	$( "#join-button" ).on("click", function(){
		$( "#login-box" ).addClass( "fly-out-top" );
		$( "#registration-box" ).addClass( "fly-in" );
	});

	//Close registration box
	$( "#discard-registration" ).on("click", function(){
		$( "#login-box" ).removeClass( "fly-out-top" );
		$( "#registration-box" ).removeClass( "fly-in" );
	});

	$( "#presentation-player" ).on("click", function(){
		iframeSRC = $( "#presentation iframe" ).attr( "src" );

		build = "<div id='popup-holder'><iframe src="+iframeSRC+"?autoplay=1 frameborder='0' allowfullscreen></iframe></div>";
		$( "body" ).append(build);

		setTimeout(function(){ $( "#popup-holder" ).addClass( "fade-in" ); }, 150);

		$( "#popup-holder" ).on("click", function(e){
			if( e.target == this ) { hidePopup(); }
		});
	});

	//If mobile
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		//Paralax view
		var background = document.getElementById('background');

		window.addEventListener('deviceorientation', function(eventData) {
		  // Retrieving the front/back tilting of the device and moves the
		  // background in the opposite way of the tilt

		  //var yTilt = Math.round((-eventData.beta + 90) * (40/180) - 40);

		  // Retrieve the side to side tilting of the device and move the
		  // background the opposite direction.

		  var xTilt = Math.round(-eventData.gamma * (20/180) - 20);

		  // Thi 'if' statement checks if the phone is upside down and corrects
		  // the value that is returned.
		  if (xTilt > 0) {
		    xTilt = -xTilt;
		  } else if (xTilt < -40) {
		    xTilt = -(xTilt + 80);
		  }

		  var backgroundPositionValue = xTilt + 'px ' + 50 + "%";

		  background.style.backgroundPosition = backgroundPositionValue;
		}, false);
	}
});