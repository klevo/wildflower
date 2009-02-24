$.jlm.component('RenameTitle', 'wild_pages.wf_edit, wild_posts.wf_edit', function() {
    
    $('.rename_title').click(function() {
        $('.title_as_heading').hide();
        $('.rename_title_section').show();
        return false;
    })
    
});