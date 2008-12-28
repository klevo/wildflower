$.jlm.component('WriteNew', 'wild_posts.wf_index, wild_posts.wf_edit', function() {
    
    $('#sidebar .add').click(function() {
        var buttonEl = $(this);
        var formAction = buttonEl.attr('href');
        var dialogEl = $($.jlm.template('posts/new_post', { action: formAction }));
        
        var contentEl = $('#content-pad');
        
        contentEl.append(dialogEl);
        
        var toHeight = 230;
        
        var hiddenContentEls = contentEl.animate({
            height: toHeight
        }, 1000).children().not(dialogEl).hide();
        
        return false;
    });
    
});