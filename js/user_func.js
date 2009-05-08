var browserWin="";
var closeAtBlur = false;
var lastFocus = false;
var closeAfterPrint = false;
var doAutoPrint = true;

function autoClose() {
	if (closeAtBlur) {
		close();
	}
}

function autoPrint() {
	if (doAutoPrint) {
		print ();
	}
	if (closeAfterPrint) {
		close();
	}
}

function setAutoClose(myValue) {
	closeAtBlur = myValue;
}

function setAutoPrintClose(myPrint,myClose) {
	doAutoPrint = myPrint;
	closeAfterPrint = myClose;
}

function openSpecialPdf(myValue) {
	if(typeof document.getElementsByName("sg_zlib_pdflink")[0] != 'undefined') {
		var input = document.getElementsByName("sg_zlib_pdflink")[0].value;
		if (input.length > 2) {
			nw = window.open(input,myValue,'');
			return (false);
		} else {
			return (true);
		}
	} else {
		return (true);
	}
}

function openPrintMode(myUrl,winMode,pluginName) {
	var pM = document.getElementsByName(pluginName)[0].value;
	window.open (myUrl+'&pM='+pM);
}

function sgPromt(prompt) {
	check = true;
	if (prompt.length > 0) {
		check = confirm (prompt);
	}
	return (check);
}

function sgGotoPage(url) {
	var Page = parseFloat('0'+document.getElementsByName("sg_gotopage")[0].value)-1;
	window.location = url+Page;
	return(false);
}

function changeImages(imgName,imgSource){if(document.images){document.images[imgName].src=imgSource;}}

