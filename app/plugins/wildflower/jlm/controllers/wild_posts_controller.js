$.jlm.bind('wild_posts.wf_edit', function() {
    
    $('#WildPostTitle').focus();
    
    var activeSectionEl = $('#title-content');
    
    // Section switching
    $('.sidebar-menu a').click(function() {
        $('.sidebar-menu .current').removeClass('current');
        
        var linkEl = $(this);
        linkEl.addClass('current');
        
        activeSectionEl.slideUp(300, function() {
            var switchToSectionId = '#' + linkEl.attr('rel');
            var switchToSectionEl = $(switchToSectionId);
            switchToSectionEl.slideDown(300);

            activeSectionEl = switchToSectionEl;
        });
        
        return false;
    });
    
});
