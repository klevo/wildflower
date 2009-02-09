<?php 
    $partialLayout->setLayoutVar('isFullEdit', true);
    $session->flash();
    
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor-form'));
?>
<script type="text/javascript">

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
                parsedContent = {};
            }
            
            console.log(parsedContent);
            for(var i = 0; i < parsedContent.length; i ++) {
                var section = parsedContent[i];
                    switch(section.type) {
                        
                        // A 'content' block is defined as text with an image
                        case 'content':
                            this.appendContentBlock(section);
                            break;
                    }
            }
            
            this.serialize();
        },
        
        appendContentBlock: function(section) {
            $(el).parent().append('<div class="content-block"><input type="text" class="group-editor-image" value="' + section.image + '" /><textarea class="group-editor-text">' + section.text + '</textarea></div>');
        },
        
        appendFileBlock: function() {
            
        },
        
        /**
         * 
         */
        serialize: function() {
            
        }
    }
    
}

$(document).ready(function(){
    $('.group_editor').each(function(i){
        var editor = new groupEditor();
        editor.init(this, i);
    });
});


</script>
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
        ?>
        <? /*
        <div class="content-block">
            <div class="image-chooser">
                
            </div>
            <textarea></textarea>
        </div>
        */ ?>
        <?
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
