
function sgReLoadSearchForm(prompt,prefix,smode,id) {
	//alert ("Prompt = "+prompt+"<br />Prefix = "+prefix);
	var formObj = document.forms["search"+smode];
	var reloadObj = document.forms["reload"+smode];
	formObj[prefix+"[searchmode]"].value = 2;
	formObj["id"].value = id;
	formObj.action=reloadObj["doReload"].value;
	formObj.submit();
}

function sgDelSearchForm(prefix,smode,delField) {
	//alert ("sgDelSearchForm - prefix='"+prefix+"' \nsmode='"+smode+"' \nsmode='"+delField+"'");
	var formObj = document.forms["search"+smode];
	formObj[prefix+"[search]["+delField+"]"].value = '';
}

function sgZeroSearchForm(prefix,smode,delField) {
	//alert ("sgZeroSearchForm - prefix='"+prefix+"' \nsmode='"+smode+"' \nsmode='"+delField+"'");
	var formObj = document.forms["search"+smode];
	formObj[prefix+"[search]["+delField+"]"].value = 0;
}

function sgDisableSearchForm(prefix,smode,disField) {
	//alert ("sgDisableSearchForm - prefix='"+prefix+"' \nsmode='"+smode+"' \nsmode='"+disField+"'");
	var formObj = document.forms["search"+smode];
	formObj[prefix+"[search]["+disField+"]"].disabled = true;
}

function sgReLoadConfSearchForm(prompt,prefix,field) {
	check = true;
	if (prompt.length>0) {
		check = confirm (prompt);
	}
	return (check);
}

function sgAbcSubmit(text,prefix,smode,field) {
	var formObj = document.forms["search"+smode];
	formObj[prefix+"[search]["+field+"]"].value = text;
	//alert ("Text='"+text+"'\nPrefix='"+prefix+"'\nSmode='"+smode+"'\nformObj="+formObj);
	formObj.submit();
	return (false);
}
