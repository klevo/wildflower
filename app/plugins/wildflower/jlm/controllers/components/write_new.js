$.jlm.component('WriteNew', 'wild_posts.wf_index, wild_posts.wf_edit, wild_pages.wf_index, wild_pages.wf_edit', function() {
    
    $('#sidebar .add').click(function() {
        var buttonEl = $(this);
        var formAction = buttonEl.attr('href');
        
        var templatePath = 'posts/new_post';
        if ($.jlm.params.controller == 'wild_pages') {
            templatePath = 'pages/new_page';
        }
        
        var dialogEl = $($.jlm.template(templatePath, { action: formAction }));
        
        var contentEl = $('#content-pad');
        
        contentEl.append(dialogEl);
        
        var toHeight = 230;
        
        var hiddenContentEls = contentEl.animate({
            height: toHeight
        }, 600).children().not(dialogEl).hide();
        
        $('.input input', dialogEl).focus();
        
        // Bind cancel link
        $('.cancel-edit a', dialogEl).click(function() {
            dialogEl.remove();
            hiddenContentEls.show();
            contentEl.height('auto');
            return false;
        });
        
        // Create link
        $('.submit input', dialogEl).click(function() {
            $(this).attr('disabled', 'disabled').attr('value', '<l18n>Saving...</l18n>');
            return true;
        });
        
        return false;
    });
    
});