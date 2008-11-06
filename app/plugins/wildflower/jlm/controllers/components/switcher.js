$.jlm.addComponent('switcher', {
    
    present: false,
    lastLink: 0,
    switcherEl: null,
    links: null,
    linksCount: 0,
    closeTimeout: null,

    startup: function() {
        $.hotkeys.add('Shift+tab', this.showSwitcher);
        $.hotkeys.add('Shift+q', this.hideSwitcher);
    },

    hideSwitcher: function() {
        $.jlm.components.switcher.switcherEl.remove();
        $.jlm.components.switcher.lastLink = 0;
        $.jlm.components.switcher.present = false;
    },

    showSwitcher: function() {
        var t = $.jlm.components.switcher;
        
        if (t.present) {
            // Cycle trough links
            $('.switch-to', t.switcherEl).removeClass('switch-to');

            if (t.lastLink == t.linksCount) {
                t.lastLink = 0;
            }
            t.cycle();
            return; 
        }

        // Append switcher
        var html = $.jlm.template('elements/switcher');
        t.switcherEl = $(html);
        $('body').append(t.switcherEl);

        // Position
        var switcherWidth = $('#switcher').width();
        var sWidthHalf = Math.floor(switcherWidth / 2);
        var leftPost = Math.floor($(window).width() / 2) - sWidthHalf; 
        var topPost = Math.floor($(window).height() / 2) - 130;
        $('#switcher, #switcher-bg').css({ left: leftPost, top: topPost });
        $('#switcher-bg').css({ top: topPost + 4 });
        $('#switcher-bg').css({ width: switcherWidth + 40, opacity: 0.3, '-moz-border-radius': '6px', '-webkit-border-radius': '6px' });

        t.links = $('a', t.switcherEl);
        t.linksCount = t.links.size();

        // Select the first link
        t.cycle();

        t.present = true;
    },

    cycle: function() {
        var t = $.jlm.components.switcher;
        t.links.eq(t.lastLink).addClass('switch-to').focus().css({ '-moz-border-radius': '5px', '-webkit-border-radius': '5px' });
        t.lastLink++;
    }

});
