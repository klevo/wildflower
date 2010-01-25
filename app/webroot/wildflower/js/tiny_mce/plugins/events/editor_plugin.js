(function() {

	tinymce.create('tinymce.plugins.Events', {
		init: function(node, state) {
			var events = new Array('onfocus','onblur','onclick','ondblclick',
						'onmousedown','onmouseup','onmouseover','onmousemove',
						'onmouseout','onkeypress','onkeydown','onkeydown','onkeyup');

			var event_elms = tinyMCE.settings['event_elements'];
			var eventArr = event_elms.split(',');

			for(var y=0; y<eventArr.length; y++){
				var elms=node.getElementsByTagName(eventArr[y]);
				for (var i=0; i<elms.length; i++) {
					var event = "";

					for (var x=0; x<events.length; x++) {
						if ((event = tinyMCE.getAttrib(elms[i], events[x])) != '') {
							event = tinyMCE.cleanupEventStr("" + event);

							if (state)
								event = "return true;" + event;
							else
								event = event.replace(/^return true;/gi, '');

							elms[i].removeAttribute(events[x]);
							elms[i].setAttribute(events[x], event);
						}
					}
				}
			}
		}

	});

	tinymce.PluginManager.add('events', tinymce.plugins.events);
})();