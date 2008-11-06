$.jlm.addComponent('nameNew', {

    startup: function() {
        var t = this;
        var dialogOpen = {};
        dialogOpen['page'] = dialogOpen['post'] = false;
        var sidebarCurrentEl = $('#sidebar .current');
        $('.add-new-page, .add-new-post').click(function() {
            // Remove any open dialogs
            $('.cancel-name-new').trigger('click');

            // Menu current indicator
            sidebarCurrentEl.removeClass('current');
            $(this).parent().addClass('current');

            var entity = $(this).attr('rel');

            if (dialogOpen[entity]) {
                return false;
            }
            dialogOpen[entity] = true;

            // Avoid content resize
            var contentEl = $('#content');
            var contentHeight = contentEl.height();

            var originalContent = $('#content').children().hide();
            contentEl.height(contentHeight);
            var controller = entity + 's';
            var model = 'Wild' + ucwords(entity);
            var templateData = { 
                entity: entity, 
                controller: controller, 
                model: model,
                url: $.jlm.base + '/' + $.jlm.params.prefix + '/' + controller + '/create'
            };
            var html = $($.jlm.template('elements/name_new', templateData)).hide();
            $('#content').append(html);

            var titleEl = html.find('input[@type=text]');
            html.slideDown(300, function() { titleEl.focus(); });

            var cancelDialog = function() {
                html.remove();
                originalContent.show();
                dialogOpen[entity] = false;
                $('#sidebar .current').removeClass('current');
                sidebarCurrentEl.addClass('current');
                return false;
            };

            // Cancel button
            $('.cancel-name-new').click(cancelDialog);

            // Form submit
            $('#name-new').submit(function() {
                var formEl = $(this);
                var title = titleEl.val();
                var data = {};
                data['data[' + model + '][title]'] = titleEl.val();
                $.post(formEl.attr('action'), data, function(response) {
                    if (response.id) {
                        $.jlm.redirect('/' + $.jlm.params.prefix + '/' + controller + '/edit/' + response.id);
                    }
                    }, 'json');
                    return false;
                });

                return false;
            });
        }

    });


