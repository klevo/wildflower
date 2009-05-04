$.jlm.addComponent('inplaceEdit', {

    startup: function() {
        this.targetEl = $('.inplace-edit');
        var t = this;
        var edit = function() {
            var entryEl = $(this);
            // If link triggers the action
            if (entryEl.attr('href')) {
                entryEl = entryEl.parent().parent().parent().children('.entry');
            }
            var elHeight = entryEl.height();
            var elWidth = entryEl.width();
            var commentId = entryEl.parent().parent('li').attr('id').split('-');
            commentId = commentId[1];

            var respond = function(json) {
                // Hide comment action
                var commentActionsEl = $('.comment-actions', entryEl.parent());
                commentActionsEl.hide();

                var content = json.content;
                var formEl = $($.jlm.template('elements/edit_comment', { commentId: commentId }));
                entryEl.hide().before(formEl);
                var textareaEl = $('textarea', formEl);
                textareaEl.css({ width: elWidth, height: elHeight }).val(content);

                // Cancel button
                var cancel = function() {
                    formEl.remove();
                    entryEl.show();
                    commentActionsEl.show();
                    return false;
                };
                $('.cancel-edit-comment', formEl).click(cancel);

                // AJAX form
                var afterSave = function(contentHtml) {
                    cancel();
                    entryEl.html(contentHtml);
                };

                formEl.ajaxForm(afterSave);
            }

            var url = $.jlm.base + '/wf/comments/get_content/' + commentId;
            var content = $.post(url, respond, {}, 'json');
            return false;
        };
        this.targetEl.dblclick(edit);
        $('.edit-comment').click(edit);

        // Bind spam buttons...
        $('.spam-comment').click(this.markAsSpam);

        // ...delete buttons...
        $('.delete-comment').click(this.deleteComment);

        // ...not spam buttons ehm links.
        $('.unspam-comment').click(this.notSpam);
    },

    deleteComment: function() {
        var commentEl = $(this).parent().parent().parent();

        var commentId = commentEl.attr('id').split('-');
        commentId = commentId[1];
        var url = $.jlm.base + '/admin/comments/delete';
        var data = { 'data[Comment][id]': commentId };
        $.post(url, data);

        // Ask if sure
        var proceed = confirm('Do you really want to delete this comment?');
        if (!proceed) {
            return false;
        }

        // Animate
        commentEl.hide("puff", {}, 400, function() { $(this).remove() });
    },

    markAsSpam: function() {
        var commentEl = $(this).parent().parent().parent();

        var commentId = commentEl.attr('id').split('-');
        commentId = commentId[1];
        var url = $.jlm.base + '/admin/comments/mark_spam';
        var data = { 'data[Comment][id]': commentId };
        $.post(url, data);

        // Animate
        commentEl.css({ backgroundColor: '#ff8181' });
        $('.comment-metadata', commentEl).css({ backgroundColor: '#ff8181' });
        commentEl.hide("drop", { direction: "right" }, 400, function() { $(this).remove() });
    },

    notSpam: function() {
        var commentEl = $(this).parent().parent().parent();

        var commentId = commentEl.attr('id').split('-');
        commentId = commentId[1];
        var url = $.jlm.base + '/admin/comments/not_spam';
        var data = { 'data[Comment][id]': commentId };
        $.post(url, data);

        // Animate
        commentEl.css({ backgroundColor: '#c7efca' });
        $('.comment-metadata', commentEl).css({ backgroundColor: '#c7efca' });
        commentEl.hide("drop", { direction: "left" }, 400, function() { $(this).remove() });
    }

});
