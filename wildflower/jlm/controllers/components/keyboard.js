// var t = null;
// var KeyboardComponent = Component.extend({
//  present: false,
//  lastLink: 0,
//  switcherEl: null,
//  links: null,
//  linksCount: 0,
//  closeTimeout: null,
//     
//     startup: function() {
//      t = this;
//      $.hotkeys.add('Shift+tab', this.showSwitcher);
//      $.hotkeys.add('Shift+q', this.hideSwitcher);
//  },
//  
//  hideSwitcher: function() {
//      t.switcherEl.remove();
//      t.lastLink = 0;
//      t.present = false;
//  },
//  
//  showSwitcher: function() {
//      if (t.present) {
//          // Clear old timeout and start new
// //           if (t.closeTimeout) {
// //               clearInterval(t.closeTimeout);
// //               t.closeTimeout = setTimeout(t.hideSwitcher, 3000);
// //           }
// //           
//          // Cycle trough links
//          $('.switch-to', t.switcherEl).removeClass('switch-to');
//          
//          if (t.lastLink == t.linksCount) {
//              t.lastLink = 0;
//          }
//          t.links.eq(t.lastLink).addClass('switch-to').focus();
//          t.lastLink++;
//          return; 
//      }
//      
//      // Append switcher
//      var html = t.controller.parseTemplate('elements/switcher');
//      t.switcherEl = $(html);
//      $('body').append(t.switcherEl);
//      
//      // Position
//      var switcherWidth = $('#switcher').width();
//      var sWidthHalf = Math.floor(switcherWidth / 2);
//      var leftPost = Math.floor($(window).width() / 2) - sWidthHalf; 
//      var topPost = Math.floor($(window).height() / 2) - 80;
//      $('#switcher, #switcher-bg').css({ left: leftPost, top: topPost });
//      $('#switcher-bg').css({ width: switcherWidth + 40, opacity: 0.3 });
//      
//      t.links = $('a', t.switcherEl);
//      t.linksCount = t.links.size();
//      
//      // Select the first link
//      t.links.eq(t.lastLink).addClass('switch-to').focus();
//         t.lastLink++;
//          
//      t.present = true;
//      
//      // Launch close timeout
// //       t.closeTimeout = setTimeout(t.hideSwitcher, 3000);
//  }
//  
// });
