$.jlm.addComponent('tinyMce', {

    startup: function() {
        if (typeof(tinyMCE) == 'object') {
            $('textarea.tinymce').each(function() {
                var id = $(this).attr('id');
                tinyMCE.execCommand("mceAddControl", true, id);
            });
        }
	},
	
	getConfig: function() {
	    var stylesheetUrl = $.jlm.base + '/css/tiny_mce.css';
	    return {
            mode: "none",
            theme: "advanced",
            // @TODO cleanup unneeded plugins
            plugins: "wfinsertimage,safari,style,paste,directionality,visualchars,nonbreaking,xhtmlxtras,inlinepopups",
            doctype: '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',

            // Theme options
            theme_advanced_buttons1: "undo,redo,|,bold,italic,strikethrough,|,formatselect,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,wfinsertimage,|,charmap,code",
    		theme_advanced_buttons2: "",
    		theme_advanced_buttons3: "",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_statusbar_location: "bottom",
            theme_advanced_resizing: true,
            theme_advanced_resize_horizontal: false,
    		theme_advanced_path: false,
            width: '100%',

            // URLs
            relative_urls: false,
            remove_script_host: true,
            document_base_url: $.jlm.base, // @TODO investage if this works as intended
            
            //
            content_css: stylesheetUrl
        };
	},
	
	insertImage: function(editor) {
	    // Append img browser
	    var browserEl = $($.jlm.element('image_browser'));
	    $('.title-input:first').after(browserEl);
	    
	    // Load images
	    
	    // @TODO: I want to do something like this:
	    // $.jlm.url({ plugin: 'wildflower', controller: 'wild_assets', action: 'wf_insert_image' });
	    var url = $.jlm.base + '/' + $.jlm.params.prefix + '/assets/insert_image';
	    
	    $.get(url, function(imagesHtml) {
            browserEl.prepend(imagesHtml);
		});
	    
	    // Bind insert button
		$('button', browserEl).click(function() {
			var imgName = $('.selected', browserEl).attr('alt');
			
			if (typeof(imgName) == 'undefined') {
			    return false;
			}
			
            // Original size (scaled)
            var width, height;
            if (isNaN(width = $('#ImageResizeX', t.dialogEl).val())) {
                width = 0;
            }
            if (isNaN(height = $('#ImageResizeY', t.dialogEl).val())) {
                height = 0;
            }
            var imgUrl = 'img/thumb/' + imgName + '/' + width + '/' + height;
			
			// Thumbnail
            if ($('#ImageSize', t.dialogEl).val() == 'thumbnail') {
             imgUrl = 'img/thumb/' + imgName + '/120/120/1';
            }
			
			// Image HTML
			var imgHtml = '<img alt="' + imgName + '" src="' + imgUrl + '" />';
			
			editor.execCommand('mceInsertContent', 0, imgHtml);
			
			return false;
		});
		
		// Bind close
        // $('#close-dialog', t.dialogEl).click(function() {
        //  t.dialogEl.remove();
        //  return false;
        // });
	    
	    return false;
	    
	    
	    // Old stuff
	    
		var t = this;
		
		// Append dialog
		this.dialogEl = $($.jlm.template('elements/insert_image_dialog'));
		//debug($.jlm.template('elements/insert_image_dialog'));
		$('body').append(this.dialogEl);
		
		// Size and positioning
		var dialogWidth = Math.floor(($(window).width() / 3) * 2);
		var dialogHeight = 500;
        var dialogWidthHalf = Math.floor(dialogWidth / 2);
        var dialogHeightHalf = Math.floor(dialogHeight / 2);
        var leftPost = Math.floor($(window).width() / 2) - dialogWidthHalf; 
        var topPost = Math.floor($(window).height() / 2) - dialogHeightHalf;
		this.dialogEl.css({
			left: leftPost,
			top: topPost,
			width: dialogWidth,
			height: dialogHeight
		});
		$('#dialog-content', this.dialogEl).css('height', dialogHeight - 93);
		
		// Make dialog dragable
		this.dialogEl.draggable({
			//containment: '#wrap',
			handle: '#dialog h3'
		});
		
		// How many images will go to the browser?
		this.limit = (dialogWidth - 16) / 128;
		t.limit = 2 * Math.floor(t.limit);
		
		// Esc = close
		$.hotkeys.add('esc', t.closeDialog);
		
		// Load image browser
		var url = $.jlm.base + '/' + $.jlm.params.prefix + '/assets/insert_image/' + t.limit;
		$.get(url, function(data) {
			// Remove loader and append image gallery
			t.loaderEl = $('.loader', this.dialogEl);
			t.loaderEl.hide().before(data);
			
			t.bindImageSelecting();
			t.bindPaginator();
			
			// Bind insert button
			$('.submit input', t.dialogEl).click(function() {
				var imgName = $('.selected', t.dialogEl).attr('alt');
				
				if (typeof(imgName) == 'undefined') {
				    alert('Please select an image!');
				    return false;
				}
				
                // Original size (scaled)
                var width, height;
                if (isNaN(width = $('#ImageResizeX', t.dialogEl).val())) {
                    width = 0;
                }
                if (isNaN(height = $('#ImageResizeY', t.dialogEl).val())) {
                    height = 0;
                }
                var imgUrl = 'img/thumb/' + imgName + '/' + width + '/' + height;
				
				// Thumbnail
				if ($('#ImageSize', t.dialogEl).val() == 'thumbnail') {
					imgUrl = 'img/thumb/' + imgName + '/120/120/1';
				}
				
				// Image HTML
				var imgHtml = '<img alt="" src="' + imgUrl + '" />';
				
				editor.execCommand('mceInsertContent', 0, imgHtml);
				return false;
			});
			
			// Bind close
			$('#close-dialog', t.dialogEl).click(function() {
				t.dialogEl.remove();
				return false;
			});
		});
	},
	
	bindImageSelecting: function() {
		var t = this;
		// Bind selecting
        $('#image-browser img').click(function() {
            $(this).toggleClass('selected');
            $('.selected', t.dialogEl).not(this).removeClass('selected');
        });
		
		// Image browser size
		$('#image-browser').width((t.limit / 2) * 128);
	},
	
	bindPaginator: function() {
		var t = this;
		$('#image-browser .paginate-page').click(function() {
            var url = $(this).attr('href');
            t.loaderEl.show();
            $('#image-browser').remove();
            $.get(url, function(data) {
                t.loaderEl.hide().before(data);
                // rebind select
                t.bindImageSelecting();
                // rebind pager
				t.bindPaginator();
            });
            return false;
        });
	},
	
	closeDialog: function() {
		$.jlm.components.tinyMce.dialogEl.remove();
	},
    
    insertLink: function() {
        log('INSERT LINK');
    }
});
