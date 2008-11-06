$.jlm.addComponent('typeSearch', {

    startup: function() {
        var searchFormEl = $('form.search');
        if (searchFormEl.size() < 1) {
            return;
        }

        searchFormEl.submit(function() {
            // @TODO perform search
            return false;
        });

        var queryInputEl = $('.search-query', searchFormEl);
        queryInputEl.val('');
        var timedAction = null;
        var searchRequest = null;
        var prevQuery = '';
        var originalContent = $('#content .list, #content .table');
        var cancelButtonPresent = false;
        var t = this;

        queryInputEl.keyup(function() {
        var query = queryInputEl.val();

        var revert = function() {
            queryInputEl.val('');
            $('.search-results, .search-loader, .search-cancel').remove();
            cancelButtonPresent = false;
            originalContent.show();
            return;
        };

        var cancelButton = $('<a class="search-cancel" href="#CancelSearch">x</a>').click(function(){
            revert();
        });

        if (query == '') {
            revert();
        }

        if (prevQuery == query || query.length < 3) {
            return;
        }

        prevQuery = query;

        if (timedAction) {
            clearTimeout(timedAction);
        }

        timedAction = setTimeout(function() {
            $('.search-results').remove();

            // Hide page list and append loader
            originalContent.hide().after('<span class="search-loader"></span>');

            // Cancel button
            if (!cancelButtonPresent) {
                queryInputEl.after(cancelButton);
                cancelButtonPresent = true;
            }

            if (searchRequest) {
                searchRequest.abort();
            }

            // Perform search
            var url = searchFormEl.attr('action');
            searchRequest = $.post(url, {
                'data[Query]': query
            }, function(responce) {
                if (queryInputEl.val() == '') {
                    return revert();
                }
                $('.search-loader').remove();
                originalContent.before(responce);
                // Don't forget to rebind the newly created list to action toolbar
                debug(t);
                t.controller.ListComponent.rebind();
            });
            }, 1000);
        });
    }

});
