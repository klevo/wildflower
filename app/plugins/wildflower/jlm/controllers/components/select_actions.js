/**
 * Select Actions Component
 *
 * Used on lists with checkboxes. On checking some, action menus pop up.
 */
$.jlm.component('SelectActions', 'wild_posts.wf_index, wild_pages.wf_index', function() {
    
     var selectActionsEl = $('.select-actions');

     function selectionChanged() {
         var selected = $('input:checked');
         
         if (selected.size() > 0) {
             selectActionsEl.slideDown(100);
         } else {
             selectActionsEl.slideUp(100);
         }
         
         return true;
     }

     $('input[@type=checkbox]').click(selectionChanged);
     
});
