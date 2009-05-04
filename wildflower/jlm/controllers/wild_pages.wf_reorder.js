$.jlm.bind('wild_pages.wf_reorder', function() {
    
    var movedPage = null;
    var dropZoneHtml = '<div class="drop_zone"><l18n>drop here</l18n></div>';
    
    function appendDropZones() {
        // Remove any old first
        $('.drop_zone').remove();
        
        $('.page_reorder_list li').prepend(dropZoneHtml)
        // Append to last
        // $('.page_reorder_list > li:last-child, .page_reorder_list ul > li:last-child').append('<div class="drop_zone">drop here</div>');
    }
    
    function showDropZones() {
        $('.drop_zone').show();
        var parentEl = $(this).parent('li');
        movedPage = parentEl.clone();
        parentEl.remove();
        $('.page_reorder_list a').unbind('click').click(createChildren);
        return false;
    }
    
    function movePage() {
        var parentEl = $(this).parent('li');
        parentEl.before(movedPage.hide());
        movedPage.fadeIn('slow');
        movedPage = null;
        $('.drop_zone').hide();
        $('.page_reorder_list a').unbind('click').click(showDropZones);
        $('.drop_zone').click(movePage);
        return false;
    }
    
    function createChildren() {
        var append = $('<ul>' + movedPage.html() + '</ul>');
        $(this).parent('li').append(append);
        movedPage = null;
        $('.drop_zone').hide();
        append.fadeIn('slow');
        $('.page_reorder_list a').unbind('click').click(showDropZones);
        $('.drop_zone').click(movePage);
        return false;
    }
    
    
   
    $('.page_reorder_list a').click(showDropZones);
    $('.drop_zone').click(movePage);
    
});