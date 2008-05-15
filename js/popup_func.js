
function openerReload(closeMode) {
	if (window.opener)	{
		window.opener.location.reload();
	}
	if (closeMode) {
		close();
	}
}

function sgReLoadForm(prompt) {
	//alert ("sgReLoadForm: Prompt = ´"+prompt+"´");
	sgConfReload(prompt,'','','',0)
}

function sgUpdateForm(prompt) {
	//alert ("sgReLoadForm: Prompt = ´"+prompt+"´");
	sgConfReload(prompt,'','','',1)
}

function sgReLoadFormParams(params) {
	//alert ("sgReLoadForm: params = ´"+params+"´");
	document.sg_editform.dS.value=0;
	document.sg_editform.dR.value=params;
	document.sg_editform.submit();
}

function sgConfReload(prompt,delField,prefix,field,updatemode) {
	//alert ("sgConfReload: Prompt = ´"+prompt+"´<br />delField = ´"+delField+"´");
	check = true;
	var formObj = document.sg_editform;
	if (prompt.length>0 && (delField.length<1 || formObj[prefix+"[data]["+delField+"]"].value!=0 && formObj[prefix+"[data]["+delField+"]"].value!="")) {
		check = confirm (prompt);
	}
	if (check) {
		if (updatemode) {
			document.sg_editform.dS.value=1;
			document.sg_editform.dE.value=1;
			document.sg_editform.dR.value=0;
		} else {
			document.sg_editform.dS.value=0;
			document.sg_editform.dR.value=1;
			document.sg_editform.dE.value=0;
		}
		if (delField.length>1)
		{
			formObj[prefix+"[data]["+delField+"]"].value = '';
		}
		document.sg_editform.submit();
	} else if (prefix.length>0 && field.length>0) {
		var formObj = document.sg_editform;
		formObj[prefix+"[data]["+field+"]"].value = formObj[prefix+"[old]["+field+"]"].value;
	}
	return (check)
}

function doDeleteEntry(myPage,myUid,myText,myParams) {
	Check = confirm(myText+' (ID='+myUid+') ?');
	if(Check == true) {
		//alert ('Loeschen von Einträgen:'+"\r\n"+"myPage="+myPage+"\r\nmyUid="+myUid+"\r\nmyText="+myText+"\r\nmyParams="+myParams);
		document.location = myPage+'&no_cache=1&doDelete=1&uid='+myUid+myParams;
	}
}

function addFromCheck (myExtension,myName,p,np,j) {
	//alert ("addFromCheck !!\nmyExtension="+myExtension+"\nmyName="+myName+"\np="+p+"\nnp="+np+"\nj="+j);
	var formObj = document.sg_editform;

	var bitsum = formObj[myExtension+"[data]["+myName+"]"].value;
	if (document.getElementsByName(myExtension+"[bits]["+myName+"]["+j+"]")[0].checked)
		{ bitsum = bitsum | p; }
	else
		{ bitsum = bitsum & np; }
	formObj[myExtension+"[data]["+myName+"]"].value = bitsum;
}

function setFromCheck (myExtension,myName,bits,value) {
	var formObj = document.sg_editform;
	for (var i=1; i<=bits; i++)	{
		document.getElementsByName(myExtension+"[bits]["+myName+"]["+i+"]")[0].checked = true;
	}
	formObj[myExtension+"[data]["+myName+"]"].value = value;
}

function clearFromCheck (myExtension,myName,bits,value) {
	var formObj = document.sg_editform;
	for (var i=1; i<=bits; i++)	{
		document.getElementsByName(myExtension+"[bits]["+myName+"]["+i+"]")[0].checked = false;
	}
	formObj[myExtension+"[data]["+myName+"]"].value = value;
}


function addFromBrowser(brPath,params,winparams) {
	//alert ("addFromBrowser !!\nbrPath="+brPath+"\nparams="+params+"\nwinparams="+winparams);
	var url = brPath+"&params="+params;
	if (winparams.length<1)
		{ winparams = "height=440,width=400,status=1,menubar=0,resizable=1,scrollbars=1"; }
	if (url.length>1900)
		{ alert ("WARNING for IE: url.length>1900: "+url.length+"\nFile: popup_func/addFromBrowser\n"); }
	browserWin = window.open(url,"Typo3SgFeBrowser",winparams);
	browserWin.focus();
}



