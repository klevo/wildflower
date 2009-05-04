
<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $node['WildMessage']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'message-' . $node['WildMessage']['id']);
        if (ListHelper::isOdd()) {
            $tree->addItemAttribute('class', 'odd');
        }
    ?>
    <a href="<?php echo $html->url(array(
        'controller' => 
        'wild_messages', 
        'action' => 'view', 
        $node['WildMessage']['id'])) ?>" title="Edit">
        <strong><?php echo hsc(strip_tags($node['WildMessage']['name'])) ?></strong>&nbsp;&nbsp;
        <?php echo $text->truncate(strip_tags($node['WildMessage']['content']), 120); ?> 
    </a>
    
    <small><?php echo $time->niceShort($node['WildMessage']['created']); ?></small>
    
    <span class="cleaner"></span>
</div>