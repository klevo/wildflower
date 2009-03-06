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
	            'image' : $.jlm.base + '/wildflower/img/cog.png'
	        });
	        
	        ed.onDblClick.add(function(ed, e) {
                $.jlm.components.widgets.edit(e.target);
            });
    
		}
		
	});

	tinymce.PluginManager.add('wfinsertimage', tinymce.plugins.wfInsertImage);
})();
