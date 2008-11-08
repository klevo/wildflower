$.jlm.addComponent('fileManager', {

    startup: function() {
        this.filesEl = $('.file-list');

        // Selecting files
        $('li', this.filesEl).click(function() {
            $(this).toggleClass('selected');
        });

        var t = this;

        // Prevent single link click because of selecting
        $('li a', this.filesEl).click(function() {
            $(this).parent('li').toggleClass('selected');
            return false;
        }).dblclick(function() {
            $(this).parent('li').addClass('selected');
            var url = $(this).attr('href');
            $.jlm.redirect(url, false);
        });

        // Toolbar
        $('.file-toolbar-delete').click(function() {
            // Ask if sure
            var proceed = confirm('<l18n>Do you really want to delete the selected files? There is no undo.</l18n>');
            if (!proceed) return false;

            var selected = t.withSelected('delete');

            if (selected.size() == 1) {
                selected.hide('puff', {}, 500, function() {
                    $(this).remove();
                });
                return false;
            }

            selected.fadeOut(300, function() {
                $(this).remove();
            });

            return false;
        });
    },

    withSelected: function(actionType) {
        var selItems = $('.selected', this.filesEl);

        // Extract IDs
        var ids = [];
        selItems.each(function() {
            var id = $(this).attr('id');
            id = id.split('-');
            id = id.pop();
            ids.push(id);
        });

        // @TODO with JLM MVC power this could look like this.Upload.massUpdate(data)
        var url = $.jlm.base + '/admin/' + $.jlm.params.controller + '/mass_update';
        var data = { 'data[Items]': ids.join(','), 'data[Action]': actionType };
        $.post(url, data);

        return selItems;
    }

});
