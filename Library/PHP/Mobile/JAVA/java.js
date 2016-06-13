function showHide() {
	$("#dropDownMenu").slideToggle('fast');
}

function showHideSideMenu() {
	$("#leftDropMenu").toggle("slide");
}

function showHideSideBar() {
	document.getElementById("sidebar-activator").className = "sidebar-activator";
	document.getElementById("sidebar-activator").removeAttribute("onclick");
	document.getElementById("sidebar-activator").onclick = showHideSideBar;

	$("#sideBar").toggle("slide");
	$("#online-list").fadeOut("fast");
	$("#suggestions-list").fadeOut("fast");
	$("#ohana-list").fadeOut("fast");
	$("#notifications-list").fadeOut("fast");
}

//Notifications manipulations
function createNotificationsIndex() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "editNotifications.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				showHideSideBar(); 
				$("#notifications-list").toggle("slide");
			}
		}
	}
}

//Explore blogger manipulations
//..Show quick menu..
function showOptionsM() {
	$("#quickMenuContainer").fadeToggle("fast");
	$("#quickMenuContainer").children("#container").slideToggle("fast");
}
//..Hide quick menu..
function hideOptionsM() {
	$("#quickMenuContainer").children("#container").slideToggle("fast");
	$("#quickMenuContainer").fadeToggle("fast");
}

//Messeges manipulations
//..Reply..
function showContainer() {
	document.getElementById("reply-button").removeAttribute("onclick");
	document.getElementById("reply-button").onclick = hideContainer;
	document.getElementById("reply-button").innerHTML = "Hide";
	$("#reply-container").animate({marginTop: '-75%'}, 500);
}
function hideContainer() {
	document.getElementById("reply-button").removeAttribute("onclick");
	document.getElementById("reply-button").onclick = showContainer;
	document.getElementById("reply-button").innerHTML = "Reply";
	$("#reply-container").animate({marginTop: '-70px'}, 500);
}
function sendMessageM(receiver) {
	if (document.getElementById('link-location').value.trim() != "") {
		img = document.getElementById('link-location').value;
		if (document.getElementById('messageTXT').value.trim() != "")
			document.getElementById('messageTXT').value += '^type*img~nl2br~src*'+img;
		else 
			document.getElementById('messageTXT').value += '^type*img~src*'+img;
	}

	if (document.getElementById('messageTXT').value.trim() != "") {
		document.cookie = "authorId="+receiver;
		document.getElementById('send-message').action = 'sendMessage.php';
		document.forms['send-message'].submit();
	} else {
		alert("Enter something in your message first !");
	}
}

//Show map menu
function showHideMapMenuM() {
	$("#menu-container").fadeToggle("fast");
	//$("#menu").slideToggle("fast");
}
function hideMapM() {
	$("#mapContainer").fadeOut("fast");
	$("#menu-container").fadeToggle("fast");

	$("#mapHolder").remove();
	document.getElementById("mapContainer").innerHTML += "<div id='mapHolder'></div>";
}

//Tag a place
function showTagContainer() {
	$("#tag-container").fadeIn("fast");
	$("#tag-container").children("#container").slideDown("fast");
}
function hideTagContainer() {
	$("#tag-container").fadeOut("fast");
	$("#tag-container").children("#container").slideUp("fast");

	document.getElementById("placeTitle").value = "";
	document.getElementById("searchInput").value = "";
	document.getElementById("placeStory").value = "";

	taggedFriends = [];
	$("#taggedFriends").children().remove();
}

//Preview place - Manipulations
function showPlaceMenuM() {
	$("#informationContainer").children("#mapContainer").fadeOut("fast");
	$("#menuContainer").fadeIn("fast");
	$("#menuContainer").children("#options").slideDown("fast");
}

function hidePlaceMenuM() {
	$("#menuContainer").fadeOut("fast");
	$("#menuContainer").children("#options").slideUp("fast");
	$("#informationContainer").children("#mapContainer").fadeIn("fast");
}

