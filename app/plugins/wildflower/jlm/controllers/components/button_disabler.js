$.jlm.component('ButtonDisabler', '*', function() {
    
    $('.save-section input').click(function() {
        $(this).attr('disabled', 'disabled').val('Saving...');
        return true;
    }).removeAttr('disabled');
    
});