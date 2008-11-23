$.jlm.addComponent('tinyMce', {

    startup: function() {
		// Initialize editor
		var ids = [];
        $('textarea.tinymce').each(function() {
            var id = $(this).attr('id');
			tinyMCE.execCommand("mceAddControl", true, id);
        });
	},
	
	insertImage: function(editor) {
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
