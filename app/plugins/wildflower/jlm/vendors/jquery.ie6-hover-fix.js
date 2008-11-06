$.ie6CssFix = function() {

    if($.browser.msie && $.browser.version < 7) {


        var cssRules = [], newStyleSheet = document.createStyleSheet();

        $("style,link[type=text/css]").each(function() {

            if(this.href) {
                $.get(this.href,function(cssText) {
                    parseStyleSheet(cssText);
                });	
            } else {
                parseStyleSheet(this.innerHTML);
            }
        });

        function parseStyleSheet(cssText) {
            var cssText = cssText.replace(/\s+/g,'');
            var arr = cssText.split("}");
            var l = arr.length;
            for(var i=0; i < l; i++) {
                if(arr[i] != "") {
                    parseRule(arr[i] + "}");	
                }
            }
        }

        function parseRule(rule) {


            var pseudo = rule.replace(/[^:]+:([a-z-]+).*/i, '$1');

            if(/(hover|after|focus)/i.test(pseudo)) {

                var prefix = "ie6fix-";
                var element = rule.replace(/:(hover|after|before|focus).*$/, '');
                var className = prefix + pseudo;
                var style = rule.match(/\{(.*)\}/)[1];

                var h =  getPseudo(pseudo);
                if(h) {
                    h(element,className);
                }

                newStyleSheet.addRule(element + "." + className,style);
            }
        }

        function handleHover(e,c) {
            $(e).hover(function() {$(this).addClass(c);}, function() {$(this).removeClass(c);});
        }

        function handleFocus(e,c) {
            $(e).focus(function() { $(this).addClass(c); }).blur(function() {$(this).removeClass(c);});
        }

        function handleAfter(e,c) {
            $(e).after(
                $("<" + e + "></" + e + ">").addClass(c)
            );
        }

        function getPseudo(pseudo) {
            switch (pseudo) {
                case "hover": return handleHover;
                case "focus": return handleFocus;
                case "after": return handleAfter;
                default: return false;
            }

        }
    }
};

$(function() {
    $.ie6CssFix();
});
