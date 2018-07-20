function _elp_submit() {
	if(document.elp_form.elp_templ_heading.value=="") {
		alert(elp_template_script.elp_template_heading)
		document.elp_form.elp_templ_heading.focus();
		return false;
	}
}

function _elp_delete(id) {
	if(confirm(elp_template_script.elp_template_delete)) {
		document.frm_elp_display.action="admin.php?page=elp-email-template&ac=del&did="+id;
		document.frm_elp_display.submit();
	}
}

function _elp_newsletter_submit() {
	if(document.elp_form.elp_templ_heading.value=="") {
		alert(elp_composenewsletter_script.elp_composenewsletter_heading)
		document.elp_form.elp_templ_heading.focus();
		return false;
	}
}

function _elp_newsletter_delete(id) {
	if(confirm(elp_composenewsletter_script.elp_composenewsletter_delete)) {
		document.frm_elp_display.action="admin.php?page=elp-composenewsletter&ac=del&did="+id;
		document.frm_elp_display.submit();
	}
}

function _elp_redirect() {
	window.location = "admin.php?page=elp-email-template";
}

function _elp_newsletter_redirect() {
	window.location = "admin.php?page=elp-composenewsletter";
}

function _elp_help() {
	window.open("http://www.gopiplus.com/work/2014/03/28/wordpress-plugin-email-posts-to-subscribers/");
}