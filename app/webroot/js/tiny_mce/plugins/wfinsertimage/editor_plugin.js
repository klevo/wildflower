(function() {
	tinymce.create('tinymce.plugins.wfInsertImage', {

		init : function(ed, url) {
			var t = this;
            ed.addButton('wfinsertimage', {
	            title : 'Insert an image',
	            onclick : function() {
	                 $.jlm.components.tinyMce.insertImage(ed);
	            },
	            'class' : 'mceIcon mce_image'
	        });
		}
		
	});

	tinymce.PluginManager.add('wfinsertimage', tinymce.plugins.wfInsertImage);
})();
