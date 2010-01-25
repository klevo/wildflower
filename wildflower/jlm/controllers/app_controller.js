// Scripts executed globaly or with more controllers

$.jlm.bind('app_controller.beforeFilter', function () {
    
    $.jlm.components.tinyMce.startup();

	$('a.remove').click(function() {
		if (confirm("Are you sure you want to remove this item?")) {
			return true;
		}
		return false;
	});


	// area toogle - sweet simple & has many advantages
	/*
		~ we have enough mana to cast many spells ~ 
		live is better than bind --- or so I have heard (poss Paul Irish) - its worth checking
		I think all these anon funcs are busted and should be resolved.
	*/
	$('#header').find('.areaToggle').live('click', function () {
		$('body').toggleClass('full');
		$(this).html('Exit').attr('title', 'Click to return to full display', '');
	}, function (event)	{
		$('body').toggleClass('full');
		$(this).html('Full').attr('title', 'Click to maximise display area');
	});
    
});
