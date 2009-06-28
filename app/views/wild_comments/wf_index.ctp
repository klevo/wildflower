<h2 class="section"><?php __('Comments'); ?></h2>

<?php
	echo 
	$form->create('WildComment', array('action' => 'wf_mass_update', 'id' => 'comments_index'));
?>

<?php
    if (empty($comments)) {
        echo '<p>', __('No comments yet.', true), '</p>';
    } else {
        $actions = $this->element('wf_select_actions', array('actions' => array('Unapprove', 'Spam!', 'Delete')));

        echo 
        $actions,
        $tree->generate($comments, array('model' => 'WildComment', 'class' => 'list pages-list', 'element' => '../wild_comments/_index_list_item')),
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


