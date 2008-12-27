// JavaScript Document
(function($) {

	$.fn.jTree = function(options) {
		var opts = $.extend({}, $.fn.jTree.defaults, options);
		var cur = 0, curOff = 0, off =0, h =0, w=0, hover = 0;
		var str='<li class="jTreePlacement" style="background:'+opts.pBg+';border:'+opts.pBorder+';color:'+opts.pColor+';height:'+opts.pHeight+'"></li>';
		var container = this;
		//events are written here
		$(this).find("li").mousedown(function(e){
			if ($("#jTreeHelper").not(":animated") && e.button !=2) {
				// append jTreePlacement to body and hides
				$("body").append(str);
				$(".jTreePlacement").hide();
				//get the current li and append to helper
				$(this).clone().appendTo("#jTreeHelper");
				
				
				// get initial state, cur and offset
				cur = this;
				curOff = $(cur).offset();
				$(cur).hide();
				// show initial helper
				$("#jTreeHelper").css ({
					position: "absolute",
					top: e.pageY + 5,
					left: e.pageX + 5,
					background: opts.hBg,
					opacity: opts.hOpacity
				}).show();
				$("#jTreeHelper *").css ({
					color: opts.hColor,
					background: opts.hBg,
				});
				// start binding events to use
				// prevent text selection
				$(document).bind("selectstart", doNothing);
				
				// doubleclick is destructive, better disable
				$(container).find("li").bind("dblclick", doNothing);
				
				// in single li calculate the offset, width height of hovered block
				$(container).find("li").bind("mouseover", getInitial);
				
				// in single li put placement in correct places, also move the helper around
				$(container).find("li").bind("mousemove", putPlacement);
				
				// handle mouse movement outside our container
				$(document).bind("mousemove", helperPosition);
			}
			//prevent bubbling of mousedown
			return false;
		});
		
		// in single li or in container, snap into placement if present then destroy placement
		// and helper then show snapped in object/li
		// also destroys events
		$(this).find("li").andSelf().mouseup(function(e){
			// if placementBox is detected
			if ($(".jTreePlacement").is(":visible")) {
				$(cur).insertBefore(".jTreePlacement").show();
			}
			$(cur).show();
			// remove helper and placement box
			$(container).find("ul:empty").remove();
			$("#jTreeHelper").empty().hide();
			$(".jTreePlacement").remove();		
			
			// remove bindings
			destroyBindings();
			
			return false;
		});
		
		$(document).mouseup(function(e){
			if (cur!=0) {
				$("#jTreeHelper").animate({
					top: curOff.top,
					left: curOff.left
						}, opts.snapBack, function(){
							$("#jTreeHelper").empty().hide();
							$(".jTreePlacement").remove();
							$(cur).show();
						}
				);
				destroyBindings();
			}
			cur = 0;
			return false;
		});
		//functions are written here
		var doNothing = function(){
			return false;
		};
		
		var destroyBindings = function(){
			$(document).unbind("selectstart", doNothing);
			$(container).find("li").unbind("dblclick", doNothing);
			$(container).find("li").unbind("mouseover", getInitial);
			$(container).find("li").unbind("mousemove", putPlacement);
			$(document).unbind("mousemove", helperPosition);
		};
		
		var helperPosition = function(e) {
			$("#jTreeHelper").css ({
				top: e.pageY + 5,
				left: e.pageX + 5
			});
			
			$(".jTreePlacement").remove();
		};
		
		var getInitial = function(e){
			off = $(this).offset();
			h = $(this).height();
			w = $(this).width();
			hover = this;
			return false;
		};
		
		var putPlacement = function(e){
			$(cur).hide();
			$("#jTreeHelper").css ({
				top: e.pageY + 5,
				left: e.pageX + 5
			});
	
			//inserting before
			if ( e.pageY >= off.top && e.pageY < (off.top + h/2 - 1) ) {
				if (!$(this).prev().hasClass("jTreePlacement")) {
					$(".jTreePlacement").remove();
					$(this).before(str);
				}
			}
			//inserting after
			else if (e.pageY >(off.top + h/2) &&  e.pageY <= (off.top + h) ) {
				// as a sibling
				if (e.pageY > off.left && e.pageX < off.left + opts.childOff) {
					if (!$(this).next().hasClass("jTreePlacement")) {
						$(".jTreePlacement").remove();
						$(this).after(str);
					}
				}
				// as a child
				else if (e.pageX > off.left + opts.childOff) {
					
					$(".jTreePlacement").remove();
					if ($(this).find("ul").length == 0)
						$(this).append('<ul>'+str+'</ul>');
					else
						$(this).find("ul").prepend(str);
				}
			}
			return false;
		}
		
		var lockIn = function(e) {
			// if placement box is present, insert before placement box
			if ($(".jTreePlacement").length==1) {
				$(cur).insertBefore(".jTreePlacement");
			}
			$(cur).show();
			
			// remove helper and placement box
			$("#jTreeHelper").empty().hide();
			
			$(".jTreePlacement").remove();;
		}

	}; // end jTree


	$.fn.jTree.defaults = {
		showHelper: false,
		hOpacity: 0.5,
		hBg: "#FCC",
		hColor: "#222",
		pBorder: "1px dashed #CCC",
		pBg: "#EEE",
		pColor: "#222",
		pHeight: "20px",
		childOff: 20,
		snapBack: 1000
	};
		  
})(jQuery);


