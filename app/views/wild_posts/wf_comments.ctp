<?php 
    echo 
    $form->create('WildComment', array('action' => 'mass_update'));
?>

<div class="section">
    <h2>Comments on <?php echo $html->link($this->data['WildPost']['title'], array('action' => 'edit', $this->data['WildPost']['id'])); ?></h2>
    <ul>
        <li><?php echo $htmla->link(__('Published', true), array('action' => 'comments', $this->data['WildPost']['id']), array('currentOn' => array('action' => 'comments', $this->data['WildPost']['id']))); ?></li>
        <li><?php echo $htmla->link(__('Awaiting approval', true), array('action' => 'comments', $this->data['WildPost']['id'], 'unapproved')); ?></li>
        <li><?php echo $htmla->link(__('Spam', true), array('action' => 'comments', $this->data['WildPost']['id'], 'spam')); ?></li>
    </ul>
</div>


<?php if (empty($this->data['WildComment'])): ?>
    <p>No comments.</p>
<?php else: ?>
    <?php echo $this->element('wf_select_actions', array('actions' => array('Approve', 'Unapprove', 'Spam', 'Unspam', 'Delete'))); ?>
    
    <ol class="comments_list">
        <?php foreach ($this->data['WildComment'] as $i => $comment): ?>      
            <li id="comment-<?php echo $comment['id'] ?>" class="<?php echo ($i % 2 == 0) ? 'odd' : 'even'; ?>">
                <span class="row-check"><?php echo $form->checkbox('id.' . $comment['id']) ?></span>
                
                <em class="comment-metadata">Posted by <?php echo $comment['url'] ? $html->link($comment['name'], $comment['url']) : $comment['name'] ?> 
                <?php echo $time->timeAgoInWords($comment['created']) ?></em>

                <div><?php echo $textile->format($comment['content']) ?></div>
            </li>
        <?php endforeach; ?>
    </ol>
    
    <?php echo $this->element('wf_select_actions', array('actions' => array('Approve', 'Unapprove', 'Spam', 'Unspam', 'Delete'))); ?>
<?php endif; ?>

<p><?php echo $html->link(__('Back to post edit', true), array('action' => 'edit', $this->params['pass'][0])); ?></p>

<?php echo $form->end(); ?>


<?php //$partialLayout->blockStart('sidebar'); ?>

<?php //$partialLayout->blockEnd(); ?>