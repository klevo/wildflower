
$.jlm.bind('posts.admin_categorize', function() {
    
    // Add new category box AJAX form
    var buttonEl = $('#add-category-box .submit input');
    var formEl = $('#add-category-box form');
    var originalLabel;
    
    var successCallback = function(json) {
        buttonEl.attr('value', originalLabel).removeAttr('disabled');
        
        // Replace the list with updated one
        $('.category-list').before(json.list).remove();
        // Rebind delete links
        bindDeleteLinks();
        
        // Hight the added category

        //parentLiEl.after(json['category-list-item']).effect('highlight', {}, 4000);
    };
    
    var errorCallback = function(data) {
        alert('Error while saving. Check FireBug console for debug data.');
        if (typeof(console) == 'object') {
            console.debug(data);
        }
        buttonEl.attr('value', originalLabel).removeAttr('disabled');
    }
    
    formEl.submit(function() {
        originalLabel = buttonEl.attr('value');
        buttonEl.attr('value', 'Adding...').attr('disabled', 'disabled');

        formEl.ajaxSubmit({ dataType: 'json', success: successCallback, error: errorCallback });
        
        return false;
    });
    
    
    // Delete category
    function bindDeleteLinks() {
        $('.category-list .trash').click(function() {
            // Really?
            // @TODO add L18n
            if (!confirm('No posts will be deleted if you remove this category. Do you want to proceed?')) {
                return false;
            }
            
            var linkEl = $(this);
            var url = linkEl.attr('href');
            $.post(url, { _method: 'POST' });
            linkEl.parent('label').parent('li')
                .css({ backgroundColor: '#cf2a2a', color: '#fff' })
                .fadeOut(1000, function() {
                    $(this).remove();
                });
                
            // Update the parent category select box in the sidebar    
                
            return false;
        });
    }
    
    bindDeleteLinks();
    
});

$.jlm.bind('posts.admin_edit', function() {
    // Update post form on category select
    $('#category_id').change(function() {
        var id = $(this).val();
        $('#CategoryCategory').val(id);
    });
    
});