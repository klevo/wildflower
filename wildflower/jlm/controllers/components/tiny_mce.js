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
        var fullBaseUrl = window.location.protocol + "//" + window.location.host + '/';
	    return {
            mode: "none",
            theme: "advanced",
            // @TODO cleanup unneeded plugins
            plugins: "wfinsertimage,safari,style,paste,directionality,visualchars,nonbreaking,xhtmlxtras,inlinepopups,fullscreen",
            doctype: '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',

            // Theme options
            theme_advanced_buttons1: "undo,redo,|,bold,italic,strikethrough,|,formatselect,styleselect,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,wfinsertimage,wfinsertwidget,|,charmap,code,fullscreen",
            theme_advanced_styles : "Align left=left;Align right=right",
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
            remove_script_host: false,
            document_base_url: fullBaseUrl,
            convert_urls: false,
            
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
	
	loadInsertImageContent: function(url) {
	    $('.insert_image_sidebar').remove();
	    
	    $.get(url, function(html) {
	        var imageSidebarEl = $(html);
	        $('.main_sidebar').hide();
	        
	        $('#sidebar > ul').append(imageSidebarEl);
            
            // Bind selecting
            $('.file-list > li', imageSidebarEl).click(function() {
                $('#image-browser .selected').removeClass('selected');
                $(this).addClass('selected');
            });
            
            // Bind insert button
    		$('#insert_image', imageSidebarEl).click(function() {
    			var imgName = $('.selected img', imageSidebarEl).attr('alt');

    			if (typeof(imgName) == 'undefined' || trim(imgName) == '') {
    			    return false;
    			}

                // Original size
                imgNameEscaped = escape(imgName);
                var imgUrl = $.jlm.base + '/' + $.jlm.params.custom.wildflowerUploads + '/' + imgNameEscaped;

                // Thumbnail
                var resizeWidth = $('#resize_x', imageSidebarEl).val();
                var crop = 1;
                var resizeHeight = $('#resize_y', imageSidebarEl).val();
                if (intval(resizeHeight) < 1) {
                    resizeHeight = resizeWidth;
                }
                if (intval(resizeHeight) > 1) {
                    imgUrl = $.jlm.base + '/wildflower/thumbnail/' + imgNameEscaped + '/' + resizeWidth + '/' + resizeHeight + '/' + crop;
                }

    			// Image HTML
    			var imgHtml = '<img alt="' + imgName + '" src="' + imgUrl + '" />';

    			$.jlm.components.tinyMce.editor.execCommand('mceInsertContent', 0, imgHtml);

    			return false;
    		});

    		// Bind close
            $('.cancel', imageSidebarEl).click(function() {
                $('.insert_image_sidebar').remove();
                $('.main_sidebar').show();
                return false;
            });
            
            // Bind pagination
            $('.paginator a', imageSidebarEl).click(function() {
                var url = $(this).attr('href');
                $.jlm.components.tinyMce.loadInsertImageContent(url);
                return false;
            });
		});
	},
	
	insertImage: function(editor) {
	    $.jlm.components.tinyMce.editor = editor;
	    
	    // Close if open
	    if ($('.insert_image_sidebar').size() > 0) {
	        $('.insert_image_sidebar').remove();
	        $('.main_sidebar').show();
	        return false;
	    }
	    
	    var url = $.jlm.base + '/' + $.jlm.params.prefix + '/assets/insert_image';
	    $.jlm.components.tinyMce.loadInsertImageContent(url);	    
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
	
	insertWidget: function(editor) {
	    // Close if open
	    var alreadyOpenEl = $('.insert_widget_sidebar');
	    if (alreadyOpenEl.size() > 0) {
	        alreadyOpenEl.remove();
	        $('.main_sidebar').show();
	        return false;
	    }
	    
	    var url = $.jlm.base + '/' + $.jlm.params.prefix + '/widgets/list_widgets';
	    
	    $.get(url, function(html) {
	        var widgetSidebarEl = $(html);
	        $('.main_sidebar').hide();
	        $('#sidebar > ul').append(widgetSidebarEl);
	        
	        // Bind insert
	        $('.widget_list a').click(function() {
	            var url = $.jlm.base + '/' + $.jlm.params.prefix + '/widgets/insert_widget';
	            var widgetId = $(this).attr('rel');
	            $.getJSON(url, function(json) {
	                var instanceId = json.id;
	                var src = $.jlm.base + '/wildflower/widgets/' + widgetId + '.png';
    	            var widgetHtml = '<img id="' + widgetId + '" class="wf_widget wf_widget_id_' + instanceId + '" src="' + src + '" />';
    	            editor.execCommand('mceInsertContent', 0, widgetHtml);
	            });
	            return false;
	        });
		});
	    
	    return false;
	},
    
    insertLink: function() {
        log('INSERT LINK');
    }
});
