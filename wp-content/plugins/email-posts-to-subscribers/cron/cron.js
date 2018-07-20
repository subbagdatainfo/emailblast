function _elp_submit() {
	if(document.elp_form.elp_cron_mailcount.value == "") {
		alert(elp_crondetails_script.elp_crondetails_number1)
		document.elp_form.elp_cron_mailcount.focus();
		return false;
	}
	else if(isNaN(document.elp_form.elp_cron_mailcount.value)) {
		alert(elp_crondetails_script.elp_crondetails_number2)
		document.elp_form.elp_cron_mailcount.focus();
		return false;
	}
}

function _elp_redirect() {
	window.location = "admin.php?page=elp-crondetails";
}

function _elp_help() {
	window.open("http://www.gopiplus.com/work/2014/05/02/email-subscribers-wordpress-plugin/");
}