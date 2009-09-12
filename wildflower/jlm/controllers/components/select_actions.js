/**
 * Select Actions Component
 *
 * Used on lists with checkboxes. On checking some, action menus pop up.
 */
$.jlm.component('SelectActions', '*', function() {
    
     var selectActionsEl = $('.select-actions');
     var handledFormEl = $('form:first');
     
     // Mark all selectedEls items
     var selectedEls = $('input:checked', handledFormEl);
     if (selectedEls.size() > 0) {
         selectedEls.parent().parent('li').addClass('selected');
         selectActionsEl.show();
     }

     function selectionChanged() {
         selectedEls = $('input:checked', handledFormEl);
         
         if (selectedEls.size() > 0) {
             selectActionsEl.slideDown(100);
         } else {
             selectActionsEl.slideUp(100);
         }
         
         // Add selectedEls class
         $(this).parent().parent('li').toggleClass('selected');
         
         return true;
     }

     $('input[type=checkbox]', handledFormEl).click(selectionChanged);
     
     function deleteChecked() {
         var checkboxEl = $('input:checked');
         $(checkboxEl).each(function() {
             var commentEl = null;
             var id = $(this).attr('name');
             id = end(explode('][', id));
             id = intval(str_replace(']', '', id));
             commentEl = $('#comment-' + id);
             commentEl.css('backgroundColor', 'red').fadeOut('slow');
         });
     }
     
     function spamChecked() {
         var checkboxEl = $('input:checked');
          $(checkboxEl).each(function() {
              var commentEl = null;
              var id = $(this).attr('name');
              id = end(explode('][', id));
              id = intval(str_replace(']', '', id));
              commentEl = $('#comment-' + id);
              commentEl
                .css('backgroundColor', '#f1d6d6')
                .hide("slide", { direction: "down" }, 1000);
          });
     }
     
     // Bind main actions
     $('.select-actions > a', handledFormEl).click(function() {
         var rel = $(this).attr('rel');
         var msg = 'Do you really want to ' + str_replace('!', '', rel) + ' selected items?';
         var doContinue = confirm(msg);
         if (doContinue) {
             var url = handledFormEl.attr('action');
             
             var formAppend = '<input type="hidden" name="data[__action]" value="' + rel + '" />';
             // @TODO add AJAX submit and UI refresh
             handledFormEl.append(formAppend).submit();

             if ('delete' == rel) {
                 deleteChecked();
             } else if ('spam!' == rel) {
                 spamChecked();
             }
         }
         return false;
     });
     
     // Bind select All/None
     $('a[href=#SelectAll]', handledFormEl).click(function() {
         $('input:checkbox', handledFormEl).attr('checked', 'true').parent().parent('li').addClass('selected');
         return false;
     });
     
     $('a[href=#SelectNone]', handledFormEl).click(function() {
         selectActionsEl.slideUp(100);
         $('input:checkbox', handledFormEl).removeAttr('checked').parent().parent('li').removeClass('selected');
         return false;
     });
     
});
