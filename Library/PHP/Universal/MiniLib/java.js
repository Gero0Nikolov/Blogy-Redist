function checkName(id) {
	if (!/^[a-zA-Z]+$/.test(document.getElementById(id).value)) {
		document.getElementById(id).style.borderBottom = "1px solid #EA3328";
		lockSelection("TRUE");
	} else {
		document.getElementById(id).style.borderBottom = "1px solid #dcdee3";
		lockSelection("FALSE");
	}
}

function checkUrl(id) {
	validate = /^(http(?:s)?\:\/\/[a-zA-Z0-9]+(?:(?:\.|\-)[a-zA-Z0-9]+)+(?:\:\d+)?(?:\/[\w\-]+)*(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/;
	if (!validate.test(document.getElementById(id).value)) {
		document.getElementById(id).style.borderBottom = "1px solid #EA3328";
		lockSelection("TRUE");
	} else {
		document.getElementById(id).style.borderBottom = "1px solid #dcdee3";
		lockSelection("FALSE");
	}
}

function tryLock(id, regEx) {
	if (!regEx.test(document.getElementById(id).value)) {
		return 1;
	} else {
		return 0;
	}
}

function lockSelection(cmd) {
	if (cmd == "TRUE") {
		document.getElementById("share-button").style.color = "#ccc";
		document.getElementById("share-button").onclick = "";
	} else
	if (cmd == "FALSE") {
		breakFlag = 0;
		for (i = 0; i < $("#form-input > input").length; i++) {
			if ($("."+i).val() == "") { breakFlag = 1; break; }
		}

		if (breakFlag == 0) {
			document.getElementById("share-button").style.color = "#08c";
			document.getElementById("share-button").onclick = function() {
				validateURL = /^(http(?:s)?\:\/\/[a-zA-Z0-9]+(?:(?:\.|\-)[a-zA-Z0-9]+)+(?:\:\d+)?(?:\/[\w\-]+)*(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/;
				validateName = /^[a-zA-Z]+$/;
				stopFlag = 0;

				if (tryLock("fName", validateName)) { checkName("fName"); stopFlag = 1; }
				if (tryLock("lName", validateName)) { checkName("lName"); stopFlag = 1; }
				if (tryLock("logoLink", validateURL)) { checkUrl("logoLink"); stopFlag = 1; }
				if (tryLock("socLink", validateURL)) { checkUrl("socLink"); stopFlag = 1; }

				if (stopFlag == 0) {
					document.getElementById("form-input").action = "confirmPartner.php";
					document.forms['form-input'].submit();
				}
			};
		}
	}
}