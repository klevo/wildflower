// Scripts executed globaly or with more controllers

$.jlm.bind('app_controller.beforeFilter', function () {
    
    $.jlm.components.list.startup();
    $.jlm.components.nameNew.startup();
    $.jlm.components.switcher.startup();
    $.jlm.components.typeSearch.startup();
    $.jlm.components.tinyMce.startup();
    
    // Pop-ups
    //$('a.permalink').attr('target', '_blank');

});
