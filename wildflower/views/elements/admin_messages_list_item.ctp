<div class="list-item">
    <a href="<?php echo $html->url(array('controller' => 'wild_messages', 'action' => 'view', $node['WildMessage']['id'])) ?>" title="Edit">
        <em><?php echo hsc($node['WildMessage']['name']) ?>:</em> <?php echo $text->truncate($node['WildMessage']['content'], 70) ?>
    </a>
</div>