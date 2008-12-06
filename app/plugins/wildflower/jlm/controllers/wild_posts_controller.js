$.jlm.bind('wild_posts.wf_edit', function() {
    
    $('#WildPostTitle').focus();
    
    var activeSectionEl = $('#title-content');
    
    // Section switching
    $('.sidebar-menu a').click(function() {
        var linkEl = $(this);
        var linkElRel = linkEl.attr('rel')
        linkEl.addClass('current');
        
        if (!linkElRel) {
            return true;
        }
        
        $('.sidebar-menu .current').not(linkEl).removeClass('current');
        
        if (linkElRel == 'post-preview') {
            loadPreview();
        }
        
        activeSectionEl.slideUp(300, function() {
            var switchToSectionId = '#' + linkElRel;
            var switchToSectionEl = $(switchToSectionId);
            switchToSectionEl.slideDown(300, function() {
                $('input[@type=text]:first:visible').focus();
            });

            activeSectionEl = switchToSectionEl;
        });
        
        return false;
    });
    
    function loadPreview() {
        // Save content back to textareas
        tinyMCE.triggerSave();

        // Post data to admin_create_preview
        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/' + $.jlm.params.controller.replace('wild_', '') + '/create_preview';
        
        var callback = function(json) {
            var contentHeight = 500; //$('.editor').height();

            var previewFileName = json.previewFileName;
            console.log(json);
            var iframeSrc = $.jlm.base + '/' + $.jlm.params.prefix + '/' + $.jlm.params.controller.replace('wild_', '') + '/preview/' + previewFileName;
        
            $('#post-preview object').attr('data', iframeSrc);
        };

        $('form:first').ajaxSubmit({
            url: url,
            success: callback,
            dataType: 'json'
        });
    }
    
});
