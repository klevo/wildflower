$.jlm.component('EditButtons', 'posts.admin_edit, posts.admin_categorize, pages.admin_edit', function() {
    
    // Save buttons
    $('#save-draft input, #save-publish input').click(editButtonsOnClick);
    
    function editButtonsOnClick() {
        buttonEl = $(this);
        var originalLabel = buttonEl.attr('value');
        buttonEl.attr('value', 'Saving...').attr('disabled', 'disabled');
        
        var isPublish = (buttonEl.parent().attr('id') == 'save-publish');
        if (isPublish) {
            $('#PageDraft, #PostDraft').val('0');
        }
        
        // Do AJAX save
        // Save content back to textareas
        tinyMCE.triggerSave();

        // Do AJAX form submit
        var successCallback = function(json) {
            buttonEl.attr('value', originalLabel).removeAttr('disabled');

            // Update post info
            $('.post-info').html(json['post-info']).effect('highlight', {}, 4000);
            
            // Update buttons
            $('#edit-buttons').html(json['edit-buttons']);
            
            // Rebind
            $('.submit input').click(editButtonsOnClick);
        };
        
        var errorCallback = function(data) {
            alert('Error while saving. Check FireBug console for debug data.');
            if (typeof(console) == 'object') {
                console.debug(data);
            }
            buttonEl.attr('value', originalLabel).removeAttr('disabled');
        }
        
        var formEl = buttonEl.parents('form').eq(0);
        formEl.ajaxSubmit({ dataType: 'json', success: successCallback, error: errorCallback });
        
        return false;
    }
    
});