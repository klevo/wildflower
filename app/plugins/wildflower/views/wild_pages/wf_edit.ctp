<?php 
    $partialLayout->setLayoutVar('isFullEdit', true);
    $session->flash();
    
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor-form'));
?>
<script src="<?=$html->url('/wildflower/js/jquery.json-1.3.min.js')?>"></script>
<script type="text/javascript">
jQuery.fn.swap = function(b) {
    b = jQuery(b)[0];
    var a = this[0];

    var t = a.parentNode.insertBefore(document.createTextNode(''), a);
    b.parentNode.insertBefore(a, b);
    t.parentNode.insertBefore(b, t);
    t.parentNode.removeChild(t);

    return this;
};

var groupEditor = function (){
    
    // Private variables and functions
    var el;
    var id;
    
    return {
        // Public functions
        init: function(element, i) {
            el = element;
            id = 'group-editor-' + id;
            var content = $(el).val();
            $(el).css('display', 'none').attr('id', id);
            
            // Parse JSON - eval is safe here because content is trusted
            var parsedContent;
            try {
                parsedContent = eval('(' + content + ')');
            } catch(e) {
                parsedContent = [{type:'content', image: '', text:''}];
            }
            
            //console.log(parsedContent);
            for(var i = 0; i < parsedContent.length; i ++) {
                var section = parsedContent[i];
                    switch(section.type) {
                        // A 'content' block is defined as text with an image
                        case 'content':
                            this.appendContentBlock(section);
                            break;
                        
                        case 'file':
                            this.appendFileBlock(section);
                            break;
                    }
            }
            $(el).parent().parent().append('<input type="button" onclick="groupEditor.appendContentBlock({type:\'content\', image: \'\', text:\'\'})" value="Add content block" />');
        },
        
        appendContentBlock: function(section) {
            $(el).parent().append('<div class="actions-handle action-attach"><div class="content-block"><input type="text" class="group-editor-image" value="' + section.image + '" /><textarea class="group-editor-text">' + section.text + '</textarea></div><span class="row-actions"><a title="Move up" href="#" onclick="groupEditor.moveSection(this, true)">^</a><a title="Move down" href="#" onclick="groupEditor.moveSection(this, false)">v</a><a title="Delete this page" href="#" class="delete-section" onclick="groupEditor.deleteSection(this)">x</a></span></div>');
            this.attachHover();
        },
        
        deleteSection: function(element) {
            if (confirm('Are you sure you want to remove this?')) {
                $(element).parent().parent().remove();
            }
        },
        
        moveSection: function(element, up) {
            var element = $(element).parent().parent();
            if(up) {
                if (element.prev().hasClass('actions-handle')) {
                    element.swap(element.prev());
                } else {
                    alert('Sorry, this section cannot be moved any further up.');
                }
            } else {
                if (element.next().hasClass('actions-handle')) {
                    element.swap(element.next());                    
                } else {
                    alert('Sorry, this section cannot be moved any further down.');
                }
            }
        },
        
        appendFileBlock: function() {
            
        },
        
        attachHover: function() {
            var actionHandleEls = $('.action-attach');
            
            if (actionHandleEls.size() < 1) return;
            
            $('.action-attach').removeClass('action-attach');
            
            var itemActionsTimeout = null;
            
            var over = function() {
                if (itemActionsTimeout) {
                    // Cancel all to be closed and hide them
                    clearTimeout(itemActionsTimeout);
                    $('.row-actions:visible').hide();
                }
                
                $(this).find('.row-actions').show();
            }
            
            var out = function() {
                if (itemActionsTimeout) {
                    clearTimeout(itemActionsTimeout);
                }
        		
        		var el = this;
        		
                itemActionsTimeout = setTimeout(function() {
                    if ($.browser.msie) { // IE7 does not handle animations well, therefore use plain hide()
                        $(el).find('.row-actions').hide();
                    }
                    else {
                        $(el).find('.row-actions').fadeOut(500);
                    }
                }, 1000);
            }
              
            actionHandleEls.hover(over, out);
        },
        
        /**
         * 
         */
        serialize: function() {
            var serialized = new Array();
            
            $('.content-block').each(function() {
                serialized.push({ "type": "content", "image": $('.group-editor-image', $(this)).val() , "text": $('.group-editor-text', $(this)).val(), "align": "right"});
            })                      
            $('.group_editor').val($.toJSON(serialized));
        }
    }
    
}()

$(document).ready(function(){
    $('.group_editor').each(function(i){
        //groupEditor();
        groupEditor.init(this, i);
    });
});


</script>
<style>
    .content-block {
        padding-bottom: 10px;
        border-bottom: 1px solid #ccc;
        margin-bottom: 10px;
    }
    .content-block textarea {
        width: 99%;
        height: 200px;
    }
</style>
<div id="title-content">
    <?php
        echo
        $form->input('title', array(
            'between' => '<br />',
            'tabindex' => '1',
            'label' => __('Page title', true),
            'div' => array('class' => 'input title-input'))),
        $form->input('content', array(
            'type' => 'textarea',
            'tabindex' => '2',
            'class' => 'group_editor',
            'rows' => '25',
            'label' => __('Body', true),
            'div' => array('class' => 'input editor'))),
        '<div>';
        echo $form->hidden('id'),
        $form->hidden('draft'),
        '</div>';
    ?>
    
    <div id="edit-buttons">
        <?php echo $this->element('wf_edit_buttons'); ?>
    </div>
</div>

<div id="post-revisions">
    <h2 class="section">Older versions of this page</h2>
    <?php 
        if (!empty($revisions)) {
            echo 
            '<ul id="revisions" class="list revision-list">';

            $first = '<span class="current-revision">&mdash;current version</span>';
            foreach ($revisions as $version) {
            	//print_r($version);
                $attr = '';
                if (ListHelper::isOdd()) {
                    $attr = ' class="odd"';
                }
                echo 
                "<li$attr>",
                '<div class="list-item">',
                $html->link("Revision {$version['WildRevision']['revision_number']}",
                    array('action' => 'wf_edit', $version['WildRevision']['node_id'], $first ? null : $version['WildRevision']['revision_number']), null, null, false),
                "<small>$first, saved {$time->niceShort($version['WildRevision']['created'])} by {$version['WildUser']['name']}</small>",
                '</div>',
                '</li>';
                $first = '';
            }
            echo '</ul>';
        } else {
            echo "<p id=\"revisions\">No revisions yet.</p>";
        }
    ?>        
</div>

<?php 
    echo 
    
    // Options for create new JS
	$form->input('parent_id_options', array('type' => 'select', 'options' => $newParentPageOptions, 'empty' => '(none)', 'div' => array('class' => 'all-page-parents input select'), 'label' => __('Parent page', true), 'escape' => false)),
	
	$form->end();
?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $this->element('../wild_pages/_sidebar_search'); ?>
    </li>
    <li>
        <?php echo $html->link(
            '<span>Write a new page</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li>
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
            <li><?php echo $html->link('Browse older versions', '#Revisions', array('rel' => 'post-revisions')); ?></li>
        </ul>
    </li>
    <li class="sidebar-box post-info">
        <?php echo $this->element('../wild_pages/_page_info'); ?>
    </li>
<?php $partialLayout->blockEnd(); ?>
