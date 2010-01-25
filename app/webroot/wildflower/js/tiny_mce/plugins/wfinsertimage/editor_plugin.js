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
			
			// Insert image button
            ed.addButton('wfinsertasset', {
	            title : 'Insert an Asset',
	            onclick : function() {
	                 $.jlm.components.tinyMce.insertAsset(ed);
	            },
	            'image' : $.jlm.base + '/wildflower/img/cog.png'
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
	        
	        // ed.onMouseOver.add(function(ed, e) { log('over the image - gimme size keywords'); });
    
		}
		
	});

	tinymce.PluginManager.add('wfinsertimage', tinymce.plugins.wfInsertImage);
})();
