$.jlm.bind('assets.admin_index', function() {
	
    // seems fixed to comments but would like to titel in place - $.jlm.components.inplaceEdit.startup();

	$.jlm.components.imagePreview.startup();

	// listNav
	$('#assetList').listnav({ 
		includeNums: false 
	});

	/* / edit for more than title
	$('#assetList li').find('.edit').live('click', function (event)	{
		console.info('edit');
		event.preventDefault();
	}); */

	// edit in place for asset titles
	$('#assetList h3').find('a').live('click', function (event)	{
		console.info('edit title');
		event.preventDefault();
	});


});

$.jlm.bind('posts.admin_edit, pages.admin_edit, sidebars.admin_edit', function() {
	
    // seems fixed to comments but would like to titel in place - $.jlm.components.inplaceEdit.startup();

	// double collapse menu
	$("#asset-browser > li > a[class=expanded]").live().find("+ ul").fadeIn("normal");
	$("#asset-browser > li > a").click(function() {
		var el = $(this).find("+ ul");

		if($(this).hasClass("collapsed"))
			$(el).fadeIn('normal');
		else
			$(el).fadeOut('normal');

		$(this).toggleClass("expanded").toggleClass("collapsed");
	});


	//*/


});