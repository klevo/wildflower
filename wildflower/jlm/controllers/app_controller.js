// Scripts executed globaly or with more controllers

$.jlm.bind('app_controller.beforeFilter', function () {
    
    $.jlm.components.tinyMce.startup();

	$('a.remove').click(function() {
		if (confirm("Are you sure you want to remove this item?")) {
			return true;
		}
		return false;
	});
    
});
