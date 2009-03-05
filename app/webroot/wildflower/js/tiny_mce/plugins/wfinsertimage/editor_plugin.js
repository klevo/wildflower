(function() {
	tinymce.create('tinymce.plugins.wfInsertImage', {

		init : function(ed, url) {
			var t = this;
			
			// Insert image button
            ed.addButton('wfinsertimage', {
	            title : 'Insert an image',
	            onclick : function() {
	                 $.jlm.components.tinyMce.insertImage(ed);
	            },
	            'class' : 'mceIcon mce_image'
	        });
	        
	        // Insert Widget button
	        ed.addButton('wfinsertwidget',  {
	            title : 'Insert a widget',
	            onclick : function() {
	                 $.jlm.components.tinyMce.insertWidget(ed);
	            },
	            'class' : 'mceIcon'
	        });
		}
		
	});

	tinymce.PluginManager.add('wfinsertimage', tinymce.plugins.wfInsertImage);
})();
