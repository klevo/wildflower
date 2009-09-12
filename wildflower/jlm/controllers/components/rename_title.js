$.jlm.component('RenameTitle', 'pages.admin_edit, posts.admin_edit', function() {
    
    $('.rename_title').click(function() {
        $('.title_as_heading').hide();
        $('.rename_title_section').show().find('input:first').focus();
        return false;
    });
    
    $('.rename_cancel .cancel').click(function() {
        $('.title_as_heading').show();
        $('.rename_title_section').hide();
        return false;
    });
    
    // Save...
    
});