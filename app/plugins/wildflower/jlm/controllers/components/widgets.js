$.jlm.addComponent('widgets', {
    
    edit: function(e) {
        var t = this;
        
        // First make sure this is a widget el
        var jEl = $(e);
        if (!jEl.hasClass('wf_widget')) {
            return false;
        }
        
        // Hide sidebar contet
        t.sidebarContent = $('#sidebar ul');
        t.sidebarContent.hide();
        
        // Hide main content
        t.contentPadEl = $('#content-pad');
        t.mainContent = t.contentPadEl.children();
        t.mainContent.hide();
        
        // Load the widget config action
        var widgetName = jEl.attr('id');
        widgetName = widgetName.replace('wf_widget_', '');
        var widgetId = jEl.attr('class').replace('wf_widget wf_widget_id_', '');
        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/widgets/' + widgetName + '_config/' + widgetId;
        
        $.get(url, function(html) {
            var configEl = $(html);
            t.contentPadEl.append(configEl);
            
            // Bind AJAX save
            function successCallback() {
                t.closeEdit();
                return false;
            }
            $('#edit_widget_form').ajaxForm({ success: successCallback });
            
            // Bind cancel button
            $('a[href=#CancelWidgetEdit]').click(successCallback);
        });
	},
	
	closeEdit: function() {
	    this.contentPadEl.children(':visible').remove();
	    this.sidebarContent.show();
	    this.mainContent.show();
	}
	
});
