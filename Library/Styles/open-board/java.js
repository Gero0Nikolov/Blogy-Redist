function chooseTheme( theme ) {
	localStorage.editorTheme = theme;
	window.location.reload( 1 );
}

function openPopUp() {
	popup_ = "\
	<div class='page-container'>\
		<div class='notification-container'>\
			<h1>Choose theme :</h1>\
			<div class='themes-holder'>\
				<div class='theme pod' onclick='chooseTheme(\"pod\");'>\
					<h1>Pastel on Dark</h1>\
				</div>\
				<div class='theme monokai' onclick='chooseTheme(\"monokai\");'>\
					<h1>Monokai</h1>\
				</div>\
				<div class='theme tomorrow' onclick='chooseTheme(\"tomorrow\");'>\
					<h1>Tomorrow</h1>\
				</div>\
				<div class='theme xcode' onclick='chooseTheme(\"xcode\");'>\
					<h1>XCode</h1>\
				</div>\
			</div>\
		</div>\
	</div>\
	";
	$( "body" ).append( popup_ );
}

$(document).ready(function(){
	if ( localStorage.editorTheme !== undefined ) {
		editorTheme = localStorage.editorTheme;
	} else {
		openPopUp();
	}

	editor.getSession().on('change', function(e) {
		stripFunctions( "---> Should be replace with bHandler function <---" );
	});

	$( "#compile-button" ).on('click', function(){
		compile(editor);
	});

	editor.commands.addCommand({
	    name: 'compile',
	    bindKey: {win: 'Ctrl-Q',  mac: 'Command-Q'},
	    exec: function(editor) {
			compile(editor);
	    },
	    readOnly: false // false if this command should not apply in readOnly mode
	});

	$( "#iframe-opener" ).on("click", function(){
		document.getElementById( "dev-view-window" ).contentWindow.location.reload( true );
		$( "#dev-view" ).addClass( "zoom-in" );
	});

	$( "#close-dev-view" ).on("click", function(){
		$( "#dev-view" ).removeClass( "zoom-in" );
	});
});

function compile(editor) {
	getSlug = window.location.href.split("?")[1].split("%")[0];
	getFile = window.location.href.split("?")[1].split("%")[1];

	getPatch = editor.getValue();
	getPatch = getPatch.replace(/\+/g, "#11");
	getPatch = getPatch.replace(/\&/g, "#33");

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "Universal/compileOpenBoardPatch.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("patch="+getPatch+"&pluginSlug="+getSlug+"&file="+getFile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ){
    			compiled_message_container = "\
    				<div id='compiled-message'>\
    					Compiled!\
    					<img src='https://cdn0.iconfinder.com/data/icons/round-ui-icons/128/tick_blue.png' alt='Broken image.' />\
    				</div>\
    			";
    			$("body").append(compiled_message_container);

    			setTimeout(function(){ $("#compiled-message").fadeOut("fast"); setTimeout(function(){ $("#compiled-message").remove(); }, 150) }, 1000);
    		} 
    		else
    		if ( requestType.responseText != "READY" ) {
    			alert(requestType.responseText);
    		}
    	}
    }
}

function stripFunctions( error_msg ) {
	editor.find("readfile(");
	editor.replaceAll("");
	editor.find("readfile (");
	editor.replaceAll("");
	editor.find("fopen(");
	editor.replaceAll("");
	editor.find("fopen (");
	editor.replaceAll("");
	editor.find("file(");
	editor.replaceAll("");
	editor.find("file (");
	editor.replaceAll("");
	editor.find("file_get_contents(");
	editor.replaceAll("");
	editor.find("file_get_contents (");
	editor.replaceAll("");
	editor.find("chmod(");
	editor.replaceAll("");
	editor.find("chmod (");
	editor.replaceAll("");
	editor.find("chown(");
	editor.replaceAll("");
	editor.find("chown (");
	editor.replaceAll("");
	editor.find("clearstatcache(");
	editor.replaceAll("");
	editor.find("clearstatcache (");
	editor.replaceAll("");
	editor.find("copy(");
	editor.replaceAll("");
	editor.find("copy (");
	editor.replaceAll("");
	editor.find("delete(");
	editor.replaceAll("");
	editor.find("delete (");
	editor.replaceAll("");
	editor.find("lchgrp(");
	editor.replaceAll("");
	editor.find("lchgrp (");
	editor.replaceAll("");
	editor.find("lchown(");
	editor.replaceAll("");
	editor.find("lchown (");
	editor.replaceAll("");
	editor.find("link(");
	editor.replaceAll("");
	editor.find("link (");
	editor.replaceAll("");
	editor.find("mkdir(");
	editor.replaceAll("");
	editor.find("mkdir (");
	editor.replaceAll("");
	editor.find("rename(");
	editor.replaceAll("");
	editor.find("rename (");
	editor.replaceAll("");
	editor.find("rmdir(");
	editor.replaceAll("");
	editor.find("rmdir (");
	editor.replaceAll("");
	editor.find("symlink(");
	editor.replaceAll("");
	editor.find("symlink (");
	editor.replaceAll("");
	editor.find("tempnam(");
	editor.replaceAll("");
	editor.find("tempnam (");
	editor.replaceAll("");
	editor.find("tmpfile (");
	editor.replaceAll("");
	editor.find("touch(");
	editor.replaceAll("");
	editor.find("touch (");
	editor.replaceAll("");
	editor.find("umask(");
	editor.replaceAll("");
	editor.find("umask (");
	editor.replaceAll("");
	editor.find("unlink(");
	editor.replaceAll("");
	editor.find("unlink (");
	editor.replaceAll("");
	editor.find("mysql_connect(");
	editor.replaceAll("");
	editor.find("mysql_connect (");
	editor.replaceAll("");
	editor.find("mysqli_connect(");
	editor.replaceAll("");
	editor.find("mysqli_connect (");
	editor.replaceAll("");
	/*editor.find("include\"");
	editor.replaceAll("");
	editor.find("include \"");
	editor.replaceAll("");
	editor.find("require");
	editor.replaceAll("");
	editor.find("require_once");
	editor.replaceAll("");*/
}