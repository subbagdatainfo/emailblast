function _elp_delete(id) {
	if(confirm(elp_sentmail_script.elp_sentmail_delete))
	{
		document.frm_elp_display.action="admin.php?page=elp-sentmail&ac=del&did="+id;
		document.frm_elp_display.submit();
	}
}

function _elp_redirect() {
	window.location = "admin.php?page=elp-sentmail";
}

function _elp_help() {
	window.open("http://www.gopiplus.com/work/2014/03/28/wordpress-plugin-email-posts-to-subscribers/");
}

function _elp_bulkaction() {
	if(document.frm_elp_display.action.value == "optimize-table") {
		if(confirm(elp_sentmail_script.elp_sentmail_delete_all)) {
			document.frm_elp_display.frm_elp_bulkaction.value = 'delete';
			document.frm_elp_display.action="admin.php?page=elp-sentmail&bulkaction=delete";
			document.frm_elp_display.submit();
		}
	}
}