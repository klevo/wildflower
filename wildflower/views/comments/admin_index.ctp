<h2 class="section"><?php __('Comments'); ?></h2>

<?php
	echo 
	$form->create('Comment', array('action' => 'admin_mass_update', 'id' => 'comments_index'));
?>

<?php
    if (empty($comments)) {
        echo '<p>', __('No comments yet.', true), '</p>';
    } else {
        $actions = $this->element('admin_select_actions', array('actions' => array('Unapprove', 'Spam!', 'Delete')));

        echo 
        $actions,
        $tree->generate($comments, array('model' => 'Comment', 'class' => 'list pages-list', 'element' => '../comments/_index_list_item')),
        $actions;
    }
   
    echo $form->end();
?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <ul class="right_menu">
            <li><?php echo $htmla->link('Published', array('action' => 'index')); ?></li>
            <li><?php echo $htmla->link('Awaiting approval', array('action' => 'awaiting')); ?></li>
            <li><?php echo $htmla->link('Spam', array('action' => 'spam')); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>


