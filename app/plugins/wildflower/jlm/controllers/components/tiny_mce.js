$.jlm.component('Editor', 'wild_pages.wf_edit', function() {
    
    $('a[href=#InsertImage]').click(showImageBrowser);
    
    function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
    myField.focus();
    sel = document.selection.createRange();
    sel.text = myValue;
    }
    //MOZILLA/NETSCAPE support
    else if (myField.selectionStart || myField.selectionStart == '0') {
    var startPos = myField.selectionStart;
    var endPos = myField.selectionEnd;
    myField.value = myField.value.substring(0, startPos)
    + myValue
    + myField.value.substring(endPos, myField.value.length);
    } else {
    myField.value += myValue;
    }
    }
    
    function showImageBrowser() {
        if ($('.insert_image_sidebar').size() > 0) {
            return closeBrowser();
        }
        
        // @TODO: I want to do something like this:
        // $.jlm.url({ plugin: 'wildflower', controller: 'wild_assets', action: 'wf_insert_image' });
        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/assets/insert_image';

        $.get(url, function(html) {
            var imageSidebarEl = $(html);
            $('#sidebar > ul li').hide();

            $('#sidebar > ul').append(imageSidebarEl);

            // Bind selecting
            $('.file-list > li', imageSidebarEl).click(function() {
                $('#image-browser .selected').removeClass('selected');
                $(this).addClass('selected');
            });

            // Bind insert button
            $('#insert_image', imageSidebarEl).click(function() {
                var imgName = $('.selected img', imageSidebarEl).attr('alt');

                if (typeof(imgName) == 'undefined' || trim(imgName) == '') {
                    return false;
                }

                // Original size
                imgNameEscaped = escape(imgName);
                var imgUrl = $.jlm.base + '/' + $.jlm.params.custom.wildflowerUploads + '/' + imgNameEscaped;

                // Thumbnail
                var resizeWidth = $('#resize_x', imageSidebarEl).val();
                var crop = 1;
                var resizeHeight = $('#resize_y', imageSidebarEl).val();
                if (intval(resizeHeight) < 1) {
                    resizeHeight = resizeWidth;
                }
                if (intval(resizeHeight) > 1) {
                    imgUrl = $.jlm.base + '/wildflower/thumbnail/' + imgNameEscaped + '/' + resizeWidth + '/' + resizeHeight + '/' + crop;
                }

                // Image HTML
                var imgHtml = '<img alt="' + imgName + '" src="' + imgUrl + '" />';
                
                insertAtCursor($('.editor textarea').eq(0), imgHtml);

                return false;
            });

            // Bind close
            $('.cancel', imageSidebarEl).click(closeBrowser);
        });
        
        return false;
    }
    
    function closeBrowser() {
        $('.insert_image_sidebar').remove();
        $('#sidebar > ul li').show();
        return false;
    }
    
});
