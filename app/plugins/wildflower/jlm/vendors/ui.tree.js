$.widget("ui.tree", {
    init: function() {

        var self = this;

        this.element.sortable({
            items: this.options.sortOn,
            placeholder: "drop-area",
            start: function() {
                $(this).data("sortable").placeholder.hide();
                $(this).data("sortable").refreshPositions(true);    
            },
            stop: function() {
                var self = $(this).data("sortable");
                $(self.options.items, self.element).css("border-top", "0").css("border-bottom", "0");
            },
            sortIndicator: function(e, item, append, hardRefresh) {
        
                append ? append[0].appendChild(this.placeholder[0]) : item.item[0].parentNode.insertBefore(this.placeholder[0], (this.direction == 'down' ? item.item[0] : item.item[0].nextSibling));
                
                $(this.options.items, this.element).css("border-top", "0").css("border-bottom", "0");
                item.item.css("border-"+(this.direction == "down" ? "top" : "bottom"), "2px solid black");
    
            }
        });
        
        //Make certain nodes droppable
        $(this.options.dropOn, this.element).droppable({
            accept: this.options.sortOn,
            hoverClass: this.options.hoverClass,
            //tolerance: "pointer",
            over: function() {
                $(self.options.sortOn, self.element).css("border-top", "0").css("border-bottom", "0");
            },
            drop: function(e, ui) {
                $(this).parent().find("ul").append(ui.draggable);
                self.element.data("sortable")._noFinalSort = true;
            }
        });

    }
});

$.extend($.ui.tree, {
    defaults: {
        sortOn: "*",
        dropOn: "div.list-item",
        hoverClass: "list-make-children"
    }
});