$.jlm.addComponent('widgets', {
    
    edit: function(e) {
        // First make sure this is a widget el
        var jEl = $(e);
        if (!jEl.hasClass('wf_widget')) {
            return false;
        }
        
        // Hide sidebar contet
        var sidebarContent = $('#sidebar ul');
        sidebarContent.hide();
	}
	
});
