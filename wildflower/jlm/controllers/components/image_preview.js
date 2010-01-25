
$.jlm.addComponent('imagePreview', {

    startup: function(){	
			xOffset = 10;
			yOffset = 30;
		$("a.icon").hover(function(e){
			this.t = this.title;
			this.title = "";	
			var c = (this.t != "") ? "<br/>" + this.t : "";
			$("body").append("<p id='screenshot'><img src='"+ this.rel +"' alt='url preview' />"+ c +"</p>");								 
			$("#screenshot")
				.css("position","absolute")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px")
				.fadeIn("fast");						
		},
		function(){
			this.title = this.t;	
			$("#screenshot").remove();
		});	
		$("a.icon").mousemove(function(e){
			$("#screenshot")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px");
		});			
	}

});