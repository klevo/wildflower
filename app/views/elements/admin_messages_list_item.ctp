
<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $node['Message']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'message-' . $node['Message']['id']);
        if (ListHelper::isOdd()) {
            $tree->addItemAttribute('class', 'odd');
        }
    ?>
    <a href="<?php echo $html->url(array(
        'controller' => 
        'messages', 
        'action' => 'view', 
        $node['Message']['id'])) ?>" title="Edit">
        <strong><?php echo hsc(strip_tags($node['Message']['name'])) ?></strong>&nbsp;&nbsp;
        <?php echo $text->truncate(strip_tags($node['Message']['content']), 120); ?> 
    </a>
    
    <small><?php echo $time->niceShort($node['Message']['created']); ?></small>
    
    <span class="cleaner"></span>
</div>