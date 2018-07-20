function _elp_redirect() {
	window.location = "admin.php?page=elp-sendemail";
}

function _elp_help() {
	window.open("http://www.gopiplus.com/work/2014/03/28/wordpress-plugin-email-posts-to-subscribers/");
}

function _elp_checkall(FormName, FieldName, CheckValue) {
	
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}

function _elp_mailsubject(guid) {
	document.getElementById("elp_set_guid").value = guid;
	document.getElementById("elp_sent_type").value = document.elp_form.elp_sent_type.value;	
	document.getElementById("elp_sent_group").value = document.elp_form.elp_sent_group.value;
	document.getElementById("sendmailsubmit").value = "no";
	document.getElementById("wp_create_nonce").value = document.elp_form.wp_create_nonce.value;
	document.getElementById("elp_search_query").value = document.elp_form.elp_search_query.value;
	document.elp_form.action="admin.php?page=elp-sendemail";
	document.elp_form.submit();
}

function _elp_sendemailsearch(elp_search_query) {
	document.getElementById("elp_set_guid").value = document.elp_form.elp_set_guid.value;
	document.getElementById("elp_sent_type").value = document.elp_form.elp_sent_type.value;	
	document.getElementById("elp_sent_group").value = document.elp_form.elp_sent_group.value;
	document.getElementById("sendmailsubmit").value = "no";
	document.getElementById("wp_create_nonce").value = document.elp_form.wp_create_nonce.value;
	document.getElementById("elp_search_query").value = elp_search_query;
	document.elp_form.action="admin.php?page=elp-sendemail";
	document.elp_form.submit();
}

function _elp_submit() {
	if(document.elp_form.elp_set_guid.value=="") {
		alert(elp_sendemail_script.elp_sendemail_configuration)
		document.elp_form.elp_set_guid.focus();
		return false;
	}
	
	if(document.elp_form.elp_sent_type.value=="") {
		alert(elp_sendemail_script.elp_sendemail_mailtype)
		document.elp_form.elp_sent_type.focus();
		return false;
	}
	
	if(confirm(elp_sendemail_script.elp_sendemail_confirm)) {
		document.getElementById("elp_set_guid").value = document.elp_form.elp_set_guid.value;
		document.getElementById("elp_sent_type").value = document.elp_form.elp_sent_type.value;	
		document.getElementById("elp_sent_group").value = document.elp_form.elp_sent_group.value;
		document.getElementById("wp_create_nonce").value = document.elp_form.wp_create_nonce.value;
		document.getElementById("elp_search_query").value = document.elp_form.elp_search_query.value;
		document.getElementById("sendmailsubmit").value = "yes";
		document.elp_form.submit();
	}
	else {
		return false;
	}
}

function _elp_mailgroup(es_email_group) {
	document.getElementById("elp_set_guid").value = document.elp_form.elp_set_guid.value;
	document.getElementById("elp_sent_type").value = document.elp_form.elp_sent_type.value;	
	document.getElementById("elp_sent_group").value = document.elp_form.elp_sent_group.value;
	document.getElementById("sendmailsubmit").value = "no";
	document.getElementById("wp_create_nonce").value = document.elp_form.wp_create_nonce.value;
	document.getElementById("elp_search_query").value = document.elp_form.elp_search_query.value;
	document.elp_form.action="admin.php?page=elp-sendemail";
	document.elp_form.submit();
}

function _elp_newsletter_submit() {
	if(document.elp_form.elp_set_templid.value=="") {
		alert(elp_sendnewsletter_script.elp_sendnewsletter_newsletter)
		document.elp_form.elp_set_templid.focus();
		return false;
	}
	
	if(document.elp_form.elp_sent_type.value=="") {
		alert(elp_sendnewsletter_script.elp_sendnewsletter_mailtype)
		document.elp_form.elp_sent_type.focus();
		return false;
	}
	
	if(document.elp_form.elp_sent_group.value=="") {
		alert(elp_sendnewsletter_script.elp_sendnewsletter_group)
		document.elp_form.elp_sent_group.focus();
		return false;
	}
	
	if(confirm(elp_sendnewsletter_script.elp_sendnewsletter_confirm)) {
		document.getElementById("elp_set_templid").value = document.elp_form.elp_set_templid.value;
		document.getElementById("elp_sent_type").value = document.elp_form.elp_sent_type.value;	
		document.getElementById("elp_sent_group").value = document.elp_form.elp_sent_group.value;
		document.getElementById("wp_create_nonce").value = document.elp_form.wp_create_nonce.value;
		document.getElementById("sendmailsubmit").value = "yes";
		document.elp_form.submit();
	}
	else {
		return false;
	}
}