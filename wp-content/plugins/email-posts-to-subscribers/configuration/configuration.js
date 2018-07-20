function _elp_submit() {
	if(document.elp_form.elp_set_name.value=="") {
		alert(elp_configuration_script.elp_configuration_mailsub);
		document.elp_form.elp_set_name.focus();
		return false;
	}
	else if(document.elp_form.elp_set_templid.value=="") {
		alert(elp_configuration_script.elp_configuration_mailtemp);
		document.elp_form.elp_set_templid.focus();
		return false;
	}
}

function _elp_delete(id) {
	if(confirm(elp_configuration_script.elp_configuration_delete)) {
		document.frm_elp_display.action="admin.php?page=elp-configuration&ac=del&did="+id;
		document.frm_elp_display.submit();
	}
}

function _elp_redirect() {
	window.location = "admin.php?page=elp-configuration";
}

function _elp_help() {
	window.open("http://www.gopiplus.com/work/2014/03/28/wordpress-plugin-email-posts-to-subscribers/");
}