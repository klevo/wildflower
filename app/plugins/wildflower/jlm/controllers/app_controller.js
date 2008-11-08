// Scripts executed globaly or with more controller

$.jlm.bind('app_controller.beforeFilter', function () {
    
    $.jlm.components.list.startup();
    $.jlm.components.nameNew.startup();
    $.jlm.components.switcher.startup();
    $.jlm.components.typeSearch.startup();
    
    // Pop-ups
    $('a[@rel=external]').attr('target', '_blank');

});

$.jlm.bind('wild_pages.wf_edit, wild_posts.wf_edit', function() {
    
    $.jlm.components.tinyMce.startup();

/* @dev
    // Save button
    $('#save').click(function() {
        // Disable button
        var buttonEl = $(this);
        //buttonEl.attr('disabled', 'disabled');

        // Save content back to textareas
        tinyMCE.triggerSave();

        // Do AJAX form submit
        var formEl = $('form');
        var removeTimeout = null;
        
        var saved = function(response) {
            // Update time
            $('#modified-time').html(response.time).attr('title', response.fullTime).effect('highlight', {}, 2000);
            
            // Add flash messsage
            // First remove any old ones
            $('.message').remove();
            var say = 'Succefuly saved at ' + response.fullTime + '.';
            var flashMessage = $($.jlm.template('elements/flash_message', { text: say })).hide();
            $('#sub-nav').after(flashMessage);
            flashMessage.slideDown();
            setTimeout(function() {
               flashMessage.animate({
                   backgroundColor: '#eee',
                   color: '#535353',
                   borderColor: '#ddd'
               }, 1000); 
            }, 3000);

            // Update revision list
            var currentVersionStringEl = $('.current-revision');
            if (currentVersionStringEl.size() > 0) {
                var lastRevNumber = $('.revision-list li:first a').text().split(' ');
                lastRevNumber = lastRevNumber.pop();
                if (lastRevNumber != response.revNumber) {
                    currentVersionStringEl.remove();
                    $('.revision-list').prepend(response.revision);
                }
            }
        };
        
        // On form submit do AJAX save
        formEl.ajaxSubmit({ 
            dataType: 'json', 
            success: saved 
        });

        return false;
    });
    
*/

});
