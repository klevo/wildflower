$.jlm.addComponent('list', {

    draftLabel: '<small class="draft-status">Draft</small>',

    startup: function() {
        this.listEl = $('.selectable-list');
        var actionClick = false;

        $('a', this.listEl).click(function() {
            actionClick = true;
            return true;
        });

        var t = this;

        // Selecting & double click
        $('.list-item', this.listEl).click(function() {
            if (actionClick) {
                return;
            }

            $(this).toggleClass('selected');
        }).dblclick(function() {
            $(this).addClass('selected');
            var url = $(this).find('a:first').attr('href');
            $.jlm.redirect(url, false);
            return false;
        });

        // Selection actions
        $('.list-toolbar-delete').click(function() {
            // Ask if sure
            var proceed = confirm('Do you really want to delete the selected items? There is no undo.');
            if (!proceed) {
                return false;
            }

            // Request delete
            var selItems = t.withSelected('delete');
            var selCount = selItems.length;

            // Remove nodes
            var i = 0;
            selItems.fadeOut(600, function() {
                $(this).remove();
                i++;

                // Reassign odd class, do only ONCE on the last item
                if (i == selCount) {
                    var j = 0;
                    $('li', t.listEl).each(function(){
                        var liEl = $(this);
                        liEl.removeClass('odd');

                        if (j % 2 == 0) {
                            liEl.addClass('odd');
                        }

                        j++;
                    });
                }
            });

            return false;
        });

        $('.list-toolbar-draft').click(function() {
            var selItems = t.withSelected('draft');

            // Add draft label
            selItems.each(function() {
                var liEl = $(this);
                var itemEl = $('.list-item', liEl).eq(0);
                var hasDraft = ($('.draft-status', itemEl).eq(0).size() > 0);
                if (!hasDraft) {
                    var label = $(t.draftLabel).hide();
                    $('a:first', itemEl).eq(0).after(label);
                    label.fadeIn(300);
                }
            });

            return false;
        });

        $('.list-toolbar-publish').click(function() {
            var selItems = t.withSelected('publish');

            // Remove draft label
            selItems.each(function() {
                var liEl = $(this);
                var itemEl = $('.list-item', liEl).eq(0);
                $('.draft-status', itemEl).eq(0).fadeOut(300, function() { $(this).remove(); });
            });

            return false;
        });
    },

    /**
    * Reinitialize component for dynamicaly created lists
    * 
    */
    rebind: function() {
        $('.list-toolbar a').unbind('click');
        this.startup();
    },

    withSelected: function(actionType) {
        var selItems = $('.selected', this.listEl).parent('li');

        // Extract IDs
        var ids = [];
        selItems.each(function() {
            var id = $(this).attr('id');
            id = id.split('-');
            id = id[1];
            ids.push(id);
        });

        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/' + $.jlm.params['controller'].replace('wild_', '') + '/mass_update';
        var data = { 'data[Items]': ids.join(','), 'data[Action]': actionType };
        $.post(url, data);

        return selItems;
    }

});
