// Scripts executed globaly or with more controller

$.jlm.bind('app_controller.beforeFilter', function () {
    
    $.jlm.components.list.startup();
    $.jlm.components.nameNew.startup();
    $.jlm.components.switcher.startup();

});

$.jlm.bind('wild_pages.wf_edit, wild_posts.wf_edit', function() {
    
    $.jlm.components.tinyMce.startup();

    // Save button
    $('#save').click(function() {
        // Disable button
        var buttonEl = $(this);
        //buttonEl.attr('disabled', 'disabled');

        // Save content back to textareas
        tinyMCE.triggerSave();

        // Do AJAX form submit
        var formEl = $('form');
        var removeTimeout = null;
        
        var saved = function(response) {
            // Update time
            $('#modified-time').html(response.time).attr('title', response.fullTime).effect('highlight', {}, 2000);
            
            // Add flash messsage
            // First remove any old ones
            $('.message').remove();
            var say = 'Succefuly saved at ' + response.fullTime + '.';
            var flashMessage = $($.jlm.template('elements/flash_message', { text: say })).hide();
            $('#sub-nav').after(flashMessage);
            flashMessage.slideDown();
            setTimeout(function() {
               flashMessage.animate({
                   backgroundColor: '#eee',
                   color: '#535353',
                   borderColor: '#ddd'
               }, 1000); 
            }, 3000);

            // Update revision list
            var currentVersionStringEl = $('.current-revision');
            if (currentVersionStringEl.size() > 0) {
                var lastRevNumber = $('.revision-list li:first a').text().split(' ');
                lastRevNumber = lastRevNumber.pop();
                if (lastRevNumber != response.revNumber) {
                    currentVersionStringEl.remove();
                    $('.revision-list').prepend(response.revision);
                }
            }
        };
        
        // On form submit do AJAX save
        formEl.ajaxSubmit({ 
            dataType: 'json', 
            success: saved 
        });

        return false;
    });
    
    // Sub navigation
    $('.sub-nav-options a').click(function() {
		// Hide editor
		$('.title-input, .editor, #revisions, #sidebar-editor, #post-categories').hide();
		$('#preview').remove();
		$('#advanced-options, .big-submit').show();
		
		$('#sub-nav .current').removeClass('current');
		$(this).parent().addClass('current');
		
		return false;
	});
	
	$('.sub-nav-revisions a').click(function() {
        // Hide editor
        $('.title-input, .editor, #advanced-options, .big-submit, #sidebar-editor, #post-categories').hide();
		$('#preview').remove();
        $('#revisions').show();
		
        $('#sub-nav .current').removeClass('current');
        $(this).parent().addClass('current');
		
        return false;
    });
	
	$('.sub-nav-title-and-content a, .sub-nav-post-edit a').click(function() {
        // Hide editor
        $('#revisions, #advanced-options, #sidebar-editor, #post-categories').hide();
		$('#preview').remove();
        $('.title-input, .editor, .big-submit').show();
		
        $('#sub-nav .current').removeClass('current');
        $(this).parent().addClass('current');
		
        return false;
    });
    
	$('.sub-nav-sidebar a').click(function() {
        // Hide editor
        $('.title-input, .editor, #revisions, #advanced-options, #post-categories').hide();
		$('#preview').remove();
        $('#sidebar-editor, #sidebar-editor .editor, .big-submit').show();
		
        $('#sub-nav .current').removeClass('current');
        $(this).parent().addClass('current');
		
        return false;
    });
    
	$('.sub-nav-categories a').click(function() {
        $('.title-input, .editor, #revisions, #advanced-options').hide();
		$('#preview').remove();
        $('#post-categories, .big-submit').show();
		
        $('#sub-nav .current').removeClass('current');
        $(this).parent().addClass('current');
		
        return false;
    });
	
    $('.sub-nav-preview a').click(function() {
		$('#sub-nav .current').removeClass('current');
        $(this).parent().addClass('current');
		
		$('#preview').remove();
		
        // Save content back to textareas
        tinyMCE.triggerSave();
        
        // Post data to admin_create_preview
        var controller = $.jlm.params.controller.replace('wild_', '');
        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/' + controller + '/create_preview';
        var callback = function(json) {
			var contentHeight = $('.editor').height();
			
            var previewFileName = json.previewFileName;
            var iframeSrc = $.jlm.base + '/' + $.jlm.params.prefix + '/' + controller + '/preview/' + previewFileName;
            var dialog = $($.jlm.template('elements/preview', { iframeSrc: iframeSrc }));
            $('.title-input, .editor, #advanced-options, #revisions, #sidebar-editor, #post-categories').hide();
			$('.big-submit').show();				
            $('#sub-nav').after(dialog);
			
			// Height
			dialog.height(contentHeight + 120);
        };
        
        $('form').ajaxSubmit({
            url: url,
            success: callback,
            dataType: 'json'
        });
        
        return false;
    });

});
