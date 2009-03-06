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
        
        // Hide main content
        var contentPadEl = $('#content-pad');
        var mainContent = contentPadEl.children();
        mainContent.hide();
        
        // Load the widget config action
        var widgetName = jEl.attr('id');
        widgetName = widgetName.replace('wf_widget_', '');
        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/widgets/' + widgetName + '_config/' + jEl.text();
        
        $.get(url, function(html) {
            var configEl = $(html);
            contentPadEl.append(configEl);
        });
	}
	
});