function setOpenerElement(myExtension,myName,myText)	{	//
	if (window.opener && window.opener.addItem)	{
		window.opener.addItem(myExtension,myName,0,myText);
		window.opener.focus();
		close();
	} else {
		alert("Error - reference to main window is not set properly!");
	}
}


function addFromTextInput(myExtension,myName) {
	//alert ("addFromTextInput !! myExtension="+myExtension+" myName="+myName);
	var newtext = document.getElementsByName('myAddTextToList')[0].value;
	//alert ("text="+newtext+" len="+newtext.length);
	addItem (myExtension,myName,0,newtext);
	document.getElementsByName('myAddTextToList')[0].value = '';
}


function addItem(myExtension,myName,type,addText) {
	var position=null;
	var formObj = getFObj(myExtension,myName)
	if (formObj)	{
		// alert ("addItem !! myExtension="+myExtension+" myName="+myName+" type="+type+" addText="+addText);
		var newid =document.getElementsByName(myExtension+"[list]["+myName+"]")[0].length
		if (document.all) position=newid;
		var Eintrag = document.createElement("option");
		Eintrag.text = addText;
		Eintrag.value = newid;
		
		if (type==1) {
			document.getElementsByName(myExtension+"[list]["+myName+"]")[0].remove(position);
			document.getElementsByName(myExtension+"[list]["+myName+"]")[0].add(Eintrag,position);
		} else {
			document.getElementsByName(myExtension+"[list]["+myName+"]")[0].add(Eintrag,position);
		}
		setHiddenFromList(formObj[myExtension+"[list]["+myName+"]"],formObj[myExtension+"[data]["+myName+"]"]);
	}
}


function addIdTextItem(myExtension,myName,type,addText,addId) {
	var position=null;
	var formObj = getFObj(myExtension,myName)
	if (formObj)	{
		//alert ("addItem !! myExtension="+myExtension+" myName="+myName+" type="+type+" addText="+addText);
		var newid =document.getElementsByName(myExtension+"[list]["+myName+"]")[0].length
		if (document.all) position=newid;
		var Eintrag = document.createElement("option");
		Eintrag.text = addText;
		Eintrag.value = addId;

		if (type==1) {
			document.getElementsByName(myExtension+"[list]["+myName+"]")[0].remove(position);
			document.getElementsByName(myExtension+"[list]["+myName+"]")[0].add(Eintrag,position);
		} else {
			document.getElementsByName(myExtension+"[list]["+myName+"]")[0].add(Eintrag,position);
		}
		setHiddenIdFromList(formObj[myExtension+"[list]["+myName+"]"],formObj[myExtension+"[data]["+myName+"]"]);
	}
}


function modifyItem (myExtension,myName,type) {
	var position=null;


	//alert ("Modify !! myExtension="+myExtension+" myName="+myName+" type="+type);

	var formObj = getFObj(myExtension,myName)
	if (formObj)	{
		var localArray_V = new Array();
		var localArray_L = new Array();
		var fObjSel = formObj[myExtension+"[list]["+myName+"]"];
		var l=fObjSel.length;
		var c=0;
		var cS=0;

		if (type=="Remove" || type=="Up")	{
			if (type=="Up")	{
				for (a=0;a<l;a++)	{
					if (fObjSel.options[a].selected==1)	{
						localArray_V[c]=fObjSel.options[a].value;
						localArray_L[c]=fObjSel.options[a].text;
						c++;
						cS++;
					}
				}
			}
			for (a=0;a<l;a++)	{
				if (fObjSel.options[a].selected!=1)	{
					localArray_V[c]=fObjSel.options[a].value;
					localArray_L[c]=fObjSel.options[a].text;
					c++;
				}
			}
		}

		fObjSel.length = c;
		for (a=0;a<c;a++)	{
			fObjSel.options[a].value = localArray_V[a];
			fObjSel.options[a].text = localArray_L[a];
			fObjSel.options[a].selected=(a<cS)?1:0;
		}
		setHiddenFromList(fObjSel,formObj[myExtension+"[data]["+myName+"]"]);

		//TBE_EDITOR_fieldChanged_fName(fName,formObj[fName+"_list"]);
	}
}


