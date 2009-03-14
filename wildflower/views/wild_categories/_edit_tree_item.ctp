<div class="actions-handle">
    <?php echo $html->link($data['WildCategory']['title'], array('action' => 'edit', $data['WildCategory']['id']), array('title' => __('Edit this category', true))); ?>

    <div class="row-actions">
        <?php echo $html->link('<span>' . __('Delete', true) . '</span>', array('action' => 'delete', $data['WildCategory']['id']), array('class' => 'action_delete', 'escape' => false, 'title' => __('Delete this category', true))); ?>
    </div>
</div>
