$.jlm.component('ListItemActions', '*', function() {
    
    var actionHandleEls = $('.actions-handle');
    
    if (actionHandleEls.size() < 1) return;
    
    var itemActionsTimeout = null;
    
    var over = function() {
        if (itemActionsTimeout) {
            // Cancel all to be closed and hide them
            clearTimeout(itemActionsTimeout);
            $('.row-actions:visible').hide();
        }
        
        $(this).find('.row-actions').show();
    }
    
    var out = function() {
        if (itemActionsTimeout) {
            clearTimeout(itemActionsTimeout);
        }
		
		var el = this;
		
        itemActionsTimeout = setTimeout(function() {
            if ($.browser.msie) { // IE7 does not handle animations well, therefore use plain hide()
                $(el).find('.row-actions').hide();
            }
            else {
                $(el).find('.row-actions').fadeOut(500);
            }
        }, 1000);
    }
      
    actionHandleEls.hover(over, out);
    
});