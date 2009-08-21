$.jlm.addComponent('widgets', {
    
    edit: function(e) {
        var t = this;
        
        // First make sure this is a widget el
        var jEl = $(e);
        if (!jEl.hasClass('admin_widget')) {
            return false;
        }
        
        // Hide sidebar contet
        t.sidebarContent = $('#sidebar ul');
        t.sidebarContent.hide();
        
        // Hide main content
        t.contentPadEl = $('#content_pad');
        t.mainContent = t.contentPadEl.children();
        t.mainContent.hide();
        
        // Load the widget config action
        var widgetName = jEl.attr('id');
        var widgetId = jEl.attr('class').replace('admin_widget admin_widget_id_', '');
        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/widgets/config/' + widgetName + '/' + widgetId;
        
        $.post(url, function(html) {
            var configEl = $(html);
            t.contentPadEl.append(configEl);
            
            // Bind AJAX save
            function successCallback() {
                t.closeEdit();
                return false;
            }
            $('#edit_widget_form').ajaxForm({ success: successCallback });
            
            // Bind cancel button
            $('#CancelWidgetEdit').click(successCallback);
            
            // Add new
            //$('a[href=#AddNewCell]').click(t.addNewCell);
        });
	},
	
	addNewCell: function() {
	    var newBlockEl = $('.slider_block:first').clone();
	    var index = $('.slider_block').size();
	    $('input:first', newBlockEl).val('').attr('name', 'data[Widget][items][' + index + '][label]');
	    $('input:last', newBlockEl).val('').attr('name', 'data[Widget][items][' + index + '][url]');
        // newBlockEl = '<div class="slider_block">' + newBlockEl.html() + '</div>';
        
        // newBlockEl = newBlockEl.replace('0', index.toString());
	    $('.slider_block:last').after(newBlockEl);
	},
	
	closeEdit: function() {
        this.contentPadEl.children(':visible').remove();
        this.sidebarContent.show();
        this.mainContent.show();
        $.jlm.components.tinyMce.resizeToFillScreen($('.tinymce'));
	}
	
});
