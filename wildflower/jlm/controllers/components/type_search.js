/**
 * Type Search
 *
 * Powers the sidebar search boxes. Searches as you type.
 */
$.jlm.component('TypeSearch', '*', function() {
    
    var searchFormEl = $('#sidebar .search');
    if (searchFormEl.size() < 1) {
        return;
    }
    
    searchFormEl.submit(function(){
        // @TODO perform search
        return false;
    }); 
    
    var queryInputEl = $('#SearchQuery');
    queryInputEl.val('');
    var timedAction = null;
    var searchRequest = null;
    var url = searchFormEl.attr('action') + '/';
    
    var doSearch = function() {
        $('#sidebar-search-results').remove();
        
        // Abort previous search
        if (searchRequest) {
            searchRequest.abort();
        }
        
        var query = queryInputEl.val();
        
        // Don't search for no query or too small query
        if (!query || query.length < 3) {
            return;
        }   

        searchRequest = $.get(url + encodeURI(query), function(html) {
            $('#sidebar-search-results').remove();
            searchFormEl.append(html);
        });
    }
    
    queryInputEl.keyup(function() {
        if (timedAction) {
            clearTimeout(timedAction);
        }
        
        timedAction = setTimeout(doSearch, 1000);
    });

});
