$.jlm.bind('settings.admin_index', function() {
    
    function showHideSmtpOptions() {
		var method = $('#setting-email_delivery select').val();
        var smtpEls = $('#setting-smtp_server, #setting-smtp_username, #setting-smtp_password');
        if (method != 'smtp') {
            smtpEls.hide();
        } else {
            smtpEls.show();
        }
	}
    
    showHideSmtpOptions();
	$('#setting-email_delivery select').change(showHideSmtpOptions);
	
});
