$.jlm.addComponent('tinyMce', {
    
    focusOnReady: false,

    startup: function() {
        if (typeof(tinyMCE) == 'object') {
            $('textarea.tinymce').each(function() {
                var textareaEl = $(this);
                $.jlm.components.tinyMce.resizeToFillScreen(textareaEl);
                
                $(window).bind('resize', function() {
                    var height = $.jlm.components.tinyMce.resizeToFillScreen(textareaEl);
                    $('.mceLayout').height(height);
                    $('.mceLayout iframe').height(height);
                });
                
                $.jlm.components.tinyMce.editorId = textareaEl.attr('id');
                tinyMCE.execCommand("mceAddControl", true, $.jlm.components.tinyMce.editorId);
            });
        }
	},
	
	getConfig: function() {
	    var stylesheetUrl = $.jlm.base + '/css/tiny_mce.css';
	    return {
            mode: "none",
            theme: "advanced",
            // @TODO cleanup unneeded plugins
            plugins: "wfinsertimage,safari,style,paste,directionality,visualchars,nonbreaking,xhtmlxtras,inlinepopups,fullscreen",
            doctype: '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',

            // Theme options
            theme_advanced_buttons1: "undo,redo,|,bold,italic,strikethrough,|,formatselect,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,wfinsertimage,|,charmap,code,fullscreen",
    		theme_advanced_buttons2: "",
    		theme_advanced_buttons3: "",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_statusbar_location: "none",
            theme_advanced_resizing: false,
            theme_advanced_resize_horizontal: false,
    		theme_advanced_path: false,
            width: '100%',

            // URLs
            relative_urls: false,
            remove_script_host: true,
            document_base_url: $.jlm.base,
            
            content_css: stylesheetUrl,
            
            init_instance_callback: $.jlm.components.tinyMce.onReady
        };
	},
	
	focus: function() {
	    $.jlm.components.tinyMce.focusOnReady = true;
	},
	
	onReady: function(ed) {
	    $.jlm.components.tinyMce.editorInstance = ed;
	    if ($.jlm.components.tinyMce.focusOnReady) {
	        ed.focus();
	    }
	},
	
	insertImage: function(editor) {
	    // Append img browser
	    var browserEl = $('#image-browser');
	    var doBindAndLoad = false;
	    if (browserEl.size() == 0) {
	        doBindAndLoad = true;
	        browserEl = $($.jlm.element('image_browser')).hide();
	    } 
	    
	    $('#sidebar > ul').prepend(browserEl);
	    
	    if (browserEl.css('display') == 'none') {
            browserEl.slideDown(600);
	    } else {
	        // Already open, close
	        browserEl.slideUp(200);
	        return false;
	    }
	    
	    if (!doBindAndLoad) {
	        return false;
	    }
	    
	    // Load images
	    // @TODO: I want to do something like this:
	    // $.jlm.url({ plugin: 'wildflower', controller: 'wild_assets', action: 'wf_insert_image' });
	    var url = $.jlm.base + '/' + $.jlm.params.prefix + '/assets/insert_image';
	    
	    $.get(url, function(imagesHtml) {
	        areImagesLoaded = true;
	        var imagesHtmlEl = $(imagesHtml).hide();
            $('h4:first', browserEl).after(imagesHtml);
            imagesHtmlEl.fadeIn('normal');
            
            // Bind selecting
            $('.file-list > li').click(function() {
                $('#image-browser .selected').removeClass('selected');
                $(this).addClass('selected');
            });
		});
	    
	    // Bind insert button
		$('button', browserEl).click(function() {
			var imgName = $('.selected img', browserEl).attr('alt');
			
			if (typeof(imgName) == 'undefined') {
			    return false;
			}
            
            var width, height;
            // if (isNaN(width = $('#ImageResizeX', browserEl).val())) {
            //     width = 0;
            // }
            // if (isNaN(height = $('#ImageResizeY', browserEl).val())) {
            //     height = 0;
            // }
            
            // Original size
            imgNameEscaped = escape(imgName);
            var imgUrl = 'uploads/' + imgNameEscaped; // @TODO get 'uploads' from config
			
            // Thumbnail
            if ($('#ImageSize', browserEl).val() == 'thumbnail') {
                imgUrl = $.params.base + '/wildflower/thumbnail/' + imgNameEscaped + '/120/120/1';
            }
			
			// Image HTML
			var imgHtml = '<img alt="' + imgName + '" src="' + imgUrl + '" />';
			
			editor.execCommand('mceInsertContent', 0, imgHtml);
			
			return false;
		});
		
		// Bind close
        $('.cancel', browserEl).click(function() {
            browserEl.slideUp(200);
            return false;
        });
	    
	    return false;
	},
	
	resizeToFillScreen: function(textareaEl) {
	    var otherContentHeight = $('body').height() - textareaEl.height();
	    var bumper = 20;
	    var result = $(window).height() - otherContentHeight - bumper; 
	    
		textareaEl.height(result);
		return result;
	},
	
	closeDialog: function() {
		$.jlm.components.tinyMce.dialogEl.remove();
	},
    
    insertLink: function() {
        log('INSERT LINK');
    }
});
