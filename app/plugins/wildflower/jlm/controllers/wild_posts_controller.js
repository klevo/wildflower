$.jlm.bind('wild_posts.wf_edit', function() {
    
    $('#WildPostTitle').focus();
    
    var activeSectionEl = $('#title-content');
    
    // Analyze the current URL for specific section request
    var currentUrl = window.location.href;
    var possibleSection = currentUrl.split('#');
    var switchToSection = false;
    if (possibleSection.length == 2) {
        switchToSection = '#' + possibleSection[1];
    }
    
    // Section switching
    $('.edit-sections a').click(function() {
        var linkEl = $(this);
        var linkElRel = linkEl.attr('rel')
        linkEl.addClass('current');
        
        if (!linkElRel) {
            return true;
        }
        
        $('.edit-sections .current').not(linkEl).removeClass('current');
        
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
        
        if (linkEl.attr('href').split('#').length == 1) {
            return false; // We don't want a reload on items without #Section
        }
        return true;
    }).each(function() {
        var linkEl = $(this);
        var linkHref = linkEl.attr('href');
        if (switchToSection == linkHref) {
            linkEl.trigger('click');
        }
    });
    
    function loadPreview() {
        // Save content back to textareas
        tinyMCE.triggerSave();

        // Post data to admin_create_preview
        var url = $.jlm.base + '/' + $.jlm.params.prefix + '/' + $.jlm.params.controller.replace('wild_', '') + '/create_preview';
        
        var callback = function(json) {
            var contentHeight = 500; //$('.editor').height();

            var previewFileName = json.previewFileName;
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