function modifyIdItem (myExtension,myName,type) {
	var position=null;


	//alert ("Modify !! myExtension="+myExtension+" myName="+myName+" type="+type);

	var formObj = getFObj(myExtension,myName)
	if (formObj)	{
		var localArray_V = new Array();
		var localArray_L = new Array();
		var fObjSel = formObj[myExtension+"[list]["+myName+"]"];
		var l=fObjSel.length;
		var c=0;
		var cS=0;

		if (type=="Remove" || type=="Up")	{
			if (type=="Up")	{
				for (a=0;a<l;a++)	{
					if (fObjSel.options[a].selected==1)	{
						localArray_V[c]=fObjSel.options[a].value;
						localArray_L[c]=fObjSel.options[a].text;
						c++;
						cS++;
					}
				}
			}
			for (a=0;a<l;a++)	{
				if (fObjSel.options[a].selected!=1)	{
					localArray_V[c]=fObjSel.options[a].value;
					localArray_L[c]=fObjSel.options[a].text;
					c++;
				}
			}
		}

		fObjSel.length = c;
		for (a=0;a<c;a++)	{
			fObjSel.options[a].value = localArray_V[a];
			fObjSel.options[a].text = localArray_L[a];
			fObjSel.options[a].selected=(a<cS)?1:0;
		}
		setHiddenIdFromList(fObjSel,formObj[myExtension+"[data]["+myName+"]"]);

		//TBE_EDITOR_fieldChanged_fName(fName,formObj[fName+"_list"]);
	}
}


function getFObj(myExtension,myName)	{	//
	var formObj = document.sg_editform;
	if (formObj)	{
		if (formObj[myExtension+"[data]["+myName+"]"] && formObj[myExtension+"[list]["+myName+"]"] && formObj[myExtension+"[list]["+myName+"]"].type=="select-multiple")	{
			return formObj;
		} else {	
			alert("Formfields missing:\n fName: "+formObj[myExtension+"[data]["+myName+"]"]+"\n fName_list:"+formObj[myExtension+"[list]["+myName+"]"]+"\n type:"+formObj[myExtension+"[list]["+myName+"]"].type+"\n fName:"+myExtension+"[list]["+myName+"]");
		}
	}
	return "";
}

function setHiddenFromList(fObjSel,fObjHid)	{	//
	l=fObjSel.length;
	fObjHid.value="";
	for (a=0;a<l;a++)	{
		if (a==0)
			{ fObjHid.value=fObjSel.options[a].text; }
		else
			{ fObjHid.value+=","+fObjSel.options[a].text; }
	}
}



function setHiddenIdFromList(fObjSel,fObjHid)	{	//
	l=fObjSel.length;
	fObjHid.value="";
	for (a=0;a<l;a++)	{
		if (a==0)
			{ fObjHid.value=fObjSel.options[a].value; }
		else
			{ fObjHid.value+=","+fObjSel.options[a].value; }
	}
}

function getListFromArray(target,myarray)	{	//
	var formObj = document.sg_editform;
	if (formObj)	{
		var liste = document.getElementsByName(myarray)
		var l=liste.length;
		var n=0;
		var result="";
		for (a=0;a<l;a++) if (liste[a].checked)	{
			if (n==0)
				{ n=1; result=liste[a].value;}
			else
				{ result+=","+liste[a].value;}
		}
	}
	formObj[target].value = result;
	return (false);
}


function setFormValueManipulate(fName,type)	{	//
	var formObj = setFormValue_getFObj(fName)
	if (formObj)	{
		var localArray_V = new Array();
		var localArray_L = new Array();
		var fObjSel = formObj[fName+"_list"];
		var l=fObjSel.length;
		var c=0;
		var cS=0;
		if (type=="Remove" || type=="Up")	{
			if (type=="Up")	{
				for (a=0;a<l;a++)	{
					if (fObjSel.options[a].selected==1)	{
						localArray_V[c]=fObjSel.options[a].value;
						localArray_L[c]=fObjSel.options[a].text;
						c++;
						cS++;
					}
				}
			}
			for (a=0;a<l;a++)	{
				if (fObjSel.options[a].selected!=1)	{
					localArray_V[c]=fObjSel.options[a].value;
					localArray_L[c]=fObjSel.options[a].text;
					c++;
				}
			}
		}
		fObjSel.length = c;
		for (a=0;a<c;a++)	{
			fObjSel.options[a].value = localArray_V[a];
			fObjSel.options[a].text = localArray_L[a];
			fObjSel.options[a].selected=(a<cS)?1:0;
		}
		setHiddenFromList(fObjSel,formObj[fName]);

		//TBE_EDITOR_fieldChanged_fName(fName,formObj[fName+"_list"]);
	}
}

