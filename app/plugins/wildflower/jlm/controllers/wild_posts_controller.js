// @TODO create a Sections menu component from this
$.jlm.bind('wild_posts.wf_edit, wild_pages.wf_edit', function() {
    
    $('.title-input input').focus();
    
    var activeSectionEl = $('#title-content');
    
    // Analyze the current URL for specific section request
    var currentUrl = window.location.href;
    var possibleSection = currentUrl.split('#');
    var switchToSection = false;
    if (possibleSection.length == 2) {
        switchToSection = '#' + possibleSection[1];
    }
    
    function switchToSectionId(switchToId) {
        if (switchToId == 'post-preview') {
            loadPreview();
        }
        
        var activeSectionHeight = activeSectionEl.height();
        
        var switchToSectionId = '#' + switchToId;
        var switchToSectionEl = $(switchToSectionId);
        
        var switchToSelectionHeight = switchToSectionEl.height();
        switchToSectionEl.css({
            height: activeSectionHeight
        });
        
        switchToSectionEl.show();
        activeSectionEl.hide();
        
        switchToSectionEl.animate({  
            height: switchToSelectionHeight
        }, 600, function() {
            $('input[@type=text]:first:visible').focus();
            activeSectionEl = switchToSectionEl;
        });
    }
    
    // Section switching
    $('.edit-sections-menu a').click(function() {
        var linkEl = $(this);
        var switchToId = linkEl.attr('rel');
        linkEl.addClass('current');
        
        if (!switchToId) {
            return true;
        }
        
        $('.edit-sections-menu .current').not(linkEl).removeClass('current');
        
        switchToSectionId(switchToId);
        
        if (linkEl.attr('href').split('#').length == 1) {
            return false; // We don't want a reload on items without #Section
        }
        return false;
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
    
    // Bind cancel section links
    $('.cancel-section').click(function() {
        switchToSectionId('title-content');
        return false;
    });
    
});

$.jlm.bind('wild_posts.wf_index, wild_pages.wf_index', function() {
    
    // Double click on a post item takes you to the edit screen
    $('.list-of-posts li').dblclick(function() {
        var editUrl = $(this).find('a:first').attr('href');
        $.jlm.redirect(editUrl, false);
        return true;
    });
    
});

$.jlm.bind('wild_posts.wf_categorize', function() {
    
    // Add new category box
    $('#add-category-box .submit input').click(function() {
        buttonEl = $(this);
        var originalLabel = buttonEl.attr('value');
        buttonEl.attr('value', 'Adding...').attr('disabled', 'disabled');
        
        var formEl = buttonEl.parents('form').eq(0);

        // Do AJAX form submit
        var successCallback = function(json) {
            buttonEl.attr('value', originalLabel).removeAttr('disabled');
            
            // Append new category to the list & check it
            var parentCategoryId = $('select', formEl).val();
            parentLiEl = $('#WildCategoryWildCategory' + parentCategoryId).parent('li');

            parentLiEl.after(json['category-list-item']).effect('highlight', {}, 4000);
        };
        
        var errorCallback = function(data) {
            alert('Error while saving. Check FireBug console for debug data.');
            if (typeof(console) == 'object') {
                console.debug(data);
            }
            buttonEl.attr('value', originalLabel).removeAttr('disabled');
        }
        
        formEl.ajaxSubmit({ dataType: 'json', success: successCallback, error: errorCallback });
        
        return false;
    });
    
});
