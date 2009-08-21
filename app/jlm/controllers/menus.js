$.jlm.bind('menus.admin_add, menus.admin_edit', function() {
    
    function bindRemove() {
        $('.menu_items .delete').click(function() {
            $(this).parent('li').fadeOut(600, function() {
                $(this).remove();
            });
            
            // If on edit delete the real item
            if ($.jlm.params.action == 'admin_edit') {
                $.post($(this).attr('href'));
            }
            
            return false;
        });
    }
    
    function renameNames() {
        var index = 0;
        $('.menu_items li').each(function() {
            var html = $(this).html();
            var labelName = $('.menu_item_label input', this).attr('name');
            var urlName = $('.menu_item_url input', this).attr('name');
            var idName = $('input[type=hidden]', this).attr('name');
            //debug(labelName);debug(urlName);
            var replace = [labelName, urlName, idName];
            var replaceWith = ['data[MenuItem][' + index + '][label]', 'data[MenuItem][' + index + '][url]', 'data[MenuItem][' + index + '][id]'];
            //debug(replace);debug(replaceWith)
            html = str_replace(replace, replaceWith, html);
            $(this).html(html);
            index++;
        });
    }
    
    function bindMove() {
        $('.menu_items').sortable({
            axis: 'y',
            handle: '.move',
            placeholder: 'drop_here',
            update: renameNames
        });
    }
    
    bindRemove();
    bindMove();
    
    $('#add_menu_item').click(function() {
        var template = $('.menu_items li:last').html();
        $('.menu_items').append('<li>' + template + '</li>');
        renameNames();
        // Reset values
        $('.menu_items li:last input').val('');
        // Remove ID
        $('.menu_items li:last input[type=hidden]').remove();
        bindRemove();
        bindMove();
        return false;
    });
    

    
});