function hideContainerFriendsM() {
	$("#storeFriends-main").fadeOut("fast");
	document.cookie = "sharePicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}


//Quick Messages - Manipulations
function showMessageBoxM(receiver) {	
	document.getElementById('receiverId').value = receiver;
	document.getElementById('receiver').innerHTML = document.getElementById(receiver).elements["blogerFN"].value + " " + document.getElementById(receiver).elements["blogerLN"].value;
	document.getElementById('messageArea').focus();
	$('#quickMessageBox').slideToggle('fast');
}

function sendMessageBoxM() {
	var messageText = document.getElementById('messageArea').value;
	var receiver = document.getElementById('receiverId').value;

	if (messageText.trim() != ""  && receiver.trim() != "") {
		getMessage = messageText.replace(/(?:\r\n|\r|\n)/g, '<br />');
		getReceiver = receiver;

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		requestType.open("POST", "sendQuickMessage.php", true);
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("MESSAGE="+getMessage+"&RECEIVER="+getReceiver);

		requestType.onreadystatechange = function() {
	        if (requestType.readyState == 4 && requestType.status == 200) {
	     		var storeResponce = requestType.responseText;
	 		   
	 		 	if (storeResponce == "READY") {
		 		  	$("#quickMessageBox").slideToggle("fast");
		 		 	document.getElementById('messageArea').value =  "";
		 		 	document.getElementById('receiverId').value = "";
	 		 	}
	     	}
	    }
	} else {
		alert("Enter something in this message.");
	}
}

//Page manipulations - Explore Stories / Explore F. Stories
function checkPos() { //Checks the scroll and loads more stories if true
	if ($(window).scrollTop() + document.body.clientHeight + 400 >= $(window).height()) {
		if (loops > 0 && flag == 0) {flag = 1; callBack();}
	}
}

//Send to friends - Manipulations
function clearStore() {
	$("#storeFriends-main").fadeOut("fast");
	sendTo = [];
	document.getElementById("sendButton").style.color = "#ccc";
	$("#chooseToSend").children("button").css({'color':'black'});
}

//Album manipulations
//..Show quick menu..
function showAlbumOptionsM(picture, sender, containerId) {
	$("#imgOptions-main").fadeToggle("fast");
	$("#imgOptions-main").children("#container").slideToggle("fast");

	document.getElementById("view-button").onclick = function(){
	   	viewImg("../../Authors/"+sender+"/Album/"+picture);
	};

	document.getElementById("share-button").onclick = function(){
		$("#storeFriends-main").fadeIn("fast");
	};

	document.getElementById("delete-button").onclick = function(){
		deleteObjectFromAlbum(picture, containerId);
		hideAlbumOptionsM();
	};

	document.getElementById("sendButton").onclick = function(){
		sendImage(picture);
	};

	document.getElementById("set-button").onclick = function(){
		setAsProfilePic(picture);
	};

	document.getElementById("set-logo").onclick = function(){
		loadClubSelector(1, "change-logo", picture);
	};

	document.getElementById("make-button").onclick = function(){
		reservePost(picture, sender);
	};
}
//..Hide quick menu..
function hideAlbumOptionsM() {
	$("#imgOptions-main").children("#container").slideToggle("fast");
	$("#imgOptions-main").fadeToggle("fast");

	document.getElementById("view-button").onclick = "";
	document.getElementById("share-button").onclick = "";
	document.getElementById("delete-button").onclick = "";
	document.getElementById("sendButton").onclick = "";
	document.getElementById("set-button").onclick = "";
	document.getElementById("set-logo").onclick = "";
	document.getElementById("make-button").onclick = "";
}

//..View img..
function viewImg(src) {
	document.getElementById("imgShower").src = src;
	$("#viewportContainer").fadeIn("fast");
}

//..Reserve post..
function reservePost(picture, sender) {
	document.getElementById("postImg").value = '../../../Library/Authors/'+sender+'/Album/'+picture;
	$("#makePost-main").fadeIn("fast");
}

function clearReservation() {
	document.getElementById("titleIdCode").value = "";
	document.getElementById("postImg").value = "";
	document.getElementById("content").value = "";
	$("#makePost-main").fadeOut("fast");
}

//ATTACH ELEMENTS TO THE PAGE ONLOAD
jQuery(document).ready(function(){
	if ( window.location.href.indexOf("startSlideShow") > -1 ) {
		//OWL-Carousel
		$('.owl-carousel').owlCarousel({
			loop:true,
			autoplay:true,
			autoplayTimeout:3000,
			responsive:{
		        0:{
		            items:1
		        },
		        600:{
		            items:1
		        },
		        1000:{
		            items:1
		        }
		    }
		});
	}
});