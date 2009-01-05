<?php 
    echo 
    $form->create('WildPost', array('url' => $html->url(array('action' => 'update', 'base' => false)), 'class' => 'horizontal-form')),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<h2 class="section">Comments on <?php echo $html->link($this->data['WildPost']['title'], array('action' => 'edit', $this->data['WildPost']['id'])); ?></h2>

<?php if (empty($this->data['WildComment'])): ?>
    <p>No-one commented on this post yet.</p>
<?php else: ?>
    <ol id="comments-list">
    <?php foreach ($this->data['WildComment'] as $comment): ?>
        <li>
            <p class="comment-head">From: <?php echo hsc($comment['name']); ?>, <?php echo $time->niceShort($comment['created']); ?></p>
            <div class="comment-body">
                <?php echo $textile->format($comment['content']); ?>
            </div>
        </li>
    <?php endforeach; ?>
    </ol>
<?php endif; ?>


<div class="horizontal-form-buttons">
    <div class="cancel-edit"><?php echo $html->link(__('Go back to post edit', true), array('action' => 'edit', $this->data['WildPost']['id'])); ?></div>
</div>

<?php echo $form->end(); ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <h4><?php __('Editing comments for post...'); ?></h4>
        <?php echo $html->link($this->data['WildPost']['title'], array('action' => 'edit', $this->data['WildPost']['id']), array('class' => 'edited-item-link')); ?>
    </li>
    <li class="sidebar-box post-info">
        <?php echo $this->element('../wild_posts/_post_info'); ?>
    </li>
<?php $partialLayout->blockEnd(); ?>