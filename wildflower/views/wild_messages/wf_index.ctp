
<h2 class="section"><?php __('Contact form messages'); ?></h2>
<?php
    $session->flash();

    echo 
    $form->create('WildMessage', array('action' => 'mass_update')),
    $this->element('wf_select_actions', array('actions' => array('Delete'))),
    $list->create($messages, array('model' => 'WildMessage', 'class' => 'list selectable-list', 'element' => 'admin_messages_list_item')),
    $this->element('wf_select_actions', array('actions' => array('Delete'))),
    $this->element('wf_pagination'),
    $form->end();
?>  


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <ul class="right_menu">
            <li><?php echo $htmla->link('Inbox', array('action' => 'index'), array('strict' => true)); ?></li>
            <li><?php echo $htmla->link('Spam', array('action' => 'spam')); ?></li>
        </ul>
    </li>
    <li>
        <p>
            <?php e($html->link('Re-check inbox for spam', array('action' => 'recheck_inbox_for_spam'))); ?>
        </p>
    </li>
<?php $partialLayout->blockEnd(); ?>
