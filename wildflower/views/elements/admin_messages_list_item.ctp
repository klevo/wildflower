<div class="list-item">
    <a href="<?php echo $html->url(array('controller' => 'messages', 'action' => 'view', $node['Message']['id'])) ?>" title="Edit">
        <em><?php echo hsc($node['Message']['name']) ?>:</em> <?php echo $text->truncate($node['Message']['content'], 70) ?>
    </a>
</div>