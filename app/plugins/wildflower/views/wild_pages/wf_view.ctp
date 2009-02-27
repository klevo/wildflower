
<?php echo $html->link(__('Edit', true), array('action' => 'edit', $page['WildPage']['id'])); ?>

<hr />

<div class="entry">
    <h2><?php echo hsc($page['WildPage']['title']); ?></h2>
    <?php echo $textile->format($page['WildPage']['content']); ?>
</div>

<hr />

