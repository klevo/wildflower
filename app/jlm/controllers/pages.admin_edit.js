$.jlm.bind('pages.admin_edit', function() {
   
   $('textarea:first').focus();
   
   var origText = $('a[href=#ShowSidebarEditor]').text();
   $('a[href=#ShowSidebarEditor]').toggle(function() {
       $('.sidebar_editor').slideDown(500, function() {
           $('.sidebar_editor textarea').focus();
       });
       $(this).text('<l18n>Hide sidebar editor</l18n>');
       return false;
   }, function() {
       $('.sidebar_editor').hide();
       $(this).text(origText);
       return false;
   });
    
});