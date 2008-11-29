$.jlm.bind('wild_posts.wf_index', function() {
   
   // Page list controls
   $('#PostAction').change(function() {
       $('form:first').
       append('<input type="hidden" name="data[__action]" value="' + $(this).val() + '" />').
       submit();
   });
    
});

$.jlm.bind('wild_posts.wf_add', function() {
    
    $('#WildPostTitle').focus();
    
});