function setFormValue_getFObj(fName)	{	//
	var formObj = document.sg_editform;
	if (formObj)	{
		if (formObj[fName] && formObj[fName+"_list"] && formObj[fName+"_list"].type=="select-multiple")	{
			return formObj;
		} else {	
			alert("Formfields missing:\n fName: "+formObj[fName]+"\n fName_list:"+formObj[fName+"_list"]+"\n type:"+formObj[fName+"_list"].type+"\n fName:"+fName);
		}
	}
	return "";
}

function exportDataParaSg(exPath,params) {
	//alert ("exportData\n\n"+"exPath="+exPath+"\n"+"params="+params+"\n");

	var formObj = document.sg_exportform;
	exportmode = 'uid';
	if (formObj)	{
		exportmode = formObj["exportmode"].value;
	}

	var url = exPath+"?PARASG="+params+"&ex="+exportmode;
	winparams = "height=440,width=400,status=1,menubar=0,resizable=1,scrollbars=1";
	browserWin = window.open(url,"Typo3SgFeExport",winparams);
	browserWin.focus();

	return "";
}

function sgSelectMultiChange (myExtension,myName,smode) {
	var formObj = document.forms["search"+smode];
	if (formObj)	{
		//alert ("addItem !! myExtension="+myExtension+" myName="+myName);
		var fObjSel = formObj[myExtension+"[searchmulti]["+myName+"]"];
		var l=fObjSel.length;
		sum = '';
		for (a=0;a<l;a++)	{
			if (fObjSel.options[a].selected==1)	{
				sum=sum+fObjSel.options[a].value+",";
			}
		}
		if (sum.length>1) {
			sum = sum.substr(0,sum.length-1);
		}
		formObj[myExtension+"[search]["+myName+"]"].value = sum;
	}
}


function insertText (text,warn) {
	var aTag = '';
	var eTag = '';
	var name = '';
	if (lastFocus) {
		name = lastFocus.name;
		var input = document.forms['sg_editform'].elements[name];
		input.focus();

		var pipe = text.split("|",3);
		aTag = pipe[0];
		eTag = pipe[1];
 

		/* für Internet Explorer */
		if(typeof document.selection != 'undefined') {
			/* Einfügen des Formatierungscodes */
			var range = document.selection.createRange();
			var insText = range.text;
			range.text = aTag + insText + eTag;
			/* Anpassen der Cursorposition */
			range = document.selection.createRange();
			if (insText.length == 0) {
			  range.move('character', -eTag.length);
			} else {
			  range.moveStart('character', aTag.length + insText.length + eTag.length);      
			}
			range.select();
		}
		/* für neuere auf Gecko basierende Browser */
		else if(typeof input.selectionStart != 'undefined') {
			/* Einfügen des Formatierungscodes */
			var start = input.selectionStart;
			var end = input.selectionEnd;
			var insText = input.value.substring(start, end);
			input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);
			/* Anpassen der Cursorposition */
			var pos;
			if (insText.length == 0) {
			  pos = start + aTag.length;
			} else {
			  pos = start + aTag.length + insText.length + eTag.length;
			}
			input.selectionStart = pos;
			input.selectionEnd = pos;
		}
		/* für die übrigen Browser */
		else {
			/* Abfrage der Einfügeposition */
			input.value = input.value + aTag + eTag;
		}

		//alert ("insertWrap("+aTag+","+eTag+")\nLastfocus='"+name+"'\n");
	} else if (warn.length>0) {
		alert (warn);
	}
}

function insertSelect (selector,warn) {
		var num = selector.selectedIndex;
		selector.selectedIndex = 0;
		var text = selector.options[num].value;
		if (text!="-" && text!="") {
			insertText (text+"|",warn);
			// alert ("insertSelect("+selector+","+warn+")\nnum="+num+" text="+text);
		}
}


function insertFromDB (warn,brPath,params,winparams) {
	if (lastFocus) {
		var url = brPath+"&params="+params;
		//alert ("DB-Insert Tag; URL="+url);
		if (winparams.length<1)
			{ winparams = "height=440,width=400,status=1,menubar=0,resizable=1,scrollbars=1"; }
		if (url.length>1900)
			{ alert ("WARNING for IE: url.length>1900: "+url.length+"\nFile: popup_func/addFromBrowser\n"); }
		browserWin = window.open(url,"Typo3SgFeBrowser",winparams);
		browserWin.focus();
	} else if (warn.length>0) {
		alert (warn);
	}
}


