function _elp_addnotification() {
	if(document.form_addnotification.elp_note_emailgroup.value=="") {
		alert(elp_notification_script.elp_notification_group);
		document.form_addnotification.elp_note_emailgroup.focus();
		return false;
	}
	else if(document.form_addnotification.elp_note_status.value=="") {
		alert(elp_notification_script.elp_notification_status);
		document.form_addnotification.elp_note_status.focus();
		return false;
	}
	else if(document.form_addnotification.elp_note_mailsubject.value=="") {
		alert(elp_notification_script.elp_notification_subject);
		document.form_addnotification.elp_note_mailsubject.focus();
		return false;
	}
}

function _elp_delete(id) {
	if(confirm(elp_notification_script.elp_notification_delete)){
		document.frm_elp_display.action="admin.php?page=elp-postnotification&ac=del&did="+id;
		document.frm_elp_display.submit();
	}
}

function _elp_redirect() {
	window.location = "admin.php?page=elp-postnotification";
}

function _elp_help() {
	window.open("http://www.gopiplus.com/work/2014/05/02/email-subscribers-wordpress-plugin/");
}